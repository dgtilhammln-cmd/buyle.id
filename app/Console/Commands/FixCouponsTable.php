<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class FixCouponsTable extends Command
{
    protected $signature = 'fix:coupons';
    protected $description = 'Add missing columns to coupons table and run seeder (bypassing migration errors)';

    public function handle()
    {
        $this->info('Checking coupons table...');

        if (!Schema::hasColumn('coupons', 'category')) {
            Schema::table('coupons', function (Blueprint $table) {
                $table->string('category', 50)->default('product');
                $table->string('description', 255)->nullable();
                $table->string('badge', 50)->nullable();
            });
            $this->info('Columns category, description, badge added to coupons table.');
        } else {
            $this->info('Columns already exist.');
        }

        // Jalankan seeder
        $this->info('Running CouponSeeder...');
        Artisan::call('db:seed', [
            '--class' => 'CouponSeeder',
            '--force' => true
        ]);
        $this->info(Artisan::output());

        // Tandai migration add_details_to_coupons_table sebagai sudah dijalankan (biar nggak error pas migrate selanjutnya)
        DB::table('migrations')->updateOrInsert([
            'migration' => '2026_08_12_000001_add_details_to_coupons_table'
        ], [
            'batch' => DB::table('migrations')->max('batch') ?? 1
        ]);

        $this->info('Fix completed successfully!');
    }
}
