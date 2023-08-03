<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

        return $this->hasMany(Product::class)->where('stock', 'yes')->where('published',1);
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
