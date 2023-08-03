<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deals extends Model
{
    use HasFactory;
    public function getImage($code){

        $upload = Upload::find($code);

        if($upload){

            return $upload->file_name;
        }else{
            return null;
        }
    }

    public function category(){

        return $this->belongsTo(Category::class,'category_id');
    }
    public function products(){
        return $this->belongsToMany(Deals::class,'DealProduct','deal_id','product_id');
    }
}
