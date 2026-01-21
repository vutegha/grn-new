<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Mise à jour des données existantes pour harmoniser les enums
        DB::table('job_offers')->where('source', 'interne')->update(['source' => 'internal']);
        DB::table('job_offers')->where('source', 'partenaire')->update(['source' => 'partner']);
        DB::table('job_offers')->where('source', 'externe')->update(['source' => 'external']);
        
        // Mise à jour des types d'emploi
        DB::table('job_offers')->where('type', 'temps_plein')->update(['type' => 'full-time']);
        DB::table('job_offers')->where('type', 'temps_partiel')->update(['type' => 'part-time']);
        DB::table('job_offers')->where('type', 'contrat')->update(['type' => 'contract']);
        DB::table('job_offers')->where('type', 'stage')->update(['type' => 'internship']);
        DB::table('job_offers')->where('type', 'freelance')->update(['type' => 'freelance']);

        // Mise à jour de la structure des colonnes avec les nouvelles valeurs
        Schema::table('job_offers', function (Blueprint $table) {
            $table->enum('source', ['internal', 'partner', 'external'])->default('internal')->change();
            $table->enum('type', ['full-time', 'part-time', 'contract', 'internship', 'freelance'])->default('full-time')->change();
            
            // Ajout d'index pour améliorer les performances
            $table->index(['status', 'source'], 'idx_status_source');
            $table->index(['type', 'location'], 'idx_type_location');
            $table->index(['application_deadline', 'status'], 'idx_deadline_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restaurer les anciennes valeurs
        DB::table('job_offers')->where('source', 'internal')->update(['source' => 'interne']);
        DB::table('job_offers')->where('source', 'partner')->update(['source' => 'partenaire']);
        DB::table('job_offers')->where('source', 'external')->update(['source' => 'externe']);
        
        DB::table('job_offers')->where('type', 'full-time')->update(['type' => 'temps_plein']);
        DB::table('job_offers')->where('type', 'part-time')->update(['type' => 'temps_partiel']);
        DB::table('job_offers')->where('type', 'contract')->update(['type' => 'contrat']);
        DB::table('job_offers')->where('type', 'internship')->update(['type' => 'stage']);

        Schema::table('job_offers', function (Blueprint $table) {
            $table->enum('source', ['interne', 'partenaire', 'externe'])->default('interne')->change();
            $table->enum('type', ['temps_plein', 'temps_partiel', 'contrat', 'stage', 'freelance'])->default('temps_plein')->change();
            
            // Supprimer les index ajoutés
            $table->dropIndex('idx_status_source');
            $table->dropIndex('idx_type_location');
            $table->dropIndex('idx_deadline_status');
        });
    }
};
