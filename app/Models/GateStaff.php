<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Hash;

class GateStaff extends Model
{
    protected $table = 'gate_staff';

    protected $fillable = [
        'product_id',
        'creator_id',
        'user_id',
        'name',
        'gate_number',
        'role_position',
        'pin',
        'is_active',
    ];

    protected $hidden = ['pin'];

    /**
     * Verifikasi PIN (raw 6 digit) terhadap hash yang tersimpan.
     */
    public function verifyPin(string $rawPin): bool
    {
        return Hash::check($rawPin, $this->pin);
    }

    /**
     * Mask NIK 16 digit: tampilkan 4 awal + 8 sensor tengah + 4 akhir.
     * Contoh: "3273082504980003" → "3273████████0003"
     */
    public static function maskNik(?string $nik): string
    {
        if (!$nik || strlen($nik) < 8) {
            return '████████████████';
        }
        return substr($nik, 0, 4) . '████████' . substr($nik, -4);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Dapatkan URL avatar staf panitia yang valid & tersinkronisasi.
     */
    public function getAvatarUrlAttribute(): string
    {
        if ($this->user && $this->user->avatar_url) {
            return $this->user->avatar_url;
        }
        $name = urlencode($this->name ?: 'Staff');
        return "https://ui-avatars.com/api/?name={$name}&background=1eb349&color=ffffff&bold=true&size=128";
    }
}
