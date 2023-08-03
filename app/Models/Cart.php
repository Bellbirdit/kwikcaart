<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    protected $fillable = [
        'email_forwarded'
    ];
    public function getImage($code){
        $upload = Upload::where('file_original_name',$code)->first();
        if($upload){

            return $upload->id;

        }else{

            return null;
        }
    }
}
