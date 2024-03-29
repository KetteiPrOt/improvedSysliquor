<?php

namespace App\Livewire\Sales;

use Livewire\Component;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Js;
use App\Models\Product;
use App\Models\MovementCategory;
use App\Models\MovementType;
use App\Models\Warehouse;
use Livewire\WithPagination;

class SelectProducts extends Component
{
    use WithPagination;

    #[Locked]
    public Warehouse $warehouse;

    public $search = null;

    #[Locked]
    public $selectedProductsIds = [];

    public function mount(Warehouse $warehouse)
    {
        $this->warehouse = $warehouse;
    }

    public function render()
    {
        $selectedProducts = $this->querySelectedProducts();
        if($this->search){
            $products = Product::searchByTag($this->search, 5, 'product');
        }
        $incomeCategory = MovementCategory::expense();
        $movementTypes = $incomeCategory
                         ->movementTypes()
                         ->where('name', '!=', MovementType::$initialInventoryName)->get();
        $saleType = MovementType::sale();
        if(count($this->selectedProductsIds) > 0){
            $this->dispatch('product-selected');
        }
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
    public function syncCountAndTotal(){
        return <<<'JS'
            $wire.manageUnitaryPriceOptions();
            
            const productKey = event.target.id.charAt(6);
            
            let unitaryPriceSelected;
            const unitaryPrices = [
                document.getElementById(
                    `salePrice1Units${productKey}Product`
                ),
                document.getElementById(
                    `salePrice6Units${productKey}Product`
                ),
                document.getElementById(
                    `salePrice12Units${productKey}Product`
                )
            ];
            for(let unitaryPrice of unitaryPrices){
                if(unitaryPrice.selected){
                    unitaryPriceSelected = unitaryPrice;
                    break;
                }
            }
            const totalPrice = document.getElementById(`totalPrice${productKey}`);
            if(event.target.value){
                let totalPriceValue = parseInt(event.target.value) 
                                   * parseFloat(unitaryPriceSelected.textContent.slice(1));
                totalPrice.value = totalPriceValue.toFixed(2)
            } else {
                totalPrice.value = 0;
            }
            $wire.calculateTotalPricesSummation();
        JS;
    }

    #[Js]
    public function manageUnitaryPriceOptions(){
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

    #[Js]
    public function syncUnitaryPriceAndTotal(){
        return <<<'JS'
            const productKey = event.target.id.charAt(13),
                  totalPriceInput = document.getElementById(`totalPrice${productKey}`),
                  amount = parseInt(document.getElementById(`amount${productKey}`).value),
                  unitaryPrice = parseFloat(event.target.selectedOptions[0].textContent.slice(1));
            if(amount){
                totalPriceInput.value = parseFloat((amount * unitaryPrice).toFixed(2));
            } else {
                totalPriceInput.value = 0;   
            }

            $wire.calculateTotalPricesSummation();
        JS;
    }

    #[Js]
    public function calculateTotalPricesSummation(){
        return <<<'JS'
            const totalPricesSummation = document.getElementById('totalPricesSummation'),
                  productsCount = document.getElementById('productsCount').textContent;
            
            let summation = 0;
            for(let i = 0; i < productsCount; i++){
                summation += parseFloat(document.getElementById(`totalPrice${i}`).value);
            }
            totalPricesSummation.textContent = summation.toFixed(2);
        JS;
    }

    #[Js]
    public function handleCreditInputChange()
    {
        return <<<'JS'
            let targetId = event.target.id,
                productId = targetId.substring(
                    targetId.indexOf('-') + 1,
                    targetId.lastIndexOf('-'),
                ),
                dateElement = document.getElementById(`due-date-${productId}`),
                dateInput = document.getElementById(`due-date-${productId}-input`);

            if(event.target.checked){
                // show input
                dateElement.classList.remove('hidden');
                dateElement.classList.add('show');
            } else {
                // reset and hide input
                let date = new Date();
                date.setDate(date.getDate() + 25);

                let month = date.getMonth() + 1,
                    day = date.getDate() < 10 ? '0' + date.getDate() : date.getDate(),
                    nextMonthDate = `${date.getFullYear()}-${
                            (month < 10) ? '0' + (month) : month
                        }-${day}`;
                dateInput.value = nextMonthDate;
                dateElement.classList.remove('show');
                dateElement.classList.add('hidden');
            }
        JS;
    }
}
