<?php

namespace App\Livewire\Entities\Products;

use App\Models\Product;
use Livewire\Attributes\Js;
use Livewire\Component;
use Livewire\WithPagination;

class Search extends Component
{
    use WithPagination;

    public $search;

    public function render()
    {
        return view('livewire..entities.products.search', [
            'products' => $this->query()
        ]);
    }

    public function query(): null|object
    {
        // Validation
        $products = null;
        if(is_string($this->search)){
            if(
                mb_strlen($this->search) >= 2
                && mb_strlen($this->search) < 120
            ){
                $products = Product::searchByTag($this->search, 5);
            }
        }
        return $products;
    }

    #[Js]
    public function removeFilter()
    {
        return <<<'JS'
            const uncheck = (product) => {
                let unchecked;
                if(product.checked){
                    product.checked = false;
                    unchecked = true;
                } else {
                    unchecked = false;
                }
                return unchecked;
            }

            const products = document.querySelectorAll('.input-product');
            for(let product of products){
                if(uncheck(product)){
                    break;
                }
            }
        JS;
    }
}
