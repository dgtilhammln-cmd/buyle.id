<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tambah kolom raja_city_id dan raja_district_id ke creator_profiles.
 *
 * Kolom ini menyimpan ID kota/kecamatan dalam format RajaOngkir/Komerce
 * (berbeda dengan EMSIFA ID). Digunakan sebagai origin_id pada kalkulasi
 * ongkir agar konsisten dengan CheckoutApiController.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('creator_profiles', function (Blueprint $table) {
            if (!Schema::hasColumn('creator_profiles', 'raja_city_id')) {
                $table->string('raja_city_id', 20)->nullable()->after('city_id')
                      ->comment('RajaOngkir/Komerce city_id — dipakai sebagai origin ongkir');
            }
            if (!Schema::hasColumn('creator_profiles', 'raja_district_id')) {
                $table->string('raja_district_id', 20)->nullable()->after('subdistrict_id')
                      ->comment('RajaOngkir/Komerce district_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('creator_profiles', function (Blueprint $table) {
            $table->dropColumn(['raja_city_id', 'raja_district_id']);
        });
    }
};
