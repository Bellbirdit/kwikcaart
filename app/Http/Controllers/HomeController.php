<?php

namespace App\Http\Controllers;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Deals;
use App\Models\DealProduct;
use App\Models\User;
use App\Models\FooterSetting;
use Carbon\Carbon;
use App\Models\StorewiseDeal;
use Session;
use DB;
class HomeController extends Controller
{
    
    public function home(Request $request){
        if(isset($request->store_id))
        {
            $s = session::put('store_id',$request->store_id); 
            $store_id =  session::get('store_id');
            return redirect("/");
        }else{
            $store_id =0;
        }
        $store_id =  session::get('store_id');
        if(session::get('store_id'))
        {
            $store_id =  session::get('store_id');  
        }else{
            $store_id =  0;
        }
        $fsubcategories = Category::where('is_featured',1)->latest()->get();
        $faeturedcat = Category::where('level',2)->latest()->orderBy('id','DESC')->get();
        $subcategories=Category::with('cbannercat')->where('order_level', '!=', 0)->orderBy('order_level','ASC')->get();

        // $todays_deal_products =  Product::where('store_id','like', '%' . $store_id. '%')->where('discount_end_date','>', date('Y-m-d'))->where('stock','yes')->where('today_deal',1)->get();
      
        $result=User::query();
        $result = $result->whereHas('roles',function($q){
            $q->where('name','Store');
        });
        $stores = $result->orderBy('id','DESC')->get();
        $today = Carbon::today();
        $deals = [];
        $storedeals = [];
        $deals=Deals::where('status',0)->where('start_date','<=',$today)->where('end_date','>=', $today)->get();
       
        $today = Carbon::today();
        $storedeals=StorewiseDeal::where('store_id',$store_id)->where('status',0)->where('start_date','<=',$today)->where('end_date','>=', $today)->get();
     
        return view('frontend.home', compact('storedeals','stores','faeturedcat','fsubcategories','subcategories','deals'));

    }
    public function near_store(Request $request)
    {
        $html="";
        $lat = $request->currentLatitude; 
        $long = $request->currentLongitude;
        $latitude       =       $lat;
        $longitude      =       $long;
        $shops          =       DB::table("users");
        $shops          =       $shops->select("*", DB::raw("6371 * acos(cos(radians(" . $latitude . "))
                                * cos(radians(latitude)) * cos(radians(longitude) - radians(" . $longitude . "))
                                + sin(radians(" .$latitude. ")) * sin(radians(latitude))) AS distance"));
        $shops          =       $shops->having('distance', '<', 1900);
        $shops          =       $shops->orderBy('distance', 'asc');
        $shops          =       $shops->limit('4')->get();
        foreach($shops as $shop)
        {
            $html.='
            <div class="col-6 mt-3 p-1">
                <div class="card loc py-3">
                <div class="px-1">
                    <p><strong> <a href=javascript:; class="click_here" id="'.$shop->code.'">'.$shop->name.'  </a> </strong></p>
                </div>
                <small class="">'.round($shop->distance, 2) . " KM ".'</small> 
                    <small> '.$shop->emirate.' </small>
                </div>
            </div> ';
          }
        return response()->json(['status'=>"success",'html'=>$html]);
    }
     public function sub_category(Request $request)
     {
        $html = "";
        $categories = Category::where('parent_id',$request->id)->where('level','!=',$request->level)->get();
          foreach($categories as $cat)
          {
            $img = $cat->getImage($cat->icon);
             $file = asset('uploads/files/'.$img);
        $html.='
        <div class="col-lg-2">
        <div class="card-2 bg-9 wow animate__animated animate__fadeInUp" data-wow-delay=".1s">
            <figure class="img-hover-scale overflow-hidden">
                <a href="javascript:;"> <img
                        src="'.$file.'" alt=""></a>
            </figure>
           <a href='.route('shop').''.'/'.''.$cat->id.'>'.$cat->name.'</a>
        </div>  </div> ';
          }
        return response()->json(['status'=>"success",'html'=>$html]);
     }
    //  public function get_emirates(Request $request)
    //  {
    //     $emirate = User::where('emirate',$request->emirate)->get();
    //     $html ="";
    //     $option="";
    //     foreach($emirate as $store)
    //     {
    //         $html.='<option value="'.$store->code.'">'.$store->name.'</option>';
    //     }
    //     $option='<option value="" selected disabled>Select Nearest Store</option>'.$html.'';

    //     return response()->json(['status'=>"success",'html'=>$option]);
    //  }
    
    public function get_emirates(Request $request)
     {
        $emirate = User::where('emirate',$request->emirate)->get();
        $html ="";
        $option="";
        if(empty($emirate)){
            $html = '<div class="col-12 mt-3 p-1">
                <div class="card loc py-3">
                <div class="px-1">
                    <p><strong> No Store Found On This Location! </strong></p>
                </div>
                </div>
            </div>';
        }
        
        foreach($emirate as $store)
        {
            // $html.='<li class="strore-list-link"><a href="/?store_id='.$store->code.'">'.$store->name.'</a></li>';
            $html .= '<div class="col-6 mt-3 p-1">
                <div class="card loc py-3">
                <div class="px-1">
                    <p><strong> <a href="javascript:;" class="click_here" id="'.$store->code.'">'.$store->name.'</a> </strong></p>
                </div>
                <small> '.$request->emirate.' </small>
                </div>
            </div>';
        }
        // $option='<option value="" selected disabled>Select Nearest Store</option>'.$html.'';

        return response()->json(['status'=>"success",'html'=>$html]);
     }
}
