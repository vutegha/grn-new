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
        Schema::create('actualite_rapport', function (Blueprint $table) {
            $table->id();
            $table->foreignId('actualite_id')->constrained('actualites')->onDelete('cascade');
            $table->foreignId('rapport_id')->constrained('rapports')->onDelete('cascade');
            $table->timestamps();

            // Index pour optimiser les requêtes
            $table->index(['actualite_id', 'rapport_id']);
            $table->index(['rapport_id', 'actualite_id']);
            
            // Contrainte d'unicité pour éviter les doublons
            $table->unique(['actualite_id', 'rapport_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('actualite_rapport');
    }
};
