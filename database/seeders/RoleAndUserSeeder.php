<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class RoleAndUserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Lista de permisos
        $permissions = [
            'role-list', 'role-create', 'role-edit', 'role-delete',
            'proforma-list', 'proforma-create', 'proforma-edit', 'proforma-delete',
            'result-list', 'result-create', 'result-edit', 'result-delete',
            'user-list', 'user-create', 'user-edit', 'user-delete',
            'permission-list', 'permission-create', 'permission-edit', 'permission-delete',
            'order-list', 'order-create', 'order-edit', 'order-delete',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }

        // 2. Crear Roles
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $bioquimicaRole = Role::firstOrCreate(['name' => 'Bioquimica']);
        $secretariaRole = Role::firstOrCreate(['name' => 'Secretaria']);

        // 3. Asignar Permisos
        $adminRole->syncPermissions(Permission::all()); // Admin tiene todo
        
        $bioquimicaRole->syncPermissions([
            'proforma-list', 'order-list', 'order-edit', 
            'result-list', 'result-create', 'result-edit'
        ]);

        $secretariaRole->syncPermissions([
            'proforma-list', 'proforma-create', 'proforma-edit', 
            'order-list', 'user-list'
        ]);

        // 4. Crear Usuarios de prueba
        
        // ADMIN
        $admin = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Wilson Flores Taboada',
                'password' => Hash::make('123456'),
                'ci' => '8548125', 'phone' => '76119524', 'address' => 'Fernando Mercy #15',
                'fecha_nacimiento' => now(), 'img' => 'admin.png'
            ]
        );
        $admin->assignRole($adminRole);

        // BIOQUIMICA
        $bio = User::firstOrCreate(
            ['email' => 'bio@gmail.com'],
            [
                'name' => 'Dra. Beatriz Bioquimica',
                'password' => Hash::make('123456'),
                'ci' => '1234567', 'phone' => '70000001', 'address' => 'Lab Sector A',
                'fecha_nacimiento' => now(), 'img' => 'bio.png'
            ]
        );
        $bio->assignRole($bioquimicaRole);

        // SECRETARIA
        $sec = User::firstOrCreate(
            ['email' => 'secretaria@gmail.com'],
            [
                'name' => 'Sandra Secretaria',
                'password' => Hash::make('123456'),
                'ci' => '7654321', 'phone' => '70000002', 'address' => 'Recepcion',
                'fecha_nacimiento' => now(), 'img' => 'sec.png'
            ]
        );
        $sec->assignRole($secretariaRole);
    }
}