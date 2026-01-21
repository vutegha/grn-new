<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class CheckUserPermissions extends Command
{
    protected $signature = 'user:check-permissions {email}';
    protected $description = 'Check permissions for a specific user';

    public function handle()
    {
        $email = $this->argument('email');
        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("Utilisateur avec l'email {$email} non trouvé!");
            return 1;
        }

        $this->info("Utilisateur trouvé: " . $user->name);
        $this->info("Email: " . $user->email);
        $this->info("Super Admin: " . ($user->isSuperAdmin() ? 'OUI' : 'NON'));
        
        $this->line("");
        $this->info("=== PERMISSIONS ACTUALITÉS ===");
        $this->line("update actualites: " . ($user->can('update actualites') ? '✅ OUI' : '❌ NON'));
        $this->line("view actualites: " . ($user->can('view actualites') ? '✅ OUI' : '❌ NON'));
        $this->line("delete actualites: " . ($user->can('delete actualites') ? '✅ OUI' : '❌ NON'));
        
        $this->line("");
        $this->info("=== PERMISSIONS ÉVÉNEMENTS ===");
        $this->line("update evenements: " . ($user->can('update evenements') ? '✅ OUI' : '❌ NON'));
        $this->line("view evenements: " . ($user->can('view evenements') ? '✅ OUI' : '❌ NON'));
        $this->line("delete evenements: " . ($user->can('delete evenements') ? '✅ OUI' : '❌ NON'));
        
        $this->line("");
        $this->info("=== RÔLES ===");
        foreach ($user->roles as $role) {
            $this->line("Rôle: " . $role->name);
        }

        return 0;
    }
}
