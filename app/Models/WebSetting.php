<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebSetting extends Model
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
}
