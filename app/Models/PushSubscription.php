<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PushSubscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'endpoint',
        'key_p256dh',
        'key_auth',
        'user_agent',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

