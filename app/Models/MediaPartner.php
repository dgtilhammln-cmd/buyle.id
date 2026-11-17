<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class MediaPartner extends Model
{
    protected $table = 'media_partners';

    protected $fillable = [
        'user_id',
        'name',
        'code',
        'pic_name',
        'pic_phone',
        'pic_email',
        'commission_rate',
        'bank_name',
        'bank_account_number',
        'bank_account_name',
        'total_commission_earned',
        'total_commission_paid',
        'balance',
        'is_active',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'commission_rate'         => 'decimal:2',
            'total_commission_earned' => 'decimal:2',
            'total_commission_paid'   => 'decimal:2',
            'balance'                 => 'decimal:2',
            'is_active'               => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function creators(): HasMany
    {
        return $this->hasMany(User::class, 'media_partner_id');
    }

    public function commissionLogs(): HasMany
    {
        return $this->hasMany(MediaPartnerCommissionLog::class, 'media_partner_id');
    }

    public function getReferralUrlAttribute(): string
    {
        return url('/register?ref=' . $this->code);
    }

    public function getAvatarUrlAttribute(): ?string
    {
        if ($this->user && $this->user->avatar) {
            $avatar = $this->user->avatar;
            if (filter_var($avatar, FILTER_VALIDATE_URL)) {
                return $avatar;
            }
            return Storage::url($avatar);
        }
        return null;
    }
}
