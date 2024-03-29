<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Http\Requests\StoreClientRequest;
use App\Models\Person;
use App\Http\Requests\UpdateClientRequest;

class ClientController extends Controller
{
    public function index(Request $request){
        $search = $request->get('search') ?? null;
        $search = is_string($search) ? $search : null;
        if($search){
            $clients = Client::join('persons', 'clients.person_id', '=', 'persons.id')
                            ->select('clients.*')
                            ->whereRaw("persons.name LIKE ?", ["%$search%"])->paginate(25);
            $formBag['search'] = $search;
        } else {
            $clients = Client::paginate(25);
            $formBag = null;
        }
        return view('entities.clients.index', [
            'clients' => $clients,
            'formBag' => $formBag
        ]);
    }

    public function create(Request $request){
        return view('entities.clients.create', [
            'success' => $request->get('success') ?? null
        ]);
    }

    public function store(StoreClientRequest $request){
        $validated = $request->validated();
        $person_id = Person::create([
            'name' => $validated['name'],
            'phone_number' => $validated['phone_number'] ?? null,
            'email' => $validated['email'] ?? null,
            'address' => $validated['address'] ?? null
        ])->id;
        Client::create([
            'identification_card' => $validated['identification_card'] ?? null,
            'ruc' => $validated['ruc'] ?? null,
            'social_reason' => $validated['social_reason'] ?? null,
            'person_id' => $person_id
        ]);
        return redirect()->route('clients.create', ['success' => true]);
    }

    public function show(Client $client){
        return view('entities.clients.show', [
            'client' => $client,
            'finalConsumer' => Client::finalConsumer()
        ]);
    }

    public function edit(Client $client){
        return view('entities.clients.edit', ['client' => $client]);
    }

    public function update(UpdateClientRequest $request, Client $client){
        $validated = $request->validated();
        if($client->id != Client::finalConsumer()->id){
            $client->update([
                'identification_card' => $validated['identification_card'] ?? null,
                'ruc' => $validated['ruc'] ?? null,
                'social_reason' => $validated['social_reason'] ?? null
            ]);
            $person = $client->person;
            $person->update([
                'name' => $validated['name'],
                'phone_number' => $validated['phone_number'] ?? null,
                'email' => $validated['email'] ?? null,
                'address' => $validated['address'] ?? null
            ]);
        }
        return redirect()->route('clients.show', $client->id);
    }

    public function destroy(Client $client){
        if($client->id != Client::finalConsumer()->id){
            $person = $client->person;
            $person->delete();
        }
        return redirect()->route('clients.index');
    }
}
