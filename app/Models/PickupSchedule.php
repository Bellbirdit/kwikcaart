<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PickupSchedule extends Model
{
    use HasFactory;
    protected $table = 'pickup_schedules';
    public function PickupTimes()
    {
        return $this->hasMany(PickupTime::class, 'date_id');
    }
}

