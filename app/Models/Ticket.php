<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;
    protected $fillable=['subject','agent_id','ticket_id','status','description'];

    public function tickets()
    {
        return $this->hasMany('App\Models\Ticket','ticket_id','ticket_id');
    }
}
