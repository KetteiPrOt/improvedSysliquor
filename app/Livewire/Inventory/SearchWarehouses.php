<?php 

namespace App\Livewire\Inventory;

use App\Livewire\Entities\Warehouses\Search;
use Livewire\Attributes\Js;

class SearchWarehouses extends Search
{
    public function render()
    {
        return view('livewire..inventory.search-warehouses', [
            'warehouses' => $this->query(),
            'currentWarehouse' => $this->current()
        ]);
    }

    #[JS]
    public function disableDate()
    {
        return <<<'JS'
            const dateInput = document.getElementById('dateInput'); 
            dateInput.disabled = true;
            dateInput.redonly = true;
            const date = new Date();
            let month = date.getMonth() + 1,
                day = date.getDate() < 10 ? '0' + date.getDate() : date.getDate();
            let currentDate =
                `${date.getFullYear()}-${
                    (month < 10) ? '0' + (month) : month
                }-${day}`;
            dateInput.value = currentDate;
        JS;
    }

    #[JS]
    public function enableDate()
    {
        return <<<'JS'
            const dateInput = document.getElementById('dateInput');
            dateInput.disabled = false;
            dateInput.redonly = false;
            const date = new Date();
            let month = date.getMonth() + 1;
            let currentDate =
                `${date.getFullYear()}-${
                    (month < 10) ? '0' + (month) : month
                }-${date.getDate()}`;
            dateInput.value = currentDate;
        JS;
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
            $wire.enableDate();
        JS;
    }
}