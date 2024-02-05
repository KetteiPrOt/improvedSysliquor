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
@script
<script>
    $wire.on('product-selected', () => {
        try{ setTimeout($wire.syncInvoiceNumberRequirement, 100); }catch(e){}
        try{ setTimeout($wire.syncInvoiceNumberRequirement, 500); }catch(e){}
        try{ setTimeout($wire.syncInvoiceNumberRequirement, 750); }catch(e){}
        try{ setTimeout($wire.syncInvoiceNumberRequirement, 1000); }catch(e){}
        try{ setTimeout($wire.syncInvoiceNumberRequirement, 3000); }catch(e){} 
    });
</script>
@endscript