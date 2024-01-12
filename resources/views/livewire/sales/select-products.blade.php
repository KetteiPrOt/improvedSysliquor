<div>
    <x-input-label :value="__('Productos')" />

    <x-sales.products.selected.table
        :products="$selectedProducts"
        :movementtypes="$movementTypes"
        :saletype="$saleType"
    />

    <x-text-input id="searchProductsInput" wire:model.live.debounce.250ms="search" placeholder="Buscar..." class="mb-3" />
    @if($products)
        <x-sales.products.select.table :products="$products" />
    @endif
</div>
