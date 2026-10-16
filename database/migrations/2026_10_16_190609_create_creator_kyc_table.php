<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('creator_kyc', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique();
            $table->enum('status', ['none', 'pending', 'verified', 'rejected'])->default('none');
            $table->string('nik', 16)->nullable();
            $table->string('full_name', 255)->nullable();
            $table->date('dob')->nullable();
            $table->enum('gender', ['L', 'P'])->nullable();
            $table->string('province', 100)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('ktp_photo_path', 500)->nullable();
            $table->string('selfie_photo_path', 500)->nullable();
            $table->json('nik_parsed_data')->nullable();
            $table->string('liveness_token', 64)->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->unsignedBigInteger('reviewed_by')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('creator_kyc');
    }
};
