<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    protected $table="support_ticket_comments";
    public function supportticket()
    {
        return $this->belongsTo(SupportTicket::class,'support_ticket_id');
    }
}