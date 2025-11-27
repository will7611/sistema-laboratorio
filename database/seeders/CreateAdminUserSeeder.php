<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'name' => 'Wilson Flores Taboada', 
            'email' => 'admin@gmail.com',
            'password' => bcrypt('123456'),
            'ci' => '8548125',
            'phone' => '76119524',
            'address' => 'Fernando Mercy #15',
            'fecha_nacimiento' => now(),
            'img' => 'img1'
        ]);
        
        $role = Role::create(['name' => 'Admin']);
         
        $permissions = Permission::pluck('id','id')->all();
       
        $role->syncPermissions($permissions);
         
        $user->assignRole([$role->id]);
    }
}
