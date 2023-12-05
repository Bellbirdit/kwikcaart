<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class DealProduct extends Model
{
    use HasFactory;
    protected $table = 'dealproduct';
    
     public function product()
    {
        return $this->belongsTo(Product::class, 'product_id'); // links this->product_id to products.id
    }

    

}
