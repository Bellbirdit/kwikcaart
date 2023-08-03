<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Auth;
use DB;
use Illuminate\Http\Request;
use Session;
use Carbon\Carbon;
use App\Models\Deals;
use App\Models\DealProduct;
use App\Models\StorewiseDeal;
use App\Models\StoredealProduct;

class CartController extends Controller
{

    public function add(Request $request, $id)
    {
        if (Auth::check() && (Auth::user()->type == '3')) {
            $store_id = session::get('store_id');
            $product = Product::find($id);
            $today = Carbon::today();
            if (isset($request->qty) && $request->qty != '') {
                $cartquantity = $request->qty;
            } else {
                $cartquantity = 1;
            }
            $cart = Cart::where('product_id', $id)->where('user_id', auth()->user()->id)->where('store_id', $store_id)->where('status', 'pending')->first();

            if (!$cart) {
                $img = $product->getImage($product->thumbnail);
                $price = $product->price;

                $storewisedeals = DB::table('storewise_deals')->where('start_date', '<=', $today)->where('end_date', '>=', $today)->where('status', '0')->where('store_id', $store_id)->first();
                 $allstoredeal = DB::table('deals')->where('start_date', '<=', $today)->where('end_date', '>=', $today)->where('status', '0')->first();


                
               if (isset($allstoredeal)) {
                         $alldealpr = DB::table('dealproduct')->where('product_id', $product->id)->where('deal_id', $allstoredeal->id)->first();
                    if ($alldealpr) {
                        if ($alldealpr->discount_type == 'percentage') {
                            $discount_price = $product->price * $alldealpr->discount / 100;
                            $price = $product->price - $discount_price;

                        } elseif ($alldealpr->discount_type == 'flat') {

                            $price = $product->price - $alldealpr->discount;
                            
                        }

                        if (isset($request->qty) && $request->qty != '') {
                            $cartquantity = $request->qty;
                        } else {
                            $cartquantity = 1;
                        }
                    }
                }
               
                if (isset($storewisedeals)) {
                    $stotedealproducts = DB::table('storedeal_products')->where('product_id', $product->id)->where('storedeal_id', $storewisedeals->id)->first();
                    if ($stotedealproducts) {
                        if ($stotedealproducts->discount_type == 'percentage') {
                            $discount_price = $product->price * $stotedealproducts->discount / 100;
                            $price = $product->price - $discount_price;
                        } else if ($stotedealproducts->discount_type == 'flat') {
                            $price = $product->price - $stotedealproducts->discount;
                        }

                        if (isset($request->qty) && $request->qty != '') {
                            $cartquantity = $request->qty;
                        } else {
                            $cartquantity = 1;
                        }
                    }
                }

                $cart = new Cart();
                $cart->product_id = $product->id;
                $cart->store_id = $store_id;
                $cart->user_id = Auth::user()->id;
                $cart->quantity = $cartquantity;
                $cart->name = $product->name;
                $cart->price = $price;
                $cart->quantity_price = $price * $cartquantity;
                $cart->image = $img;
                Session::forget('checkout_amount');
                Session::forget('coupon_status');
                Session::forget('coupondiscount');


                $cart->save();
            } else {
                $cart->quantity = $cart->quantity + 1;
                $cart->quantity_price = $cart->quantity * $cart->price;
                Session::forget('checkout_amount');
                Session::forget('coupon_status');
                Session::forget('coupondiscount');


                $cart->save();
            }

            $html = "";
            $carts = Cart::where('user_id', auth()->user()->id)->where('store_id', $store_id)->where('status', 'pending')->get();
            foreach ($carts as $cart) {
                $at = $cart->image;

                $file = asset('uploads/files/' . $at);
                $html .= '
                <li id="remove' . $cart->id . '">
                    <div class="shopping-cart-img">
                        <a href="shop-product-right.html"><img alt="Nest" src="' . $file . '" /></a>
                    </div>
                    <div class="shopping-cart-title">
                        <h4><a href="shop-product-right.html">' . $cart->name . '</a></h4>
                        <h4><span>' . $cart->quantity . '×</span>' . round($cart->price, 2) . '</h4>
                    </div>
                    <div class="shopping-cart-delete">
                        <a href="javascript:;" id="' . $cart->id . '" class="remove"><i class="fi-rs-cross-small"></i></a>
                    </div>
                </li>

			    ';
            }
            $cart_count = Cart::where('user_id', auth()->user()->id)->where('store_id', $store_id)->where('status', 'pending')->count();
            $total = Cart::where('user_id', auth()->user()->id)->where('store_id', Session::get('store_id'))->where('status', 'pending')->sum('quantity_price');
            $total = round($total, 2);
            return response()->json(['status' => 'success', 'msg' => 'Item added to cart', 'total' => $total, 'html' => $html, 'cart_count' => $cart_count]);
        } else {

            return response()->json(['status' => 'fail', 'msg' => 'Please login as user to proceed']);
        }
    }

    public function add_multiple(Request $request)
    {

        if (Auth::check() && (Auth::user()->type == '3')) {
            $store_id = session::get('store_id');
            if (isset($request->qty) && $request->qty != '') {
                $cartquantity = $request->qty;
            } else {
                $cartquantity = 1;
            }

            foreach (json_decode($request->products) as $pro) {
                $today = Carbon::today();
                $product = Product::find($pro);
                $cart = Cart::where('product_id', $product->id)->where('user_id', auth()->user()->id)->where('store_id', $store_id)->where('status', 'pending')->first();
                if (!$cart) {

                    $img = $product->getImage($product->thumbnail);

                    $price = $product->price;

                    $stores_deal = DB::table('storedeal_products')->where('product_id', $product->id)->first();
                    $allstoredeal = DB::table('dealproduct')->where('product_id', $product->id)->first();

                    if (isset($stores_deal)) {
                        $deal_discount = DB::table('storewise_deals')->where('id', $stores_deal->storedeal_id)->where('start_date', '<=', $today)->where('end_date', '>=', $today)->where('status', '0')->first();
                        if ($deal_discount) {
                            $discount_price = $product->price * $deal_discount->discount / 100;
                            $price = $product->price - $discount_price;
                            if (isset($request->qty) && $request->qty != '') {
                                $cartquantity = $request->qty;
                            } else {
                                $cartquantity = 1;
                            }
                        }
                    } else if (isset($allstoredeal)) {

                        $alldeal_discount = DB::table('deals')->where('id', $allstoredeal->deal_id)->where('start_date', '<=', $today)->where('end_date', '>=', $today)->where('status', '0')->first();
                        if ($alldeal_discount) {
                            $discount_price = $product->price * $alldeal_discount->discount / 100;
                            $price = $product->price - $discount_price;
                            if (isset($request->qty) && $request->qty != '') {
                                $cartquantity = $request->qty;
                            } else {
                                $cartquantity = 1;
                            }
                        }
                    } else {
                        $price = $product->discounted_price;
                    }

                    $cart = new Cart();
                    $cart->product_id = $product->id;
                    $cart->store_id = $store_id;
                    $cart->user_id = Auth::user()->id;
                    $cart->quantity = $cartquantity;
                    $cart->name = $product->name;
                    $cart->price = $price;
                    $cart->quantity_price = $price * $cartquantity;
                    $cart->image = $img;



                    Session::forget('checkout_amount');
                    Session::forget('coupon_status');
                    Session::forget('coupondiscount');
                    $cart->save();
                } else {
                    $cart->quantity = $cart->quantity + 1;
                    $cart->quantity_price = $cart->quantity * $cart->price;
                    Session::forget('checkout_amount');
                    Session::forget('coupon_status');
                    Session::forget('coupondiscount');
                    $cart->save();
                }
            }
            $html = "";

            $carts = Cart::where('user_id', auth()->user()->id)->where('store_id', $store_id)->where('status', 'pending')->get();

            foreach ($carts as $cart) {
                $at = $cart->image;
                $file = asset('uploads/files/' . $at);
                $html .= '
			<li id="remove' . $cart->id . '">
				<div class="shopping-cart-img">
					<a href="shop-product-right.html"><img alt="Nest" src="' . $file . '" /></a>
				</div>
				<div class="shopping-cart-title">
					<h4><a href="shop-product-right.html">' . $cart->name . '</a></h4>
					<h4><span>' . $cart->quantity . ' × </span>' . round($cart->price, 2) . '</h4>
				</div>
				<div class="shopping-cart-delete">
					<a href="javascript:;" id="' . $cart->id . '" class="remove"><i class="fi-rs-cross-small"></i></a>
				</div>
			</li>

			';
            }
            $cart_count = Cart::where('user_id', auth()->user()->id)->where('store_id', $store_id)->where('status', 'pending')->count();
            $total = Cart::where('user_id', auth()->user()->id)->where('store_id', $store_id)->where('status', 'pending')->sum('quantity_price');
            $total = round($total, 2);
            return response()->json(['status' => 'success', 'msg' => 'Item added to cart', 'total' => $total, 'html' => $html, 'cart_count' => $cart_count]);
        } else {
            return response()->json(['status' => 'fail', 'msg' => 'Please login as user to proceed']);
        }
    }
    public function remove($id)
    {
        $store_id = Session::get('store_id');
        $cart = Cart::find($id);

        if ($cart) {

            $cart->delete();
            $total = Cart::where('user_id', auth()->user()->id)->where('store_id', $store_id)->sum('price');
            $total = round($total, 2);
            $cart_count = Cart::where('user_id', auth()->user()->id)->where('store_id', $store_id)->count();
            Session::forget('checkout_amount');
            Session::forget('coupon_status');
            Session::forget('coupondiscount');

            return response()->json(['status' => 'success', 'msg' => 'Item Removed Successfully', 'total' => $total, 'cart_count' => $cart_count]);
        }
    }
    public function view_cart()
    {
        $store_id = Session::get('store_id');
        $checkout = Session::get('checkout_amount');
        $carts = Cart::where('user_id', auth()->user()->id)->where('store_id', $store_id)->where('status', 'pending')->get();
        $deliverycharges = $carts->sum('shipping_cost');
        if ($deliverycharges) {
            Session::put('deliverycharges', $deliverycharges);
        }


        $subtotal = $carts->sum('quantity_price');
        if ($checkout != '') {
            $coupondiscount = $subtotal - $checkout;
        } else {
            $coupondiscount = 0;
        }
        if ($coupondiscount) {
            Session::put('coupondiscount', $coupondiscount);
        }
        $grandtotal = $subtotal - $coupondiscount + $deliverycharges;
        if ($grandtotal) {
            Session::put('grandtotal', $grandtotal);
        }
        return view('frontend/cart/cart-view', compact('grandtotal', 'carts', 'subtotal', 'checkout', 'coupondiscount', 'deliverycharges'));
    }
    public function increment($id)
    {

        $cart = Cart::find($id);
        if ($cart) {
            $cart->quantity = $cart->quantity + 1;
            $cart->quantity_price = $cart->quantity * $cart->price;

            $cart->save();
            Session::forget('checkout_amount');
            Session::forget('coupon_status');
            Session::forget('coupondiscount');
            return redirect()->back();
        }
    }
    public function decrement($id)
    {
        $cart = Cart::find($id);
        if ($cart) {
            $cart->quantity = $cart->quantity - 1;
            $cart->quantity_price = $cart->quantity * $cart->price;

            if ($cart->quantity > 1) {
                $cart->save();
            } else {
                $cart->quantity = 1;
                $cart->quantity_price = $cart->quantity * $cart->price;

                $cart->save();
            }

            Session::forget('checkout_amount');
            Session::forget('coupon_status');
            Session::forget('coupondiscount');

            return redirect()->back();
        }
    }
}
