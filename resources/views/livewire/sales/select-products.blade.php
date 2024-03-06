<div>
    <!-- Main label -->
    <x-input-label :value="__('Productos')" />

    @if(count($selectedProducts) > 0)
        <!-- Selected Products -->
        <x-sales.products.selected.table
            :data="compact(
                'selectedProducts',
                'movementTypes',
                'saleType',
                'warehouse'
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

    <x-input-error :messages="$errors->get('products')" />
    @foreach($errors->get('products.*') as $error)
        <x-input-error :messages="$error" />
    @endforeach

    <x-input-error :messages="$errors->get('amounts')" />
    @foreach($errors->get('amounts.*') as $error)
        <x-input-error :messages="$error" />
    @endforeach

    <x-input-error :messages="$errors->get('sale_prices')" />
    @foreach($errors->get('sale_prices.*') as $error)
        <x-input-error :messages="$error" />
    @endforeach

    <x-input-error :messages="$errors->get('movement_types')" />
    @foreach($errors->get('movement_types.*') as $error)
        <x-input-error :messages="$error" />
    @endforeach

    <x-input-error :messages="$errors->get('comments')" />
    @foreach($errors->get('comments.*') as $error)
        <x-input-error :messages="$error" />
    @endforeach

    <x-input-error :messages="$errors->get('credits')" />
    @foreach($errors->get('credits.*') as $error)
        <x-input-error :messages="$error" />
    @endforeach

    <x-input-error :messages="$errors->get('due_dates')" />
    @foreach($errors->get('due_dates.*') as $error)
        <x-input-error :messages="$error" />
    @endforeach

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
