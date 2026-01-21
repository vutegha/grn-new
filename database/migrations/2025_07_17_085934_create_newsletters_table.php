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
        // Check if newsletters table exists and add missing columns
        if (Schema::hasTable('newsletters')) {
            Schema::table('newsletters', function (Blueprint $table) {
                if (!Schema::hasColumn('newsletters', 'nom')) {
                    $table->string('nom')->nullable()->after('email');
                }
                if (!Schema::hasColumn('newsletters', 'token')) {
                    $table->string('token')->unique()->after('nom');
                }
                if (!Schema::hasColumn('newsletters', 'actif')) {
                    $table->boolean('actif')->default(true)->after('token');
                }
                if (!Schema::hasColumn('newsletters', 'confirme_a')) {
                    $table->timestamp('confirme_a')->nullable()->after('actif');
                }
                
                // Add index (Laravel 11 compatible way)
                try {
                    $table->index(['email', 'actif'], 'newsletters_email_actif_index');
                } catch (\Exception $e) {
                    // Index probably already exists, ignore
                }
            });
        } else {
            // If table doesn't exist, create it
            Schema::create('newsletters', function (Blueprint $table) {
                $table->id();
                $table->string('email', 191)->unique(); // Limiter à 191 chars pour MySQL 5.7
                $table->string('nom')->nullable();
                $table->string('token', 191)->unique(); // Token pour gérer les préférences
                $table->boolean('actif')->default(true);
                $table->timestamp('confirme_a')->nullable(); // Date de confirmation
                $table->timestamps();
                
                $table->index(['email', 'actif']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('newsletters');
    }
};
