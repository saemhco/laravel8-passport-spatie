<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $administrador = Role::create(['name' => 'Administrador']);
        $registrador   = Role::create(['name' => 'Registrador(a)']);

        Permission::create(['name' => "administrador.usuario.index",   'es_administrador' => true])->syncRoles($administrador);
        Permission::create(['name' => "administrador.usuario.store",   'es_administrador' => true])->syncRoles($administrador);
        Permission::create(['name' => "administrador.usuario.show",    'es_administrador' => true])->syncRoles($administrador);
        Permission::create(['name' => "administrador.usuario.update",  'es_administrador' => true])->syncRoles($administrador);
        Permission::create(['name' => "administrador.usuario.destroy", 'es_administrador' => true])->syncRoles($administrador);

        Permission::create(['name' => "registrador.usuario.index"])->syncRoles($registrador);
        Permission::create(['name' => "registrador.usuario.store"])->syncRoles($registrador);
        Permission::create(['name' => "registrador.usuario.show"])->syncRoles($registrador);
        Permission::create(['name' => "registrador.usuario.update"])->syncRoles($registrador);
        Permission::create(['name' => "registrador.usuario.destroy"])->syncRoles($registrador);
    }
}
