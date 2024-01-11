<?php

namespace App\Livewire\Sales;

use Livewire\Component;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Js;
use App\Models\Product;
use App\Models\MovementCategory;
use App\Models\MovementType;

class SelectProducts extends Component
{
    public $search = null;

    #[Locked]
    public $selectedProductsIds = [];

    public function render()
    {
        $selectedProducts = $this->querySelectedProducts();
        if($this->search){
            $products = Product::searchByTag($this->search, 5);
        }
        $incomeCategory = MovementCategory::expense();
        $movementTypes = $incomeCategory
                         ->movementTypes()
                         ->where('name', '!=', MovementType::$initialInventoryName)->get();
        $saleType = MovementType::sale();
        return view('livewire.sales.select-products', [
            'products' => $products ?? null,
            'selectedProducts' => $selectedProducts,
            'movementTypes' => $movementTypes,
            'saleType' => $saleType
        ]);
    }

    private function querySelectedProducts(){
        $selectedProducts = [];
        foreach($this->selectedProductsIds as $id){
            $product = Product::find($id);
            if($product){
                $selectedProducts[] = $product;
            }
        }
        return $selectedProducts;
    }

    public function selectProduct($id){
        if(is_numeric($id)){
            $alreadySelected = false;
            foreach($this->selectedProductsIds as $selectedProductId){
                if($selectedProductId === $id){
                    $alreadySelected = true;
                    break;
                }
            }
            if(!$alreadySelected){
                $this->selectedProductsIds[] = $id;
            }
        }
    }

    public function dropProduct($id){
        if(is_numeric($id)){
            foreach($this->selectedProductsIds as $key => $selectedProductId){
                if($selectedProductId === $id){
                    unset($this->selectedProductsIds[$key]);
                }
            }
        }
    }

    #[Js]
    public function syncUnitaryPrice(){
        return <<<'JS'
            const productKey = event.target.id.charAt(6);
            const unitaryPriceOneUnit = document.getElementById(
                `salePrice1Units${productKey}Product`
            );
            const unitaryPriceSixUnits = document.getElementById(
                `salePrice6Units${productKey}Product`
            );
            const unitaryPriceTwelveUnits = document.getElementById(
                `salePrice12Units${productKey}Product`
            );
            if(event.target.value < 6){
                unitaryPriceSixUnits.hidden = true;
                unitaryPriceSixUnits.selected = false;
                unitaryPriceOneUnit.selected = true;
            } else {
                unitaryPriceSixUnits.hidden = false;
            }
            if(event.target.value < 12){
                unitaryPriceTwelveUnits.hidden = true;
                unitaryPriceSixUnits.selected = false;
                if((event.target.value >= 6) && (!unitaryPriceOneUnit.selected)){
                    unitaryPriceSixUnits.selected = true;
                }
            } else {
                unitaryPriceTwelveUnits.hidden = false;
            }
        JS;
    }
}
