<div>
    <!-- Main label -->
    <x-input-label :value="__('Productos')" />

    @if(count($selectedProducts) > 0)
        <!-- Selected Products -->
        <x-sales.products.selected.table
            :data="compact(
                'selectedProducts',
                'movementTypes',
                'saleType'
            )"
        />

        <!-- Alternative search label -->
        <x-input-label :value="__('Buscar productos')" for="searchProductsInput" />
    @endif

    <!-- Search Products input -->
    <x-text-input
        id="searchProductsInput" wire:model.live.debounce.250ms="search" 
        placeholder="Buscar..." class="mb-3"
    />

    @if($products)
        @if($products->count() > 0)
            <!-- Select Products -->
            <x-sales.products.select.table
                :data="compact('products', 'warehouse')"
            />
        @else
            <p class="text-red-400 mb-3">No se encontraron productos...</p>
        @endif
    @endif

    @script
    <script>
        $wire.on('product-selected', () => {            
            try{ setTimeout($wire.calculateTotalPricesSummation, 100); }catch(e){}
            try{ setTimeout($wire.calculateTotalPricesSummation, 500); }catch(e){}
            try{ setTimeout($wire.calculateTotalPricesSummation, 750); }catch(e){}
            try{ setTimeout($wire.calculateTotalPricesSummation, 1000); }catch(e){}
            try{ setTimeout($wire.calculateTotalPricesSummation, 3000); }catch(e){}
        });
    </script>
    @endscript
</div>
