<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreShippingSchedule extends Model
{
    use HasFactory;
    protected $table = 'store_shipping_schedules';

    public function shippingTimes()
    {
        return $this->hasMany(ShippingTime::class, 'date_id');
    }

}
