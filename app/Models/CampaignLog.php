<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampaignLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'campaign_id',
        'user_id',
        'sender_ig_id',
        'sender_username',
        'comment_id',
        'media_id',
        'comment_text',
        'dm_text_sent',
        'public_reply_sent',
        'status',
        'error_message',
    ];

    public function campaign()
    {
        return $this->belongsTo(AutomationCampaign::class, 'campaign_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
