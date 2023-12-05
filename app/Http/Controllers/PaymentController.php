<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\PickupTime;
use App\Models\User;
use App\Models\Wallet;
use App\Models\UserAddress;
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
use App\Helpers\Cybersource;



class PaymentController extends Controller
{
    //
    public function payment(Request $request)
    {
        $user = auth()->user();
        
        $tr_id = $request->transaction_id ?? "";
        $payment_token = $request->payment_token ?? "";
        $decision = $request->decision ?? "";
        $tr_message = $request->message ?? "";
        $request->payment_option = "no";
        if(isset($_GET['po'])){
            // dd("============================",$request->all());
            $userId = $request->req_reference_number ?? "";
            $store_id = $request->req_transaction_uuid ?? "";
            $message = $request->message ?? "";
            // $userId = $_GET['user_id'];
            if(isset($tr_id) and !empty($tr_id)){
                cache()->put('tr_id_'.$userId, $tr_id);    
            }
            $user = User::find($userId);
            session::put('store_id',$store_id); 
            Auth::login($user);
            $sessionData = cache()->get('online_payment_'.$userId);
            $request->merge($sessionData);
            $request->payment_option = "online";
            if($decision != "ACCEPT"){
                session::put('message',$tr_message); 
                return redirect('/checkout');
            }
        }
        
        
        $request->first_name = Auth::user()->name;
        $request->email = Auth::user()->email;
        $request->phone = Auth::user()->contact;
        $defaultAddress = UserAddress::where('user_id', auth()->id())
            ->where('is_default', 1)
            ->first();
        if(!isset($defaultAddress) || empty($defaultAddress)){
            $defaultAddress = UserAddress::where('user_id', auth()->id())
            ->first();
        }
        $request->address_id = $defaultAddress->id;
        $request->address = $defaultAddress->address;
        $request->additional_info = $defaultAddress->delivery_instructions;
        $data_order = $request->all();
        if(isset($data_order['time_id']) and !empty($data_order['time_id'])){
            $timeA = explode("-", $data_order['time_id']);
            if($timeA[1] == "s"){
                $shippings = ShippingTime::where('id', $timeA[0])->first();
                $shippingcount = $shippings->count;
                $request->delivery_option = "standerd_delivery";
            }else{
                $shippings = PickupTime::where('id', $timeA[0])->first();
                $count = $shippings->count;
                $request->delivery_option = "self_pickup";
            }
        }
        // if (isset($request->pick_time)) {
        //     $time_string = $request->pick_time;
        //     $time_parts = explode(" To ", $time_string);
        //     $start_time = $time_parts[0];
        //     $end_time = $time_parts[1];
        //     $count = PickupTime::where('start_time', $start_time)->where('end_time', $end_time)->pluck('count')->first();
        //     $shippingcount = ShippingTime::where('start_time', $start_time)->where('end_time', $end_time)->pluck('count')->first();
        // }
        $orderId = $this->generateUniqueId();
        $store_id = Session::get('store_id');
        $coupondiscount = Session::get('coupondiscount');
        // $subtotal = Cart::where('user_id', auth()->user()->id)->where('store_id', $store_id)->where('status', 'pending')->sum('quantity_price');
        $shipping = DB::table('web_settings')->pluck('standard_delivery')->first();
        $carts = Cart::where('user_id', $user->id)->where('store_id', $store_id)->where('status', 'pending')->get();
        $subtotal = 0;
        foreach($carts as $cart){
            $product = Product::find($cart->product_id);
            if($product){
                $priceArray = $product->get_deal_price();
                $price = $priceArray['price'];
                $subtotal += $price*$cart->quantity;    
            }
        }
        
        if (isset($request->delivery_option) && $request->delivery_option != 'self_pickup') {
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
                $product = Product::find($item->product_id);
                if($product){
                    $priceArray = $product->get_deal_price();
                    $price = $priceArray['price'];
                }
                // $priceArray = $product->get_deal_price();
                // $price = $priceArray['price'];
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'store_id' => $store_id,
                    'quantity' => $item->quantity,
                    'payment_getway' => 'Cash on Delivery',
                    'product_price' => $price,
                    'products_quantityprice' => $price * $item->quantity,
                ]);
            }
            if ($order) {
                Mail::send('email.order-confirmation', ['order' => $order], function ($message) use ($order) {
                    $message->to($order->email)
                        ->from('admin@kwikcaart.com', 'Safeer Markeet')
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
                // if ($request->pick_time != '') {
                //     if ($count) {
                //         $subtracted_value = $count - 1;
                //         PickupTime::where('start_time', $start_time)->where('end_time', $end_time)->update(['count' => $subtracted_value]);
                //     } else if ($shippingcount) {
                //         $subtracted_value = $shippingcount - 1;
                //         ShippingTime::where('start_time', $start_time)->where('end_time', $end_time)->update(['count' => $subtracted_value]);
                //     }
                // }
                $shippings->decrement('count');
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
                    $product = Product::find($item->product_id);
                    if($product){
                        $priceArray = $product->get_deal_price();
                        $price = $priceArray['price'];
                    }
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $item->product_id,
                        'store_id' => $store_id,
                        'quantity' => $item->quantity,
                        'payment_getway' => 'Cash on Delivery',
                        'product_price' => $price,
                        'products_quantityprice' => $price * $item->quantity,
                    ]);
                }

                if ($order) {
                    Mail::send('email.order-confirmation', ['order' => $order], function ($message) use ($order) {
                        $message->to($order->email)
                            ->from('admin@kwikcaart.com', 'Safeer Markeet')
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
            // Stripe\Stripe::setApiKey('sk_test_51Jeb4lCFc8UmysBw6zAYE967rwvDiyn6OBtN63kYE0lEpUSO7tSYBKxIVcg5OyVk0VbwgjPHpIMDOfKufoe5bHBe00Ir9geDsP');
            // $payment = Stripe\Charge::create([
            //     "amount" => $total * 100,
            //     "currency" => "usd",
            //     "source" => $request->stripeToken,
            //     "description" => "Purchase Food",
            // ]);
            if ($tr_id) {
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
                    'stripe_payment_id' => $tr_id,
                    'payment_token' => $payment_token,
                    'payment_decision' => $decision,
                    'payment_message' => $tr_message,
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

                $items = Cart::where('user_id', $user->id)->where('store_id', $store_id)->where('status', 'pending')->get();
                foreach ($items as $item) {
                    $product = Product::find($item->product_id);
                    if($product){
                        $priceArray = $product->get_deal_price();
                        $price = $priceArray['price'];
                    }
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $item->product_id,
                        'store_id' => $store_id,
                        'quantity' => $item->quantity,
                        'payment_getway' => 'stripe',
                        'product_price' => $price,
                        'products_quantityprice' => $price * $item->quantity,

                    ]);
                }
            }

            if ($order) {
                Mail::send('email.order-confirmation', ['order' => $order], function ($message) use ($order) {
                    $message->to($order->email)
                        ->from('admin@kwikcaart.com', 'Safeer Markeet')
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
                $status = Cart::where('user_id', $user->id)->where('store_id', $store_id)->where('status', 'pending')->get();
                foreach ($status as $sta) {
                    if ($sta->status == 'pending') {
                        $sta->delete();
                    }
                }
                return redirect()->route('success');
            }
        }
    }
    
    public function makePaymentObjectToken(Request $request){
            
            cache()->put('online_payment_'.auth()->user()->id, $request->all());
            
            $profileId = config('app.MERCHENT_PROFILE');
            $accessKey = config('app.MERCHENT_ACCESS_KEY');
            $payment = $request->all();
            
            $defaultAddress = UserAddress::where('id', $payment['address_id'])->first();
            // dd($defaultAddress);
            
             $store_id =  session::get('store_id');
             $nameArray = explode(" ", auth()->user()->name);
             $fname = $nameArray[0]??"";
             $lname = $nameArray[1]??"";
            $body = '{
                "targetOrigins": [
                  "https://kwikcaart.com"
                ],
                "clientVersion": "0.17",
                "allowedCardNetworks": [
                  "VISA",
                  "MASTERCARD",
                  "AMEX"
                ],
                "allowedPaymentTypes": [
                  "PANENTRY",
                  "SRC"
                ],
                "country": "AE",
                "locale": "en_AE",
                "captureMandate": {
                  "billingType": "FULL",
                  "requestEmail": true,
                  "requestPhone": false,
                  "requestShipping": false,
                  "shipToCountries": [
                    "AE"
                  ],
                  "showAcceptedNetworkIcons": true
                },
                "orderInformation": {
                  "amountDetails": {
                    "totalAmount": "'.$payment['amount'].'",
                    "currency": "AED"
                  },
                  "billTo": {
                      "address1": "'.substr($defaultAddress->address, 0, 55).'",
                      "locality": "'.$defaultAddress->landmark.'",
                      "administrativeArea": "AE", 
                      "postalCode": "99235",
                      "country": "AE",
                      "email": "'.auth()->user()->email.'",
                      "firstName": "'.$fname.'",
                      "lastName": "'.$lname.'",
                      "buildingNumber": "'.$defaultAddress->flat_name.'"
                    }
                },
                "checkoutApiInitialization": {
                  "profile_id": "'.$profileId.'",
                  "access_key": "'.$accessKey.'",
                  "reference_number": "'.auth()->user()->id.'",
                  "transaction_uuid": "'.$store_id.'",
                  "transaction_type": "authorization",
                  "currency": "AED",
                  "amount": "'.$payment['amount'].'",
                  "locale": "en-us",
                  "override_custom_receipt_page": "https://kwikcaart.com/place/order?po=true",
                  "unsigned_field_names": "transient_token"
                }
              }';
        // dd($body);die;
        $cyber = new Cybersource;
        $resource = config('app.MERCHENT_RESOURCE');
        $gmtDateTime = gmdate("D, d M Y H:i:s") . " GMT";
        $digest = $cyber->GenerateDigest($body);
        $signature = $cyber->GenerateSignature($digest, $gmtDateTime, "post", $resource);
        $result = $cyber->CallCyberSourceAPI("POST", $body, $gmtDateTime, $digest, $signature);
        list($headerString, $body) = explode("\r\n\r\n", $result, 2);
        return $body;
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
