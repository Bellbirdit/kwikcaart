<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Order;
use Illuminate\Http\Request;
use Session;
use Carbon\Carbon;

class CouponController extends Controller
{
    public function coupon_view()
    {
        $coupon = Coupon::all();

        return view('admin.coupon.coupon_list', ['coupons' => $coupon]);
    }

    public function coupon_add(Request $request)
    {
        if ($request->coupon_type == 'percentage' && $request->coupon_value > 100) {

            return response()->json(['status' => 'fail', 'msg' => 'It must be less than or equal to 100%']);
        }

        $coupon = new Coupon();
        $coupon->coupon_title = $request->coupon_title;
        $coupon->coupon_type = $request->coupon_type;
        $coupon->coupon_code = $request->coupon_code;
        $coupon->coupon_value = $request->coupon_value;
        $coupon->start_date = $request->start_date;
        $coupon->expiry = $request->expiry;
        $coupon->coupon_limit = $request->coupon_limit;

        if ($coupon->save()) {
            return response()->json(['status' => 'success', 'msg' => 'Coupon Add Successfully']);
        } else {
            return response()->json(['status' => 'fail', 'msg' => 'Try again']);
        }
    }

    public function coupon_edit($id)
    {
        $coupon = Coupon::find($id);
        if ($coupon) {
            return response()->json(['status' => 'success', 'msg' => 'Coupon Add Successfully', 'data' => $coupon]);
        } else {
            return response()->json(['status' => 'fail', 'msg' => 'Try again']);
        }
    }

    public function coupon_update(Request $request)
    {
        $coupon = Coupon::find($request->id);
        $coupon->coupon_title = $request->coupon_title;
        $coupon->coupon_type = $request->coupon_type;
        $coupon->coupon_code = $request->coupon_code;
        $coupon->coupon_value = $request->coupon_value;
        $coupon->start_date = $request->start_date;
        $coupon->expiry = $request->expiry;
        $coupon->coupon_limit = $request->coupon_limit;

        if ($coupon->save()) {
            return response()->json(['status' => 'success', 'msg' => 'Coupon Add Successfully']);
        } else {
            return response()->json(['status' => 'fail', 'msg' => 'Try again']);
        }
    }

    public function coupon_delete($id)
    {
        $coupon = Coupon::find($id);
        if ($coupon->delete()) {
            return response()->json(['status' => 'success', 'msg' => 'Coupon Deleted Successfully']);
        } else {
            return response()->json(['status' => 'fail', 'msg' => 'Try again']);
        }
    }

    public function coupon_status($id)
    {
        $coupon = Coupon::find($id);
        $coupon->status = $request->status;
        if ($coupon->save()) {
            return response()->json(['status' => 'success', 'msg' => 'Coupon ' . ucwords($request->status) . ' Successfully']);
        } else {
            return response()->json(['status' => 'fail', 'msg' => 'Try again']);
        }
    }

    public function coupon_apply(Request $request)
    {
        
        $store_id = Session::get('store_id');
        $couponId = Order::where('user_id', auth()->user()->id)
            ->where('coupon_id',$request->coupon_code)
            ->first();
        if ($couponId) {
            return response()->json(['status' => 'fail', 'msg' => 'Coupon already used']);
        } else {
            $coupon = Coupon::where('coupon_code', $request->coupon_code)->first();
            if ($coupon) {
                $total = Cart::where('user_id', auth()->user()->id)->where('store_id', $store_id)->where('status', 'pending')->sum('quantity_price');
                $start_date = Carbon::parse($coupon->start_date);
                $end_date = Carbon::parse($coupon->expiry);
                $today = Carbon::today();
                if ($coupon->status != 1) {
                    return response()->json(['status' => 'fail', 'msg' => 'Coupon is inactive']);
                } elseif ($today->greaterThan($end_date)) {
                    return response()->json(['status' => 'fail', 'msg' => 'Coupon has expired']);
                } elseif ($total >= $coupon->coupon_limit) {
                    $coupon_status = Session::get('coupon_status');
                    if ($coupon_status == 'yes') {
                        return response()->json(['status' => 'fail', 'msg' => 'Coupon already applied']);
                    } else {
                        if ($coupon->coupon_type == 'flat') {
                            if ($total > $coupon->coupon_value) {
                                $coupon_flatvalue = $coupon->coupon_value;
                                $checkout_amount = $total - $coupon->coupon_value;
                                Session::put('coupondiscount', $coupon_flatvalue);
                                Session::put('checkout_amount', $checkout_amount);
                                Session::put('coupon_status', 'yes');
                                Session::put('coupon_value', $coupon_flatvalue);
                                Session::put('coupon_id', $coupon->coupon_code);
                                return response()->json(['status' => 'success', 'msg' => 'Coupon Applied Successfully']);
                            } else {
                                return response()->json(['status' => 'fail', 'msg' => 'Coupon does not apply on this amount']);
                            }
                        } else  if ($coupon->coupon_type == 'percentage'){
                            $total = $total;
                            $coupon_value = $coupon->coupon_value;
                            
                            $discount_amount = $total / 100 * $coupon->coupon_value;
                            $checkout_amount = $total - $discount_amount;
                            // dd($checkout_amount);
                            if ($total > $checkout_amount) {
                                Session::put('coupondiscount', $discount_amount);
                                Session::put('checkout_amount', $checkout_amount);
                                Session::put('coupon_status', 'yes');
                                Session::put('coupon_value', $coupon_value);
                                Session::put('coupon_id', $coupon->coupon_code);
                                return response()->json(['status' => 'success', 'msg' => 'Coupon Applied Successfully']);
                            } else {
                                return response()->json(['status' => 'fail', 'msg' => 'Coupon does not apply on this amount']);
                            }
                        }
                    }
                } else {
                    return response()->json(['status' => 'fail', 'msg' => 'Coupon does not apply on this amount']);
                }
            } else {
                return response()->json(['status' => 'fail', 'msg' => 'Coupon does not exist']);
            }
        }
    }
    
    public function ChangeCouponStatus(Request $request)
    {

        $coupon = Coupon::find($request->coupon_id);
        $coupon->status = $request->status;
        $coupon->save();
        return response()->json(['status' => 'success', 'msg' => 'Coupon status has been change successfully']);
    }

    // edit coupon
    public function CouponEdit(Request $request)
    {
        $coupons = Coupon::where('id', $request->coupon)->first();
        // return $coupons;

        if (!$coupons) {
            return response()->json([
                'status' => 'error',
                'message' => 'Coupon not Found',
            ], 400);
        } else {
            return response()->json([
                'status' => 'success',
                'data' => $coupons,
            ]);
        }
    }

    // update coupon
    public function CouponUpdate(Request $request)
    {

        $coupons = Coupon::find($request->id);
        $coupons->coupon_title = $request->coupon_title;
        $coupons->coupon_code = $request->coupon_code;
        $coupons->coupon_type = $request->coupon_type;
        $coupons->coupon_value = $request->coupon_value;
        $coupons->expiry = $request->expiry;

        if ($coupons->save()) {
            return response()->json([
                'status' => 'success',
                'msg' => 'Update Updated Successfully',
            ], 200);
        } else {
            return response()->json([
                'status' => 'fail',
                'msg' => 'something went wrong',
            ], 200);
        }
    }
}
