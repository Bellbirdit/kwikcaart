<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\PickupTime;
use App\Models\User;
use App\Models\Wallet;
use App\Notifications\OrderNotification;
use Auth;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Session;
use Spatie\Permission\Models\Role;
use Stripe;
use App\Models\ShippingTime;
use Carbon\Carbon;



class PaymentController extends Controller
{
    //
    public function payment(Request $request)
    {
        if ($request->pick_time) {
            $time_string = $request->pick_time;
            $time_parts = explode(" To ", $time_string);
            $start_time = $time_parts[0];
            $end_time = $time_parts[1];
            $count = PickupTime::where('start_time', $start_time)->where('end_time', $end_time)->pluck('count')->first();
            $shippingcount = ShippingTime::where('start_time', $start_time)->where('end_time', $end_time)->pluck('count')->first();
        }
        $orderId = $this->generateUniqueId();
        $store_id = Session::get('store_id');
        $coupondiscount = Session::get('coupondiscount');
        $subtotal = Cart::where('user_id', auth()->user()->id)->where('store_id', $store_id)->where('status', 'pending')->sum('quantity_price');
        $shipping = DB::table('web_settings')->pluck('standard_delivery')->first();
        if ($request->delivery_option != 'self_pickup') {
            if ($subtotal >= 100) {
                $deliverycharges = 0;
            } else {
                $deliverycharges = $shipping;
            }
        } else {
            $deliverycharges = 0;
        }
        $coupon_value = Session::get('coupon_value');
        $coupon_flatvalue = Session::get('coupon_value');
        if ($coupon_value != '') {
            $coupontyp = 'percentage';
            $couponpercentg = $coupon_value;
        } else {
            $coupontyp = 'flat';
            $couponpercentg = $coupon_value;
        }
        $total = $subtotal + $deliverycharges - $coupondiscount;
        $couponId = Session::get('coupon_id', 0);

        if ($request->payment_option == 'no') {
            $order = Order::create([
                'first_name' => $request->first_name,
                'address' => $request->address,
                'phone' => $request->phone,
                'email' => $request->email,
                'store_id' => $store_id,
                'user_id' => Auth::id(),
                'delivery_option' => $request->delivery_option,
                'additional_info' => $request->additional_info,
                'pick_time' => $request->pick_time,
                'pick_date' => $request->pick_date,
                'order_number' => $orderId,
                'coupon_payment' => $total,
                'deliverycharges' => $deliverycharges,
                'coupondiscount' => $coupondiscount,
                'couponpercentage' => $couponpercentg,
                'coupontype' => $coupontyp,
                'coupon_id' => $couponId,
            ]);
            $check = User::where('email', $request->email)->first();
            if (!$check) {
                $user = User::create([
                    'name' => $request->first_name,
                    'status' => 'active',
                    'address' => $request->address,
                    'contact' => $request->phone,
                    'email' => $request->email,
                    'customer_store_id' => $store_id,
                    'password' => bcrypt($request->password),
                ]);
                $usr = User::where('id', $user->id)->first();
                $role = Role::where('name', 'User')->first();
                $usr->assignRole($role);
            } else {
                $check->customer_store_id = $store_id;
                $check->save();
            }
            $items = Cart::where('user_id', auth()->user()->id)->where('store_id', $store_id)->where('status', 'pending')->get();
            foreach ($items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'store_id' => $store_id,
                    'quantity' => $item->quantity,
                    'payment_getway' => 'Cash on Delivery',
                    'product_price' => $item->price,
                    'products_quantityprice' => $item->price * $item->quantity,
                ]);
            }
            if ($order) {
                Mail::send('email.order-confirmation', ['order' => $order], function ($message) use ($order) {
                    $message->to($order->email)
                        ->from('admin@safeermarket.com', 'Safeer Markeet')
                        ->subject("Order Confirmation");
                });
                $us = User::where('type', 2)->where('code', $order->store_id)->get();
                $noti = Order::where('id', $order->id)->first();
                Notification::send($us, new OrderNotification($noti));
                Session::forget('grandtotal');
                Session::forget('coupun_status');
                Session::forget('vats');
                Session::forget('deliverycharges');
                Session::forget('coupondiscount');
                Session::forget('coupon_value');
                Session::forget('checkout_amount');
                Session::forget('coupon_id');
                $status = Cart::where('user_id', auth()->user()->id)->where('store_id', $store_id)->where('status', 'pending')->get();
                foreach ($status as $sta) {
                    if ($sta->status == 'pending') {
                        $sta->delete();
                    }
                }
                if ($request->pick_time != '') {
                    if ($count) {
                        $subtracted_value = $count - 1;
                        PickupTime::where('start_time', $start_time)->where('end_time', $end_time)->update(['count' => $subtracted_value]);
                    } else if ($shippingcount) {
                        $subtracted_value = $shippingcount - 1;
                        ShippingTime::where('start_time', $start_time)->where('end_time', $end_time)->update(['count' => $subtracted_value]);
                    }
                }
                return response()->json(['status' => 'success', 'msg' => 'Order Placed Successfully']);
            }
        } elseif ($request->payment_option == 'wallet') {
            $wallet_amount = Wallet::where('user_id', Auth::id())->pluck('amount')->first();
            if ($total > $wallet_amount) {
                return response()->json(['status' => 'fail', 'msg' => 'You have insufficent amount in wallet please choose another payment method']);
            } else {
                $order = Order::create([
                    'first_name' => $request->first_name,
                    'address' => $request->address,
                    'phone' => $request->phone,
                    'email' => $request->email,
                    'store_id' => $store_id,
                    'user_id' => Auth::id(),
                    'delivery_option' => $request->delivery_option,
                    'additional_info' => $request->additional_info,
                    'pick_time' => $request->pick_time,
                    'pick_date' => $request->pick_date,
                    'order_number' => $orderId,
                    'coupon_payment' => $total,
                    'deliverycharges' => $deliverycharges,
                    'coupondiscount' => $coupondiscount,
                    'couponpercentage' => $couponpercentg,
                    'coupontype' => $coupontyp,
                    'coupon_id'=>$couponId,
                ]);

                $check = User::where('email', $request->email)->first();
                if (!$check) {
                    $user = User::create([
                        'name' => $request->first_name,
                        'country' => $request->country,
                        'state' => $request->state,
                        'status' => 'active',
                        'address' => $request->address,
                        'city' => $request->city,
                        'postal_code' => $request->zip,
                        'contact' => $request->phone,
                        'email' => $request->email,
                        'customer_store_id' => $store_id,
                        'password' => bcrypt($request->password),
                    ]);
                    $usr = User::where('id', $user->id)->first();
                    $role = Role::where('name', 'User')->first();
                    $usr->assignRole($role);
                } else {
                    $check->customer_store_id = $store_id;
                    $check->save();
                }
                $items = Cart::where('user_id', auth()->user()->id)->where('store_id', $store_id)->where('status', 'pending')->get();
                foreach ($items as $item) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $item->product_id,
                        'store_id' => $store_id,
                        'quantity' => $item->quantity,
                        'payment_getway' => 'Cash on Delivery',
                        'product_price' => $item->price,
                        'products_quantityprice' => $item->price * $item->quantity,
                    ]);
                }

                if ($order) {
                    Mail::send('email.order-confirmation', ['order' => $order], function ($message) use ($order) {
                        $message->to($order->email)
                            ->from('admin@safeermarket.com', 'Safeer Markeet')
                            ->subject("Order Confirmation");
                    });
                    $us = User::where('type', 2)->where('code', $order->store_id)->get();
                    $noti = Order::where('id', $order->id)->first();
                    Notification::send($us, new OrderNotification($noti));
                    Session::forget('grandtotal');
                    Session::forget('coupun_status');
                    Session::forget('vats');
                    Session::forget('deliverycharges');
                    Session::forget('coupondiscount');
                    Session::forget('coupon_value');
                    Session::forget('checkout_amount');
                    Session::forget('coupon_id');
                    $status = Cart::where('user_id', auth()->user()->id)->where('store_id', $store_id)->where('status', 'pending')->get();
                    foreach ($status as $sta) {
                        if ($sta->status == 'pending') {
                            $sta->delete();
                        }
                    }
                    return response()->json(['status' => 'success', 'msg' => 'Order Placed Successfully']);
                }
            }
        } else {
            Stripe\Stripe::setApiKey('sk_test_51Jeb4lCFc8UmysBw6zAYE967rwvDiyn6OBtN63kYE0lEpUSO7tSYBKxIVcg5OyVk0VbwgjPHpIMDOfKufoe5bHBe00Ir9geDsP');
            $payment = Stripe\Charge::create([
                "amount" => $total * 100,
                "currency" => "usd",
                "source" => $request->stripeToken,
                "description" => "Purchase Food",
            ]);
            if ($payment['status'] == 'succeeded') {
                $order = Order::create([
                    'first_name' => $request->first_name,
                    'store_id' => $store_id,
                    'address' => $request->address,
                    'phone' => $request->phone,
                    'email' => $request->email,
                    'delivery_option' => $request->delivery_option,
                    'pick_time' => $request->pick_time,
                    'pick_date' => $request->pick_date,
                    'user_id' => Auth::id(),
                    'additional_info' => $request->additional_info,
                    'stripe_payment_id' => $payment['id'],
                    'order_number' => $orderId,
                    'coupon_payment' => $total,

                    'deliverycharges' => $deliverycharges,
                    'coupondiscount' => $coupondiscount,
                    'couponpercentage' => $couponpercentg,
                    'coupontype' => $coupontyp,
                    'coupon_id' => $couponId,
                ]);
                $check = User::where('email', $request->email)->first();
                if (!$check) {
                    $user = User::create([
                        'name' => $request->first_name,
                        'status' => 'active',
                        'address' => $request->address,
                        'contact' => $request->phone,
                        'email' => $request->email,
                        'password' => bcrypt($request->password),
                        'customer_store_id' => $store_id,
                    ]);

                    $usr = User::where('id', $user->id)->first();
                    $role = Role::where('name', 'User')->first();
                    $usr->assignRole($role);
                } else {
                    $check->customer_store_id = $store_id;
                    $check->save();
                }

                $items = Cart::where('user_id', auth()->user()->id)->where('store_id', $store_id)->where('status', 'pending')->get();
                foreach ($items as $item) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $item->product_id,
                        'store_id' => $store_id,
                        'quantity' => $item->quantity,
                        'payment_getway' => 'stripe',
                        'product_price' => $item->price,
                        'products_quantityprice' => $item->price * $item->quantity,

                    ]);
                }
            }

            if ($order) {
                Mail::send('email.order-confirmation', ['order' => $order], function ($message) use ($order) {
                    $message->to($order->email)
                        ->from('admin@safeermarket.com', 'Safeer Markeet')
                        ->subject("Order Confirmation");
                });
                $us = User::where('type', 2)->where('code', $order->store_id)->get();
                $noti = Order::where('id', $order->id)->first();
                Notification::send($us, new OrderNotification($noti));
                Session::forget('grandtotal');
                Session::forget('coupun_status');
                Session::forget('vats');
                Session::forget('deliverycharges');
                Session::forget('coupondiscount');
                Session::forget('coupon_value');
                Session::forget('checkout_amount');
                Session::forget('coupon_id');
                $status = Cart::where('user_id', auth()->user()->id)->where('store_id', $store_id)->where('status', 'pending')->get();
                foreach ($status as $sta) {
                    if ($sta->status == 'pending') {
                        $sta->delete();
                    }
                }
                return redirect()->route('success');
            }
        }
    }

    public function checkout_success()
    {
        return view('frontend/cart/checkout_success');
    }

    public function generateUniqueId()
    {
        $store_id = Session::get('store_id');
        $date = Carbon::today()->format('m d');
        $last = Order::orderBy('id', 'DESC')->first();
        if (empty($last)) {
            $increment = 1;
        } else {
            $increment = $last->id + 1;
        }
        $prefix = "$store_id-";
        $orderNumber = $prefix . $increment;
        return $orderNumber;
    }
}
