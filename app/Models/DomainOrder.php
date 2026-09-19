<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DomainOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'domain_name',
        'extension',
        'amount',
        'status',
        'snap_token',
        'midtrans_transaction_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function creatorProfile()
    {
        return $this->hasOneThrough(CreatorProfile::class, User::class, 'id', 'user_id', 'user_id', 'id');
    }
}
