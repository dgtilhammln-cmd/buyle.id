<?php

namespace App\Http\Controllers\Creator;

use App\Http\Controllers\Controller;
use App\Models\CreatorNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CreatorNotificationController extends Controller
{
    public function index(Request $request)
    {
        $userId = auth()->id();

        // Jika notifikasi masih kosong sama sekali untuk user ini,
        // inisialisasi notifikasi pertama agar user dapat mencoba fiturnya
        $totalNotifs = CreatorNotification::where('user_id', $userId)->count();
        if ($totalNotifs === 0) {
            $this->seedInitialNotifications($userId);
            Cache::forget('creator_unread_notif_' . $userId);
        }

        $notifications = CreatorNotification::where('user_id', $userId)
            ->orderByDesc('id')
            ->paginate(15);

        // Ambil hitungan belum dibaca dengan aman
        $unreadCount = CreatorNotification::where('user_id', $userId)
            ->where('is_read', false)
            ->count();

        // Update cache server unread notif agar sinkron
        Cache::put('creator_unread_notif_' . $userId, $unreadCount, 180);

        return response()
            ->view('creator.notif.index', compact('notifications', 'unreadCount'))
            ->header('Cache-Control', 'no-cache, private'); // Aman: hanya update ketika di-refresh
    }

    public function markAllRead(Request $request)
    {
        $userId = auth()->id();
        CreatorNotification::where('user_id', $userId)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        // Hapus cache unread notif
        Cache::forget('creator_unread_notif_' . $userId);
        Cache::put('creator_unread_notif_' . $userId, 0, 180);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Semua notifikasi telah ditandai telah dibaca.',
            ]);
        }

        return redirect()->back()->with('success', 'Semua notifikasi berhasil ditandai telah dibaca.');
    }

    public function deleteAll(Request $request)
    {
        $userId = auth()->id();
        CreatorNotification::where('user_id', $userId)->delete();

        // Reset cache unread
        Cache::forget('creator_unread_notif_' . $userId);
        Cache::put('creator_unread_notif_' . $userId, 0, 180);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Semua riwayat notifikasi berhasil dibersihkan.',
            ]);
        }

        return redirect()->back()->with('success', 'Semua riwayat notifikasi telah dihapus.');
    }

    public function readAndRedirect($id)
    {
        $userId = auth()->id();
        $notif = CreatorNotification::where('user_id', $userId)->findOrFail($id);

        if (!$notif->is_read) {
            $notif->update(['is_read' => true]);
            Cache::forget('creator_unread_notif_' . $userId);
        }

        if (!empty($notif->action_url)) {
            return redirect($notif->action_url);
        }

        return redirect()->route('creator.notif.index');
    }

    private function seedInitialNotifications($userId)
    {
        // 1. Notifikasi Welcome
        CreatorNotification::create([
            'user_id' => $userId,
            'type' => 'system',
            'title' => 'Selamat Datang di Buyle Creator Hub!',
            'message' => 'Kelola produk digital & fisik, penjualan tiket event, dan pantau performa toko Anda dalam satu tempat.',
            'action_url' => route('creator.dashboard'),
            'is_read' => false,
            'created_at' => now(),
        ]);

        // 2. Notifikasi KYC Status
        $kyc = \App\Models\CreatorKyc::where('user_id', $userId)->first();
        if ($kyc && $kyc->status === 'verified') {
            CreatorNotification::create([
                'user_id' => $userId,
                'type' => 'kyc',
                'title' => 'Verifikasi Identitas e-KYC Berhasil',
                'message' => 'Akun Anda telah terverifikasi resmi. Anda dapat mencairkan saldo dan mengakses scanner tiket BuyleTIX.',
                'action_url' => route('creator.ticket.scanner'),
                'is_read' => true,
                'created_at' => now()->subHours(2),
            ]);
        } else {
            CreatorNotification::create([
                'user_id' => $userId,
                'type' => 'kyc',
                'title' => 'Lengkapi Verifikasi e-KYC',
                'message' => 'Verifikasi identitas KTP Anda untuk mengaktifkan pencairan saldo dan fitur scanner tiket konser/event.',
                'action_url' => route('creator.kyc.index'),
                'is_read' => false,
                'created_at' => now()->subHours(1),
            ]);
        }
    }
}
