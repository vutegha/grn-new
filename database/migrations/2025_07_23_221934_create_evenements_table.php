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
        Schema::create('evenements', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->datetime('date_evenement'); // Utilisé dans le modèle au lieu de 'date_debut'
            $table->string('lieu')->nullable();
            $table->string('adresse')->nullable();
            $table->string('organisateur')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_telephone')->nullable();
            $table->integer('places_disponibles')->nullable();
            $table->text('programme')->nullable();
            $table->string('rapport_url')->nullable();
            $table->string('statut')->default('brouillon'); // brouillon, publie, archive
            $table->string('type')->default('conference'); // conference, seminaire, atelier, etc.
            $table->boolean('inscription_requise')->default(false);
            $table->datetime('date_limite_inscription')->nullable();
            $table->json('meta_data')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
            
            $table->index(['statut', 'date_evenement']);
            $table->index('slug');
            $table->index('date_evenement');
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evenements');
    }
};
