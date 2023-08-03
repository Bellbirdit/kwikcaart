<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreProducts extends Model
{
    use HasFactory;
    protected $fillable = [
        
        'store_id',
       
        'barcode',
       
        'stock'
        
    ];
    public function getImage($code){
        $upload = Upload::where('file_original_name',$code)->first();
        if($upload){

            return $upload->file_name;

        }else{

            return null;
        }
    }
}
