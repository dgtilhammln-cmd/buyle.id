<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->string('report_type')->default('link_in_bio'); // link_in_bio, product, store
            $table->string('target_url');
            $table->string('target_name')->nullable();
            $table->string('reason'); // penipuan, hak_cipta, konten_ilegal, spam, lainnya
            $table->text('description')->nullable();
            $table->string('reporter_email')->nullable();
            $table->string('reporter_ip')->nullable();
            $table->string('status')->default('pending'); // pending, reviewed, resolved, dismissed
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
