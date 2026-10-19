<?php

namespace App\Console\Commands;

use App\Models\InstagramAccount;
use App\Services\InstagramApiService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class RefreshInstagramTokensCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'instagram:refresh-tokens';

    /**
     * The console command description.
     */
    protected $description = 'Perbarui Long-Lived Access Token Instagram yang masa berlakunya kurang dari 15 hari';

    /**
     * Execute the console command.
     */
    public function handle(InstagramApiService $apiService): int
    {
        $this->info('Memeriksa token Instagram yang perlu diperbarui...');

        // Cari akun yang tokennya akan kedaluwarsa dalam 15 hari ke depan
        $threshold = now()->addDays(15);
        $accounts = InstagramAccount::where('is_connected', true)
            ->whereNotNull('access_token')
            ->where('token_expires_at', '<=', $threshold)
            ->get();

        if ($accounts->isEmpty()) {
            $this->info('Semua token Instagram masih aktif dan belum memerlukan pembaruan.');
            return Command::SUCCESS;
        }

        $refreshedCount = 0;
        foreach ($accounts as $account) {
            try {
                $this->line("Memperbarui token untuk @{$account->username}...");
                $tokenData = $apiService->exchangeForLongLivedToken($account->access_token);

                $newExpiresIn = $tokenData['expires_in'] ?? (60 * 86400); // 60 hari
                $account->update([
                    'access_token'     => $tokenData['access_token'],
                    'token_expires_at' => now()->addSeconds($newExpiresIn),
                ]);

                $this->info("✓ Token untuk @{$account->username} berhasil diperpanjang 60 hari.");
                $refreshedCount++;
            } catch (\Exception $e) {
                $this->error("✗ Gagal memperbarui token @{$account->username}: " . $e->getMessage());
                Log::error('Instagram token refresh error', [
                    'account_id' => $account->id,
                    'error'      => $e->getMessage(),
                ]);
            }
        }

        $this->info("Proses selesai. {$refreshedCount} token berhasil diperbarui.");
        return Command::SUCCESS;
    }
}
