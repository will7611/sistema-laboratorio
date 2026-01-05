<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // lista de permisos
        $permissions = [
            'role-list',
            'role-create',
            'role-edit',
            'role-delete',

            'proforma-list',
            'proforma-create',
            'proforma-edit',
            'proforma-delete',

            // Permisos de resultados
            'result-list',
            'result-create',
            'result-edit',
            'result-delete',

            // Permisos de usuarios
            'user-list',
            'user-create',
            'user-edit',
            'user-delete',

            // Permisos de permisos
            'permission-list',
            'permission-create',
            'permission-edit',
            'permission-delete',

            // Permisos de orden
            'order-list',
            'order-create',
            'order-edit',
            'order-delete',
        ];

        // Creamos el rol admin (si no existe)
        $adminRole = Role::firstOrCreate(
            ['name' => 'Admin', 'guard_name' => 'web']
        );

        foreach ($permissions as $permissionName) {
            // Creamos el permiso si no existe
            $permission = Permission::firstOrCreate(
                ['name' => $permissionName, 'guard_name' => 'web']
            );

            // Asignamos el permiso al rol admin
            if (!$adminRole->hasPermissionTo($permission)) {
                $adminRole->givePermissionTo($permission);
            }
        }

        // Opcional: asignar rol admin al usuario con ID 1
        $user = \App\Models\User::find(1);
        if ($user && !$user->hasRole('Admin')) {
            $user->assignRole('Admin');
        }
    }
}
