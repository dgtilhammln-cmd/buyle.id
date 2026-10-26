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
    protected $fillable = [
        'name', 'email', 'password', 'username', 'phone', 'avatar', 'google_id', 
        'role', 'parent_seller_id', 'shift_hours', 'cashier_role_title',
        'menu_permissions', 'is_active', 'last_seen_at', 
        'bank_name', 'bank_account_number', 'bank_account_name'
    ];
    protected $hidden = ['password', 'remember_token'];
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public function isPosCashier(): bool
    {
        return $this->role === 'pos_cashier' || !is_null($this->parent_seller_id);
    }

    public function parentSeller()
    {
        return $this->belongsTo(User::class, 'parent_seller_id');
    }

    public function posCashiers(): HasMany
    {
        return $this->hasMany(User::class, 'parent_seller_id');
    }

    public function isOnline()
    {
        return \Illuminate\Support\Facades\Cache::has('user-is-online-' . $this->id);
    }

    /**
     * Cek apakah user admin memiliki izin akses ke menu tertentu.
     */
    public function hasMenuPermission(string $menuKey): bool
    {
        if (in_array($this->role, ['super_admin', 'admin_super'])) {
            return true;
        }
        if (!in_array($this->role, ['admin', 'super_admin'])) {
            return false;
        }
        if (is_null($this->menu_permissions)) {
            return true; // Default full access jika belum di-set terbatas
        }
        return in_array($menuKey, (array) $this->menu_permissions);
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
            'menu_permissions'  => 'array',
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

    /**
     * Verifikasi e-KYC creator.
     */
    public function kyc(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(CreatorKyc::class);
    }

    public function isKycVerified(): bool
    {
        return $this->kyc?->status === 'verified';
    }

    public function weddings(): HasMany
    {
        return $this->hasMany(Wedding::class)->latest();
    }
}
