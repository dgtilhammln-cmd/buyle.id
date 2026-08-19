<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->json('faqs')->nullable()->after('og_image');       // [{q:'',a:''}]
            $table->json('cta_button')->nullable()->after('faqs');     // {text:'',url:'',type:'wa|url'}
            $table->string('meta_keywords')->nullable()->after('meta_desc');
            $table->boolean('show_toc')->default(false)->after('cta_button'); // Table of Contents
        });
    }

    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn(['faqs', 'cta_button', 'meta_keywords', 'show_toc']);
        });
    }
};
