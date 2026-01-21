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
        Schema::table('auteurs', function (Blueprint $table) {
            // Add missing base columns first if they don't exist
            if (!Schema::hasColumn('auteurs', 'telephone')) {
                $table->string('telephone', 50)->nullable()->after('email');
            }
            
            if (!Schema::hasColumn('auteurs', 'titre_professionnel')) {
                $table->string('titre_professionnel', 255)->nullable()->after('institution');
            }
            
            if (!Schema::hasColumn('auteurs', 'linkedin')) {
                $table->string('linkedin', 500)->nullable()->after('photo');
            }
            
            if (!Schema::hasColumn('auteurs', 'twitter')) {
                $table->string('twitter', 500)->nullable()->after('linkedin');
            }
            
            if (!Schema::hasColumn('auteurs', 'website')) {
                $table->string('website', 500)->nullable()->after('twitter');
            }
            
            if (!Schema::hasColumn('auteurs', 'active')) {
                $table->boolean('active')->default(true)->after('website');
            }
            
            // Now add ORCID - Open Researcher and Contributor ID
            if (!Schema::hasColumn('auteurs', 'orcid')) {
                $table->string('orcid', 19)->nullable()->after('email');
                $table->index('orcid');
            }
            
            // Add new social media links
            if (!Schema::hasColumn('auteurs', 'facebook')) {
                $table->string('facebook', 500)->nullable()->after('twitter');
            }
            
            if (!Schema::hasColumn('auteurs', 'instagram')) {
                $table->string('instagram', 500)->nullable()->after('facebook');
            }
            
            if (!Schema::hasColumn('auteurs', 'github')) {
                $table->string('github', 500)->nullable()->after('instagram');
            }
            
            if (!Schema::hasColumn('auteurs', 'researchgate')) {
                $table->string('researchgate', 500)->nullable()->after('github');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('auteurs', function (Blueprint $table) {
            $table->dropIndex(['orcid']);
            $table->dropColumn([
                'orcid',
                'facebook',
                'instagram',
                'github',
                'researchgate'
            ]);
        });
    }
};
