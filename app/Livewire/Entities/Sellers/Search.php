<?php

namespace App\Livewire\Entities\Sellers;

use App\Models\Seller;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Js;
use Livewire\Component;
use Livewire\WithPagination;

class Search extends Component
{
    use WithPagination;

    public $search;

    public function render()
    {
        return view('livewire..entities.sellers.search', [
            'sellers' => $this->query(),
            'currentSeller' => Auth::user()->seller
        ]);
    }

    public function query(): null|object
    {
        // Validation
        $sellers = null;
        if(is_string($this->search)){
            if(
                mb_strlen($this->search) >= 2
                && mb_strlen($this->search) < 120
            ){
                $query = Seller::join('persons', 'sellers.person_id', '=', 'persons.id')
                                ->select('sellers.*')
                                ->whereRaw("persons.name LIKE ?", ["%$this->search%"]);
                $sellers = $query->paginate(5);
            }
        }
        return $sellers;
    }

    #[Js]
    public function removeFilter()
    {
        return <<<'JS'
            const uncheck = (seller) => {
                let unchecked;
                if(seller.checked){
                    seller.checked = false;
                    unchecked = true;
                } else {
                    unchecked = false;
                }
                return unchecked;
            }

            const sellers = document.querySelectorAll('.input-seller');
            for(let seller of sellers){
                if(uncheck(seller)){
                    break;
                }
            }
        JS;
    }
}
