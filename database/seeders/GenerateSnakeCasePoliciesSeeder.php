<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class GenerateSnakeCasePoliciesSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('🛠️ Génération des policies en snake_case...');
        
        // Template pour les policies avec format snake_case
        $policyTemplate = function($modelName, $permissions) {
            $className = ucfirst($modelName) . 'Policy';
            $modelClass = ucfirst($modelName);
            
            return "<?php

namespace App\Policies;

use App\Models\\{$modelClass};
use App\Models\User;
use Illuminate\Auth\Access\Response;

class {$className}
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User \$user): bool
    {
        // Super-admin a tous les droits
        if (\$user->hasRole('super-admin', 'web')) {
            return true;
        }
        
        return \$user->hasPermissionTo('{$permissions['view_any']}', 'web') ||
               \$user->hasAnyRole(['admin', 'moderator', 'contributeur'], 'web');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User \$user, {$modelClass} \${$modelName}): bool
    {
        // Super-admin a tous les droits
        if (\$user->hasRole('super-admin', 'web')) {
            return true;
        }
        
        return \$user->hasPermissionTo('{$permissions['view_any']}', 'web') ||
               \$user->hasAnyRole(['admin', 'moderator', 'contributeur'], 'web');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User \$user): bool
    {
        // Super-admin a tous les droits
        if (\$user->hasRole('super-admin', 'web')) {
            return true;
        }
        
        return \$user->hasPermissionTo('{$permissions['create']}', 'web') ||
               \$user->hasAnyRole(['admin', 'contributeur'], 'web');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User \$user, {$modelClass} \${$modelName}): bool
    {
        // Super-admin a tous les droits
        if (\$user->hasRole('super-admin', 'web')) {
            return true;
        }
        
        return \$user->hasPermissionTo('{$permissions['update']}', 'web') ||
               \$user->hasAnyRole(['admin'], 'web');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User \$user, {$modelClass} \${$modelName}): bool
    {
        // Super-admin a tous les droits
        if (\$user->hasRole('super-admin', 'web')) {
            return true;
        }
        
        return \$user->hasPermissionTo('{$permissions['delete']}', 'web') ||
               \$user->hasAnyRole(['admin'], 'web');
    }

    /**
     * Determine whether the user can moderate the model.
     */
    public function moderate(User \$user, ?{$modelClass} \${$modelName} = null): bool
    {
        // Super-admin a tous les droits
        if (\$user->hasRole('super-admin', 'web')) {
            return true;
        }
        
        return \$user->hasPermissionTo('{$permissions['moderate']}', 'web') ||
               \$user->hasAnyRole(['admin', 'moderator'], 'web');
    }

    /**
     * Determine whether the user can publish the model.
     */
    public function publish(User \$user, {$modelClass} \${$modelName}): bool
    {
        // Super-admin a tous les droits
        if (\$user->hasRole('super-admin', 'web')) {
            return true;
        }
        
        return \$user->hasPermissionTo('{$permissions['publish']}', 'web') ||
               \$user->hasAnyRole(['admin', 'moderator'], 'web');
    }



                }";

            // Ajouter la méthode unpublish seulement si la permission existe
            if (isset($permissions['unpublish'])) {
                $template .= "

    /**
     * Determine whether the user can unpublish the model.
     */
    public function unpublish(User \$user, {$modelClass} \${$modelName}): bool
    {
        // Super-admin a tous les droits
        if (\$user->hasRole('super-admin', 'web')) {
            return true;
        }
        
        return \$user->hasPermissionTo('{$permissions['unpublish']}', 'web') ||
               \$user->hasAnyRole(['admin', 'moderator'], 'web');
    }";
            }

            // Ajouter des méthodes spécifiques selon le modèle
            if (isset($permissions['approve'])) {
                $template .= "

    /**
     * Determine whether the user can approve the model.
     */
    public function approve(User \$user, {$modelClass} \${$modelName}): bool
    {
        // Super-admin a tous les droits
        if (\$user->hasRole('super-admin', 'web')) {
            return true;
        }
        
        return \$user->hasPermissionTo('{$permissions['approve']}', 'web') ||
               \$user->hasAnyRole(['admin', 'moderator'], 'web');
    }";
            }

            if (isset($permissions['reject'])) {
                $template .= "

    /**
     * Determine whether the user can reject the model.
     */
    public function reject(User \$user, {$modelClass} \${$modelName}): bool
    {
        // Super-admin a tous les droits
        if (\$user->hasRole('super-admin', 'web')) {
            return true;
        }
        
        return \$user->hasPermissionTo('{$permissions['reject']}', 'web') ||
               \$user->hasAnyRole(['admin', 'moderator'], 'web');
    }";
            }

            if (isset($permissions['download'])) {
                $template .= "

    /**
     * Determine whether the user can download the model.
     */
    public function download(User \$user, {$modelClass} \${$modelName}): bool
    {
        // Super-admin a tous les droits
        if (\$user->hasRole('super-admin', 'web')) {
            return true;
        }
        
        return \$user->hasPermissionTo('{$permissions['download']}', 'web') ||
               \$user->hasAnyRole(['admin', 'moderator', 'contributeur'], 'web');
    }";
            }

            $template .= "
}";

            return $template;
        };
        
        // Définir les modèles avec leurs permissions snake_case
        $models = [
            'actualite' => [
                'view_any' => 'view_actualites',
                'create' => 'create_actualite',
                'update' => 'update_actualite',
                'delete' => 'delete_actualite',
                'moderate' => 'moderate_actualites',
                'publish' => 'publish_actualites',
                'unpublish' => 'unpublish_actualites'
            ],
            'projet' => [
                'view_any' => 'view_projets',
                'create' => 'create_projet',
                'update' => 'update_projet',
                'delete' => 'delete_projet',
                'moderate' => 'moderate_projets',
                'publish' => 'publish_projets',
                'unpublish' => 'unpublish_projets'
            ],
            'publication' => [
                'view_any' => 'view_publications',
                'create' => 'create_publication',
                'update' => 'update_publication',
                'delete' => 'delete_publication',
                'moderate' => 'moderate_publications',
                'publish' => 'publish_publications',
                'unpublish' => 'unpublish_publications'
            ],
            'evenement' => [
                'view_any' => 'view_evenements',
                'create' => 'create_evenement',
                'update' => 'update_evenement',
                'delete' => 'delete_evenement',
                'moderate' => 'moderate_evenements',
                'publish' => 'publish_evenements',
                'unpublish' => 'unpublish_evenements'
            ],
            'service' => [
                'view_any' => 'view_services',
                'create' => 'create_service',
                'update' => 'update_service',
                'delete' => 'delete_service',
                'moderate' => 'moderate_services',
                'publish' => 'publish_services',
                'unpublish' => 'unpublish_services'
            ],
            'media' => [
                'view_any' => 'view_media',
                'create' => 'create_media',
                'update' => 'update_media',
                'delete' => 'delete_media',
                'moderate' => 'moderate_media',
                'publish' => 'publish_media',
                'approve' => 'approve_media',
                'reject' => 'reject_media',
                'download' => 'download_media'
            ]
        ];
        
        foreach ($models as $modelName => $permissions) {
            $policyContent = $policyTemplate($modelName, $permissions);
            $policyPath = app_path("Policies/{$modelName}Policy.php");
            
            // Sauvegarder l'ancien si il existe
            if (File::exists($policyPath)) {
                File::move($policyPath, $policyPath . '.backup');
                $this->command->info("📁 Sauvegarde: {$modelName}Policy.php.backup");
            }
            
            // Créer le nouveau
            File::put($policyPath, $policyContent);
            $this->command->info("✅ Généré: {$modelName}Policy.php (snake_case)");
        }
        
        $this->command->info('🎉 Toutes les policies snake_case ont été générées!');
    }
}
