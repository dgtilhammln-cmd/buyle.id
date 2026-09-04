<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class TicketPass extends Model
{
    protected $table = 'ticket_passes';

    protected $fillable = [
        'order_id',
        'order_item_id',
        'product_id',
        'user_id',
        'seller_id',
        'ticket_code',
        'qr_token',
        'holder_name',
        'holder_email',
        'holder_phone',
        'holder_nik',
        'status',
        'checked_in_at',
        'checked_in_by',
    ];

    protected $casts = [
        'checked_in_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->ticket_code)) {
                $model->ticket_code = 'TKT-' . date('Ymd') . '-' . strtoupper(Str::random(6));
            }
            if (empty($model->qr_token)) {
                $model->qr_token = Str::uuid()->toString();
            }
        });
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function organizer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function checkedInBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'checked_in_by');
    }
}
