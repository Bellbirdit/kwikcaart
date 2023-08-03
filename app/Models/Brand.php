<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'logo',
        'brand_code',
        'slug',
      
        'order_level',
        
        'meta_description',
        'meta_title'
        
    ];
    public function getImage($code){

        $upload = Upload::find($code);

        if($upload){

            return $upload->file_name;
        }else{
            return null;
        }
    }
}
