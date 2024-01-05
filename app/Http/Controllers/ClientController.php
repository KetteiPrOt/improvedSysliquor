<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;

class ClientController extends Controller
{
    public function index(Request $request){
        $search = $request->get('search');
        if($search){
            $clients = Client::join('persons', 'clients.person_id', '=', 'persons.id')
                            ->whereRaw("persons.name LIKE ?", ["%$search%"])->paginate(25);
            $formBag['search'] = $search;
        } else {
            $clients = null;
            $formBag = null;
        }
        return view('entities.clients.index', [
            'clients' => $clients,
            'formBag' => $formBag
        ]);
    }
}
