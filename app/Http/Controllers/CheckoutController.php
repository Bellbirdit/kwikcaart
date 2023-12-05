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
use App\Models\StoreProducts;
class CheckoutController extends Controller
{
    public function checkoutView(Request $request)
   
    {
        $store_id = Session::get('store_id');
        $today = Carbon::today();
        
        $storeproducts = StoreProducts::where('store_id', $store_id)
            ->where('stock', 'yes')
            ->pluck('product_id');
            
        $counts = Cart::where('user_id', auth()->id())
            ->where('store_id', $store_id)
            ->whereIn('product_id', $storeproducts)
            ->where('status', 'pending')
            ->where('quantity', '>', 0)
            ->count();

        $carts = Cart::where('user_id', auth()->id())
            ->where('store_id', $store_id)
            ->whereIn('product_id', $storeproducts)
            ->where('status', 'pending')
            ->where('quantity', '>', 0)
            ->get();

        $defaultAddress = UserAddress::where('user_id', auth()->id())
            ->where('is_default', 1)
            ->first();
        if(!isset($defaultAddress) || empty($defaultAddress)){
            $defaultAddress = UserAddress::where('user_id', auth()->id())
            ->first();
        }
     

          
    
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
    
    public function addAddress(Request $request){
        UserAddress::where('user_id', auth()->id())->update(['is_default'=>0]);
        UserAddress::create([
            'building_name' => $request->building_name??"",
            'street_name' => $request->street_name??"",
            'flat_name' => $request->flat_name??"",
            'address' => $request->address??"",
            'landmark' => $request->landmark??"",
            'delivery_instructions' => $request->delivery_instructions??"",
            'address_type' => $request->address_type??"",
            'is_default' => 1,
            'user_id' => auth()->id()
        ]);
        $addreses = UserAddress::where('user_id', auth()->id())
            ->get();
        return response()->json(["success"=>true, 'addresses'=>$addreses]);
    }
    
    public function listAddress(Request $request){
        $addreses = UserAddress::where('user_id', auth()->id())
            ->get();
        return response()->json(["success"=>true, 'addresses'=>$addreses]);
    }
    
    public function deleteAddress(Request $request){
        UserAddress::where('user_id', $request->id)->delete();
        return response()->json(["success"=>true]);
    }
    
    public function currentAddress(Request $request){
        $defaultAddress = UserAddress::where('user_id', auth()->id())
            ->where('is_default', 1)
            ->first();
        if(!isset($defaultAddress) || empty($defaultAddress)){
            $defaultAddress = UserAddress::where('user_id', auth()->id())
            ->first();
        }
        return response()->json(["address"=>$defaultAddress]);
    }
    
    public function defaultAddress(Request $request){
        UserAddress::where('user_id', auth()->id())
            ->update(['is_default'=>0]);
        UserAddress::where('id', $request->id)->update(['is_default'=> "1"]);
        return response()->json(["success"=>true]);
    }
}
