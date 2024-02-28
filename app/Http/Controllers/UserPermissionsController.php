<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserPermissionsRequest;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserPermissionsController extends Controller
{    
    public function users(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'search' => 'nullable|string|min:2|max:255'
        ], attributes: ['search' => 'Buscar']);
        if($validator->fails()){
            return redirect()
                ->route('user-permissions.users')
                ->withErrors($validator)->withInput();
        }
        $validated = $validator->validated();
        if(isset($validated['search'])){
            $search = $validated['search'];
            $users =
                User::with('roles')
                    ->whereRaw("
                        `name` LIKE ?
                    ", ["%$search%"])
                    ->orderBy('name')->paginate(15);
        } else {
            $users = User::with('roles')->orderBy('name')->paginate(15);
        }
        return view('user-permissions.users', [
            'users' => $users->withQueryString(),
            'search' => $search ?? null,
            'translator' => new TranslatePermissionController,
        ]);
    }

    public function edit(User $user)
    {
        return view('user-permissions.edit', [
            'user' => $user,
            'translator' => new TranslatePermissionController
        ]);
    }

    public function update(UpdateUserPermissionsRequest $request, User $user)
    {
        $validated = $request->validated();
        foreach(Permission::$directPermissions as $permission){
            if(isset($validated[$permission])){
                $user->givePermissionTo($permission);
            } else {
                $user->revokePermissionTo($permission);
            }
        }
        return redirect()->route('user-permissions.users');
    }
}
