<?php

namespace App\Http\Controllers\Api;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\paginationTrait;
use App\Traits\responseTrait;

class PermissionController extends Controller {

    use paginationTrait,
        responseTrait;

    public function index(Request $request) {

        if ($request->pagination) {
            $permissions = Permission::paginate($this->pagination($request))->orderBy('group')->get();
        } else {
            $permissions = Permission::orderBy('group')->get();
        }

        return $this->sendResponse($permissions, 'Todos los Permisos');
    }

    public function assignPermission(Request $request) {

        $input = $request->all();
        $validator = Validator::make($input, [
                    'role' => 'required',
                    'permission' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Error en la validacion de los datos.', $validator->errors());
        }
        $role = Role::findOrFail($input['role']);
        if (is_null($role))
            return $this->sendError('Rol no encontrado.');
        $role->givePermissionTo($input['permission']);

        return response()->json('Permiso asignado a rol: ' . $role->name, 200);
    }

    public function ListRolePermissions(Request $request) {

        $input = $request->all();
        $validator = Validator::make($input, [
                    'role' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Error en la validacion de los datos.', $validator->errors());
        }
        $role = Role::findOrFail($input['role']);
        if (is_null($role))
            return $this->sendError('Rol no encontrado.');
        $permissions = $role->getAllPermissions();

        return $this->sendResponse($permissions->toArray(), 'Todos los Permisos del rol ' . $role->name);
    }


    public function removePermission(Request $request) {

        $input = $request->all();
        $validator = Validator::make($input, [
                    'role' => 'required',
                    'permission' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Error en la validacion de los datos.', $validator->errors());
        }
        $role = Role::findOrFail($input['role']);
        if (is_null($role))
            return $this->sendError('Rol no encontrado.');
        $role->revokePermissionTo($input['permission']);

        return response()->json('Permiso ' . $input['permission'] . ' removido a rol: ' . $role->name, 200);
    }

    public function modules(Request $request) {

        try{

            $modulos = \DB::select("select distinct module from permissions");

        }catch(Exepcion $e){
            return $this->sendResponse('Error al consultar modulos'. $e->getMessage());
        }

        return response()->json($modulos, 200);
    }

    public function groups($modulo) {

        try{

            $grupos = \DB::select("select distinct `group` from permissions where module = '".$modulo."'");

        }catch(Exepcion $e){
            return $this->sendResponse('Error al consultar modulos'. $e->getMessage());
        }

        return response()->json($grupos, 200);
    }


}
