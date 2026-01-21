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
        Schema::create('projets', function (Blueprint $table) {
            $table->id();
            $table->string('nom'); // Utilisé dans le modèle au lieu de 'titre'
            $table->string('slug')->unique();
            $table->longText('description');
            $table->string('image')->nullable();
            $table->date('date_debut');
            $table->date('date_fin')->nullable();
            $table->string('etat')->default('en_cours'); // en_cours, termine, suspendu, planifie
            $table->integer('beneficiaires_hommes')->nullable();
            $table->integer('beneficiaires_femmes')->nullable();
            $table->integer('beneficiaires_total')->nullable();
            $table->decimal('budget', 12, 2)->nullable();
            $table->boolean('is_published')->default(false);
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->foreignId('service_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamp('published_at')->nullable();
            $table->foreignId('published_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('moderation_comment')->nullable();
            $table->timestamps();
            
            $table->index(['is_published', 'date_debut']);
            $table->index('slug');
            $table->index('etat');
            $table->index('date_debut');
            $table->index('service_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projets');
    }
};
