<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;
	protected $guarded= [];
	
	public function order(){
		return $this->belongsTo('App\Models\Order');
	}
	public function product(){
		return $this->belongsTo('App\Models\Product');
	}

	public function orderss()
    {
        return $this->hasMany(Order::class,'store_id');
    }
   

}
