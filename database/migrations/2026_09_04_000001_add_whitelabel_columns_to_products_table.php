<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('is_whitelabel')->default(false)->after('is_featured');
            $table->decimal('whitelabel_price', 12, 2)->nullable()->after('is_whitelabel');
            $table->text('whitelabel_terms')->nullable()->after('whitelabel_price');
            $table->string('whitelabel_approval_status', 20)->default('none')->after('whitelabel_terms');
            $table->text('whitelabel_rejection_reason')->nullable()->after('whitelabel_approval_status');

            $table->index(['is_whitelabel', 'whitelabel_approval_status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['is_whitelabel', 'whitelabel_approval_status']);
            $table->dropColumn([
                'is_whitelabel',
                'whitelabel_price',
                'whitelabel_terms',
                'whitelabel_approval_status',
                'whitelabel_rejection_reason',
            ]);
        });
    }
};
