<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class FixRapportPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Créer la permission unpublish_rapports si elle n'existe pas
        $unpublishPermission = Permission::firstOrCreate([
            'name' => 'unpublish_rapports',
            'guard_name' => 'web'
        ]);

        // Assigner aux rôles appropriés
        $adminRole = Role::where('name', 'admin')->first();
        $superAdminRole = Role::where('name', 'super-admin')->first();
        $moderateurRole = Role::where('name', 'moderateur')->first();

        if ($adminRole && !$adminRole->hasPermissionTo('unpublish_rapports')) {
            $adminRole->givePermissionTo('unpublish_rapports');
        }

        if ($superAdminRole && !$superAdminRole->hasPermissionTo('unpublish_rapports')) {
            $superAdminRole->givePermissionTo('unpublish_rapports');
        }

        if ($moderateurRole && !$moderateurRole->hasPermissionTo('unpublish_rapports')) {
            $moderateurRole->givePermissionTo('unpublish_rapports');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Permission::where('name', 'unpublish_rapports')->delete();
    }
}
