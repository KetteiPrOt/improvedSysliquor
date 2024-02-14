<?php

namespace App\Livewire\Entities\Warehouses;

use App\Models\Warehouse;
use Livewire\Attributes\Js;
use Livewire\Component;
use Livewire\WithPagination;

class Search extends Component
{
    use WithPagination;
    
    public $search;

    public function render()
    {
        return view('livewire..entities.warehouses.search', [
            'warehouses' => $this->query(),
            'currentWarehouse' => $this->current()
        ]);
    }

    private function query(): null|object
    {
        // Validation
        $warehouses = null;
        if(is_string($this->search)){
            if(
                mb_strlen($this->search) >= 2
                && mb_strlen($this->search) < 120
            ){
                $query = Warehouse::where('name', 'LIKE', '%' . $this->search . '%');
                $warehouses = $query->paginate(5);
            }
        }
        return $warehouses;
    }

    private function current(): null|object
    {
        $id = session('current-sales-warehouse', null);
        $warehouse = Warehouse::find($id);
        return $warehouse;
    }

    #[Js]
    public function removeFilter()
    {
        return <<<'JS'
            const uncheck = (warehouse) => {
                let unchecked;
                if(warehouse.checked){
                    warehouse.checked = false;
                    unchecked = true;
                } else {
                    unchecked = false;
                }
                return unchecked;
            }

            const warehouses = document.querySelectorAll('.input-warehouse');
            for(let warehouse of warehouses){
                if(uncheck(warehouse)){
                    break;
                }
            }
        JS;
    }
}
