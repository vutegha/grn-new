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
        Schema::table('evenements', function (Blueprint $table) {
            // Supprimer l'index avant de supprimer la colonne statut
            $table->dropIndex(['statut', 'date_evenement']);
            
            // Supprimer les champs SEO
            $table->dropColumn('meta_title');
            $table->dropColumn('meta_description');
            
            // Supprimer les champs d'inscription
            $table->dropColumn('inscription_requise');
            $table->dropColumn('places_disponibles');
            $table->dropColumn('date_limite_inscription');
            
            // Supprimer le champ adresse
            $table->dropColumn('adresse');
            
            // Supprimer le champ statut
            $table->dropColumn('statut');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evenements', function (Blueprint $table) {
            // Restaurer les champs supprimés
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->boolean('inscription_requise')->default(false);
            $table->integer('places_disponibles')->nullable();
            $table->datetime('date_limite_inscription')->nullable();
            $table->string('adresse')->nullable();
            $table->string('statut')->default('brouillon');
            
            // Recréer l'index
            $table->index(['statut', 'date_evenement']);
        });
    }
};
