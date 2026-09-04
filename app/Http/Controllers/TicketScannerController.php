<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\TicketPass;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
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

        // Ambil daftar event/produk bertipe ticket milik creator ini
        $events = Product::where(function ($q) use ($sellerId) {
                $q->where('user_id', $sellerId)->orWhere('seller_id', $sellerId);
            })
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
     */
    public function verify(Request $request): JsonResponse
    {
        $token = trim($request->input('code') ?? $request->input('qr_token'));

        if (empty($token)) {
            return response()->json([
                'status'  => 'invalid',
                'message' => 'Kode tiket atau QR Code tidak valid.'
            ], 400);
        }

        // Cari tiket berdasarkan qr_token atau ticket_code
        $ticket = TicketPass::with(['product', 'order', 'buyer', 'organizer'])
            ->where('seller_id', auth()->id())
            ->where(function($q) use ($token) {
                $q->where('qr_token', $token)->orWhere('ticket_code', $token);
            })
            ->first();

        // Fallback pencarian tanpa filter seller_id (jika tiket ada di sistem)
        if (!$ticket) {
            $ticket = TicketPass::with(['product', 'order', 'buyer', 'organizer'])
                ->where('qr_token', $token)
                ->orWhere('ticket_code', $token)
                ->first();
        }

        if (!$ticket) {
            return response()->json([
                'status'  => 'invalid',
                'title'   => 'Tiket Tidak Ditemukan',
                'message' => 'Kode tiket / QR Code ini tidak terdaftar di sistem buyle.id.'
            ]);
        }

        // Cek jika tiket dibatalkan
        if ($ticket->status === 'cancelled') {
            return response()->json([
                'status'  => 'invalid',
                'title'   => 'Tiket Dibatalkan',
                'message' => 'Tiket ini telah dibatalkan atau direfund.',
                'ticket'  => [
                    'code'        => $ticket->ticket_code,
                    'event_name'  => $ticket->product?->name,
                    'holder_name' => $ticket->holder_name,
                ]
            ]);
        }

        // Cek jika tiket sudah pernah dipakai
        if ($ticket->status === 'used') {
            $time = $ticket->checked_in_at ? $ticket->checked_in_at->format('H:i (d M Y)') : '-';
            return response()->json([
                'status'  => 'used',
                'title'   => 'Tiket Sudah Digunakan',
                'message' => "Tiket a.n {$ticket->holder_name} sudah dipindai sebelumnya pada pukul {$time}.",
                'ticket'  => [
                    'code'          => $ticket->ticket_code,
                    'event_name'    => $ticket->product?->name,
                    'holder_name'   => $ticket->holder_name,
                    'checked_in_at' => $time,
                ]
            ]);
        }

        // Tiket Valid! Update status menjadi used
        $ticket->update([
            'status'        => 'used',
            'checked_in_at' => now(),
            'checked_in_by' => auth()->id(),
        ]);

        return response()->json([
            'status'  => 'valid',
            'title'   => 'Tiket Valid!',
            'message' => "Selamat datang, {$ticket->holder_name}! Tiket berhasil diverifikasi.",
            'ticket'  => [
                'code'          => $ticket->ticket_code,
                'event_name'    => $ticket->product?->name,
                'event_date'    => $ticket->product?->event_date?->format('d M Y') ?? '-',
                'event_time'    => $ticket->product?->event_time ?? '-',
                'event_location'=> $ticket->product?->event_location ?? '-',
                'holder_name'   => $ticket->holder_name,
                'holder_email'  => $ticket->holder_email,
                'holder_phone'  => $ticket->holder_phone,
                'checked_in_at' => now()->format('H:i:s'),
            ]
        ]);
    }

    /**
     * Toggle Check-In status dari Tabel Data Kehadiran.
     */
    public function toggleCheckin(Request $request, TicketPass $ticket)
    {
        $sellerId = auth()->id();
        
        if ($ticket->seller_id != $sellerId && $ticket->product?->user_id != $sellerId) {
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
            // Add BOM for UTF-8 Excel compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // CSV Header
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
