<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CreatorKyc extends Model
{
    protected $table = 'creator_kyc';

    protected $fillable = [
        'user_id', 'status', 'nik', 'full_name', 'dob', 'gender',
        'province', 'city', 'ktp_photo_path', 'selfie_photo_path',
        'nik_parsed_data', 'liveness_token', 'submitted_at',
        'reviewed_at', 'reviewed_by', 'rejection_reason',
    ];

    protected $casts = [
        'nik_parsed_data' => 'array',
        'submitted_at'    => 'datetime',
        'reviewed_at'     => 'datetime',
        'dob'             => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function isVerified(): bool
    {
        return $this->status === 'verified';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }
}
