<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
      public function run()
      {
            //Permisos Roles  
            Permission::create(['name' => 'listar_roles','group' => 'Roles','module' => 'admin']);
            Permission::create(['name' => 'detalle_rol','group' => 'Roles','module' => 'admin']);
            Permission::create(['name' => 'registrar_nuevo_rol','group' => 'Roles','module' => 'admin']);
            Permission::create(['name' => 'actualizar_rol','group' => 'Roles','module' => 'admin']);
            Permission::create(['name' => 'assignar_rol','group' => 'Roles','module' => 'admin']);
            Permission::create(['name' => 'user_roles','group' => 'Roles','module' => 'admin']);
            //Permisos Permisos
            Permission::create(['name' => 'listar_permisos','group' => 'Permisos','module' => 'admin']);
            Permission::create(['name' => 'assignar_permisos','group' => 'Permisos','module' => 'admin']);
            Permission::create(['name' => 'listar_rol_permiso','group' => 'Permisos','module' => 'admin']);    
            //Permisos Usuarios
            Permission::create(['name' => 'listar_usuarios','group' => 'Usuarios','module' => 'admin']);      
            Permission::create(['name' => 'registrar_nuevo_usuario','group' => 'Usuarios','module' => 'admin']);
            Permission::create(['name' => 'actualizar_datos_usuarios','group' => 'Usuarios','module' => 'admin']);
            Permission::create(['name' => 'restablecer_clave','group' => 'Usuarios','module' => 'admin']);          
           
            //Permisos Notificaciones
            Permission::create(['name' => 'listar_notificaciones','group' => 'Notificaciones','module' => 'admin']);
            Permission::create(['name' => 'detalle_notificacion','group' => 'Notificaciones','module' => 'admin']);
            Permission::create(['name' => 'registrar_notificacion','group' => 'Notificaciones','module' => 'admin']);
            Permission::create(['name' => 'actualizar_notificacion','group' => 'Notificaciones','module' => 'admin']);          


     }
}
