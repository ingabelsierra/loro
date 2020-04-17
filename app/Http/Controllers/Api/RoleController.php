<?php

namespace App\Http\Controllers\Api;

use Spatie\Permission\Models\Role;
//use Spatie\Permission\Models\Permission;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Traits\paginationTrait;
use App\Traits\responseTrait;

class RoleController extends Controller {

    use paginationTrait,
        responseTrait;

    public function __construct() {
        $this->middleware('auth');
    }

    public function index(Request $request) {

        if ($request->pagination) {
            $roles = Role::paginate($this->pagination($request))->all();
        } else {
            $roles = Role::all();
        }

        return $this->sendResponse($roles, 'Todos los Roles.');
    }

    public function show(Request $request, $id) {

        $roles = Role::find($id);
        if (is_null($roles)) {
            return $this->sendError('Rol no encontrado.');
        }

        return $this->sendResponse($roles->toArray(), 'Rol encontrado.');
    }

    public function store(Request $request) {

        $input = $request->all();
        $validator = Validator::make($input, [
                    'rol' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Error en la validacion de los datos.', $validator->errors());
        }

        $rol = Role::create(['name' => $input['rol']]);

        return $this->sendResponse($rol->toArray(), 'Rol Registrado');
    }

    public function update(Request $request, $id) {

        $input = $request->all();
        $validator = Validator::make($input, [
                    'rol' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Error en la validacion de los datos.', $validator->errors());
        }

        $roles = Role::find($id);
        //$input = $request->all();
        $roles->update(['name' => $input['rol']]);

        return $this->sendResponse($roles->toArray(), 'Rol actualizado con exito.');
    }

    public function assignRole(Request $request) {
        $input = $request->all();
        $validator = Validator::make($input, [
                    'role' => 'required',
                    'user' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Error en la validacion de los datos.', $validator->errors());
        }

        $user = User::findOrFail($input['user']);
        if (is_null($user)) {
            return $this->sendError('Usuario no encontrado.');
        }

        $user->assignRole($input['role']);

        return response()->json('Rol asignado a: ' . $user->name, 200);
    }

    public function UserRoles(Request $request) {
        $input = $request->all();
        $validator = Validator::make($input, [
                    'user' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Error en la validacion de los datos.', $validator->errors());
        }

        $user = User::findOrFail($input['user']);
        $roles = $user->getRoleNames();

        return $this->sendResponse($roles->toArray(), 'Roles de user: ' . $user->name);
    }

    public function removeRole(Request $request) {
        $input = $request->all();
        $validator = Validator::make($input, [
                    'role' => 'required',
                    'user' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Error en la validacion de los datos.', $validator->errors());
        }
        $user = User::findOrFail($input['user']);
        if (is_null($user)) {
            return $this->sendError('Usuario no encontrado.');
        }
        $user->removeRole($input['role']);
        return response()->json('Rol ' . $input['role'] . 'removido de: ' . $user->name, 200);
    }

}
