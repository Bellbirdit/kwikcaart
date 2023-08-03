<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CancelRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'reason',
        'store_id',
        'user_id',
        'product_id',
        
        
    ];
}
