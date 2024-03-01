<?php

namespace App\Http\Controllers;

use App\Models\Presentation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Psy\VarDumper\Presenter;

class PresentationController extends Controller
{
    public function index()
    {
        return view('entities.presentations.index', [
            'presentations' => Presentation::orderBy('content')->paginate(15)
        ]);
    }

    public function create()
    {
        return view('entities.presentations.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'content' => 'required|integer|min:1|max:65500|unique:presentations,content',
        ], attributes: ['content' => 'Contenido']);
 
        if($validator->fails()){
            return
                redirect()->route('presentations.create')
                    ->withErrors($validator)->withInput();
        }
        $presentation = Presentation::create($validator->validated());
        return redirect()->route('presentations.show', $presentation->id);
    }

    public function show(Presentation $presentation)
    {
        return view('entities.presentations.show', compact('presentation'));
    }

    public function edit(Presentation $presentation)
    {
        return view('entities.presentations.edit', compact('presentation'));
    }

    public function update(Request $request, Presentation $presentation)
    {
        $validator = Validator::make($request->all(), [
            'content' => [
                'required', 'integer', 'min:1', 'max:65500',
                Rule::unique('presentations', 'content')->ignore($presentation->id)
            ],
            'active' => ['sometimes', 'accepted']
        ], attributes: [
            'content' => 'Contenido',
            'active' => 'Estado'
        ]);
        if($validator->fails()){
            return
                redirect()->route('presentations.edit', $presentation->id)
                    ->withErrors($validator)->withInput();
        }
        $validated = $validator->validated();
        $presentation->update([
            'content' => $validated['content'],
            'active' => isset($validated['active']) ? true : false
        ]);
        return redirect()->route('presentations.show', $presentation->id);
    }
}
