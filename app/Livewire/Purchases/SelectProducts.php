<?php

namespace App\Livewire\Purchases;

use Livewire\Component;
use Livewire\Attributes\Locked;
use App\Models\Product;
use App\Models\MovementCategory;
use App\Models\MovementType;
use Livewire\Attributes\Js;
use Livewire\WithPagination;

class SelectProducts extends Component
{
    use WithPagination;
    
    public $search = null;

    #[Locked]
    public $selectedProductsIds = [];

    public function render()
    {
        $selectedProducts = $this->querySelectedProducts();
        if($this->search){
            $products = Product::searchByTag($this->search, 5);
        }
        $incomeCategory = MovementCategory::income();
        $movementTypes = $incomeCategory
                         ->movementTypes()
                         ->where('name', '!=', MovementType::$initialInventoryName)->get();
        $purchaseType = MovementType::purchase();
        $initialInventoryType = MovementType::initialInventory();
        if(count($this->selectedProductsIds) > 0){
            $this->dispatch('product-selected');
        }
        return view('livewire.purchases.select-products', [
            'products' => $products ?? null,
            'selectedProducts' => $selectedProducts,
            'movementTypes' => $movementTypes,
            'purchaseType' => $purchaseType,
            'initialInventoryType' => $initialInventoryType
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
    public function syncInvoiceNumberRequirement()
    {
        return <<<'JS'
            const productsCount = parseInt(document.getElementById('productsCount').textContent),
                  initialInventoryId = parseInt(document.getElementById('initialInventoryid').textContent);
            let movementTypeInputs = [],
                allAreInitialInventory = true;
            for(let i = 0; i < productsCount; i++){
                let movementTypeInput = document.getElementById(`movementType${i}`);
                if(parseInt(movementTypeInput.value) != initialInventoryId){
                    allAreInitialInventory = false;
                    $wire.requireInvoiceNumber();
                    break;
                }
            }
            if(allAreInitialInventory){
                $wire.optionalInvoiceNumber();
            }
        JS;
    }

    #[Js]
    public function requireInvoiceNumber()
    {
        return <<<'JS'
            const invoiceNumberInputs = [
                document.getElementById('invoiceNumberFirstInput'),
                document.getElementById('invoiceNumberSecondInput'),
                document.getElementById('invoiceNumberThirdInput')
            ];
            for(let invoiceNumberInput of invoiceNumberInputs){
                invoiceNumberInput.required = true;
            }
        JS;
    }

    #[Js]
    public function optionalInvoiceNumber()
    {
        return <<<'JS'
            const invoiceNumberInputs = [
                document.getElementById('invoiceNumberFirstInput'),
                document.getElementById('invoiceNumberSecondInput'),
                document.getElementById('invoiceNumberThirdInput')
            ];
            for(let invoiceNumberInput of invoiceNumberInputs){
                invoiceNumberInput.required = false;
            }
        JS;
    }
}