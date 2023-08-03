<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingTime extends Model
{
    use HasFactory;
    protected $table = 'shipping_times';
    public function storeShippingSchedule()
    {
        return $this->belongsTo(StoreShippingSchedule::class, 'date_id');
    }
}
