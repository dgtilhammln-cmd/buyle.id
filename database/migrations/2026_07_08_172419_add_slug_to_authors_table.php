<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('authors', function (Blueprint $table) {
            $table->string('slug', 120)->nullable()->unique()->after('name');
        });

        // Populate existing authors
        $authors = DB::table('authors')->get();
        foreach ($authors as $a) {
            $slug = Str::slug($a->name);
            $base = $slug; $i = 1;
            while (DB::table('authors')->where('slug', $slug)->exists()) {
                $slug = $base . '-' . $i++;
            }
            DB::table('authors')->where('id', $a->id)->update(['slug' => $slug]);
        }
    }

    public function down(): void
    {
        Schema::table('authors', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
