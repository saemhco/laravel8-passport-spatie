<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class InstaladorSeeder extends Seeder
{

    public function run()
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
        ]);

        // ==================== Usuario por defecto ====================
        User::create(
            [
                'name' => 'Administrador',
                'usuario' => 'admin',
                'email' => 'admin@mail.com',
                'password' => Hash::make('password')
            ]
        )->syncRoles(['Administrador']);
        User::create(
            [
                'name' => 'Registrador',
                'usuario' => 'registrador',
                'email' => 'registrador@mail.com',
                'password' => Hash::make('password')
            ]
        )->syncRoles(['Registrador(a)']);
        // ==================== Fin Usuario por defecto ====================
    }
}
