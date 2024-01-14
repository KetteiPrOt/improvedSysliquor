<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Provider;
use App\Http\Requests\StoreProviderRequest;
use App\Models\Person;

class ProviderController extends Controller
{
    public function index(){
        $providers = Provider::paginate(25);
        return view('entities.providers.index', ['providers' => $providers]);
    }

    public function create(Request $request){
        return view('entities.providers.create', [
            'success' => $request->get('success') ?? null
        ]);
    }

    public function store(StoreProviderRequest $request){
        $validated = $request->validated();
        $person = Person::create([
            'name' => $validated['name'],
            'phone_number' => $validated['phone_number'],
            'email' => $validated['email'],
            'address' => $validated['address'],
        ]);
        Provider::create([
            'ruc' => $validated['ruc'],
            'social_reason' => $validated['social_reason'],
            'person_id' => $person->id
        ]);
        return redirect()->route('providers.create', ['success' => true]);
    }
}
