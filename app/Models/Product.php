<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Models\Deals;
use App\Models\StorewiseDeal;
use App\Models\DealProduct;
use App\Models\StoredealProduct;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'stock',
        'name',
        'slug',
        'barcode',
        'price',
        'store_id',
        'user_id',
        'added_by',
        'category_id',
        'brand_id',
        'thumbnail',
        'galleryimg1',
        'galleryimg2',
        'galleryimg3',
        'express_delivery',
        'video_link',
        'short_description',
        'description',
        'warrenty',
        'featured',
        'cash_on_delivery',
        'vat_status',
        'colors',
        'size',
        'weight',
        'product_acess',
        'shipping_cost',
        'shipping_height',
        'shipping_width',
        'shipping_weight',
        'discounted_price',
        'published',
        'barcode'
        
    ];

    public function deals(){
        return $this->belongsToMany(Deals::class,'DealProduct','product_id','deal_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getImage($code){
        $upload = Upload::where('file_original_name',$code)->first();
        if($upload){

            return $upload->file_name;

        }else{

            return null;
        }
    }
    public function store(){

        return $this->belongsTo(User::class,'user_id');
    }

    public function discount_price($product)
    {
        $price = $product->price;
        // $tax = 0;

        if ($product->discount != NULL) {
            $pri = ($price * $product->discount) / 100;
            $price= $price-$pri;
        } else{
            $price = $product->price;
        }

        // $discount_applicable = false;

        // if ($product->discount_start_date == null) {
        //     $discount_applicable = true;
        // } elseif (
        //     strtotime(date('d-m-Y H:i:s')) >= $product->discount_start_date &&
        //     strtotime(date('d-m-Y H:i:s')) <= $product->discount_end_date
        // ) {
        //     $discount_applicable = true;
        // }

        // if ($discount_applicable = true) {
        //     if ($product->discount_type == 'percent') {
        //         $price -= ($price * $product->discount) / 100;
        //     } elseif ($product->discount_type == 'flat') {
        //         $price -= $product->discount;
        //     }
        // }

        // // foreach ($product->taxes as $product_tax) {
        // //     if ($product_tax->tax_type == 'percent') {
        // //         $tax += ($price * $product_tax->tax) / 100;
        // //     } elseif ($product_tax->tax_type == 'amount') {
        // //         $tax += $product_tax->tax;
        // //     }
        // // }
        // $price += $tax;

        return $price;
    }
    public function orders(){

        return $this->belongsToMany(Order::class,'order_items','product_id','order_id');

    }
    
    public function get_deal_price(){
        $products = $this;
        $today = Carbon::today();
        $alldeals = Deals::where('end_date', '>=', $today)->where('status', 0)->get();
        $storedeals = StorewiseDeal::where('end_date', '>=', $today)->where('status', 0)->get();
        if(isset($alldeals) && sizeof($alldeals) > 0){
            foreach($alldeals as $aldeal){
                    $alldealpr = DealProduct::where('deal_id', $aldeal->id)->where('product_id', $products->id)->first();
                    if($alldealpr) {
                        $dealproducts = Product::where('stock', 'yes')->where('id', $alldealpr->product_id)->get();
                    }
                if($alldealpr){
                    foreach($dealproducts as $dealproduct){
                        if($dealproduct){
                            $dprice = null; // initialize dprice variable
                            if($alldealpr->discount_type == 'percentage') {
                                $discounted = $dealproduct->price * $alldealpr->discount / 100;
                                $dprice = $dealproduct->price - $discounted;
                            } elseif($alldealpr->discount_type == 'flat') {
                                $dprice = $dealproduct->price - $alldealpr->discount;
                            }
                        }
                    }
                }
            }
        }


        $sdprice = null;

        foreach($storedeals as $storedeal){
                $storedealpr = StoredealProduct::where('storedeal_id', $storedeal->id)->where('product_id',$products->id)->first();
                if ($storedealpr) {
                    $stdealproduct = Product::where('stock', 'yes')->where('id', $storedealpr->product_id)->first();
                    if($stdealproduct){
                        if($storedealpr->discount_type == 'percentage'){
                            $discounted = $stdealproduct->price * $storedealpr->discount / 100;
                            $sdprice = $stdealproduct->price - $discounted;
                            // dd($discounted, $stdealproduct, $sdprice);
                        } else if($storedealpr->discount_type == 'flat'){
                            $sdprice = $stdealproduct->price - $storedealpr->discount;
                        }
                        break;
                    }
                }
        }
        
        if(isset($dealproduct)){
            return ['price'=>round($dprice,2), 'old_price'=>$products->price, 'all_deal'];
        }elseif(isset($stdealproduct)){
            return ['price'=>round($sdprice,2), 'old_price'=>$products->price, 'store_deal'];
        }elseif($products->price != $products->discounted_price){
            return ['price'=>round($products->discounted_price,2), 'old_price'=>$products->price, 'discounted'];   
        }else{
            return ['price'=>round($products->discounted_price,2), 'normal'];
        }
        
    }

  
}
