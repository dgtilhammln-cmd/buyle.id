<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AutomationCampaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'instagram_account_id',
        'product_id',
        'custom_link',
        'name',
        'target_type',
        'target_media_id',
        'target_media_caption',
        'target_media_url',
        'trigger_type',
        'trigger_keywords',
        'spintax_variants',
        'auto_comment_enabled',
        'auto_comment_variants',
        'delay_seconds',
        'auto_follow_check',
        'is_active',
        'total_comments_detected',
        'total_dms_sent',
        'total_clicks',
        'total_orders',
        'total_revenue',
    ];

    protected $casts = [
        'trigger_keywords'      => 'array',
        'spintax_variants'      => 'array',
        'auto_comment_variants' => 'array',
        'auto_comment_enabled'  => 'boolean',
        'auto_follow_check'     => 'boolean',
        'is_active'             => 'boolean',
        'delay_seconds'         => 'integer',
        'total_revenue'         => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function instagramAccount()
    {
        return $this->belongsTo(InstagramAccount::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function logs()
    {
        return $this->hasMany(CampaignLog::class, 'campaign_id');
    }

    public function shortLinks()
    {
        return $this->hasMany(AutomationShortLink::class, 'campaign_id');
    }

    /**
     * Hitung Click-Through Rate (CTR) dari DM yang dikirim
     */
    public function getCtrAttribute(): float
    {
        if ($this->total_dms_sent <= 0) {
            return 0.0;
        }

        return round(($this->total_clicks / $this->total_dms_sent) * 100, 1);
    }

    /**
     * Cek apakah komentar cocok dengan trigger keyword campaign
     */
    public function matchesComment(string $commentText): bool
    {
        $comment = mb_strtolower(trim($commentText));

        if ($this->trigger_type === 'any') {
            return true;
        }

        $keywords = $this->trigger_keywords ?? [];
        if (empty($keywords)) {
            return true;
        }

        foreach ($keywords as $kw) {
            $normalizedKw = mb_strtolower(trim($kw));
            if ($normalizedKw === '') {
                continue;
            }

            if ($this->trigger_type === 'exact') {
                if ($comment === $normalizedKw) {
                    return true;
                }
            } else {
                // Default: contains
                if (str_contains($comment, $normalizedKw)) {
                    return true;
                }
            }
        }

        return false;
    }
}
