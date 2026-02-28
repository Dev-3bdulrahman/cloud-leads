<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $fillable = [
        'sender_email',
        'sender_name',
        'subject',
        'body',
        'status',
        'assigned_to',
        'received_at',
        'assigned_at',
    ];

    public function assignedTo()
    {
        return $this->belongsTo(User::class , 'assigned_to');
    }
}
