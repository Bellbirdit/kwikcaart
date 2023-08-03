<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CancelRequest;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\StoreProducts;
use App\Models\User;
use App\Models\Wallet;
use Auth;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Stripe;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use Picqer\Barcode\BarcodeGeneratorPNG;



class OrderController extends Controller
{

    public function orderCount(Request $request)
    {
        $filterTitle = $request->filterTitle;
        $filterStatus = $request->filterStatus;
        $filterStore = $request->filterStore;
        $filterFrom = $request->filterFromDate;
        $filterTo = $request->filterToDate;

        $result = Order::query();

        if (isset($filterTitle) && $filterTitle != ' ') {

            $result = $result->where('order_number', 'like', '%' . $filterTitle . '%');
        }

        if (isset($filterStore) && $filterStore != 'code') {

            $result = $result->where('store_id', $filterStore);
        }
        if (isset($filterStatus) && $filterStatus != 'all') {

            $result = $result->where('order_status', $filterStatus);
        }

        if ($request->check == "1") {

            $result = $result->whereDate('created_at', '<=', $filterTo)->whereDate('created_at', '>=', $filterFrom);
        }

        $count = $result->count();

        if ($count > 0) {

            return response()->json(['status' => 'success', 'data' => $count]);

        } else {
            return response()->json(['status' => 'fail']);

        }
    }

    public function orderList(Request $request)
    {

        $filterTitle = $request->filterTitle;
        $filterStore = $request->filterStore;
        $filterStatus = $request->filterStatus;
        $filterFrom = $request->filterFromDate;
        $filterTo = $request->filterToDate;

        $result = Order::query();

        if ($filterTitle != '') {
            $result = $result->where('order_number', 'like', '%' . $filterTitle . '%');
        }
        if ($filterStatus != 'all') {

            $result = $result->where('order_status', $filterStatus);
        }
        if ($filterStore != 'code') {
            $result = $result->where('store_id', $filterStore);
        }
        if ($request->check == "1") {

            $result = $result->whereDate('created_at', '<=', $filterTo)->whereDate('created_at', '>=', $filterFrom);
        }

        $orders = $result->take($request->filterLength)->skip($request->offset)->orderBy('id', 'DESC')->get();

        if (isset($orders) && sizeof($orders) > 0) {
            $html = "";
            foreach ($orders as $order) {
                $status = OrderItem::where(['order_id' => $order->id])->pluck('status')->first();
                $id = OrderItem::where(['order_id' => $order->id])->pluck('id')->first();
                $paymentmethod= OrderItem::where('order_id',$order->id)->first();
                $paymentmethod= OrderItem::where('order_id',$order->id)->pluck('payment_getway')->first();
                if($paymentmethod =='Cash on Delivery'){
                    $paymethod = 'COD';
                }else{
                    $paymethod = 'Stripe';
                }
                if ($order->order_status == "cancelled") {
                    $badge = "badge rounded-pill alert-danger";
                    $btn = '
                    ';
                } elseif ($order->order_status == "deliverd") {
                    $badge = "badge rounded-pill alert-success";
                    $btn = '
                    ';
                } elseif ($order->order_status == "Return Pending") {
                    $badge = "badge rounded-pill alert-danger";
                    $btn = '
                    ';
                } elseif ($order->order_status == "refund rejected") {
                    $badge = "badge rounded-pill alert-danger";
                    $btn = '
                    ';
                } elseif ($order->order_status == "refunded") {
                    $badge = "badge rounded-pill alert-success";
                    $btn = '
                    ';
                } elseif ($order->order_status == "pending") {
                    $badge = "badge rounded-pill alert-danger";

                    $btn = '
                    <a href="javascript:;" id="' . $order->id . '" class="dropdown-item text-danger btnCancel" data-bs-target="#orderCancellation" data-bs-toggle="modal">Cancel</a>
                    <a class="dropdown-item change_status change_st' . $order->id . '"  id="' . $order->id . '" href="javascript:;">Accepted</a>


                    ';
                } elseif ($order->order_status == "accepted") {
                    $badge = "badge rounded-pill alert-success";

                    $btn = '
                    <a class="dropdown-item change_status change_st' . $order->id . '"  id="' . $order->id . '" href="javascript:;">Dispatch</a>
                    ';
                } elseif ($order->order_status == "dispatch") {
                    $badge = "badge rounded-pill alert-success";

                    $btn = '
                    <a class="dropdown-item change_status change_st' . $order->id . '"  id="' . $order->id . '" href="javascript:;">Deliverd</a>

                     ';
                } elseif ($order->order_status == "return") {
                    $badge = "badge badge-pill bg-danger me-1 my-2";
                    $btn = '
                    ';
                }
                if ($order->order_status == 'deliverd') {
                    $badges = "badge rounded-pill alert-success";
                    $pstatus = "Paid";
                } else if($order->stripe_payment_id !=''){
                    $badges = "badge rounded-pill alert-success";
                    $pstatus = "Paid";
                }else{
                    $badges = "badge rounded-pill alert-danger";
                    $pstatus = "Pending";
                }

                $adstorename = User::where(['code' => $order->store_id])->pluck('name')->first();
                $html .= '

                <tr>
                <td> ' . ($order->created_at)->isoFormat('MMM Do YYYY') . ' </td>
                    <td><b>' . $order->order_number . '</b></td>
                    <td>'.$order->first_name.' <br>
                    <span>'.$order->phone.'</span>
                   </td>
                    <td>' . round($order->coupon_payment, 2) . '</td>
                    <td>'.$paymethod.'</td>
                   
               <td>
               <span class=" ' . $badges . ' ">' . $pstatus . ' </span>
               </td>
                    <td>
                        <span class=" ' . $order->id . '' . $badge . ' ">' . ucwords($order->order_status) . '</span>
                    </td>
                    <td>'.$order->delivery_option.'</td>
                    <td> ' . $adstorename . ' <br>
                    <span>  <b>Store Code: ' . $order->store_id . '<span>
                    </td>
                    <td class="text-end">
                        <a href="order/detail/' . $order->id . '"
                            class="btn btn-md rounded font-sm">Detail</a>
                           
                        </div>
                        </div>
                        <!-- dropdown //end -->
                    </td>
                </tr>
                ';
            }

            return response()->json(['status' => 'success', 'rows' => $html]);
        } else {
            return response()->json(['status' => 'fail']);
        }
    }

    public function storeorderCount(Request $request)
    {
        $filterTitle = $request->filterTitle;
        $filterStatus = $request->filterStatus;

        $filterFrom = $request->filterFromDate;
        $filterTo = $request->filterToDate;

        $result = Order::query();

        if (isset($filterTitle) && $filterTitle != ' ') {

            $result = $result->where('order_number', 'like', '%' . $filterTitle . '%');
        }

        if (isset($filterStatus) && $filterStatus != 'all') {

            $result = $result->where('order_status', $filterStatus);
        }

        if ($request->check == "1") {

            $result = $result->whereDate('created_at', '<=', $filterTo)->whereDate('created_at', '>=', $filterFrom);
        }

        $count = $result->count();

        if ($count > 0) {

            return response()->json(['status' => 'success', 'data' => $count]);

        } else {
            return response()->json(['status' => 'fail']);

        }
    }
    public function storeOrderlist(Request $request)
    {

        $filterTitle = $request->filterTitle;
        $filterStatus = $request->filterStatus;
        $filterFrom = $request->filterFromDate;
        $filterTo = $request->filterToDate;

        $result = Order::query();

        if ($filterTitle != '') {
            $result = $result->where('order_number', 'like', '%' . $filterTitle . '%');
        }
        if ($filterStatus != 'all') {

            $result = $result->where('order_status', $filterStatus);
        }

        if ($request->check == "1") {

            $result = $result->whereDate('created_at', '<=', $filterTo)->whereDate('created_at', '>=', $filterFrom);
        }

        if (Auth::user()->hasRole('Store')) {

            $orders = $result->take($request->filterLength)->skip($request->offset)->where('store_id', Auth::user()->code)->orderBy('id', 'DESC')->get();
        } else {
            $orders = $result->take($request->filterLength)->skip($request->offset)->where('store_id', Auth::user()->store_id)->orderBy('id', 'DESC')->get();

        }

        if (isset($orders) && sizeof($orders) > 0) {
            $html = "";
            foreach ($orders as $order) {
                $status = OrderItem::where(['order_id' => $order->id])->pluck('status')->first();
                $id = OrderItem::where(['order_id' => $order->id])->pluck('id')->first();
                $paymentmethod= OrderItem::where('order_id',$order->id)->pluck('payment_getway')->first();
                if($paymentmethod =='Cash on Delivery'){
                    $paymethod = 'COD';
                }else{
                    $paymethod = 'Stripe';
                }
                if ($order->order_status == "cancelled") {
                    $badge = "badge rounded-pill alert-danger";
                    $btn = '
                    ';
                } elseif ($order->order_status == "deliverd") {
                    $badge = "badge rounded-pill alert-success";
                    $btn = '
                    ';
                } elseif ($order->order_status == "Return Pending") {
                    $badge = "badge rounded-pill alert-danger";
                    $btn = '
                    ';
                } elseif ($order->order_status == "refund rejected") {
                    $badge = "badge rounded-pill alert-danger";
                    $btn = '
                    ';
                } elseif ($order->order_status == "refunded") {
                    $badge = "badge rounded-pill alert-success";
                    $btn = '
                    ';
                } elseif ($order->order_status == "pending") {
                    $badge = "badge rounded-pill alert-danger";

                    $btn = '
                    <a href="javascript:;" id="' . $order->id . '" class="dropdown-item text-danger btnCancel" data-bs-target="#orderCancellation" data-bs-toggle="modal">Cancel</a>
                    <a class="dropdown-item change_status change_st' . $order->id . '"  id="' . $order->id . '" href="javascript:;">Accepted</a>


                    ';
                } elseif ($order->order_status == "accepted") {
                    $badge = "badge rounded-pill alert-success";

                    $btn = '
                    <a class="dropdown-item change_status change_st' . $order->id . '"  id="' . $order->id . '" href="javascript:;">Dispatch</a>
                    <a href="javascript:;" id="' . $order->id . '" class="dropdown-item text-danger btnCancel" data-bs-target="#orderCancellation" data-bs-toggle="modal">Cancel</a>
                    
                    ';
                } elseif ($order->order_status == "dispatch") {
                    $badge = "badge rounded-pill alert-success";

                    $btn = '
                    <a class="dropdown-item change_status change_st' . $order->id . '"  id="' . $order->id . '" href="javascript:;">Deliverd</a>
                    <a href="javascript:;" id="' . $order->id . '" class="dropdown-item text-danger btnCancel" data-bs-target="#orderCancellation" data-bs-toggle="modal">Cancel</a>

                     ';
                } elseif ($order->order_status == "return") {
                    $badge = "badge badge-pill bg-danger me-1 my-2";
                    $btn = '
                    ';
                }
                if ($order->order_status == 'deliverd') {
                    $badges = "badge rounded-pill alert-success";
                    $pstatus = "Paid";
                } else if($order->stripe_payment_id !=''){
                    $badges = "badge rounded-pill alert-success";
                    $pstatus = "Paid";
                }else{
                    $badges = "badge rounded-pill alert-danger";
                    $pstatus = "Pending";
                }

                // $adstorename = User::where(['code' => $order->store_id])->pluck('name')->first();
                $html .= '

                <tr>
                <td> ' . ($order->created_at)->isoFormat('MMM Do YYYY') . ' </td>
                    <td><b>' . $order->order_number . '</b></td>
                    <td>'.$order->first_name.' <br>
                    <span>'.$order->phone.'</span>
                   </td>
                   
                    <td>' . round($order->coupon_payment, 2) . '</td>
                    <td>'.$paymethod.'</td>
                   
               <td>
               <span class=" ' . $badges . ' ">' . $pstatus . ' </span>
               </td>
                    <td>
                        <span class=" ' . $order->id . '' . $badge . ' ">' . ucwords($order->order_status) . '</span>
                    </td>
                    <td>'.$order->delivery_option.'</td>
                    
                    <span>  <b>Store Code: ' . $order->store_id . '<span>
                    </td>
                    <td class="text-end">
                        <a href="/store/order/detail/' . $order->id . '"
                            class="btn btn-md rounded font-sm">Detail</a>
                           
                        </div>
                        </div>
                        <!-- dropdown //end -->
                    </td>
                </tr>
                ';
            }

            return response()->json(['status' => 'success', 'rows' => $html]);
        } else {
            return response()->json(['status' => 'fail']);
        }
    }

    public function getOrders()
    {
        $orders = Order::orderBy('id', 'DESC')->get();
        return view('admin.orders.order-list', compact('orders'));
    }

    public function getCancelOrders()
    {
        $orders = CancelRequest::where('store_id', Auth::user()->code)->get();
        return view('admin.orders.order-cancel-list', compact('orders'));
    }

    public function orderDetail($id)
    {

        $orders = Order::where('id', $id)->first();
        $order = OrderItem::where('order_id', $orders->id)->get();

        if ($orders) {
            return view('admin.orders.order-detail', compact('orders', 'order'));
        }

    }

    public function storeOrders(Request $request)
    {
        if (Auth::user()->hasRole('Store')) {
            $orders = Order::latest()->where('store_id', Auth::user()->code)->get();
        } else {
            $orders = Order::latest()->where('store_id', Auth::user()->store_id)->get();
        }
        return view('admin.orders.stores-order-list', compact('orders'));
    }
    public function storeorderDetail($id)
    {

        $orders = Order::where('id', $id)->first();
        $order = OrderItem::where('order_id', $orders->id)->get();

        if (auth::user()->hasRole('Store')) {
            $storeproductsss = StoreProducts::where('store_id', auth::user()->code)->where('stock', 'yes')->get();
            foreach ($storeproductsss as $storpross) {

                $store_product = Product::where('barcode', $storpross->barcode)->where('published', 1)->get();

            }
        } elseif (auth::user()->type == '2') {
            $storeproductsss = StoreProducts::where('store_id', auth::user()->code)->where('stock', 'yes')->get();
            // foreach($storeproductsss as $storpross){
            //     $store_product = Product::where('id',$storpross->product_id)->where('published',0)->get();

            // }
        }

        if ($orders) {
            return view('admin.orders.store-order-detail', compact('orders', 'order', 'store_product', 'storeproductsss'));
        }

    }

    public function editOrder(Request $request)
    {
        $orderx = OrderItem::where('id', $request->orderx)->first();
        if (!$orderx) {
            return response()->json([
                'status' => 'error',
                'message' => 'order not Found',
            ], 400);
        } else {
            return response()->json([
                'status' => 'success',
                'data' => $orderx,
            ]);
        }
    }

    public function updateOrder(Request $request)
    {
        $productorder = Product::where('id', $request->product_id)->first();
        $productprice = $productorder->discounted_price;
        $orderxitem = OrderItem::find($request->id);
        $orderxitem->product_id = $request->product_id;
        $orderxitem->quantity = $request->quantity;

        $orderxitem->product_price = $productprice;
        $orderxitem->products_quantityprice = $productprice * $request->quantity;
       
        $orderxitem->save();

        $orderx = Order::where('id', $orderxitem->order_id)->first();
        $subtotal = OrderItem::where('order_id', $orderx->id)->sum('products_quantityprice');
        $productvat = $productorder->where('vat', 'yes');
        if ($productorder->vat == 'yes') {
            $vat_price = DB::table('web_settings')->pluck('vat')->first();
            $qprice = $orderxitem->products_quantityprice;
            $vatprice = ($qprice * $vat_price) / 100;

        } else {
            $vatprice = '0';
        }
        if ($orderx->total_vat) {
            $orderx->total_vat = $vatprice;
        }

        //   $productdeliverycharges = $productorder->shipping_cost;
        //   if($productdeliverycharges){

        //   }
        if ($orderx->coupontype != null) {
            if ($orderx->coupontype == 'flate') {
                $orderx->coupondiscount = $subtotal - $orderx->couponpercentage;
            } elseif ($orderx->coupontype == 'percentage') {
                $coupon_value = $orderx->couponpercentage;
                $checkout_amount = $subtotal * $coupon_value / 100;
                $orderx->coupondiscount = $checkout_amount;
            } else {
                $orderx->coupondiscount = '0';
            }
        }
        if ($orderx->coupondiscount) {
            $orderx->coupon_payment = $subtotal - $orderx->coupondiscount + $orderx->total_vat;
        } else {
            $orderx->coupon_payment = $subtotal + $orderx->total_vat;
        }
        $orderx->updated_by=Auth::user()->name;
        $orderx->save();

        if ($orderx) {
            return response()->json([
                'status' => 'success',
                'msg' => 'Order Updated Successfully',
            ], 200);

        } else {
            return response()->json([
                'status' => 'fail',
                'msg' => 'something went wrong',
            ], 200);
        }

    }

    public function cancel_order(Request $request, $id)
    {
       $reason = $request->get("reason");
       
        $ordr = Order::where('id', $id)->first();
        
        $ordr = $ordr->changeStatus("cancelled");
        $reasonMsg = 'Cancelled by user';
        if(isset($reason) and !empty($reason)){
            $ordr->reason = $reason;
            $ordr->save();
            $reasonMsg = $reason;
        }
        $orders = OrderItem::where('order_id', $id)->get();
        foreach ($orders as $order) {
            $orderitem = OrderItem::find($order->id);
            $orderitem->status = 'cancelled';
            $orderitem->save();
        }
        
        $fromEmail = config('app.from_email');
        $fromApp = config('app.name');
        Mail::send('email.order-cancel', ['order' => $ordr, 'reason' => $reasonMsg], function ($message) use ($ordr, $fromEmail, $fromApp) {
            $message->to($ordr->email)
                ->from($fromEmail, $fromApp)
                ->subject("Order Cancelled");
        });
        return response()->json(['status' => 'success', 'msg' => "Status updated succesfully "]);
    }

    public function userorderDetail($id)
    {

        $orders = Order::where('id', $id)->first();
        $order = OrderItem::where('order_id', $orders->id)->get();
        if ($orders) {
            return view('user.orders.user-order-detail', compact('orders', 'order'));
        }
    }

    public function order_status(Request $request)
    {
        $orders = OrderItem::where('order_id', $request->order_id)->get();
        if ($request->status == 'accepted') {
            $status = "accepted";
        } elseif ($request->status == 'dispatch') {
            $status = "dispatch";
        } elseif ($request->status == 'deliverd') {
            $status = "deliverd";
        }

        $or = Order::where('id', $request->order_id)->first();
        // $or->order_status = $status;
        $or->changeStatus($status);
         $or->updated_by = Auth::user()->name;
        $or->save();

        if ($or->order_status == 'deliverd') {
            $fromEmail = config('app.from_email');
            $fromApp = config('app.name');
            Mail::send('email.order-delivered', ['order' => $or], function ($message) use ($or, $fromEmail, $fromApp) {
                $message->to($or->email)
                    ->from($fromEmail, $fromApp)
                    ->subject("Order Delivered");
            });
        }
        foreach ($orders as $order) {

            $orderitem = OrderItem::find($order->id);
            $orderitem->status = $status;
            $orderitem->save();
        }
        return response()->json(['status' => 'success', 'msg' => "Order Status Changed Succesffully "]);

    }

    public function cancelRequest(Request $request)
    {
        $productprice = OrderItem::where('product_id',$request->product_id)->first();
        $edit = new cancelRequest();
        $edit->order_id = $request->order_id;
        $edit->user_id = Auth::user()->id;
        $edit->reason = $request->reason;
        $edit->product_id = $request->product_id;
        $edit->product_price = $productprice->products_quantityprice;
        $edit->return_type = $request->return_type;
        $edit->store_id = $request->store_id;
        $edit->refund_status = "refund";
        if ($edit->save()) {
            $payment_gateway = OrderItem::where('order_id', $request->order_id)->first();
            if ($payment_gateway->payment_getway == 'stripe') {
                OrderItem::where('order_id', $edit->order_id)->where('product_id', $edit->product_id)->update(['refund_status' => '1' , 'status' => 'Return Pending']);
                Order::where('id', $edit->order_id)->update(['order_status' => 'Return Pending']);
            } else {
                OrderItem::where('order_id', $edit->order_id)->update(['refund_status' => '1', 'status' => 'Return Pending']);
                Order::where('id', $edit->order_id)->update(['order_status' => 'Return Pending']);
            }
        }
        return response()->json(['status' => 'success', 'msg' => 'Cancel Rquest placed successfully']);
    }

    public function cancelRequestUpdate(Request $request)
    {

        $order = CancelRequest::find($request->id);
        if ($request->dbid == '1') {
            $charge = Order::where('id', $order->order_id)->pluck('stripe_payment_id')->first();
            $payment_gateway = OrderItem::where('order_id', $order->order_id)->first();
            if ($payment_gateway->payment_gateway == 'stripe') {
                $product_price = OrderItem::where('order_id', $order->order_id)->where('product_id', $order->product_id)->first();
            } else {
                $product_price = OrderItem::where('order_id', $order->order_id)->where('product_id', $order->product_id)->first();
            }
            if ($order->return_type == 'return_wallet') {
                $check = Wallet::where('user_id', $order->user_id)->first();
                if ($check) {
                    $wallet = Wallet::where('user_id', $order->user_id)->first();
                    $wallet->amount = $check->amount + $product_price->product_price;
                    $wallet->save();
                } else {
                    $wallet = new Wallet();
                    $wallet->user_id = $order->user_id;
                    $wallet->amount = $product_price->product_price;
                    $wallet->save();
                }
            }

                // $st = OrderItem::where('order_id',$order->order_id)->where('product_id',$order->product_id);
                // $st->refund_status = '2';
                // $st->save();

            if ($product_price->payment_getway == 'stripe') {
                \Stripe\Stripe::setApiKey("sk_test_51Jeb4lCFc8UmysBw6zAYE967rwvDiyn6OBtN63kYE0lEpUSO7tSYBKxIVcg5OyVk0VbwgjPHpIMDOfKufoe5bHBe00Ir9geDsP");
                $refund = \Stripe\Refund::create([
                    'charge' => $charge,
                    'amount' => $product_price->product_price * 100, // For 10 $
                    'reason' => $order->reason,
                ]);
                $order->status = "1";
                 $order->updated_by = Auth::user()->name;
                $order->save();
                $payment_gateways = OrderItem::where('order_id', $order->order_id)->get();
                foreach ($payment_gateways as $payment_gateway) {
                    $payment_gateway->refund_status = "2";
                    $payment_gateway->status = "refunded";
                    $payment_gateway->save();
                }
                $paymentStatus = Order::where('id', $order->order_id)->first();
                $paymentStatus->order_status = "refunded";
                 $paymentStatus->updated_by = Auth::user()->name;
                $paymentStatus->save();
                return response()->json(['status' => 'success', 'msg' => 'Refund Approved Successfully']);
            } else {
                $order->status = "1";
                $order->save();
                $payment_gateways = OrderItem::where('order_id', $order->order_id)->get();
                foreach ($payment_gateways as $payment_gateway) {
                    $payment_gateway->refund_status = "2";
                    $payment_gateway->status = "refunded";
                    $payment_gateway->save();
                }
                $paymentStatus = Order::where('id', $order->order_id)->first();
                $paymentStatus->order_status = "refunded";
                 $paymentStatus->updated_by = Auth::user()->name;
                $paymentStatus->save();
                return response()->json(['status' => 'success', 'msg' => 'Refund Approved Successfully']);
            }
        } 
    }
    public function rejectlRequestUpdate(Request $request)
    {
        $ordr = CancelRequest::find($request->id);
        $ordr->status = '2';
        $ordr->rejectreason = $request->rejectreason;
        $ordr->save();

        OrderItem::where('order_id', $ordr->order_id)->where('product_id', $ordr->product_id)->update(['refund_status' => '0', 'status' => 'refund rejected']);
        Order::where('id', $ordr->order_id)->update(['order_status' => 'refund rejected']);

        $user = User::where('id',$ordr->user_id)->first();
        $fromEmail = config('app.from_email');
        $fromApp = config('app.name');
        Mail::send('email.order-cancel', ['order' => $ordr, 'reason' => $request->rejectreason], function ($message) use ($user, $fromApp, $fromEmail) {
            $message->to($user->email)
                ->from($fromEmail, $fromApp)
                ->subject("Order Rejected");
        });

        return response()->json(['status' => 'success', 'msg' => 'Request Rejected successfully']);
    }


    public function returnDetailview($id)
    {
        $orders = CancelRequest::where('id',$id)->first();
        $order = OrderItem::where('order_id', $orders->order_id)->get();
      

      
        if ($orders) {
            return view('admin.orders.cancel-order-detail', compact('orders','order'));
        }
    }

    public function optionAppend(Request $request)
    {
        $check = CancelRequest::where('order_id', $request->id)->first();
        if ($check) {
            return response()->json(['status' => 'fail', 'msg' => 'Cancel Request already submited']);
        }
        $products = OrderItem::where('order_id', $request->id)->where('refund_status', 0)->get();
        $product_method = OrderItem::where('order_id', $request->id)->pluck('payment_getway')->first();
        $html = "";
        $select1 = "";
        $select2 = "";
        foreach ($products as $product) {
            $html .= '
            <option  disabled selected></option>
            <option value=' . $product->product_id . '>' . Product::where('id', $product->product_id)->pluck('name')->first() . '</option>
            ';
        }
        if ($product_method == 'stripe') {
            $select1 .= '
            <div class="mb-3">
                <label for="" class="form-label">Select fund type</label>
                <select class="form-select" aria-label="Default select example" required name="return_type">
                    <option  disabled>Select type</option>
                    <option value="return_wallet">Return to wallet</option>
                    <option value="return_bank">Return to bank</option>
                </select>
            </div>
        ';
        } else {
            $select2 .= '
            <div class="mb-3">
                <label for="" class="form-label">Select return type</label>
                <select class="form-select" aria-label="Default select example" required name="return_type">
                    <option  disabled>Select type</option>
                    <option value="return_store">Return to store</option>
                    <option value="collect_shipping">Collect from shipping address</option>
                </select>
            </div>
        ';
        }
        return response()->json(['status' => 'success', 'html' => $html, 'select1' => $select1, 'select2' => $select2]);
    }

    public function orderTracking(Request $request)
    {
        $order = Order::where('order_number', $request->order_number)->first();
        if ($order) {
            $orderStatus = OrderItem::where('order_id', $order->id)->pluck('status')->first();
            $html = "";
            // $html.=' <p>'.$orderStatus.'</p>';
            $html .= '<div class="container">
            <article class="card">
                <header class="card-header bg-success text-white"> Order Tracking </header>
                <div class="card-body">
                    <h6 class="text-success">Order ID: ' . $request->order_number . '</h6>
                    <article class="card">
                        <div class="card-body row">
                            <div class="col"> <strong>Status:</strong> <br> ' . $orderStatus . ' </div>
                            <div class="col"> <strong>Tracking #:</strong> <br> ' . $request->order_number . ' </div>
                        </div>
                    </article>
                </div>
            </article>
        </div>';
            return response()->json(['status' => 'success', 'html' => $html]);
        }
        return response()->json(['status' => 'fail', 'msg' => "Order Number is invalid"]);
    }

    public function invoiceDownload($id)
    {
        $order = Order::find($id);
        $orders = OrderItem::where('order_id', $order->id)->get();
        $generator = new BarcodeGeneratorPNG();
        $pdf = PDF::loadView('invoice', ['order' => $order, 'orders' => $orders,'generator'=> $generator]);
      
        return $pdf->setPaper('A4', 'portrait')->download($order->order_number . " " . '.pdf');
    }


    public function exportWorkingHour(Request $request){

       
        $filterFromDate=$request->filterFromDate;
        $filterToDate=$request->filterToDate;
        $filterStore=$request->filterStore;
        $filterStatus=$request->filterStatus;
       
        $result=Order::query();
        if (isset($filterStore) && $filterStore != 'all') {

            $result = $result->where('store_id', $filterStore);
        }
        if (isset($filterStatus) && $filterStatus != 'all') {

            $result = $result->where('order_status', $filterStatus);
        }
        $result=$result->whereDate('created_at', '>=', $filterFromDate)
            ->whereDate('created_at', '<=', $filterToDate);

           
        $result = $result->get();
    //   foreach($result as $r){
    //       if(!empty($r->cancelBy)){
    //           dd($r->cancelBy);
    //       }
    //   }
    //   dd($result);
        if (isset($result) && $result->count()>0){
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            $sheet->setCellValue('A1', 'Store Code')
            ->setCellValue('B1', 'Store Name')
            ->setCellValue('C1', 'Order Number')
            ->setCellValue('D1', 'Total Value')
            ->setCellValue('E1', 'Customer Name')
            ->setCellValue('F1', 'Order Status')
            ->setCellValue('G1', 'Order Date')
            ->setCellValue('H1', 'Cancelled By')
            ->setCellValue('I1', 'From Date')
            ->setCellValue('J1', 'To Date')
            ->setCellValue('K1', 'Updated By')
             ->setCellValue('L1', 'Payment Type');
      
      $header_style = $sheet->getStyle('A1:L1');
      $header_font = $header_style->getFont();
      $header_font->setBold(true);
      
     
            $rows = 2;
            foreach($result as $hours){
                $store=User::where('CODE',$hours->store_id)->first();
               
                $sheet->setCellValue('A' . $rows,  $hours['store_id']);
                $sheet->setCellValue('B' . $rows,  $store['name']);
                $sheet->setCellValue('C' . $rows,  $hours['order_number']);
               $sheet->setCellValue('D' . $rows, round($hours['coupon_payment'], 2));

                $sheet->setCellValue('E' . $rows, $hours['first_name']);
                $sheet->setCellValue('F' . $rows, $hours['order_status']);
                $sheet->setCellValue('G' . $rows,  date("d M Y", strtotime($hours['created_at'])));
                $sheet->setCellValue('H' . $rows,  $hours->cancelBy->email??"" );
                $sheet->setCellValue('I' . $rows, date("d M Y", strtotime($filterFromDate)));
                $sheet->setCellValue('J' . $rows, date("d M Y", strtotime($filterToDate)));
                $sheet->setCellValue('K' . $rows,  $hours['updated_by']);
               
                if($hours->stripe_payment_id !=''){
                     $sheet->setCellValue('L' .  $rows,  'Stripe');
                }else{
                    $sheet->setCellValue('L' .  $rows,  'COD');
                }
                $rows++;
            }

            $type='xls';
            $rand=rand(100,100000).time();
            $fileName = $rand.".".$type;
            $export = new Xls($spreadsheet);
            $path= "uploads/content/".$fileName;
           
            $export->save($path);
           
            return response()->json(['status'=>'success','msg'=>'Export created successfully', 'data' => $fileName]);
        }else{
            return response()->json(['status'=>'fail','msg'=>'No data found between these dates']);
        }
    }

    public function deleteReport(Request $request,$filename){

       
       $path= "uploads/content/";
        @unlink($path.$filename);
        return response()->json(['msg'=>'file deleted']);
    }

}
