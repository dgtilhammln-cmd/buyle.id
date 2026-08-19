<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('article_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id')->constrained('articles')->onDelete('cascade');
            $table->string('locale')->index();
            $table->string('title')->nullable();
            $table->text('excerpt')->nullable();
            $table->longText('content')->nullable();
            $table->json('faqs')->nullable();
            $table->json('cta_button')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_desc')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->boolean('is_complete')->default(false);
            $table->string('translated_by')->nullable();
            $table->timestamps();

            $table->unique(['article_id', 'locale']);
        });

        // Migrate existing data
        $articles = DB::table('articles')->get();
        foreach ($articles as $article) {
            DB::table('article_translations')->insert([
                'article_id'    => $article->id,
                'locale'        => 'id',
                'title'         => $article->title,
                'excerpt'       => $article->excerpt,
                'content'       => $article->content,
                'faqs'          => $article->faqs,
                'cta_button'    => $article->cta_button,
                'meta_title'    => $article->meta_title,
                'meta_desc'     => $article->meta_desc,
                'meta_keywords' => $article->meta_keywords,
                'is_complete'   => true,
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);
        }

        // Drop columns from articles table
        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn([
                'title', 'excerpt', 'content', 'faqs', 'cta_button',
                'meta_title', 'meta_desc', 'meta_keywords'
            ]);
        });
    }

    public function down(): void
    {
        // Re-add columns to articles table
        Schema::table('articles', function (Blueprint $table) {
            $table->string('title')->nullable();
            $table->text('excerpt')->nullable();
            $table->longText('content')->nullable();
            $table->json('faqs')->nullable();
            $table->json('cta_button')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_desc')->nullable();
            $table->string('meta_keywords')->nullable();
        });

        // Restore data from translations for 'id'
        $translations = DB::table('article_translations')->where('locale', 'id')->get();
        foreach ($translations as $trans) {
            DB::table('articles')->where('id', $trans->article_id)->update([
                'title'         => $trans->title,
                'excerpt'       => $trans->excerpt,
                'content'       => $trans->content,
                'faqs'          => $trans->faqs,
                'cta_button'    => $trans->cta_button,
                'meta_title'    => $trans->meta_title,
                'meta_desc'     => $trans->meta_desc,
                'meta_keywords' => $trans->meta_keywords,
            ]);
        }

        Schema::dropIfExists('article_translations');
    }
};
