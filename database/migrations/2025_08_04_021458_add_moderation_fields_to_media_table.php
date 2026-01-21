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
        Schema::table('media', function (Blueprint $table) {
            // Vérifier si les colonnes n'existent pas déjà
            if (!Schema::hasColumn('media', 'moderated_by')) {
                $table->unsignedBigInteger('moderated_by')->nullable()->after('status');
            }
            if (!Schema::hasColumn('media', 'moderated_at')) {
                $table->timestamp('moderated_at')->nullable()->after('moderated_by');
            }
            if (!Schema::hasColumn('media', 'created_by')) {
                $table->unsignedBigInteger('created_by')->nullable()->after('moderated_at');
            }
            if (!Schema::hasColumn('media', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable()->after('created_by');
            }
            if (!Schema::hasColumn('media', 'tags')) {
                $table->json('tags')->nullable()->after('rejection_reason');
            }
            
            // Ces colonnes existent déjà, on les ignore
            // status, is_public, file_size, mime_type, alt_text
            
            // Index et contraintes de clés étrangères (si les colonnes existent)
            try {
                if (Schema::hasColumn('media', 'moderated_by')) {
                    $table->foreign('moderated_by')->references('id')->on('users')->onDelete('set null');
                }
                if (Schema::hasColumn('media', 'created_by')) {
                    $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
                }
            } catch (Exception $e) {
                // Les contraintes existent peut-être déjà
            }
            
            // Les index existent déjà dans la migration précédente, on les ignore
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('media', function (Blueprint $table) {
            // Supprimer les contraintes de clés étrangères
            $table->dropForeign(['moderated_by']);
            $table->dropForeign(['created_by']);
            
            // Supprimer les index
            $table->dropIndex(['status', 'is_public']);
            $table->dropIndex(['created_by']);
            $table->dropIndex(['type', 'status']);
            
            // Supprimer les colonnes
            $table->dropColumn([
                'status',
                'is_public',
                'moderated_by',
                'moderated_at',
                'created_by',
                'rejection_reason',
                'tags',
                'file_size',
                'mime_type',
                'alt_text'
            ]);
        });
    }
};
