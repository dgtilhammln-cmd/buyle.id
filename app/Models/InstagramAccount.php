<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstagramAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'instagram_business_id',
        'page_id',
        'username',
        'name',
        'profile_picture_url',
        'access_token',
        'token_expires_at',
        'account_type',
        'daily_dm_limit',
        'daily_dm_count',
        'last_dm_sent_at',
        'is_connected',
        'raw_metadata',
    ];

    protected $casts = [
        'is_connected'     => 'boolean',
        'token_expires_at' => 'datetime',
        'last_dm_sent_at'  => 'datetime',
        'raw_metadata'     => 'array',
        'access_token'     => 'encrypted',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function campaigns()
    {
        return $this->hasMany(AutomationCampaign::class);
    }

    /**
     * Cek apakah kuota pengiriman DM harian masih tersedia.
     */
    public function canSendDm(): bool
    {
        if (!$this->is_connected || empty($this->access_token)) {
            return false;
        }

        // Reset hitungan jika hari sudah berganti
        if ($this->last_dm_sent_at && !$this->last_dm_sent_at->isToday()) {
            $this->update(['daily_dm_count' => 0]);
        }

        return $this->daily_dm_count < $this->daily_dm_limit;
    }

    /**
     * Hitung sisa masa aktif token dalam hari.
     */
    public function daysUntilTokenExpires(): int
    {
        if (!$this->token_expires_at) {
            return 60;
        }

        return (int) now()->diffInDays($this->token_expires_at, false);
    }
}
