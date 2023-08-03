<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\UserAddress;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use App\Models\StoreShippingSchedule;
use App\Models\ShippingTime;
use App\Models\PickupSchedule;

class CheckoutController extends Controller
{
    public function checkoutView(Request $request)
   
    {
        $store_id = Session::get('store_id');
        $today = Carbon::today();

        $counts = Cart::where('user_id', auth()->id())
            ->where('store_id', $store_id)
            ->where('status', 'pending')
            ->where('quantity', '>', 0)
            ->count();

        $carts = Cart::where('user_id', auth()->id())
            ->where('store_id', $store_id)
            ->where('status', 'pending')
            ->where('quantity', '>', 0)
            ->get();

        $defaultAddress = UserAddress::where('user_id', auth()->id())
            ->where('is_default', 1)
            ->first();

     

          
    
            $shippingschedules = StoreShippingSchedule::with('shippingTimes')
                ->where('store_id', $store_id)
                ->whereDate('date', '>=', $today)
                ->orderBy('date', 'asc')
                ->take(3)
                ->get();
        
           
           
                $pickupchedules = PickupSchedule::with('PickupTimes')
                ->where('store_id', $store_id)
                ->whereDate('date', '>=', $today)
                ->orderBy('date', 'asc')
                ->take(3)
                ->get();
               
        return view('frontend.cart.checkout', compact('counts', 'carts', 'defaultAddress', 'shippingschedules','today','pickupchedules'));
    }
}
