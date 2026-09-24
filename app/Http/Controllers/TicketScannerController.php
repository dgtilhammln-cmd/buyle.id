<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\TicketPass;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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

        // Query TicketPasses milik creator
        $ticketsQuery = TicketPass::with(['product', 'order', 'buyer'])
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

        $allTickets = (clone $ticketsQuery)->get();
        $tickets = $ticketsQuery->orderBy('updated_at', 'desc')->paginate(20)->withQueryString();

        // Stats Ringkasan
        $totalTickets = $allTickets->count();
        $totalCheckedIn = $allTickets->where('status', 'used')->count();
        $totalUnchecked = $allTickets->where('status', 'valid')->count();
        $totalCancelled = $allTickets->where('status', 'cancelled')->count();
        $attendanceRate = $totalTickets > 0 ? round(($totalCheckedIn / $totalTickets) * 100, 1) : 0;

        $stats = [
            'total'         => $totalTickets,
            'checked_in'    => $totalCheckedIn,
            'unchecked'     => $totalUnchecked,
            'cancelled'     => $totalCancelled,
            'rate'          => $attendanceRate,
        ];

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
            'stats'
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
                // 1. Cari tiket milik seller yang sedang login (prioritas)
                $ticket = TicketPass::with(['product', 'buyer'])
                    ->where('seller_id', auth()->id())
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

                // 2. Fallback: cari di seluruh sistem (misal saat cross-event scanning)
                if (!$ticket) {
                    $ticket = TicketPass::with(['product', 'buyer'])
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
                }

                if (!$ticket) {
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
}
