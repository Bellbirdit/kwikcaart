<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StorewiseDeal extends Model
{
    use HasFactory;
    public function getImage($code){
        $upload = Upload::where('id',$code)->first();
        if($upload){

            return $upload->file_name;

        }else{

            return null;
        }
    }
}
