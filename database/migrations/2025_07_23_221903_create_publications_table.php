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
        if (!Schema::hasTable('publications')) {
            Schema::create('publications', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->string('slug')->unique();
            $table->text('resume')->nullable();
            $table->string('fichier_pdf')->nullable();
            $table->text('citation')->nullable();
            $table->boolean('en_vedette')->default(false);
            $table->boolean('a_la_une')->default(false);
            $table->boolean('is_published')->default(false);
            $table->integer('downloads_count')->default(0);
            $table->integer('views_count')->default(0);
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->foreignId('categorie_id')->nullable()->constrained('categories')->onDelete('set null');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamp('published_at')->nullable();
            $table->foreignId('published_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('moderation_comment')->nullable();
            $table->timestamps();
            
            $table->index(['is_published', 'published_at']);
            $table->index('slug');
            $table->index('categorie_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('publications');
    }
};
