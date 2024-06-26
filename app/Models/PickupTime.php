<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PickupTime extends Model
{
    use HasFactory;
    protected $table = 'pickup_times';
    public function PickupSchedules()
    {
        return $this->belongsTo(PickupSchedule::class, 'date_id');
    }
}
