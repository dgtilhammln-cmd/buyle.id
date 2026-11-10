<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class TicketPass extends Model
{
    protected $table = 'ticket_passes';

    protected $fillable = [
        'order_id',
        'order_item_id',
        'product_id',
        'user_id',
        'seller_id',
        'ticket_code',
        'qr_token',
        'holder_name',
        'holder_email',
        'holder_phone',
        'holder_nik',
        'status',
        'checked_in_at',
        'checked_in_by',
        'scanned_by_staff_name',
        'scanned_at_gate',
    ];

    protected $casts = [
        'holder_nik'    => 'encrypted', // AES-256 via APP_KEY, otomatis encrypt/decrypt
        'checked_in_at' => 'datetime',
    ];

    /**
     * Mask nomor identitas: tampilkan prefix, sebagian awal, sensor tengah, dan akhir.
     * Contoh: "3273082504980003" → "3273████████0003"
     * Contoh: "Paspor: A1234567" → "Paspor: A1████67"
     */
    public static function maskNik(?string $nik): string
    {
        if (!$nik) {
            return '—';
        }

        $clean = trim($nik);
        $prefix = '';
        if (str_contains($clean, ': ')) {
            [$prefix, $clean] = explode(': ', $clean, 2);
            $prefix = $prefix . ': ';
        } elseif (str_contains($clean, ':')) {
            [$prefix, $clean] = explode(':', $clean, 2);
            $prefix = trim($prefix) . ': ';
        }

        $len = strlen($clean);
        if ($len <= 4) {
            return $prefix . str_repeat('█', max(1, $len));
        }
        if ($len <= 8) {
            return $prefix . substr($clean, 0, 2) . str_repeat('█', max(1, $len - 4)) . substr($clean, -2);
        }
        return $prefix . substr($clean, 0, 4) . str_repeat('█', max(4, $len - 8)) . substr($clean, -4);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->ticket_code)) {
                $model->ticket_code = 'TKT-' . date('Ymd') . '-' . strtoupper(Str::random(6));
            }
            if (empty($model->qr_token)) {
                $model->qr_token = Str::uuid()->toString();
            }
        });
    }

    /**
     * Otomatis membuatkan TicketPass untuk seluruh produk tipe ticket pada suatu Order.
     */
    public static function generateForOrder(Order $order): void
    {
        if (!$order->relationLoaded('items')) {
            $order->load('items.product');
        }

        $allHolders = $order->shipping_address['ticket_holders'] ?? [];
        $allNiks    = $order->shipping_address['ticket_niks'] ?? [];
        $allIdTypes = $order->shipping_address['ticket_id_types'] ?? [];

        foreach ($order->items as $item) {
            if ($item->product && $item->product->product_type === 'ticket') {
                $existingCount = static::where('order_id', $order->id)
                    ->where('order_item_id', $item->id)
                    ->count();

                $qtyNeeded = $item->qty - $existingCount;
                $holdersForProduct = $allHolders[$item->product_id] ?? [];
                $niksForProduct    = $allNiks[$item->product_id] ?? [];
                $idTypesForProduct = $allIdTypes[$item->product_id] ?? [];

                for ($i = 0; $i < $qtyNeeded; $i++) {
                    $slotIndex = $existingCount + $i;
                    $customHolder = !empty($holdersForProduct[$slotIndex]) ? trim($holdersForProduct[$slotIndex]) : null;
                    $customNik    = !empty($niksForProduct[$slotIndex]) ? trim($niksForProduct[$slotIndex]) : null;
                    $customIdType = !empty($idTypesForProduct[$slotIndex]) ? trim($idTypesForProduct[$slotIndex]) : null;

                    $finalNik = $customNik;
                    if ($customNik && $customIdType && !str_starts_with(strtolower($customNik), strtolower($customIdType))) {
                        $finalNik = "{$customIdType}: {$customNik}";
                    }

                    static::create([
                        'order_id'      => $order->id,
                        'order_item_id' => $item->id,
                        'product_id'    => $item->product_id,
                        'user_id'       => $order->user_id,
                        'seller_id'     => $item->product->seller_id,
                        'holder_name'   => $customHolder ?: ($order->user?->name ?? 'Pemegang Tiket'),
                        'holder_email'  => $order->user?->email,
                        'holder_phone'  => $order->user?->phone,
                        'holder_nik'    => $finalNik,
                        'status'        => 'valid',
                    ]);
                }
            }
        }
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function organizer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function checkedInBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'checked_in_by');
    }
}
