<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MediaPartnerCommissionLog extends Model
{
    protected $table = 'media_partner_commission_logs';

    protected $fillable = [
        'media_partner_id',
        'creator_id',
        'order_id',
        'order_item_id',
        'event_name',
        'ticket_price',
        'quantity',
        'gross_amount',
        'commission_rate',
        'commission_amount',
        'status',
        'paid_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'ticket_price'      => 'decimal:2',
            'gross_amount'      => 'decimal:2',
            'commission_rate'   => 'decimal:2',
            'commission_amount' => 'decimal:2',
            'paid_at'           => 'datetime',
        ];
    }

    public function mediaPartner(): BelongsTo
    {
        return $this->belongsTo(MediaPartner::class, 'media_partner_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(OrderItem::class, 'order_item_id');
    }
}
