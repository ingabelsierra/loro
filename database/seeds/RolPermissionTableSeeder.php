<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\User;

class RolPermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Asignar Rol 
        $admin = User::findOrFail(1);
        $admin = $admin->assignRole('Administrator'); 
        
        //Asignar Permiso
        $admin = Role::findOrFail(1);
        $admin->givePermissionTo(Permission::all());     
        
        //Asignar Rol 
        /*$user = User::findOrFail(2);
        $user = $user->assignRole('Usuario'); 
        
        //Asignar Permiso
        $user = Role::findOrFail(2);
        $user->givePermissionTo(Permission::all()); */ 
    
      
    }
}
