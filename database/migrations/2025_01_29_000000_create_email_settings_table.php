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
        Schema::create('email_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key', 191)->unique(); // clé unique pour identifier le type de configuration
            $table->string('label'); // libellé pour l'interface admin
            $table->json('emails'); // tableau des adresses email
            $table->text('description')->nullable(); // description pour l'admin
            $table->boolean('active')->default(true); // activation/désactivation
            $table->boolean('required')->default(false); // obligatoire ou non
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_settings');
    }
};
