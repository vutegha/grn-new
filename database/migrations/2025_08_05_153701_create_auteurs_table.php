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
        Schema::create('auteurs', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 100);
            $table->string('prenom', 100)->nullable();
            $table->string('email', 191)->nullable()->unique(); // Limité pour MySQL 5.7
            $table->string('institution', 191)->nullable(); // Limité pour MySQL 5.7
            $table->text('biographie')->nullable();
            $table->string('photo', 500)->nullable();
            $table->timestamps();
            
            // Index pour améliorer les performances des recherches
            $table->index(['nom', 'prenom']);
            $table->index('email');
            $table->index('institution');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auteurs');
    }
};
