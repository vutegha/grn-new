<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

return new class extends Migration
{
    public function up()
    {
        // Créer le rôle gestionnaire des projets
        $role = Role::create(['name' => 'gestionnaire_projets']);

        // Créer les permissions pour les projets
        $projectPermissions = [
            'view_projets',
            'view_projet',
            'create_projet',
            'update_projet',
            'delete_projet',
            'moderate_projet',
            'publish_projet',
            'unpublish_projet',
        ];

        // Créer les permissions pour actualités
        $actualitePermissions = [
            'view_actualites',
            'view_actualite',
            'create_actualite',
            'update_actualite',
            'delete_actualite',
            'moderate_actualite',
            'publish_actualite',
            'unpublish_actualite',
        ];

        // Créer les permissions pour médias
        $mediaPermissions = [
            'view_medias',
            'view_media',
            'create_media',
            'update_media',
            'delete_media',
        ];

        // Créer les permissions pour rapports
        $reportPermissions = [
            'view_rapports',
            'view_rapport',
            'create_rapport',
            'update_rapport',
            'delete_rapport',
        ];

        // Créer les permissions pour le dashboard
        $dashboardPermissions = [
            'view_admin_dashboard',
        ];

        // Toutes les permissions pour ce rôle
        $allPermissions = array_merge(
            $projectPermissions,
            $actualitePermissions,
            $mediaPermissions,
            $reportPermissions,
            $dashboardPermissions
        );

        // Créer les permissions si elles n'existent pas
        foreach ($allPermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Assigner toutes les permissions au rôle
        $role->givePermissionTo($allPermissions);

        echo "Rôle 'gestionnaire_projets' créé avec les permissions appropriées.\n";
    }

    public function down()
    {
        // Supprimer le rôle et ses permissions
        $role = Role::where('name', 'gestionnaire_projets')->first();
        if ($role) {
            $role->delete();
        }

        // Supprimer les permissions spécifiques si nécessaire
        $permissions = [
            'view_projets', 'view_projet', 'create_projet', 'update_projet', 'delete_projet',
            'moderate_projet', 'publish_projet', 'unpublish_projet',
            'view_actualites', 'view_actualite', 'create_actualite', 'update_actualite', 'delete_actualite',
            'moderate_actualite', 'publish_actualite', 'unpublish_actualite',
            'view_medias', 'view_media', 'create_media', 'update_media', 'delete_media',
            'view_rapports', 'view_rapport', 'create_rapport', 'update_rapport', 'delete_rapport',
            'view_admin_dashboard',
        ];

        Permission::whereIn('name', $permissions)->delete();
    }
};
