<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('orders', 'claim_proof')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->text('claim_proof')->nullable()->change();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('orders', 'claim_proof')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->string('claim_proof', 500)->nullable()->change();
            });
        }
    }
};
