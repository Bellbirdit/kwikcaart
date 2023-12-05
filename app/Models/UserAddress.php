<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'address',
        'building_name',
        'flat_name',
        'address_type',
        'street_name',
        'landmark',
        'delivery_instructions',
        'is_default',
        'user_id'
    ];
}
