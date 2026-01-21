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
        Schema::create('chercheur_affilies', function (Blueprint $table) {
            $table->id();
            $table->string('nom')->comment('Nom du chercheur');
            $table->string('prenom')->comment('Prénom du chercheur');
            $table->string('email')->nullable()->comment('Email du chercheur');
            $table->string('telephone')->nullable()->comment('Téléphone du chercheur');
            $table->string('titre_academique')->nullable()->comment('Titre académique (Dr, Prof, etc.)');
            $table->string('institution_origine')->nullable()->comment('Institution d\'origine');
            $table->string('departement')->nullable()->comment('Département ou unité');
            $table->json('domaine_recherche')->nullable()->comment('Domaines de recherche');
            $table->json('specialites')->nullable()->comment('Spécialités');
            $table->string('photo')->nullable()->comment('Photo du chercheur');
            $table->text('biographie')->nullable()->comment('Biographie du chercheur');
            $table->string('orcid')->nullable()->comment('ID ORCID');
            $table->string('google_scholar')->nullable()->comment('Profil Google Scholar');
            $table->string('researchgate')->nullable()->comment('Profil ResearchGate');
            $table->string('linkedin')->nullable()->comment('Profil LinkedIn');
            $table->enum('statut', ['actif', 'inactif', 'suspendu'])->default('actif')->comment('Statut du chercheur');
            $table->date('date_affiliation')->nullable()->comment('Date d\'affiliation');
            $table->date('date_fin_affiliation')->nullable()->comment('Date de fin d\'affiliation');
            $table->json('publications_collaboratives')->nullable()->comment('Publications en collaboration');
            $table->json('projets_collaboration')->nullable()->comment('Projets de collaboration');
            $table->json('contributions')->nullable()->comment('Contributions diverses');
            $table->boolean('afficher_publiquement')->default(true)->comment('Afficher sur le site public');
            $table->integer('ordre_affichage')->default(0)->comment('Ordre d\'affichage');
            $table->timestamps();
            
            // Index pour les recherches
            $table->index(['statut', 'afficher_publiquement']);
            $table->index('ordre_affichage');
            $table->index('institution_origine');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chercheur_affilies');
    }
};
