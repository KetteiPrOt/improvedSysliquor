<?php

namespace App\Http\Controllers;

use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class TypeController extends Controller
{
    public function index()
    {
        return view('entities.types.index', [
            'types' => Type::orderBy('name')->paginate(15)
        ]);
    }

    public function create()
    {
        return view('entities.types.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:2|max:20|unique:types,name',
        ], attributes: ['name' => 'Nombre']);
 
        if($validator->fails()){
            return
                redirect()->route('types.create')
                    ->withErrors($validator)->withInput();
        }
        $validated = $validator->validated();
        $type = Type::create([
            'name' => str_replace(
                "ñ", "Ñ", strtoupper($validated['name'])
            )
        ]);
        return redirect()->route('types.show', $type->id);
    }

    public function show(Type $type)
    {
        return view('entities.types.show', compact('type'));
    }

    public function edit(Type $type)
    {
        return view('entities.types.edit', compact('type'));
    }

    public function update(Request $request, Type $type)
    {
        $validator = Validator::make($request->all(), [
            'name' => [
                'required', 'string', 'min:2', 'max:20', Rule::unique('types')->ignore($type->id)
            ],
            'active' => ['sometimes', 'accepted']
        ], attributes: ['name' => 'Nombre', 'active' => 'Estado']);
 
        if($validator->fails()){
            return
                redirect()->route('types.edit', $type->id)
                    ->withErrors($validator)->withInput();
        }
        $validated = $validator->validated();
        $type->update([
            'name' => str_replace(
                "ñ", "Ñ", strtoupper($validated['name'])
            ),
            'active' => isset($validated['active']) ? true : false
        ]);
        return redirect()->route('types.show', $type->id); 
    }
}
