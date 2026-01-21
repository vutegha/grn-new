<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer l'utilisateur administrateur par défaut
        $admin = User::firstOrCreate(
            ['email' => 'admin@iriucbc.com'],
            [
                'name' => 'Administrateur IRI-UCBC',
                'password' => Hash::make('admin123'),
                'email_verified_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        );

        // Assigner le rôle admin
        $admin->assignRole('admin');

        // Créer quelques utilisateurs de test
        $moderator = User::firstOrCreate(
            ['email' => 'moderator@iriucbc.com'],
            [
                'name' => 'Modérateur Test',
                'password' => Hash::make('moderator123'),
                'email_verified_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        );
        $moderator->assignRole('moderator');

        $editor = User::firstOrCreate(
            ['email' => 'editor@iriucbc.com'],
            [
                'name' => 'Éditeur Test',
                'password' => Hash::make('editor123'),
                'email_verified_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        );
        $editor->assignRole('editor');

        $user = User::firstOrCreate(
            ['email' => 'user@iriucbc.com'],
            [
                'name' => 'Utilisateur Test',
                'password' => Hash::make('user123'),
                'email_verified_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        );
        $user->assignRole('user');

        $this->command->info('Utilisateurs par défaut créés avec succès!');
        $this->command->info('Admin: admin@iriucbc.com / admin123');
        $this->command->info('Modérateur: moderator@iriucbc.com / moderator123');
        $this->command->info('Éditeur: editor@iriucbc.com / editor123');
        $this->command->info('Utilisateur: user@iriucbc.com / user123');
    }
}
