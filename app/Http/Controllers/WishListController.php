<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\WishList;
use App\Models\Product;
use App\Models\User;
use Session;
use Auth;

class WishListController extends Controller
{
    public function home(){
        $wishlists = WishList::Where('user_id',Auth::user()->id)->get();
        $wishlist_count = WishList::where('user_id',Auth::id())->count();

        return view('frontend.wishlist.wish-list', compact('wishlists','wishlist_count'));
    }

    public function add_wishlist(Request $request){

        if(Auth::check())
        {
          $check = Wishlist::where('product_id',$request->id)->Where('user_id',Auth::user()->id)->first();
          if($check){
            return response()->json(['status' => 'fail','msg'=>'product is already added to wishlist']);

          }else{ 
                $wishlist = new Wishlist();
                $wishlist->product_id =$request->id;
                $wishlist->user_id=Auth::user()->id;
                $wishlist->save();
                if($wishlist->save())
                {
                    $wishlist_count = Wishlist::where('user_id',Auth::id())->count();
                    return response()->json(['status' => 'success','msg'=>'product addedd','wishlist_count'=>$wishlist_count]);
                }
            }
        }else{
            return response()->json(['status' => 'fail','msg'=>'Please Login First To Add wishlist']);
        }
    }

    // delete wishlist
    public function deleteWishlistProduct($id){
        $wishlist=WishList::find($id);
        
        $wishlist->delete();
        if ($wishlist){
            return response()->json(['status'=>'success','msg'=>'Wishlist product is Deleted']);
        }
        return response()->json(['status'=>'fail','msg'=>'failed to delete wishlist product']);
    }

}