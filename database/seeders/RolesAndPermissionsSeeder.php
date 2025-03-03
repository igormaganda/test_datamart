<?php

// database/seeders/RolesAndPermissionsSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Création des rôles
        $admin = Role::create(['name' => 'admin']);
        $employee = Role::create(['name' => 'employee']);
        $user = Role::create(['name' => 'user']);

        // Création des permissions
        $viewUsers = Permission::create(['name' => 'view users']);
        $manageProfile = Permission::create(['name' => 'manage profile']);
        $viewOwnProfile = Permission::create(['name' => 'view own profile']);
        
        // Assigner des permissions aux rôles
        $admin->givePermissionTo($viewUsers);
        $admin->givePermissionTo($manageProfile);
        $admin->givePermissionTo($viewOwnProfile);
        
        $employee->givePermissionTo($manageProfile);
        $employee->givePermissionTo($viewOwnProfile);
        
        $user->givePermissionTo($viewOwnProfile);
    }
}
