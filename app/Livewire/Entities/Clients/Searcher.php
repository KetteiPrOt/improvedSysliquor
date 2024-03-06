<?php

namespace App\Livewire\Entities\Clients;

use App\Models\Client;
use Livewire\Component;

class Searcher extends Component
{
    public $search = null;
    
    public function render()
    {
        return view('livewire..entities.clients.searcher', [
            'clients' => $this->search()
        ]);
    }

    private function search()
    {
        $validated = $this->validateSearch();
        if($validated){
            $clients = 
                Client::join('persons', 'persons.id', '=', 'clients.person_id')
                    ->selectRaw('`persons`.`name`, `clients`.`id`')
                    ->whereRaw("
                        `persons`.`name`
                        LIKE ?
                    ", ["%$validated%"])
                    ->paginate(5, pageName: 'client');
        } else {
            $clients = null;
        }
        return $clients;
    }

    private function validateSearch()
    {
        $validated = null;
        if(is_string($this->search)){
            if(
                mb_strlen($this->search) <= 255
                && mb_strlen($this->search) >= 2
            ){
                $validated = $this->search;
            }
        }
        return $validated;
    }
}
