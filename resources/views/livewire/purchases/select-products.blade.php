<div>
    <x-input-label :value="__('Productos')" />

    <x-purchases.products.selected.table
        :products="$selectedProducts"
        :movementtypes="$movementTypes"
        :purchasetype="$purchaseType"
        :initialinventorytype="$initialInventoryType"
    />

    <x-text-input wire:model.live.debounce.250ms="search" placeholder="Buscar..." class="mb-3" id="searchProductsInput" />

    @if($products)
        <x-purchases.products.select.table :products="$products" />
    @endif
</div>
