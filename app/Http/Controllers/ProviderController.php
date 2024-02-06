<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Provider;
use App\Http\Requests\StoreProviderRequest;
use App\Http\Requests\UpdateProviderRequest;
use App\Models\Person;

class ProviderController extends Controller
{
    public function index(Request $request){
        $search = $request->get('search') ?? null;
        $search = is_string($search) ? $search : null;
        if($search){
            $providers = Provider::join('persons', 'providers.person_id', '=', 'persons.id')
                            ->select('providers.*')
                            ->whereRaw("persons.name LIKE ?", ["%$search%"])->paginate(25);
        } else {
            $providers = Provider::paginate(25);
        }
        return view('entities.providers.index', [
            'providers' => $providers,
            'search' => $search
        ]);
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

    public function show(Provider $provider){
        return view('entities.providers.show', ['provider' => $provider]);
    }

    public function edit(Provider $provider){
        return view('entities.providers.edit', ['provider' => $provider]);
    }

    public function update(UpdateProviderRequest $request, Provider $provider){
        dump($request->validated());
        return 'Actualizar';
    }

    public function destroy(Provider $provider){
        $person = $provider->person;
        $person->delete();
        return redirect()->route('providers.index');
    }
}
