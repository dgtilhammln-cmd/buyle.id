<?php

namespace App\Http\Controllers;

use App\Models\TicketPass;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TicketScannerController extends Controller
{
    /**
     * Tampilkan halaman scanner QR Code tiket.
     */
    public function index()
    {
        $seo = [
            'title'   => 'Ticket QR Scanner - buyle.id',
            'robots'  => 'noindex, nofollow',
        ];
        return view('creator.ticket_scanner', compact('seo'));
    }

    /**
     * API untuk validasi QR Code / Kode Tiket.
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
            ->where('qr_token', $token)
            ->orWhere('ticket_code', $token)
            ->first();

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
}
