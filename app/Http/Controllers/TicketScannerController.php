<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\TicketPass;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;


class TicketScannerController extends Controller
{
    /**
     * Tampilkan halaman scanner QR Code tiket & Data Kehadiran.
     */
    public function index(Request $request)
    {
        $sellerId = auth()->id();
        // Jumlah tiket yang di-scan SENDIRI oleh user ini (untuk tampilan volunteer/crew)
        $myPersonalScanCount = \DB::table('ticket_passes')
            ->where('checked_in_by', $sellerId)
            ->where('status', 'used')
            ->count();

        $kyc = \App\Models\CreatorKyc::where('user_id', $sellerId)->first();
        $isKycVerified = isset($kyc) && $kyc && $kyc->status === 'verified';

        // Event tiket yang ditugaskan kepada user ini sebagai panitia aktif (Gate Scanner, Volunteer, Crew, dll)
        $assignedEventIds = \App\Models\GateStaff::where('user_id', $sellerId)
            ->where('is_active', true)
            ->pluck('product_id')
            ->toArray();

        // Staf / Volunteer yang ditugaskan ke event mendapatkan akses operasional tanpa syarat e-KYC
        $isAssignedStaff = !empty($assignedEventIds);
        $hasKycBypass    = $isKycVerified || $isAssignedStaff;

        // Detail penugasan staf untuk user saat ini (posisi/divisi & nomor gate)
        $myStaffRecord   = \App\Models\GateStaff::where('user_id', $sellerId)
            ->where('is_active', true)
            ->first();
        $myStaffPosition = $myStaffRecord?->role_position ?? 'Gate Scanner';
        $isGateDivision  = stripos($myStaffPosition, 'gate') !== false || stripos($myStaffPosition, 'scan') !== false;

        // ── View Mode Switch ──────────────────────────────────────────────────
        // Default mode:
        // Jika akun panitia/kru murni (assigned staff dan belum KYC) -> default 'crew'
        // Jika akun creator/EO (sudah KYC atau pemilik event) -> default 'creator'
        $defaultMode = ($isAssignedStaff && !$isKycVerified) ? 'crew' : 'creator';
        $viewMode = session('scanner_view_mode_' . $sellerId, $defaultMode);
        // Dalam mode crew, akses dibatasi ke scanner & profil pribadi
        $isCrewMode = ($viewMode === 'crew');

        $selectedEventId = $request->input('event_id');
        $statusFilter = $request->input('status');
        $search = trim($request->input('search', ''));
        $rawTab = $request->input('tab');
        if (in_array($rawTab, ['settings', 'rekening', 'profile'])) {
            return redirect()->route('creator.ticket.profile');
        } elseif (in_array($rawTab, ['scanner', 'data', 'gate', 'home', 'ots'])) {
            // Crew mode: data & gate butuh KYC / creator
            if ($isCrewMode && in_array($rawTab, ['data', 'gate'])) {
                return redirect()->route('creator.ticket.scanner', ['tab' => 'home']);
            }
            if (!$isCrewMode && !$isKycVerified && in_array($rawTab, ['data', 'gate'])) {
                return redirect()->route('creator.ticket.scanner', ['tab' => 'home']);
            }
            $activeTab = $rawTab;
        } else {
            $activeTab = 'home';
        }

        // Ambil daftar event/produk bertipe ticket milik creator ini ATAU yang ditugaskan sebagai panitia
        $events = Product::where(function ($q) use ($sellerId, $assignedEventIds) {
                $q->where('seller_id', $sellerId);
                if (!empty($assignedEventIds)) {
                    $q->orWhereIn('id', $assignedEventIds);
                }
            })
            ->where('product_type', 'ticket')
            ->with(['variantOptions.values'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Query dasar tiket milik creator (baik sebagai seller tiket, pemilik produk, ATAU panitia yang ditugaskan)
        $baseQuery = TicketPass::with(['product', 'buyer', 'organizer', 'order'])
            ->where(function ($q) use ($sellerId, $assignedEventIds) {
                $q->where('seller_id', $sellerId)
                  ->orWhereHas('product', function ($pq) use ($sellerId) {
                      $pq->where('seller_id', $sellerId);
                  });
                if (!empty($assignedEventIds)) {
                    $q->orWhereIn('product_id', $assignedEventIds);
                }
            });

        // Seluruh tiket milik creator ini untuk Offline/Crowded In-Memory Client Cache
        $allCreatorTickets = (clone $baseQuery)->orderBy('id', 'desc')->get();

        // Format data cache ringan untuk pencarian super cepat (0ms latency di lapangan)
        $allTicketsCached = $allCreatorTickets->map(function ($t) {
            $order = $t->order;
            return [
                'id'            => $t->id,
                'code'          => $t->ticket_code,
                'qr_token'      => $t->qr_token,
                'product_id'    => $t->product_id,
                'event_name'    => $t->product?->name ?? 'Event',
                'event_date'    => $t->product?->event_date ? $t->product->event_date->format('d M Y') : '-',
                'holder_name'   => $t->holder_name ?? '-',
                'holder_email'  => $t->holder_email ?? '-',
                'holder_phone'  => $t->holder_phone ?? '-',
                'holder_nik'    => \App\Models\TicketPass::maskNik($t->holder_nik),
                'status'        => $t->status,
                'checked_in_at' => $t->checked_in_at ? $t->checked_in_at->format('H:i:s (d M Y)') : null,
                'scanned_by'    => $t->scanned_by_staff_name
                    ? ($t->scanned_by_staff_name . ' · Gate ' . $t->scanned_at_gate)
                    : ($t->checked_in_by ? 'Creator Studio' : null),
                'order_number'  => $order?->order_number ?? '-',
                'ticket_type'   => $t->product?->product_type === 'ticket' ? 'Tiket Event' : ($t->product?->product_type ?? 'Tiket Event'),
                'toggle_url'    => route('creator.ticket.scanner.toggle-checkin', $t->id),
                'claim_proof'   => $order?->claim_proof ? (str_starts_with($order->claim_proof, 'http') ? $order->claim_proof : asset('storage/' . $order->claim_proof)) : null,
                'claim_proofs'  => $order?->claim_proofs_list ?? [],
                'extra_data'    => $t->extra_data ?? [],
            ];
        });

        // Query terfilter untuk server-side fallback
        $ticketsQuery = clone $baseQuery;

        if ($selectedEventId) {
            $ticketsQuery->where('product_id', $selectedEventId);
        }

        if ($statusFilter && in_array($statusFilter, ['valid', 'used', 'cancelled'])) {
            $ticketsQuery->where('status', $statusFilter);
        }

        if (!empty($search)) {
            $ticketsQuery->where(function ($q) use ($search) {
                $q->where('holder_name', 'like', "%{$search}%")
                  ->orWhere('holder_email', 'like', "%{$search}%")
                  ->orWhere('holder_phone', 'like', "%{$search}%")
                  ->orWhere('ticket_code', 'like', "%{$search}%");
            });
        }

        $filteredTickets = (clone $ticketsQuery)->get();
        $tickets = $ticketsQuery->orderBy('updated_at', 'desc')->paginate(25)->withQueryString();

        // Hitung kapasitas total stok tiket (dari variasi tiket atau kolom stock produk)
        $eventStockMap = [];
        $totalCreatorCapacity = 0;

        foreach ($events as $ev) {
            $cap = 0;
            if ($ev->relationLoaded('variantOptions') && $ev->variantOptions->isNotEmpty()) {
                $variantSum = (int) $ev->variantOptions->flatMap->values->sum('stock');
                $cap = $variantSum > 0 ? $variantSum : (int) ($ev->stock ?? 0);
            } else {
                $cap = (int) ($ev->stock ?? 0);
            }
            $eventSold = $allCreatorTickets->where('product_id', $ev->id)->where('status', '!=', 'cancelled')->count();
            $eventRemaining = max(0, $cap - $eventSold);
            $eventStockMap[$ev->id] = [
                'capacity'  => $cap,
                'sold'      => $eventSold,
                'remaining' => $eventRemaining,
            ];
            $totalCreatorCapacity += $cap;
        }

        // Siapkan map variasi per event untuk mini-chips compact & reaktif
        $eventVariantsMap = [];
        foreach ($events as $ev) {
            $evVariants = [];
            if ($ev->relationLoaded('variantOptions') && $ev->variantOptions->isNotEmpty()) {
                foreach ($ev->variantOptions as $opt) {
                    foreach ($opt->values as $val) {
                        $soldCount = TicketPass::where('product_id', $ev->id)
                            ->where('status', '!=', 'cancelled')
                            ->whereHas('orderItem', function($q) use ($val) {
                                $q->where('variant_value_id', $val->id)
                                  ->orWhere('variant_name', $val->value);
                            })->count();

                        $stock = (int) ($val->stock ?? 0);
                        $remaining = max(0, $stock - $soldCount);
                        $evVariants[] = [
                            'name'      => $val->value ?? $opt->name,
                            'capacity'  => $stock,
                            'sold'      => $soldCount,
                            'remaining' => $remaining,
                        ];
                    }
                }
            }
            $eventVariantsMap[$ev->id] = $evVariants;
        }

        // Stats Ringkasan (dihitung dari seluruh tiket creator atau per event terpilih)
        $statSource = $selectedEventId ? $allCreatorTickets->where('product_id', $selectedEventId) : $allCreatorTickets;
        $totalTickets   = $statSource->count();
        $totalCheckedIn = $statSource->where('status', 'used')->count();
        $totalUnchecked = $statSource->where('status', 'valid')->count();
        $totalCancelled = $statSource->where('status', 'cancelled')->count();
        $attendanceRate = $totalTickets > 0 ? round(($totalCheckedIn / $totalTickets) * 100, 1) : 0;

        $activeTicketsCount = $statSource->where('status', '!=', 'cancelled')->count();
        if ($selectedEventId && isset($eventStockMap[$selectedEventId])) {
            $totalCapacity  = $eventStockMap[$selectedEventId]['capacity'];
            $totalSold      = $eventStockMap[$selectedEventId]['sold'];
            $remainingStock = $eventStockMap[$selectedEventId]['remaining'];
        } else {
            $totalCapacity  = $totalCreatorCapacity;
            $totalSold      = $activeTicketsCount;
            $remainingStock = max(0, $totalCapacity - $totalSold);
        }

        // Hitung total omzet tiket terjual
        try {
            $revenueQuery = \App\Models\OrderItem::whereHas('product', function ($pq) use ($sellerId, $selectedEventId) {
                $pq->where('seller_id', $sellerId)->where('product_type', 'ticket');
                if ($selectedEventId) {
                    $pq->where('id', $selectedEventId);
                }
            })->whereHas('order', function ($oq) {
                $oq->whereNotIn('status', ['cancelled', 'refunded', 'pending']);
            });
            $totalRevenue = (float) $revenueQuery->sum('subtotal');
        } catch (\Throwable $e) {
            $totalRevenue = 0;
        }

        $stats = [
            'total'          => $totalTickets,
            'total_sold'     => $totalSold,
            'total_capacity' => $totalCapacity,
            'remaining'      => $remainingStock,
            'checked_in'     => $totalCheckedIn,
            'unchecked'      => $totalUnchecked,
            'cancelled'      => $totalCancelled,
            'rate'           => $attendanceRate,
            'revenue'        => $totalRevenue,
        ];

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'stats'   => $stats,
                'tickets' => $allTicketsCached,
            ]);
        }

        $kyc = \App\Models\CreatorKyc::where('user_id', $sellerId)->first();

        // Primary / Featured upcoming event for Home tab
        $primaryEvent = $selectedEventId ? $events->firstWhere('id', $selectedEventId) : $events->first();
        $daysUntilEvent = null;
        if ($primaryEvent && $primaryEvent->event_date) {
            $daysUntilEvent = max(0, (int) now()->startOfDay()->diffInDays($primaryEvent->event_date->startOfDay(), false));
        }

        // Semua tanggal event (untuk dot marker di kalender)
        $eventDates = $events
            ->filter(fn($e) => !is_null($e->event_date))
            ->map(fn($e) => $e->event_date->format('Y-m-d'))
            ->unique()
            ->values()
            ->toArray();

        $seo = [
            'title'   => 'Ticket System Management · Creator Studio',
            'robots'  => 'noindex, nofollow',
        ];

        $gateStaffList = \App\Models\GateStaff::where('creator_id', $sellerId)
            ->with(['product:id,name,event_date', 'user:id,name,username,avatar', 'user.creatorProfile:id,user_id'])
            ->orderBy('id', 'desc')
            ->get()
            ->map(function ($st) {
                $st->avatar_url = $st->avatar_url;
                $st->is_synced  = (bool) $st->user_id;
                $st->scans_count = TicketPass::where('product_id', $st->product_id)
                    ->where('status', 'used')
                    ->where(function ($q) use ($st) {
                        if ($st->user_id) {
                            $q->where('checked_in_by', $st->user_id)
                              ->orWhere('scanned_by_staff_name', $st->name);
                        } else {
                            $q->where('scanned_by_staff_name', $st->name);
                        }
                    })->count();
                return $st;
            });

        $allTicketEvents = Product::where('product_type', 'ticket')
            ->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('event_date')
                  ->orWhere('event_date', '>=', now()->startOfDay());
            })
            ->with(['seller.creatorProfile'])
            ->orderBy('event_date', 'asc')
            ->get();

        // Data & Laporan Tiket OTS (On-The-Spot / Kasir Loket)
        $otsOrderQuery = \App\Models\Order::where('source', 'ots')
            ->whereHas('items', function ($q) use ($events) {
                $q->whereIn('product_id', $events->pluck('id'));
            });

        $otsOrders = (clone $otsOrderQuery)
            ->with(['items.product'])
            ->orderBy('id', 'desc')
            ->take(15)
            ->get();

        $otsStats = [
            'total_tickets' => TicketPass::where('is_ots', true)->whereIn('product_id', $events->pluck('id'))->count(),
            'total_revenue' => (float) (clone $otsOrderQuery)->where('status', 'confirmed')->sum('total'),
            'total_cash'    => (float) (clone $otsOrderQuery)->where('status', 'confirmed')
                ->where(function ($q) {
                    $q->where('shipping_address->payment_method', 'cash')
                      ->orWhere('notes', 'like', '%cash%');
                })->sum('total'),
            'total_qris'    => (float) (clone $otsOrderQuery)->where('status', 'confirmed')
                ->where('shipping_address->payment_method', 'qris')
                ->sum('total'),
        ];

        return view('creator.ticket_scanner', compact(
            'seo',
            'events',
            'tickets',
            'selectedEventId',
            'statusFilter',
            'search',
            'activeTab',
            'stats',
            'eventStockMap',
            'eventVariantsMap',
            'totalCreatorCapacity',
            'allTicketsCached',
            'kyc',
            'isKycVerified',
            'isAssignedStaff',
            'hasKycBypass',
            'gateStaffList',
            'primaryEvent',
            'daysUntilEvent',
            'eventDates',
            'allTicketEvents',
            'myPersonalScanCount',
            'viewMode',
            'isCrewMode',
            'myStaffRecord',
            'myStaffPosition',
            'isGateDivision',
            'otsOrders',
            'otsStats'
        ));
    }

    /**
     * Toggle antara Mode Crew dan Mode Creator via session.
     */
    public function switchViewMode(\Illuminate\Http\Request $request)
    {
        $sellerId = auth()->id();
        $current = session('scanner_view_mode_' . $sellerId, 'crew');
        $newMode = $current === 'crew' ? 'creator' : 'crew';
        session(['scanner_view_mode_' . $sellerId => $newMode]);

        $tab = $request->input('tab', 'home');
        // Kalau switch ke crew mode dan sedang di tab data/gate → redirect ke home
        if ($newMode === 'crew' && in_array($tab, ['data', 'gate'])) {
            $tab = 'home';
        }
        return redirect()->route('creator.ticket.scanner', ['tab' => $tab]);
    }

    /**
     * API untuk validasi QR Code / Kode Tiket via Scanner.
     * Dilindungi dengan DB transaction + lockForUpdate() untuk mencegah race condition.
     */
    public function verify(Request $request): JsonResponse
    {
        $rawInput = trim($request->input('code') ?? $request->input('qr_token') ?? '');

        if (empty($rawInput)) {
            return response()->json([
                'status'  => 'invalid',
                'title'   => 'Kode Kosong',
                'message' => 'Kode tiket atau QR Code tidak terdeteksi.'
            ], 400);
        }

        // Ekstraksi kandidat token pintar dari URL, JSON, atau teks biasa
        $tokensToSearch = array_values(array_unique(array_filter($this->extractCandidateTokens($rawInput))));
        if (empty($tokensToSearch)) {
            $tokensToSearch = [$rawInput];
        }

        $result = null;

        try {
            DB::transaction(function () use ($tokensToSearch, $rawInput, &$result) {
                $sellerId = auth()->id();
                $assignedEventIds = \App\Models\GateStaff::where('user_id', $sellerId)
                    ->where('is_active', true)
                    ->pluck('product_id')
                    ->toArray();

                // 1. Cari tiket KHUSUS milik creator yang sedang login (baik sebagai seller_id tiket, pemilik produk, ATAU panitia yang ditugaskan)
                $ticket = TicketPass::with(['product.seller', 'buyer'])
                    ->where(function ($q) use ($sellerId, $assignedEventIds) {
                        $q->where('seller_id', $sellerId)
                          ->orWhereHas('product', function ($pq) use ($sellerId) {
                              $pq->where('seller_id', $sellerId);
                          });
                        if (!empty($assignedEventIds)) {
                            $q->orWhereIn('product_id', $assignedEventIds);
                        }
                    })
                    ->where(function ($q) use ($tokensToSearch) {
                        foreach ($tokensToSearch as $t) {
                            $q->orWhere('qr_token', $t)
                              ->orWhere('ticket_code', $t)
                              ->orWhere('ticket_code', strtoupper($t))
                              ->orWhere('qr_token', strtolower($t));
                        }
                    })
                    ->lockForUpdate()
                    ->first();

                // 2. Jika tidak ditemukan di akun ini, cek apakah tiket terdaftar di sistem tapi milik creator LAIN
                if (!$ticket) {
                    $otherTicket = TicketPass::with(['product.seller', 'organizer'])
                        ->where(function ($q) use ($tokensToSearch) {
                            foreach ($tokensToSearch as $t) {
                                $q->orWhere('qr_token', $t)
                                  ->orWhere('ticket_code', $t)
                                  ->orWhere('ticket_code', strtoupper($t))
                                  ->orWhere('qr_token', strtolower($t));
                            }
                        })
                        ->first();

                    if ($otherTicket) {
                        $eventCreator = $otherTicket->product?->seller?->name ?? $otherTicket->organizer?->name ?? 'Creator Lain';
                        $eventName = $otherTicket->product?->name ?? 'Event Lain';

                        $result = response()->json([
                            'status'  => 'invalid',
                            'title'   => 'Bukan Tiket Event Anda!',
                            'message' => "Tiket {$otherTicket->ticket_code} ini milik event \"{$eventName}\" yang dikelola oleh {$eventCreator}. Anda tidak memiliki izin untuk memverifikasi tiket ini.",
                            'ticket'  => [
                                'code'        => $otherTicket->ticket_code,
                                'event_name'  => $eventName,
                                'holder_name' => $otherTicket->holder_name ?? '-',
                            ]
                        ]);
                        return;
                    }

                    $result = response()->json([
                        'status'  => 'invalid',
                        'title'   => 'Tiket Tidak Ditemukan',
                        'message' => 'Kode tiket / QR Code ini tidak terdaftar di sistem buyle.id.'
                    ]);
                    return;
                }

                // 3. Tiket dibatalkan
                if ($ticket->status === 'cancelled') {
                    $result = response()->json([
                        'status'  => 'invalid',
                        'title'   => 'Tiket Dibatalkan',
                        'message' => 'Tiket ini telah dibatalkan atau direfund. Tidak dapat check-in.',
                        'ticket'  => [
                            'code'        => $ticket->ticket_code,
                            'event_name'  => $ticket->product?->name ?? '-',
                            'holder_name' => $ticket->holder_name ?? '-',
                        ]
                    ]);
                    return;
                }

                // 4. Tiket sudah digunakan (termasuk yang baru saja di-scan bersamaan)
                if ($ticket->status === 'used') {
                    $time = $ticket->checked_in_at ? $ticket->checked_in_at->format('H:i (d M Y)') : '-';
                    $result = response()->json([
                        'status'  => 'used',
                        'title'   => 'Tiket Sudah Digunakan',
                        'message' => "Tiket a.n {$ticket->holder_name} sudah dipindai sebelumnya pada pukul {$time}.",
                        'ticket'  => [
                            'code'          => $ticket->ticket_code,
                            'event_name'    => $ticket->product?->name ?? '-',
                            'event_image'   => $ticket->product?->image_url ?? null,
                            'holder_name'   => $ticket->holder_name ?? '-',
                            'checked_in_at' => $time,
                        ]
                    ]);
                    return;
                }

                // 5. Tiket valid — tandai sebagai used
                $staffRecord = \App\Models\GateStaff::where('user_id', $sellerId)
                    ->where('product_id', $ticket->product_id)
                    ->first();

                $ticket->update([
                    'status'                => 'used',
                    'checked_in_at'         => now(),
                    'checked_in_by'         => $sellerId,
                    'scanned_by_staff_name' => $staffRecord ? (auth()->user()->name . ' (' . ($staffRecord->role_position ?? 'Gate Scanner') . ')') : $ticket->scanned_by_staff_name,
                    'scanned_at_gate'       => $staffRecord ? ($staffRecord->gate_number ?: 1) : $ticket->scanned_at_gate,
                ]);

                $result = response()->json([
                    'status'  => 'valid',
                    'title'   => 'Check-In Berhasil! ✓',
                    'message' => "Selamat datang, {$ticket->holder_name}! Tiket berhasil diverifikasi.",
                    'ticket'  => [
                        'code'           => $ticket->ticket_code,
                        'event_name'     => $ticket->product?->name ?? '-',
                        'event_image'    => $ticket->product?->image_url ?? null,
                        'event_date'     => $ticket->product?->event_date?->format('d M Y') ?? '-',
                        'event_time'     => $ticket->product?->event_time ?? '-',
                        'event_location' => $ticket->product?->event_location ?? '-',
                        'holder_name'    => $ticket->holder_name ?? '-',
                        'holder_email'   => $ticket->holder_email ?? '-',
                        'holder_phone'   => $ticket->holder_phone ?? '-',
                        'holder_nik'        => \App\Models\TicketPass::maskNik($ticket->holder_nik),
                        'holder_nik_masked' => \App\Models\TicketPass::maskNik($ticket->holder_nik),
                        'checked_in_at'  => now()->format('H:i:s'),
                    ]
                ]);
            });
        } catch (\Throwable $e) {
            Log::error('[TicketScanner] verify error: ' . $e->getMessage());
            return response()->json([
                'status'  => 'invalid',
                'title'   => 'Kesalahan Sistem',
                'message' => 'Terjadi kesalahan saat memproses tiket. Silakan coba lagi.'
            ], 500);
        }

        return $result;
    }

    /**
     * Helper pintar untuk mengekstrak token dari URL, JSON, atau Teks Biasa
     */
    private function extractCandidateTokens(string $raw): array
    {
        $candidates = [$raw, trim($raw)];

        // Parsing JSON jika payload berupa string JSON
        if (\Illuminate\Support\Str::startsWith($raw, '{') && \Illuminate\Support\Str::endsWith($raw, '}')) {
            $json = json_decode($raw, true);
            if (is_array($json)) {
                foreach (['code', 'qr_token', 'token', 'ticket_code', 'id'] as $k) {
                    if (!empty($json[$k])) {
                        $candidates[] = (string) $json[$k];
                    }
                }
            }
        }

        // Parsing URL jika berupa link (misal: https://buyle.id/verify?token=XYZ atau https://buyle.id/t/XYZ)
        if (\Illuminate\Support\Str::startsWith($raw, ['http://', 'https://'])) {
            $parsedUrl = parse_url($raw);
            if (!empty($parsedUrl['query'])) {
                parse_str($parsedUrl['query'], $queryParams);
                foreach (['token', 'qr_token', 'code', 'ticket_code', 't'] as $key) {
                    if (!empty($queryParams[$key])) {
                        $candidates[] = (string) $queryParams[$key];
                    }
                }
            }
            if (!empty($parsedUrl['path'])) {
                $pathSegments = array_values(array_filter(explode('/', $parsedUrl['path'])));
                if (!empty($pathSegments)) {
                    $lastSegment = end($pathSegments);
                    if (strlen($lastSegment) > 3) {
                        $candidates[] = $lastSegment;
                    }
                }
            }
        }

        // Tambahkan variasi UPPERCASE, lowercase, dan stripped format
        $expanded = [];
        foreach ($candidates as $c) {
            $c = trim($c);
            if (!empty($c)) {
                $expanded[] = $c;
                $expanded[] = strtoupper($c);
                $expanded[] = strtolower($c);
            }
        }

        return array_unique($expanded);
    }

    /**
     * Toggle Check-In status dari Tabel Data Kehadiran.
     */
    public function toggleCheckin(Request $request, TicketPass $ticket)
    {
        $sellerId = auth()->id();
        $isAssignedStaff = \App\Models\GateStaff::where('user_id', $sellerId)
            ->where('product_id', $ticket->product_id)
            ->where('is_active', true)
            ->exists();
        
        if ($ticket->seller_id != $sellerId && $ticket->product?->seller_id != $sellerId && !$isAssignedStaff) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
            }
            return redirect()->back()->with('error', 'Akses ditolak.');
        }

        if ($ticket->status === 'used') {
            $ticket->update([
                'status'        => 'valid',
                'checked_in_at' => null,
                'checked_in_by' => null,
            ]);
            $msg = "Status tiket {$ticket->ticket_code} diubah menjadi Belum Hadir.";
        } else {
            $ticket->update([
                'status'        => 'used',
                'checked_in_at' => now(),
                'checked_in_by' => auth()->id(),
            ]);
            $msg = "Berhasil check-in manual untuk tiket {$ticket->ticket_code} (a.n {$ticket->holder_name}).";
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $msg,
                'status'  => $ticket->status,
                'checked_in_at' => $ticket->checked_in_at ? $ticket->checked_in_at->format('d M Y H:i') : '-'
            ]);
        }

        return redirect()->back()->with('success', $msg);
    }

    /**
     * JSON detail satu tiket untuk modal pengunjung.
     */
    public function ticketDetail(TicketPass $ticket): JsonResponse
    {
        $sellerId = auth()->id();
        $isAssignedStaff = \App\Models\GateStaff::where('user_id', $sellerId)
            ->where('product_id', $ticket->product_id)
            ->where('is_active', true)
            ->exists();

        if ($ticket->seller_id != $sellerId && $ticket->product?->seller_id != $sellerId && !$isAssignedStaff) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }

        $ticket->loadMissing(['order', 'product']);
        $order = $ticket->order;

        return response()->json([
            'success' => true,
            'ticket'  => [
                'id'            => $ticket->id,
                'code'          => $ticket->ticket_code,
                'holder_name'   => $ticket->holder_name ?? '—',
                'holder_nik'    => TicketPass::maskNik($ticket->holder_nik),
                'holder_phone'  => $ticket->holder_phone ?? '—',
                'holder_email'  => $ticket->holder_email ?? '—',
                'event_name'    => $ticket->product?->name ?? '—',
                'event_date'    => $ticket->product?->event_date ? $ticket->product->event_date->format('d M Y') : '—',
                'order_number'  => $order?->order_number ?? '—',
                'status'        => $ticket->status,
                'checked_in_at' => $ticket->checked_in_at ? $ticket->checked_in_at->format('H:i:s · d M Y') : null,
                'scanned_by'    => $ticket->scanned_by_staff_name
                    ? ($ticket->scanned_by_staff_name . ' · Gate ' . $ticket->scanned_at_gate)
                    : ($ticket->checked_in_by ? 'Creator Studio' : null),
                'claim_proof'   => $order?->claim_proof ? (str_starts_with($order->claim_proof, 'http') ? $order->claim_proof : asset('storage/' . $order->claim_proof)) : null,
                'claim_proofs'  => $order?->claim_proofs_list ?? [],
                'extra_data'    => $ticket->extra_data ?? [],
            ],
        ]);
    }

    /**
     * Export data kehadiran event ke file CSV.
     */
    public function exportCsv(Request $request): StreamedResponse
    {
        $sellerId = auth()->id();
        $selectedEventId = $request->input('event_id');
        $statusFilter = $request->input('status');
        $search = trim($request->input('search', ''));

        $ticketsQuery = TicketPass::with(['product', 'order', 'orderItem'])
            ->where(function ($q) use ($sellerId) {
                $q->where('seller_id', $sellerId)
                  ->orWhereHas('product', function ($pq) use ($sellerId) {
                      $pq->where('seller_id', $sellerId);
                  });
            });

        if ($selectedEventId) {
            $ticketsQuery->where('product_id', $selectedEventId);
        }

        if ($statusFilter && in_array($statusFilter, ['valid', 'used', 'cancelled'])) {
            $ticketsQuery->where('status', $statusFilter);
        }

        if (!empty($search)) {
            $ticketsQuery->where(function ($q) use ($search) {
                $q->where('holder_name', 'like', "%{$search}%")
                  ->orWhere('holder_email', 'like', "%{$search}%")
                  ->orWhere('holder_phone', 'like', "%{$search}%")
                  ->orWhere('ticket_code', 'like', "%{$search}%");
            });
        }

        $tickets = $ticketsQuery->orderBy('created_at', 'desc')->get();

        $filename = 'data_kehadiran_event_' . date('Ymd_His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        $callback = function () use ($tickets) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Kumpulkan semua custom field keys secara dinamis dari tickets yang diexport
            $dynamicKeys = [];
            foreach ($tickets as $t) {
                if (!empty($t->extra_data) && is_array($t->extra_data)) {
                    foreach (array_keys($t->extra_data) as $k) {
                        if (!in_array($k, $dynamicKeys)) {
                            $dynamicKeys[] = $k;
                        }
                    }
                }
            }

            $legacyMap = [
                'gender'           => 'Gender',
                'tgl_lahir'        => 'Tanggal Lahir',
                'ukuran_jersey'    => 'Ukuran Jersey',
                'divisi_lari'      => 'Divisi / Kategori Lari',
                'kontak_darurat'   => 'Kontak Darurat',
                'institusi'        => 'Institusi / Perusahaan',
                'jabatan'          => 'Divisi / Jabatan',
                'nama_tim'         => 'Nama Tim',
                'posisi'           => 'Posisi / Nomor',
                'kebutuhan_khusus' => 'Kebutuhan Khusus',
            ];

            $headerRow = [
                'No',
                'Kode Tiket',
                'Nama Event',
                'Kategori / Varian',
                'Nama Pemegang',
                'Email',
                'No. HP',
                'NIK / Identitas',
                'Status Kehadiran',
                'Waktu Check-In',
                'Petugas Gate',
                'No. Gate',
                'Tanggal Pembelian',
            ];
            foreach ($dynamicKeys as $dKey) {
                $headerRow[] = $legacyMap[$dKey] ?? $dKey;
            }
            fputcsv($file, $headerRow);

            foreach ($tickets as $index => $ticket) {
                $statusLabel = match($ticket->status) {
                    'used'      => 'Checked-In (Hadir)',
                    'cancelled' => 'Dibatalkan',
                    default     => 'Belum Hadir',
                };

                $extra = is_array($ticket->extra_data) ? $ticket->extra_data : [];
                $variantName = $ticket->orderItem?->variant_name ?? '-';

                $row = [
                    $index + 1,
                    $ticket->ticket_code,
                    $ticket->product?->name ?? 'Event',
                    $variantName,
                    $ticket->holder_name ?? '-',
                    $ticket->holder_email ?? '-',
                    $ticket->holder_phone ?? '-',
                    \App\Models\TicketPass::maskNik($ticket->holder_nik),
                    $statusLabel,
                    $ticket->checked_in_at ? $ticket->checked_in_at->format('Y-m-d H:i:s') : '-',
                    $ticket->scanned_by_staff_name ?? ($ticket->checked_in_by ? 'Creator Studio' : '-'),
                    $ticket->scanned_at_gate ? ('Gate ' . $ticket->scanned_at_gate) : '-',
                    $ticket->created_at ? $ticket->created_at->format('Y-m-d H:i:s') : '-',
                ];
                foreach ($dynamicKeys as $dKey) {
                    $row[] = $extra[$dKey] ?? '-';
                }

                fputcsv($file, $row);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export Lembar Cetak Gelang Tiket Fisik (Wristbands) via PDF untuk Vendor Percetakan.
     * Berisi: Barcode 1D (Code 128) + QR Code 2D + No. Tiket + Nama Event + No. Urut.
     */
    public function exportWristbandsPdf(Request $request)
    {
        $sellerId = auth()->id();
        $selectedEventId = $request->input('event_id');
        $statusFilter = $request->input('status');
        $search = trim($request->input('search', ''));

        $ticketsQuery = TicketPass::with(['product', 'order', 'orderItem'])
            ->where(function ($q) use ($sellerId) {
                $q->where('seller_id', $sellerId)
                  ->orWhereHas('product', function ($pq) use ($sellerId) {
                      $pq->where('seller_id', $sellerId);
                  });
            });

        if ($selectedEventId) {
            $ticketsQuery->where('product_id', $selectedEventId);
        }

        // Secara default jangan sertakan tiket yang dibatalkan, kecuali difilter eksplisit
        if ($statusFilter && in_array($statusFilter, ['valid', 'used', 'cancelled'])) {
            $ticketsQuery->where('status', $statusFilter);
        } else {
            $ticketsQuery->where('status', '!=', 'cancelled');
        }

        if (!empty($search)) {
            $ticketsQuery->where(function ($q) use ($search) {
                $q->where('holder_name', 'like', "%{$search}%")
                  ->orWhere('holder_email', 'like', "%{$search}%")
                  ->orWhere('holder_phone', 'like', "%{$search}%")
                  ->orWhere('ticket_code', 'like', "%{$search}%");
            });
        }

        $tickets = $ticketsQuery->orderBy('id', 'asc')->get();

        $event = $selectedEventId ? \App\Models\Product::find($selectedEventId) : null;
        $eventSlug = $event ? \Illuminate\Support\Str::slug($event->name) : 'semua_event';
        $filename = 'gelang_tiket_' . $eventSlug . '_' . date('Ymd_His') . '.pdf';

        if (!file_exists(storage_path('fonts'))) {
            @mkdir(storage_path('fonts'), 0775, true);
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('creator.ticket_wristbands_pdf', compact('tickets', 'event'))
            ->setPaper('a4', 'portrait')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', true);

        return $pdf->download($filename);
    }

    /**
     * Tampilkan Halaman Scanner Khusus Panitia / Gate Staff (Mobile Friendly, No Login Required).
     */
    public function gateIndex(Request $request, string $key)
    {
        $event = Product::where('gate_scanner_key', $key)
            ->where('product_type', 'ticket')
            ->first();

        if (!$event) {
            abort(404, 'Link Gate Scanner tidak valid atau telah diganti oleh penyelenggara event.');
        }

        $gateStatus = $event->getGateStatus();
        if (!$gateStatus['is_open'] && $gateStatus['status'] === 'disabled') {
            $seo = [
                'title'  => 'Gate Scanner Dinonaktifkan · ' . $event->name,
                'robots' => 'noindex, nofollow',
            ];
            return view('gate.disabled', compact('event', 'seo', 'gateStatus'));
        }

        // Tiket khusus event ini untuk 0ms instant scan & offline-ready lookup
        $eventTickets = TicketPass::where('product_id', $event->id)->get();

        $totalTickets   = $eventTickets->count();
        $totalCheckedIn = $eventTickets->where('status', 'used')->count();
        $totalUnchecked = $eventTickets->where('status', 'valid')->count();
        $attendanceRate = $totalTickets > 0 ? round(($totalCheckedIn / $totalTickets) * 100, 1) : 0;

        $stats = [
            'total'      => $totalTickets,
            'checked_in' => $totalCheckedIn,
            'unchecked'  => $totalUnchecked,
            'rate'       => $attendanceRate,
        ];

        $cachedTickets = $eventTickets->map(function ($t) use ($event) {
            return [
                'id'            => $t->id,
                'code'          => $t->ticket_code,
                'qr_token'      => $t->qr_token,
                'product_id'    => $t->product_id,
                'event_name'    => $event->name,
                'holder_name'   => $t->holder_name ?? '-',
                'holder_email'  => $t->holder_email ?? '-',
                'holder_phone'  => $t->holder_phone ?? '-',
                'holder_nik_masked' => \App\Models\TicketPass::maskNik($t->holder_nik),
                'status'        => $t->status,
                'checked_in_at' => $t->checked_in_at ? $t->checked_in_at->format('H:i:s (d M Y)') : null,
                'scanned_by'    => $t->scanned_by_staff_name
                    ? ($t->scanned_by_staff_name . ' · Gate ' . $t->scanned_at_gate)
                    : null,
            ];
        });

        if ($request->wantsJson()) {
            return response()->json([
                'success'     => true,
                'stats'       => $stats,
                'gate_status' => $gateStatus,
                'tickets'     => $cachedTickets,
            ]);
        }

        $requiresPin        = !empty($event->gate_scanner_pin) && !session('gate_auth_' . $event->id);
        $hasActiveStaff     = \App\Models\GateStaff::where('product_id', $event->id)->where('is_active', true)->exists();
        $staffSession       = session('gate_staff_' . $event->id);
        $requiresStaffLogin = $hasActiveStaff && empty($staffSession);
        $activeStaffList    = $hasActiveStaff 
            ? \App\Models\GateStaff::where('product_id', $event->id)->where('is_active', true)->orderBy('gate_number')->orderBy('name')->get(['id', 'name', 'gate_number'])
            : collect();

        return view('gate.scanner', compact('event', 'key', 'stats', 'cachedTickets', 'gateStatus', 'requiresPin', 'requiresStaffLogin', 'staffSession', 'activeStaffList'));
    }

    /**
     * Verifikasi Tiket di Gate Scanner oleh Panitia (Akses Terisolasi ke Event ini saja).
     * Terlindungi dengan DB Transaction & lockForUpdate() agar tidak pernah dobel check-in.
     */
    public function gateVerify(Request $request, string $key): JsonResponse
    {
        $event = Product::where('gate_scanner_key', $key)
            ->where('product_type', 'ticket')
            ->first();

        if (!$event) {
            return response()->json([
                'status'  => 'invalid',
                'title'   => 'Akses Tidak Valid',
                'message' => 'Link Gate Scanner tidak ditemukan.'
            ], 404);
        }

        $gateStatus = $event->getGateStatus();
        if (!$gateStatus['is_open']) {
            return response()->json([
                'status'      => 'gate_closed',
                'title'       => 'Pintu Masuk (Gate) Ditutup',
                'message'     => $gateStatus['reason'],
                'gate_status' => $gateStatus
            ], 403);
        }

        // Cek PIN Akses Gate jika event diproteksi password
        if (!empty($event->gate_scanner_pin) && !session('gate_auth_' . $event->id)) {
            $providedPin = $request->header('X-Gate-Pin') ?? $request->input('gate_pin');
            if ($providedPin !== (string) $event->gate_scanner_pin) {
                return response()->json([
                    'status'  => 'auth_required',
                    'title'   => 'PIN Gate Diperlukan',
                    'message' => 'Silakan masukkan PIN Gate sebelum melakukan verifikasi tiket.'
                ], 401);
            }
        }

        $rawInput = trim($request->input('code') ?? $request->input('qr_token') ?? '');
        if (empty($rawInput)) {
            return response()->json([
                'status'  => 'invalid',
                'title'   => 'Kode Kosong',
                'message' => 'Kode tiket atau QR Code tidak terdeteksi.'
            ], 400);
        }

        $tokensToSearch = array_values(array_unique(array_filter($this->extractCandidateTokens($rawInput))));
        if (empty($tokensToSearch)) {
            $tokensToSearch = [$rawInput];
        }

        $result = null;

        try {
            DB::transaction(function () use ($tokensToSearch, $event, &$result) {
                // 1. Cari tiket KHUSUS untuk event ini dengan Pessimistic Lock
                $ticket = TicketPass::with(['product', 'buyer'])
                    ->where('product_id', $event->id)
                    ->where(function ($q) use ($tokensToSearch) {
                        foreach ($tokensToSearch as $t) {
                            $q->orWhere('qr_token', $t)
                              ->orWhere('ticket_code', $t)
                              ->orWhere('ticket_code', strtoupper($t))
                              ->orWhere('qr_token', strtolower($t));
                        }
                    })
                    ->lockForUpdate()
                    ->first();

                // 2. Jika tidak ditemukan di event ini, cek apakah tiket milik event lain
                if (!$ticket) {
                    $otherTicket = TicketPass::with('product')
                        ->where(function ($q) use ($tokensToSearch) {
                            foreach ($tokensToSearch as $t) {
                                $q->orWhere('qr_token', $t)
                                  ->orWhere('ticket_code', $t)
                                  ->orWhere('ticket_code', strtoupper($t))
                                  ->orWhere('qr_token', strtolower($t));
                            }
                        })
                        ->first();

                    if ($otherTicket) {
                        $otherName = $otherTicket->product?->name ?? 'Event Lain';
                        $result = response()->json([
                            'status'  => 'invalid',
                            'title'   => 'Bukan Tiket Event Ini!',
                            'message' => "Tiket {$otherTicket->ticket_code} ini terdaftar untuk event \"{$otherName}\", bukan untuk \"{$event->name}\".",
                            'ticket'  => [
                                'code'        => $otherTicket->ticket_code,
                                'holder_name' => $otherTicket->holder_name ?? '-',
                                'event_name'  => $otherName,
                            ]
                        ]);
                        return;
                    }

                    $result = response()->json([
                        'status'  => 'invalid',
                        'title'   => 'Tiket Tidak Ditemukan',
                        'message' => 'Kode tiket atau QR Code ini tidak terdaftar di sistem buyle.id.'
                    ]);
                    return;
                }

                // 3. Tiket dibatalkan
                if ($ticket->status === 'cancelled') {
                    $result = response()->json([
                        'status'  => 'invalid',
                        'title'   => 'Tiket Dibatalkan',
                        'message' => 'Tiket ini telah dibatalkan atau direfund. Tidak dapat check-in.',
                        'ticket'  => [
                            'code'        => $ticket->ticket_code,
                            'event_name'  => $event->name,
                            'holder_name' => $ticket->holder_name ?? '-',
                        ]
                    ]);
                    return;
                }

                // 4. Tiket sudah digunakan sebelumnya (mencegah tabrakan antar panitia)
                if ($ticket->status === 'used') {
                    $time = $ticket->checked_in_at ? $ticket->checked_in_at->format('H:i (d M Y)') : '-';
                    $result = response()->json([
                        'status'  => 'used',
                        'title'   => 'Tiket Sudah Digunakan',
                        'message' => "Tiket a.n {$ticket->holder_name} sudah dipindai sebelumnya pada pukul {$time}.",
                        'ticket'  => [
                            'code'          => $ticket->ticket_code,
                            'event_name'    => $event->name,
                            'event_image'   => $event->image_url,
                            'holder_name'   => $ticket->holder_name ?? '-',
                            'checked_in_at' => $time,
                        ]
                    ]);
                    return;
                }

                // 5. Tiket valid -> tandai sebagai used + catat atribusi staff
                $staffInfo = session('gate_staff_' . $event->id);

                $ticket->update([
                    'status'                => 'used',
                    'checked_in_at'         => now(),
                    'scanned_by_staff_name' => $staffInfo['name'] ?? null,
                    'scanned_at_gate'       => $staffInfo['gate'] ?? null,
                ]);

                $maskedNik = \App\Models\TicketPass::maskNik($ticket->holder_nik);
                $staffLabel = $staffInfo
                    ? ($staffInfo['name'] . ' · Gate ' . $staffInfo['gate'])
                    : 'Panitia (Tidak Login)';

                $result = response()->json([
                    'status'  => 'valid',
                    'title'   => 'Check-In Berhasil!',
                    'message' => "Selamat datang, {$ticket->holder_name}! Tiket diverifikasi.",
                    'ticket'  => [
                        'code'              => $ticket->ticket_code,
                        'event_name'        => $event->name,
                        'event_image'       => $event->image_url,
                        'holder_name'       => $ticket->holder_name ?? '-',
                        'holder_email'      => $ticket->holder_email ?? '-',
                        'holder_phone'      => $ticket->holder_phone ?? '-',
                        'holder_nik'        => $maskedNik,
                        'holder_nik_masked' => $maskedNik,
                        'checked_in_at'     => now()->format('H:i:s (d M Y)'),
                        'scanned_by'        => $staffLabel,
                    ]
                ]);
            });
        } catch (\Throwable $e) {
            Log::error('Gate Scanner Error: ' . $e->getMessage());
            return response()->json([
                'status'  => 'error',
                'title'   => 'Kesalahan Sistem',
                'message' => 'Gagal memproses verifikasi. Silakan coba lagi.'
            ], 500);
        }

        return $result ?? response()->json(['status' => 'error', 'message' => 'Terjadi kesalahan.'], 500);
    }

    /**
     * Creator me-reset Gate Key untuk event ini (agar panitia lama otomatis terputus).
     */
    public function resetGateKey(Request $request, Product $product): JsonResponse
    {
        if ($product->seller_id !== auth()->id()) {
            abort(403);
        }

        $newKey = Str::random(32);
        $product->update([
            'gate_scanner_key' => $newKey,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kunci akses Gate Scanner berhasil diperbarui. Link panitia sebelumnya otomatis tidak berlaku.',
            'new_key' => $newKey,
            'new_url' => route('gate.scanner', ['key' => $newKey]),
        ]);
    }

    /**
     * Creator mengaktifkan / menonaktifkan Gate Scanner untuk event ini.
     */
    public function toggleGateEnabled(Request $request, Product $product): JsonResponse
    {
        if ($product->seller_id !== auth()->id()) {
            abort(403);
        }

        $product->update([
            'gate_scanner_enabled' => !$product->gate_scanner_enabled,
        ]);

        return response()->json([
            'success' => true,
            'enabled' => $product->gate_scanner_enabled,
            'message' => $product->gate_scanner_enabled 
                ? 'Akses Gate Scanner untuk panitia diaktifkan.' 
                : 'Akses Gate Scanner untuk panitia dinonaktifkan.',
        ]);
    }

    /**
     * Creator mengatur jadwal Open Gate & Close Gate untuk event ini.
     */
    public function updateGateSchedule(Request $request, Product $product): JsonResponse
    {
        if ($product->seller_id !== auth()->id()) {
            abort(403);
        }

        $openTime = $request->input('open_time') ? trim($request->input('open_time')) : null;
        $closeTime = $request->input('close_time') ? trim($request->input('close_time')) : null;
        $bypass = $request->boolean('bypass', false);

        $product->update([
            'gate_open_time'   => $openTime,
            'gate_close_time'  => $closeTime,
            'gate_bypass_time' => $bypass,
        ]);

        return response()->json([
            'success'     => true,
            'message'     => 'Jadwal Gate Scanner berhasil diperbarui.',
            'gate_status' => $product->getGateStatus(),
        ]);
    }

    /**
     * Creator memperpanjang Close Gate secara cepat (+30 Menit) saat antrean padat.
     */
    public function quickExtendGate(Request $request, Product $product): JsonResponse
    {
        if ($product->seller_id !== auth()->id()) {
            abort(403);
        }

        $now = now();
        if (!empty($product->gate_close_time)) {
            $parts = explode(':', $product->gate_close_time);
            $closeCarbon = now()->setTime((int)($parts[0] ?? 0), (int)($parts[1] ?? 0));
            $base = $closeCarbon->isPast() ? $now : $closeCarbon;
        } else {
            $base = $now;
        }

        $newCloseTime = $base->copy()->addMinutes(30)->format('H:i');

        $product->update([
            'gate_close_time'  => $newCloseTime,
            'gate_bypass_time' => false,
        ]);

        return response()->json([
            'success'        => true,
            'new_close_time' => $newCloseTime,
            'message'        => 'Waktu tutup gate diperpanjang hingga ' . $newCloseTime . ' WIB (+30 Menit).',
            'gate_status'    => $product->getGateStatus(),
        ]);
    }

    /**
     * Creator mengaktifkan/menonaktifkan Bypass Waktu Darurat (Buka Terus Tanpa Batasan Jam).
     */
    public function toggleGateBypass(Request $request, Product $product): JsonResponse
    {
        if ($product->seller_id !== auth()->id()) {
            abort(403);
        }

        $product->update([
            'gate_bypass_time' => !$product->gate_bypass_time,
        ]);

        return response()->json([
            'success'     => true,
            'bypass'      => $product->gate_bypass_time,
            'message'     => $product->gate_bypass_time 
                ? 'Mode Darurat: Gate sekarang dibuka tanpa batasan jam (Bypass Aktif).' 
                : 'Mode Normal: Gate kembali mengikuti jadwal jam buka/tutup.',
            'gate_status' => $product->getGateStatus(),
        ]);
    }

    /**
     * Autentikasi PIN / Password Gate untuk Panitia di HP.
     */
    public function gateAuthPin(Request $request, string $key): JsonResponse
    {
        $event = Product::where('gate_scanner_key', $key)
            ->where('product_type', 'ticket')
            ->first();

        if (!$event) {
            return response()->json(['success' => false, 'message' => 'Event tidak ditemukan.'], 404);
        }

        if (empty($event->gate_scanner_pin)) {
            return response()->json(['success' => true]);
        }

        $inputPin = trim($request->input('pin', ''));
        if ($inputPin === (string) $event->gate_scanner_pin) {
            session(['gate_auth_' . $event->id => true]);
            return response()->json(['success' => true, 'message' => 'PIN Benar.']);
        }

        return response()->json(['success' => false, 'message' => 'PIN / Password Gate salah.'], 401);
    }

    /**
     * Creator mengupdate PIN / Password Gate untuk event ini.
     */
    public function updateGatePin(Request $request, Product $product): JsonResponse
    {
        if ($product->seller_id !== auth()->id()) {
            abort(403);
        }

        $pin = trim($request->input('pin', ''));
        $product->update([
            'gate_scanner_pin' => !empty($pin) ? $pin : null,
        ]);

        return response()->json([
            'success' => true,
            'has_pin' => !empty($pin),
            'pin'     => $pin,
            'message' => !empty($pin) 
                ? 'Password Gate berhasil disimpan: ' . $pin
                : 'Proteksi PIN dinonaktifkan (Gate terbuka langsung).',
        ]);
    }

    /**
     * Simpan / update rekening bank creator dari halaman Event Attendance.
     * Data otomatis tersinkron dengan /creator/payout-settings.
     */
    public function updateBankAccount(\Illuminate\Http\Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'bank_name'           => 'required|string|max:100',
            'bank_account_number' => 'required|string|max:50|regex:/^[0-9\-]+$/',
            'bank_account_name'   => 'required|string|max:100',
        ], [
            'bank_account_number.regex' => 'Nomor rekening hanya boleh berisi angka.',
        ]);

        $inputName = trim($request->bank_account_name);
        $userName  = trim($user->name);

        // Validasi: nama rekening HARUS sama dengan nama akun terdaftar (case-insensitive)
        if (mb_strtolower($inputName) !== mb_strtolower($userName)) {
            return back()
                ->withInput()
                ->with('bank_error', 'Nama pemilik rekening harus sama persis dengan nama akun terdaftar: "' . $userName . '".')
                ->withFragment('tab-rekening');
        }

        $user->update([
            'bank_name'           => $request->bank_name,
            'bank_account_number' => $request->bank_account_number,
            'bank_account_name'   => $inputName,
        ]);

        if ($request->header('referer') && str_contains($request->header('referer'), 'ticketing/profile')) {
            return redirect()
                ->route('creator.ticket.profile')
                ->with('bank_success', 'Rekening pencairan berhasil disimpan!');
        }

        return redirect()
            ->route('creator.ticket.scanner', ['tab' => 'home'])
            ->with('bank_success', 'Rekening pencairan berhasil disimpan!');
    }

    /**
     * Halaman Menu & Profil Penyelenggara Tiket (/creator/ticketing/profile).
     * Desain 100% konsisten & identik dengan /creator/pos/profile.
     */
    public function profileMenu(Request $request)
    {
        $seller = auth()->user();
        $sellerId = $seller->id;

        $cp = \App\Models\CreatorProfile::firstOrCreate(
            ['user_id' => $seller->id],
            ['store_name' => $seller->name]
        );

        $balanceData = \App\Services\SellerBalanceService::calculateBalance($seller);
        $gmv              = $balanceData['gmv'] ?? 0;
        $availableBalance = $balanceData['available_balance'] ?? 0;
        $tierInfo         = $cp->getTierInfo($gmv);

        $kyc = \App\Models\CreatorKyc::where('user_id', $sellerId)->first();

        // Query tiket milik creator
        $baseQuery = TicketPass::where(function ($q) use ($sellerId) {
            $q->where('seller_id', $sellerId)
              ->orWhereHas('product', function ($pq) use ($sellerId) {
                  $pq->where('seller_id', $sellerId);
              });
        });

        $totalTickets   = (clone $baseQuery)->count();
        $totalCheckedIn = (clone $baseQuery)->where('status', 'used')->count();
        $totalEvents    = Product::where('seller_id', $sellerId)->where('product_type', 'ticket')->count();
        $gateStaffCount = \App\Models\GateStaff::where('creator_id', $sellerId)->count();

        // Omset penjualan tiket (tanpa error column not found)
        try {
            $ticketRevenue = (float) \App\Models\OrderItem::whereHas('product', function ($pq) use ($sellerId) {
                $pq->where('seller_id', $sellerId)->where('product_type', 'ticket');
            })->whereHas('order', function ($oq) {
                $oq->whereNotIn('status', ['cancelled', 'refunded', 'pending']);
            })->sum('subtotal');
        } catch (\Throwable $e) {
            $ticketRevenue = 0;
        }

        if ($ticketRevenue <= 0 && $gmv > 0) {
            $ticketRevenue = $gmv;
        }

        $hasPushSub = \App\Models\PushSubscription::where('user_id', $seller->id)->exists();

        $viewMode = session('scanner_view_mode_' . $sellerId, 'creator');
        $isCrewMode = ($viewMode === 'crew');

        $seo = [
            'title'  => 'Menu & Profil Penyelenggara Event · Buyle.id Ticketing',
            'robots' => 'noindex, nofollow',
        ];

        return view('creator.ticketing_profile', compact(
            'seller',
            'cp',
            'gmv',
            'availableBalance',
            'tierInfo',
            'ticketRevenue',
            'kyc',
            'totalTickets',
            'totalCheckedIn',
            'totalEvents',
            'gateStaffCount',
            'hasPushSub',
            'seo',
            'isCrewMode'
        ));
    }

    /**
     * Proses transaksi penjualan tiket OTS (On-The-Spot / Kasir Loket Hari-H).
     */
    public function storeOtsOrder(Request $request): JsonResponse
    {
        $sellerId = auth()->id();
        $user = auth()->user();

        $request->validate([
            'product_id'       => 'required|exists:products,id',
            'qty'              => 'required|integer|min:1|max:50',
            'buyer_name'       => 'required|string|max:150',
            'buyer_phone'      => 'nullable|string|max:30',
            'buyer_email'      => 'nullable|email|max:150',
            'payment_method'   => 'required|in:cash,qris',
            'cash_received'    => 'nullable|numeric|min:0',
            'fulfillment_type' => 'required|in:auto_checkin,issue_ticket',
            'notes'            => 'nullable|string|max:500',
        ]);

        $product = Product::findOrFail($request->product_id);

        // Security check: Must be seller's product OR staff assigned to this product
        $isAssigned = \App\Models\GateStaff::where('user_id', $sellerId)
            ->where('product_id', $product->id)
            ->where('is_active', true)
            ->exists();

        if ($product->seller_id !== $sellerId && !$isAssigned) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki hak akses untuk menjual tiket event ini.'
            ], 403);
        }

        $qty = (int) $request->qty;
        $price = $product->effective_ots_price;
        $subtotal = $price * $qty;

        // Pajak tiket jika diaktifkan
        $taxTotal = 0;
        if ($product->tax_enabled && $product->ticket_tax_rate > 0) {
            $taxTotal = round($subtotal * ($product->ticket_tax_rate / 100));
        }
        $total = $subtotal + $taxTotal;

        // Validasi stok tiket
        if ($product->stock !== null && $product->stock < $qty) {
            return response()->json([
                'success' => false,
                'message' => "Stok tiket tidak mencukupi (Tersisa {$product->stock} tiket)."
            ], 422);
        }

        // Validasi uang tunai
        $cashReceived = (float) $request->input('cash_received', 0);
        $change = 0;
        if ($request->payment_method === 'cash') {
            if ($cashReceived < $total) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nominal uang tunai yang diterima kurang dari total tagihan.'
                ], 422);
            }
            $change = $cashReceived - $total;
        }

        DB::beginTransaction();
        try {
            // Pengurangan stok secara atomik
            if ($product->stock !== null) {
                $product->decrement('stock', $qty);
            }

            // Generate Order
            $orderNumber = 'OTS-' . date('ymd') . '-' . strtoupper(Str::random(5));
            $order = \App\Models\Order::create([
                'order_number'        => $orderNumber,
                'user_id'             => null,
                'customer_email'      => $request->buyer_email,
                'status'              => \App\Enums\OrderStatus::Confirmed,
                'subtotal'            => $subtotal,
                'shipping_cost'       => 0,
                'platform_fee'        => 0,
                'admin_fee'           => $taxTotal,
                'payment_gateway_fee' => 0,
                'discount'            => 0,
                'total'               => $total,
                'source'              => 'ots',
                'cashier_id'          => $sellerId,
                'cashier_name'        => $user->name,
                'notes'               => $request->notes,
                'shipping_address'    => [
                    'receiver_name'   => $request->buyer_name,
                    'phone'           => $request->buyer_phone,
                    'email'           => $request->buyer_email,
                    'payment_method'  => $request->payment_method,
                    'cash_received'   => $cashReceived,
                    'change'          => $change,
                    'is_ots'          => true,
                    'tax_name'        => $product->ticket_tax_name,
                    'tax_total'       => $taxTotal,
                    'ticket_taxes'    => $product->ticket_taxes_list,
                    'fulfillment'     => $request->fulfillment_type,
                ],
            ]);

            // Order Item
            $orderItem = \App\Models\OrderItem::create([
                'order_id'     => $order->id,
                'product_id'   => $product->id,
                'seller_id'    => $product->seller_id,
                'product_name' => $product->name,
                'qty'          => $qty,
                'price'        => $price,
                'subtotal'     => $subtotal,
            ]);

            // Terbitkan Tiket Pass
            $isAutoCheckin = ($request->fulfillment_type === 'auto_checkin');
            $createdTickets = [];

            for ($i = 0; $i < $qty; $i++) {
                $ticket = TicketPass::create([
                    'order_id'              => $order->id,
                    'order_item_id'         => $orderItem->id,
                    'product_id'            => $product->id,
                    'user_id'               => null,
                    'seller_id'             => $product->seller_id,
                    'holder_name'           => $request->buyer_name . ($qty > 1 ? " (#" . ($i + 1) . ")" : ""),
                    'holder_phone'          => $request->buyer_phone,
                    'holder_email'          => $request->buyer_email,
                    'status'                => $isAutoCheckin ? 'used' : 'valid',
                    'is_ots'                => true,
                    'cashier_user_id'       => $sellerId,
                    'payment_method'        => $request->payment_method,
                    'checked_in_at'         => $isAutoCheckin ? now() : null,
                    'checked_in_by'         => $isAutoCheckin ? $sellerId : null,
                    'scanned_by_staff_name' => $isAutoCheckin ? ($user->name . ' (Kasir Loket OTS)') : null,
                    'scanned_at_gate'       => $isAutoCheckin ? 'Loket OTS' : null,
                ]);

                $createdTickets[] = [
                    'id'          => $ticket->id,
                    'ticket_code' => $ticket->ticket_code,
                    'qr_token'    => $ticket->qr_token,
                    'holder_name' => $ticket->holder_name,
                    'status'      => $ticket->status,
                    'qr_url'      => 'https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=' . urlencode($ticket->qr_token),
                ];
            }

            DB::commit();

            // Kirim E-Tiket ke Email melalui SMTP Hostinger jika diisi pembeli
            $emailSent = false;
            if (!empty($request->buyer_email)) {
                try {
                    \App\Services\MailConfigService::apply();
                    $magicLoginUrl = route('account.orders');
                    \Illuminate\Support\Facades\Notification::route('mail', $request->buyer_email)
                        ->notify(new \App\Notifications\OrderPaidNotification($order, $magicLoginUrl, false));
                    $emailSent = true;
                } catch (\Throwable $mailEx) {
                    \Log::warning('Gagal mengirim E-Tiket OTS ke email ' . $request->buyer_email . ': ' . $mailEx->getMessage());
                }
            }

            // Format tautan WhatsApp
            $waUrl = null;
            if (!empty($request->buyer_phone)) {
                $cleanPhone = preg_replace('/[^0-9]/', '', $request->buyer_phone);
                if (str_starts_with($cleanPhone, '0')) {
                    $cleanPhone = '62' . substr($cleanPhone, 1);
                } elseif (!str_starts_with($cleanPhone, '62')) {
                    $cleanPhone = '62' . $cleanPhone;
                }
                $ticketListText = implode("\n", array_map(fn($t) => "• " . $t['ticket_code'] . " (" . $t['holder_name'] . ")", $createdTickets));
                $waMsg = "Halo *{$request->buyer_name}*,\n\nTerima kasih telah membeli Tiket OTS event *{$product->name}*!\n\n" .
                         "ID Pesanan: *{$orderNumber}*\n" .
                         "Jumlah: *{$qty} Tiket*\n" .
                         "Total Bayar: *Rp " . number_format($total, 0, ',', '.') . " (" . strtoupper($request->payment_method) . ")*\n\n" .
                         "Daftar Kode Tiket:\n{$ticketListText}\n\n" .
                         ($isAutoCheckin 
                            ? "✅ *Tiket Anda telah langsung di-check in di loket.* Selamat menikmati acara!"
                            : "🎟️ Tunjukkan kode tiket atau QR kepada petugas di pintu masuk gate.");
                $waUrl = "https://wa.me/{$cleanPhone}?text=" . rawurlencode($waMsg);
            }

            return response()->json([
                'success'        => true,
                'message'        => 'Transaksi Tiket OTS berhasil diproses!',
                'order_number'   => $orderNumber,
                'event_name'     => $product->name,
                'buyer_name'     => $request->buyer_name,
                'buyer_phone'    => $request->buyer_phone,
                'buyer_email'    => $request->buyer_email,
                'email_sent'     => $emailSent,
                'qty'            => $qty,
                'price'          => $price,
                'subtotal'       => $subtotal,
                'tax_total'      => $taxTotal,
                'total'          => $total,
                'payment_method' => $request->payment_method,
                'cash_received'  => $cashReceived,
                'change'         => $change,
                'fulfillment'    => $request->fulfillment_type,
                'tickets'        => $createdTickets,
                'whatsapp_url'   => $waUrl,
                'created_at'     => now()->format('d M Y H:i:s'),
                'cashier_name'   => $user->name,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('OTS Ticket Error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memproses tiket OTS: ' . $e->getMessage()
            ], 500);
        }
    }
}
