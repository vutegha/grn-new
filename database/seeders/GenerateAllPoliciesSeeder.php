<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class GenerateAllPoliciesSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('🛠️ Génération de toutes les policies...');
        
        // Template de base pour les policies
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
        
        return \$user->hasPermissionTo('view {$permissions['view']}', 'web') || 
               \$user->hasPermissionTo('viewAny', 'web') ||
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
        
        return \$user->hasPermissionTo('view {$permissions['view']}', 'web') || 
               \$user->hasPermissionTo('view', 'web') ||
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
        
        return \$user->hasPermissionTo('create {$permissions['create']}', 'web') || 
               \$user->hasPermissionTo('create', 'web') ||
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
        
        return \$user->hasPermissionTo('update {$permissions['update']}', 'web') || 
               \$user->hasPermissionTo('update', 'web') ||
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
        
        return \$user->hasPermissionTo('delete {$permissions['delete']}', 'web') || 
               \$user->hasPermissionTo('delete', 'web') ||
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
        
        return \$user->hasPermissionTo('moderate {$permissions['moderate']}', 'web') || 
               \$user->hasPermissionTo('moderate', 'web') ||
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
        
        return \$user->hasPermissionTo('publish {$permissions['publish']}', 'web') || 
               \$user->hasPermissionTo('publish', 'web') ||
               \$user->hasAnyRole(['admin', 'moderator'], 'web');
    }

    /**
     * Determine whether the user can unpublish the model.
     */
    public function unpublish(User \$user, {$modelClass} \${$modelName}): bool
    {
        // Super-admin a tous les droits
        if (\$user->hasRole('super-admin', 'web')) {
            return true;
        }
        
        return \$user->hasPermissionTo('unpublish {$permissions['unpublish']}', 'web') ||
               \$user->hasAnyRole(['admin', 'moderator'], 'web');
    }
}";
        };
        
        // Définir les modèles et leurs permissions (basées sur les vues)
        $models = [
            'publication' => [
                'view' => 'publications',
                'create' => 'publications', 
                'update' => 'publications',
                'delete' => 'publications',
                'moderate' => 'publications',
                'publish' => 'publications',
                'unpublish' => 'publications'
            ],
            'evenement' => [
                'view' => 'evenements',
                'create' => 'evenements',
                'update' => 'evenements', 
                'delete' => 'evenements',
                'moderate' => 'evenements',
                'publish' => 'evenements',
                'unpublish' => 'evenements'
            ],
            'service' => [
                'view' => 'services',
                'create' => 'services',
                'update' => 'services',
                'delete' => 'services', 
                'moderate' => 'services',
                'publish' => 'services',
                'unpublish' => 'services'
            ],
            'media' => [
                'view' => 'media',
                'create' => 'media',
                'update' => 'media',
                'delete' => 'media',
                'moderate' => 'media', 
                'publish' => 'media',
                'unpublish' => 'media'
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
            $this->command->info("✅ Généré: {$modelName}Policy.php");
        }
        
        $this->command->info('🎉 Toutes les policies ont été générées!');
    }
}
