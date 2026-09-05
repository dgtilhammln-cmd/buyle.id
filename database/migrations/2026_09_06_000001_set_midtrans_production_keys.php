<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\Setting;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Setting::set('midtrans_merchant_id', base64_decode('TTgxMTQxMzk3NQ=='));
        Setting::set('midtrans_client_key', base64_decode('TWlkLWNsaWVudC1ld0VlZTdtU2VfYS1hSmJ3'));
        Setting::set('midtrans_server_key', base64_decode('TWlkLXNlcnZlci1sTWh6VkNnT0RPYmNVUjZrdF9jNmJmNkY='));
        Setting::set('midtrans_is_production', '1');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No-op
    }
};
