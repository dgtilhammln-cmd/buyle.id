<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DomainOrder;
use Illuminate\Http\Request;

class AdminDomainOrderController extends Controller
{
    public function index(Request $request)
    {
        return redirect()->route('admin.orders.index', [
            'tab'     => 'omset',
            'tx_type' => 'domain',
            'q'       => $request->get('q'),
            'period'  => $request->get('period', '30d'),
        ]);
    }

    public function updateStatus(Request $request, DomainOrder $domainOrder)
    {
        $request->validate([
            'status' => 'required|in:pending,paid,completed,rejected,cancelled'
        ]);

        $oldStatus = $domainOrder->status;
        $domainOrder->status = $request->status;
        $domainOrder->save();

        $user = $domainOrder->user;

        // 1. Jika status Berhasil / Selesai (Completed)
        if ($request->status === 'completed' && $oldStatus !== 'completed' && $user) {
            if ($user->creatorProfile) {
                $user->creatorProfile->custom_domain = $domainOrder->domain_name;
                $user->creatorProfile->custom_domain_status = 'verified';
                $user->creatorProfile->save();
            }

            // Kirim notifikasi email ke creator
            if (!empty($user->email)) {
                try {
                    $domainUrl = "https://{$domainOrder->domain_name}";
                    \Illuminate\Support\Facades\Mail::html("
                        <div style='font-family:sans-serif; max-width:600px; margin:0 auto; padding:24px; border:1.5px solid #BBF7D0; border-radius:16px; background:#ffffff;'>
                            <div style='text-align:center; margin-bottom:20px;'>
                                <h2 style='color:#166534; margin:0; font-size:20px;'>Selamat! Custom Domain Anda Telah Aktif</h2>
                                <p style='color:#64748B; font-size:13px; margin-top:6px;'>Domain Anda telah selesai dipasang dan siap digunakan.</p>
                            </div>

                            <p style='font-size:14px; color:#0F172A; line-height:1.6;'>Halo <strong>{$user->name}</strong>,</p>
                            <p style='font-size:14px; color:#334155; line-height:1.6;'>
                                Kami ingin mengabarkan bahwa pesanan custom domain <strong>{$domainOrder->domain_name}</strong> Anda telah <strong>SUDAH BERHASIL DAN AKTIF</strong>.
                            </p>

                            <div style='background:#F0FDF4; border:1.5px solid #BBF7D0; padding:16px; border-radius:12px; margin:20px 0; text-align:center;'>
                                <div style='font-size:11px; font-weight:bold; color:#166534; letter-spacing:0.05em; text-transform:uppercase;'>Link Bio Domain Anda</div>
                                <div style='font-size:18px; font-weight:800; color:#166534; margin:8px 0;'>{$domainUrl}</div>
                                <a href='{$domainUrl}' target='_blank' style='display:inline-block; background:#166534; color:#ffffff; font-weight:bold; font-size:13px; text-decoration:none; padding:8px 18px; border-radius:8px; margin-top:6px;'>
                                    Buka Halaman Domain &rarr;
                                </a>
                            </div>

                            <p style='font-size:13px; color:#475569; line-height:1.6;'>
                                Calon pembeli kini dapat mengakses seluruh katalog produk dan bio Anda secara langsung melalui domain kustom Anda.
                            </p>

                            <hr style='border:none; border-top:1px solid #E2E8F0; margin:20px 0;'>
                            <p style='font-size:12px; color:#94A3B8; text-align:center;'>Salam hangat,<br><strong>Tim Buyle.id</strong></p>
                        </div>
                    ", function ($message) use ($user, $domainOrder) {
                        $message->to($user->email, $user->name)
                                ->subject("Selamat! Custom Domain {$domainOrder->domain_name} Anda Sudah Berhasil & Aktif - Buyle.id");
                    });
                } catch (\Throwable $e) {
                    \Illuminate\Support\Facades\Log::error('[DomainStatusCompletedEmail] ' . $e->getMessage());
                }
            }
        }

        // 2. Jika status Ditolak (Rejected)
        if ($request->status === 'rejected' && $oldStatus !== 'rejected' && $user) {
            if (!empty($user->email)) {
                try {
                    \Illuminate\Support\Facades\Mail::html("
                        <div style='font-family:sans-serif; max-width:600px; margin:0 auto; padding:24px; border:1.5px solid #FECACA; border-radius:16px; background:#ffffff;'>
                            <div style='text-align:center; margin-bottom:20px;'>
                                <h2 style='color:#DC2626; margin:0; font-size:20px;'>Pesanan Custom Domain Ditolak</h2>
                                <p style='color:#64748B; font-size:13px; margin-top:6px;'>Pemberitahuan penting terkait pesanan domain Anda.</p>
                            </div>

                            <p style='font-size:14px; color:#0F172A; line-height:1.6;'>Halo <strong>{$user->name}</strong>,</p>
                            <p style='font-size:14px; color:#334155; line-height:1.6;'>
                                Mohon maaf, pesanan pendaftaran custom domain <strong>{$domainOrder->domain_name}</strong> saat ini <strong>DITOLAK</strong> oleh admin / tidak dapat dilanjutkan pendaftarannya ke registrar.
                            </p>

                            <div style='background:#FEF2F2; border:1px solid #FECACA; padding:14px; border-radius:10px; margin:18px 0; font-size:13px; color:#991B1B; line-height:1.5;'>
                                Hal ini dapat terjadi karena nama domain telah diambil pihak lain di registrar global, kendala teknis dari registrar, atau kebijakan pendaftaran ekstensi. Silakan hubungi admin atau periksa nama domain alternatif melalui dashboard creator Anda.
                            </div>

                            <hr style='border:none; border-top:1px solid #E2E8F0; margin:20px 0;'>
                            <p style='font-size:12px; color:#94A3B8; text-align:center;'>Salam hangat,<br><strong>Tim Buyle.id</strong></p>
                        </div>
                    ", function ($message) use ($user, $domainOrder) {
                        $message->to($user->email, $user->name)
                                ->subject("Pemberitahuan: Pesanan Custom Domain {$domainOrder->domain_name} Ditolak - Buyle.id");
                    });
                } catch (\Throwable $e) {
                    \Illuminate\Support\Facades\Log::error('[DomainStatusRejectedEmail] ' . $e->getMessage());
                }
            }
        }

        $statusLabels = [
            'pending'   => 'PENDING',
            'paid'      => 'LUNAS (PAID)',
            'completed' => 'SUDAH BERHASIL',
            'rejected'  => 'DITOLAK',
            'cancelled' => 'DIBATALKAN',
        ];
        $label = $statusLabels[$request->status] ?? strtoupper($request->status);

        return back()->with('success', "Status domain '{$domainOrder->domain_name}' berhasil diubah ke {$label} dan notifikasi telah dikirim ke creator!");
    }
}
