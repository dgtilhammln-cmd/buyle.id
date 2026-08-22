<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    protected $fillable = ['name', 'email', 'password', 'username', 'phone', 'avatar', 'google_id', 'role', 'is_active', 'last_seen_at'];
    protected $hidden = ['password', 'remember_token'];
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public function isOnline()
    {
        return \Illuminate\Support\Facades\Cache::has('user-is-online-' . $this->id);
    }
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'last_seen_at'      => 'datetime',
        ];
    }

    // =========================================================================
    // Relationships (E-commerce)
    // =========================================================================

    /**
     * Semua alamat pengiriman milik user.
     */
    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }

    /**
     * Profil creator (store info, SEO, dsb).
     */
    public function creatorProfile(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(CreatorProfile::class);
    }

    /**
     * Semua alamat pengiriman utama (default).
     */
    public function defaultAddress(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Address::class)->where('is_default', true);
    }

    /**
     * Produk yang dimiliki oleh user ini sebagai seller.
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'seller_id');
    }

    /**
     * Semua order milik user.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Semua item cart milik user.
     */
    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class);
    }

    /**
     * Semua wishlist milik user.
     */
    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    /**
     * Semua pemakaian kupon oleh user.
     */
    public function couponUsages(): HasMany
    {
        return $this->hasMany(CouponUsage::class);
    }
}
