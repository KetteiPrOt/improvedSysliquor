<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        return view('entities.roles.index', [
            'roles' => Role::with(['permissions', 'users'])->paginate(1),
            'translator' => new TranslatePermissionController
        ]);
    }
}
