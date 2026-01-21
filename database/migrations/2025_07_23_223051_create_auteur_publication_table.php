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
        Schema::create('auteur_publication', function (Blueprint $table) {
            $table->id();
            $table->foreignId('auteur_id')->constrained()->onDelete('cascade');
            $table->foreignId('publication_id')->constrained()->onDelete('cascade');
            $table->integer('ordre')->default(0); // Pour l'ordre des auteurs
            $table->timestamps();
            
            $table->unique(['auteur_id', 'publication_id']);
            $table->index('publication_id');
            $table->index('auteur_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auteur_publication');
    }
};
