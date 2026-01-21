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
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->string('medias')->nullable()->comment('Chemin de stockage du fichier');
            $table->string('titre')->nullable()->comment('Titre du média');
            $table->text('description')->nullable()->comment('Description détaillée du média');
            $table->string('type', 100)->nullable()->comment('Type de média (image, video, document, etc.)');
            $table->string('mime_type')->nullable()->comment('Type MIME du fichier');
            $table->string('file_name')->nullable()->comment('Nom original du fichier');
            $table->integer('file_size')->nullable()->comment('Taille du fichier en octets');
            $table->string('extension')->nullable()->comment('Extension du fichier');
            $table->integer('width')->nullable()->comment('Largeur pour les images');
            $table->integer('height')->nullable()->comment('Hauteur pour les images');
            $table->integer('duration')->nullable()->comment('Durée pour les vidéos (en secondes)');
            $table->json('metadata')->nullable()->comment('Métadonnées additionnelles');
            $table->string('alt_text')->nullable()->comment('Texte alternatif pour accessibilité');
            $table->boolean('is_featured')->default(false)->comment('Média principal/à la une');
            $table->boolean('is_public')->default(true)->comment('Média accessible publiquement');
            $table->string('status', 50)->default('active')->comment('Statut du média (active, deleted, processing)');
            
            // Relations
            $table->foreignId('projet_id')->nullable()->constrained('projets')->onDelete('set null')->comment('Lien vers le projet associé');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null')->comment('Utilisateur qui a uploadé le média');
            
            // Polymorphic relations pour permettre l'association à différents modèles
            $table->string('mediable_type')->nullable()->comment('Type du modèle associé');
            $table->unsignedBigInteger('mediable_id')->nullable()->comment('ID du modèle associé');
            
            // Index pour les relations polymorphiques
            $table->index(['mediable_type', 'mediable_id']);
            $table->index(['type', 'status']);
            $table->index(['is_featured', 'is_public']);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
