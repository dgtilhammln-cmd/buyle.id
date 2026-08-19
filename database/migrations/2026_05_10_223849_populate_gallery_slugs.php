<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        // Populate slugs for gallery projects that don't have one
        $items = DB::table('gallery_projects')->whereNull('slug')->orWhere('slug', '')->get();
        foreach ($items as $item) {
            $base = Str::slug($item->title);
            $slug = $base;
            $i    = 1;
            while (DB::table('gallery_projects')->where('slug', $slug)->where('id', '!=', $item->id)->exists()) {
                $slug = $base . '-' . $i++;
            }
            DB::table('gallery_projects')->where('id', $item->id)->update(['slug' => $slug]);
        }
    }

    public function down(): void {}
};
