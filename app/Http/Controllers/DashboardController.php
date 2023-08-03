<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Product;
Use App\Models\StoreProducts;
use App\Models\WishList;
use Auth;
use Session;
class DashboardController extends Controller
{
    public function dashboard(Request $request)
    {
        
        $admin = User::where('type',1)->where('code',1234)->first();
        
        $adminordersss= Order::latest()->get();

        if(Auth::user()->hasRole('Store')){
            
            $ordersss = Order::latest()->where('store_id',Auth::user()->code)->get();
        }else{
            $ordersss = Order::latest()->where('store_id',Auth::user()->store_id)->get();
        }
        if(Auth::user()->hasRole('Store')){
            
            $storetotalorders = Order::latest()->where('store_id',Auth::user()->code)->count();
        }else{
            $storetotalorders = Order::latest()->where('store_id',Auth::user()->store_id)->count();
        }
      
        // if(Auth::user()->hasRole('Store')){
            
        //     $storeproduct = StoreProducts::where('store_id',Auth::user()->code)->where('stock','yes')->get();
        //     $storeproducts=$storeproduct->unique('barcode')->count();
        // }else{
        //     $storeproduct = StoreProducts::where('store_id',Auth::user()->store_id)->where('stock','yes')->get();
        //      $storeproducts=$storeproduct->unique('barcode')->count();
        // }
        if(Auth::user()->hasRole('Store')){
            
            $storecancelled= Order::where('order_status','cancelled')->where('store_id',Auth::user()->code)->count();
        }else{
            $storecancelled= Order::where('order_status','cancelled')->where('store_id',Auth::user()->store_id)->count();
        }
       
       
        if(Auth::user()->hasRole('Store')){
            $storeAddress = User::where('code',Auth::user()->code)->first();
          
        }else{
            $storeAddress = User::where('code',Auth::user()->store_id)->first();
        }

        if(Auth::user()->hasRole('Store')){
            $storecustomercount = User::where('customer_store_id',Auth::user()->code)->count();
          
        }else{
            $storecustomercount = User::where('customer_store_id',Auth::user()->store_id)->count();
        }

      
      
        return view('admin.dashboard.dashboard',compact('admin','storecancelled','storetotalorders','storecustomercount','storeAddress','ordersss','adminordersss'));
    }

 public function userDashboard(Request $request)
    {
        if (auth()->check()) {
        $orders = Order::orderBy('id', 'DESC')->where('user_id',Auth::user()->id)->get();
        $wishlists = WishList::Where('user_id',Auth::user()->id)->get();
        $wishlist_count = WishList::where('user_id',Auth::id())->count();
        return view('user.dashboard.dashboard',compact('orders','wishlists','wishlist_count'));
        }else{
            return redirect()->route('login');
        }
    }
}
