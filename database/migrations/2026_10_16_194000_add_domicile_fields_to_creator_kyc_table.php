<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('creator_kyc', function (Blueprint $table) {
            $table->string('ktp_address', 500)->nullable()->after('city');
            $table->string('ktp_rt_rw', 50)->nullable()->after('ktp_address');
            $table->string('ktp_subdistrict', 100)->nullable()->after('ktp_rt_rw');
            $table->string('ktp_district', 100)->nullable()->after('ktp_subdistrict');
            
            $table->boolean('is_domicile_same')->default(true)->after('ktp_district');
            $table->string('domicile_province', 100)->nullable()->after('is_domicile_same');
            $table->string('domicile_city', 100)->nullable()->after('domicile_province');
            $table->string('domicile_district', 100)->nullable()->after('domicile_city');
            $table->string('domicile_subdistrict', 100)->nullable()->after('domicile_district');
            $table->string('domicile_rt_rw', 50)->nullable()->after('domicile_subdistrict');
            $table->string('domicile_postal_code', 20)->nullable()->after('domicile_rt_rw');
            $table->text('domicile_address')->nullable()->after('domicile_postal_code');
            $table->json('ai_extracted_data')->nullable()->after('nik_parsed_data');
        });
    }

    public function down(): void
    {
        Schema::table('creator_kyc', function (Blueprint $table) {
            $table->dropColumn([
                'ktp_address',
                'ktp_rt_rw',
                'ktp_subdistrict',
                'ktp_district',
                'is_domicile_same',
                'domicile_province',
                'domicile_city',
                'domicile_district',
                'domicile_subdistrict',
                'domicile_rt_rw',
                'domicile_postal_code',
                'domicile_address',
                'ai_extracted_data',
            ]);
        });
    }
};
