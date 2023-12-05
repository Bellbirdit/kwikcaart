<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Session;
use App\Models\StoreProducts;

class Category extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'parent_id',
        'slug',
        'level',
        'category_code',
        'parent_level',
        'order_level',
        'banner',
        'icon',
        'is_featured',
        'meta_description',
        'meta_title'
        
    ];
    public function attributes()
    {
        return $this->belongsToMany(Attribute::class,'attribute_categories','category_id','attribute_id');
    }
    public function parent() {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    // Each category may have multiple children
    public function children() {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function products(){
        $store_id = "";
        if (session::get('store_id')) {
            $store_id = session::get('store_id');
        }
        $storeproducts = StoreProducts::where('store_id', $store_id)
            ->where('stock', 'yes')
            ->pluck('product_id');
            // dd($storeproducts);
        return $this->hasMany(Product::class)
        // ->select('products.*')->join('store_products', 'products.barcode', '=', 'store_products.barcode')->where('store_products.stock', 'yes')
        ->where('stock', 'yes')
        ->whereIn('id', $storeproducts)
        ->where('published',1);
    }
         public function subcategories()
     {
         return $this->hasMany(Category::class, 'parent_id');
     }
    public function getImage($code){
        $upload = Upload::where('id',$code)->first();
        if($upload){

            return $upload->file_name;

        }else{

            return null;
        }
    }

    public function cbannercat(){
        return $this->hasMany('App\Models\Category','parent_id');
     }


}
