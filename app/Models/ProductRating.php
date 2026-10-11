<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductRating extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'order_id',
        'rating',
        'review_text',
        'review_images',
        'is_approved',
        'reviewer_name',
    ];

    protected $casts = [
        'rating'         => 'integer',
        'review_images'  => 'array',
        'is_approved'    => 'boolean',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Only approved reviews.
     */
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    /**
     * Only reviews that have text or images (meaningful reviews).
     */
    public function scopeWithContent($query)
    {
        return $query->where(function($q) {
            $q->whereNotNull('review_text')
              ->orWhereNotNull('review_images');
        });
    }

    /**
     * Get display name (reviewer_name or user name, anonymized).
     */
    public function getDisplayNameAttribute(): string
    {
        if ($this->reviewer_name) {
            return $this->reviewer_name;
        }
        if ($this->user) {
            $name = $this->user->name ?? '';
            // Anonymize: "John Doe" -> "J*** D**"
            $parts = explode(' ', $name);
            return implode(' ', array_map(function($p) {
                return strlen($p) <= 1 ? $p : (substr($p, 0, 1) . str_repeat('*', min(strlen($p) - 1, 3)));
            }, $parts));
        }
        return 'Pengguna';
    }

    /**
     * Get reviewer avatar URL.
     */
    public function getAvatarUrlAttribute(): ?string
    {
        if (!$this->user) return null;
        $avatar = $this->user->avatar;
        if (!$avatar) return null;
        return str_starts_with($avatar, 'http') ? $avatar : asset('storage/' . $avatar);
    }
}

