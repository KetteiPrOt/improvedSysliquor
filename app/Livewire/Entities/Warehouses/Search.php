<?php

namespace App\Livewire\Entities\Warehouses;

use App\Models\Warehouse;
use Livewire\Attributes\Js;
use Livewire\Component;
use Livewire\WithPagination;

class Search extends Component
{
    use WithPagination;
    
    private bool $show = false;

    public $search;    

    public function render()
    {
        return view('livewire..entities.warehouses.search', [
            'warehouses' => $this->query(),
            'currentWarehouse' => $this->current()
        ]);
    }

    public function mount($show = false)
    {
        $this->show = $show;
    }

    protected function query(): null|object
    {
        // Show all by default?
        $warehouses = $this->show
                        ? Warehouse::paginate(5)
                        : null;
        // Validation
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

    protected function current(): null|object
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
