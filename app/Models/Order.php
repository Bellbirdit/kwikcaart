<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\OrderActivity;
use Illuminate\Support\Facades\Auth;

class Order extends Model
{
    use HasFactory;
    protected $guarded= [];
    

	public function orderItem(){
		return $this->hasMany('App\Models\OrderItem');
	}
	
	public function orderActivity(){
		return $this->hasMany('App\Models\OrderActivity', 'order_id')->orderBy('id', 'asc');
// 		return $this->belongsToMany(Product::class,'order_items','order_id','product_id');
	}
	
	public function payment(){
		return $this->hasOne('App\Models\Payment');
	}
	
    public function products(){

        return $this->belongsToMany(Product::class,'order_items','order_id','product_id');

    }

    public function agent(){
        
        return $this->belongsTo(User::class,'user_id');
    }
    // Each category may have multiple children
    public function orderitems()
    {
        return $this->belongsTo(OrderItem::class, 'store_id');
    }
    
    public function cancelBy(){
        return $this->belongsTo(User::class,'cancelled_by');
    }
    
    public function changeStatus($status)
    {
        $this->order_status = $status;
        if($status == "cancelled" && Auth::user()){
            $this->cancelled_by = auth()->user()->id;
        }
        $this->save();
        OrderActivity::create([
            'user_id' => auth()->user()->id,
            'order_id' => $this->id,
            'status' => $status
        ]);
        return $this;
    }
}
