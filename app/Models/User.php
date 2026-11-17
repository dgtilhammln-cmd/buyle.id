<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    protected $fillable = [
        'name', 'email', 'password', 'username', 'phone', 'nik', 'avatar', 'google_id', 
        'role', 'parent_seller_id', 'shift_hours', 'cashier_role_title',
        'menu_permissions', 'is_active', 'last_seen_at', 
        'bank_name', 'bank_account_number', 'bank_account_name',
        'security_pin', 'biometric_enabled', 'biometric_credential_id',
        'pos_pin',
        'business_persona', 'referral_source_type', 'referral_source_name',
        'media_partner_name', 'media_partner_id', 'referral_code', 'referrer_id',
        'primary_goal', 'onboarding_completed_at'
    ];
    protected $hidden = ['password', 'remember_token', 'security_pin', 'pos_pin'];
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

    public function referrer()
    {
        return $this->belongsTo(User::class, 'referrer_id');
    }

    public function referrals()
    {
        return $this->hasMany(User::class, 'referrer_id');
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
            'email_verified_at'       => 'datetime',
            'password'                => 'hashed',
            'last_seen_at'            => 'datetime',
            'onboarding_completed_at' => 'datetime',
            'menu_permissions'        => 'array',
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

    public function mediaPartner(): BelongsTo
    {
        return $this->belongsTo(MediaPartner::class, 'media_partner_id');
    }

    public function ticketPasses(): HasMany
    {
        return $this->hasMany(TicketPass::class, 'user_id');
    }

    /**
     * Dapatkan NIK resmi (dari users.nik atau otomatis dari riwayat e-tiket)
     */
    public function getResolvedNikAttribute(): ?string
    {
        if (!empty($this->nik)) {
            return $this->nik;
        }
        $ticketNik = $this->ticketPasses()->whereNotNull('holder_nik')->where('holder_nik', '!=', '')->latest()->value('holder_nik');
        if ($ticketNik) {
            if (str_contains($ticketNik, ':')) {
                [, $ticketNik] = explode(':', $ticketNik, 2);
                $ticketNik = trim($ticketNik);
            }
            return $ticketNik;
        }
        return null;
    }
}
