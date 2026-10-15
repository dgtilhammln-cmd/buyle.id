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
        $selectedEventId = $request->input('event_id');
        $statusFilter = $request->input('status');
        $search = trim($request->input('search', ''));
        $activeTab = $request->input('tab', 'scanner'); // 'scanner' or 'data'

        // Ambil daftar event/produk bertipe ticket milik creator ini (menggunakan seller_id)
        $events = Product::where('seller_id', $sellerId)
            ->where('product_type', 'ticket')
            ->orderBy('created_at', 'desc')
            ->get();

        // Query dasar tiket milik creator (baik sebagai seller tiket atau seller produk)
        $baseQuery = TicketPass::with(['product', 'buyer', 'organizer'])
            ->where(function ($q) use ($sellerId) {
                $q->where('seller_id', $sellerId)
                  ->orWhereHas('product', function ($pq) use ($sellerId) {
                      $pq->where('seller_id', $sellerId);
                  });
            });

        // Seluruh tiket milik creator ini untuk Offline/Crowded In-Memory Client Cache
        $allCreatorTickets = (clone $baseQuery)->orderBy('id', 'desc')->get();

        // Format data cache ringan untuk pencarian super cepat (0ms latency di lapangan)
        $allTicketsCached = $allCreatorTickets->map(function ($t) {
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
                'status'        => $t->status,
                'checked_in_at' => $t->checked_in_at ? $t->checked_in_at->format('H:i:s (d M Y)') : null,
                'toggle_url'    => route('creator.ticket.scanner.toggle-checkin', $t->id),
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

        // Stats Ringkasan (dihitung dari seluruh tiket creator atau per event terpilih)
        $statSource = $selectedEventId ? $allCreatorTickets->where('product_id', $selectedEventId) : $allCreatorTickets;
        $totalTickets   = $statSource->count();
        $totalCheckedIn = $statSource->where('status', 'used')->count();
        $totalUnchecked = $statSource->where('status', 'valid')->count();
        $totalCancelled = $statSource->where('status', 'cancelled')->count();
        $attendanceRate = $totalTickets > 0 ? round(($totalCheckedIn / $totalTickets) * 100, 1) : 0;

        $stats = [
            'total'         => $totalTickets,
            'checked_in'    => $totalCheckedIn,
            'unchecked'     => $totalUnchecked,
            'cancelled'     => $totalCancelled,
            'rate'          => $attendanceRate,
        ];

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'stats'   => $stats,
                'tickets' => $allTicketsCached,
            ]);
        }

        $seo = [
            'title'   => 'Scan Tiket & Data Kehadiran · Creator Studio',
            'robots'  => 'noindex, nofollow',
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
            'allTicketsCached'
        ));
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
                // 1. Cari tiket KHUSUS milik creator yang sedang login (baik sebagai seller_id tiket atau pemilik produk)
                $ticket = TicketPass::with(['product.seller', 'buyer'])
                    ->where(function ($q) {
                        $q->where('seller_id', auth()->id())
                          ->orWhereHas('product', function ($pq) {
                              $pq->where('seller_id', auth()->id());
                          });
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
                            'holder_name'   => $ticket->holder_name ?? '-',
                            'checked_in_at' => $time,
                        ]
                    ]);
                    return;
                }

                // 5. Tiket valid — tandai sebagai used
                $ticket->update([
                    'status'        => 'used',
                    'checked_in_at' => now(),
                    'checked_in_by' => auth()->id(),
                ]);

                $result = response()->json([
                    'status'  => 'valid',
                    'title'   => 'Check-In Berhasil! ✓',
                    'message' => "Selamat datang, {$ticket->holder_name}! Tiket berhasil diverifikasi.",
                    'ticket'  => [
                        'code'           => $ticket->ticket_code,
                        'event_name'     => $ticket->product?->name ?? '-',
                        'event_date'     => $ticket->product?->event_date?->format('d M Y') ?? '-',
                        'event_time'     => $ticket->product?->event_time ?? '-',
                        'event_location' => $ticket->product?->event_location ?? '-',
                        'holder_name'    => $ticket->holder_name ?? '-',
                        'holder_email'   => $ticket->holder_email ?? '-',
                        'holder_phone'   => $ticket->holder_phone ?? '-',
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
        
        if ($ticket->seller_id != $sellerId && $ticket->product?->seller_id != $sellerId) {
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
     * Export data kehadiran event ke file CSV.
     */
    public function exportCsv(Request $request): StreamedResponse
    {
        $sellerId = auth()->id();
        $selectedEventId = $request->input('event_id');
        $statusFilter = $request->input('status');
        $search = trim($request->input('search', ''));

        $ticketsQuery = TicketPass::with(['product', 'order'])
            ->where('seller_id', $sellerId);

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

            fputcsv($file, [
                'No',
                'Kode Tiket',
                'Nama Event',
                'Nama Pemegang',
                'Email',
                'No. HP',
                'NIK',
                'Status Kehadiran',
                'Waktu Check-In',
                'Tanggal Pembelian'
            ]);

            foreach ($tickets as $index => $ticket) {
                $statusLabel = match($ticket->status) {
                    'used'      => 'Checked-In (Hadir)',
                    'cancelled' => 'Dibatalkan',
                    default     => 'Belum Hadir',
                };

                fputcsv($file, [
                    $index + 1,
                    $ticket->ticket_code,
                    $ticket->product?->name ?? 'Event',
                    $ticket->holder_name ?? '-',
                    $ticket->holder_email ?? '-',
                    $ticket->holder_phone ?? '-',
                    $ticket->holder_nik ?? '-',
                    $statusLabel,
                    $ticket->checked_in_at ? $ticket->checked_in_at->format('Y-m-d H:i:s') : '-',
                    $ticket->created_at ? $ticket->created_at->format('Y-m-d H:i:s') : '-'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
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
                'status'        => $t->status,
                'checked_in_at' => $t->checked_in_at ? $t->checked_in_at->format('H:i:s (d M Y)') : null,
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

        $requiresPin = !empty($event->gate_scanner_pin) && !session('gate_auth_' . $event->id);

        return view('gate.scanner', compact('event', 'key', 'stats', 'cachedTickets', 'seo', 'gateStatus', 'requiresPin'));
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
                            'holder_name'   => $ticket->holder_name ?? '-',
                            'checked_in_at' => $time,
                        ]
                    ]);
                    return;
                }

                // 5. Tiket valid -> tandai sebagai used
                $ticket->update([
                    'status'        => 'used',
                    'checked_in_at' => now(),
                ]);

                $result = response()->json([
                    'status'  => 'valid',
                    'title'   => 'Check-In Berhasil!',
                    'message' => "Selamat datang, {$ticket->holder_name}! Tiket diverifikasi.",
                    'ticket'  => [
                        'code'           => $ticket->ticket_code,
                        'event_name'     => $event->name,
                        'holder_name'    => $ticket->holder_name ?? '-',
                        'holder_email'   => $ticket->holder_email ?? '-',
                        'holder_phone'   => $ticket->holder_phone ?? '-',
                        'holder_nik'     => $ticket->holder_nik ?? '-',
                        'checked_in_at'  => now()->format('H:i:s (d M Y)'),
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
}
