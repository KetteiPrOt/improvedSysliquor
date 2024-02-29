<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'search' => 'nullable|string|min:2|max:255'
        ], attributes: ['search' => 'Buscar']);
        if($validator->fails()){
            return 
                redirect()->route('roles.index')
                    ->withErrors($validator)->withInput();
        }
        $validated = $validator->validated();
        if(isset($validated['search'])){
            $search = $validated['search'];
            $query = 
                Role::whereRaw("
                    `name` LIKE ?
                ", ["%$search%"])->with(['permissions', 'users']);
        } else {
            $query = Role::with(['permissions', 'users']);
        }
        return view('entities.roles.index', [
            'roles' => $query->paginate(15)->withQueryString(),
            'translator' => new TranslatePermissionController,
            'search' => $search ?? null
        ]);
    }

    public function create()
    {
        return view('entities.roles.create', [
            'translator' => new TranslatePermissionController
        ]);
    }

    public function store(StoreRoleRequest $request)
    {
        $validated = $request->validated();
        $role = Role::create(['name' => $validated['name']]);
        foreach(Permission::$directPermissions as $permission){
            if(isset($validated[$permission])){
                $role->givePermissionTo($permission);
            }
        }
        return redirect()->route('roles.index');
    }

    public function edit(Role $role)
    {
        if($role->name !== 'Administrador'){
            return view('entities.roles.edit', [
                'role' => $role,
                'translator' => new TranslatePermissionController
            ]);
        } else {
            return redirect()->route('roles.index');
        }
    }

    public function update(UpdateRoleRequest $request, Role $role)
    {
        $validated = $request->validated();
        $role->update(['name' => $validated['name']]);
        foreach(Permission::$directPermissions as $permission){
            if(isset($validated[$permission])){
                $role->givePermissionTo($permission);
            } else {
                $role->revokePermissionTo($permission);
            }
        }
        return redirect()->route('roles.index');
    }

    public function destroy(Role $role)
    {
        if($role->name !== 'Administrador'){
            $role->delete();
        }
        return redirect()->route('roles.index');
    }

    public function editUsers(Role $role)
    {
        return view('entities.roles.edit-users', [
            'role' => $role
        ]);
    }
}
