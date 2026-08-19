<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Add fields to article_translations
        Schema::table('article_translations', function (Blueprint $table) {
            $table->text('featured_snippet')->nullable()->after('faqs');
            $table->string('thumbnail_alt')->nullable()->after('featured_snippet');
        });

        // 2. Create authors table
        Schema::create('authors', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('photo')->nullable();
            $table->json('social_links')->nullable();
            $table->timestamps();
        });

        // 3. Create author_translations table
        Schema::create('author_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('author_id')->constrained('authors')->onDelete('cascade');
            $table->string('locale', 10);
            $table->text('bio')->nullable();
            $table->timestamps();

            $table->unique(['author_id', 'locale']);
        });

        // 4. Update articles table (add author_id)
        Schema::table('articles', function (Blueprint $table) {
            $table->foreignId('author_id')->nullable()->after('author')->constrained('authors')->onDelete('set null');
        });

        // Migrate existing authors to the authors table
        $existingAuthors = DB::table('articles')
            ->select('author')
            ->whereNotNull('author')
            ->where('author', '!=', '')
            ->distinct()
            ->pluck('author');

        foreach ($existingAuthors as $authorName) {
            $authorId = DB::table('authors')->insertGetId([
                'name' => $authorName,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('articles')
                ->where('author', $authorName)
                ->update(['author_id' => $authorId]);
        }
    }

    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropForeign(['author_id']);
            $table->dropColumn('author_id');
        });

        Schema::dropIfExists('author_translations');
        Schema::dropIfExists('authors');

        Schema::table('article_translations', function (Blueprint $table) {
            $table->dropColumn(['featured_snippet', 'thumbnail_alt']);
        });
    }
};
