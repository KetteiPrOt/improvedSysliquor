<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Producto') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl sm:mx-auto">

                    <!-- Create form -->
                    <form action="{{route('products.update', $product->id)}}" method="POST">
                        @csrf
                        @method('put')
                        <!-- Type -->
                        <x-input-label for="type" :value="__('Tipo')" :required="true" />
                        <x-select-input id="type" name="type" class="block" required>
                            <option value="">Selecciona</option>
                            @foreach($types as $type)
                                <option 
                                    value="{{$type->id}}"
                                    @selected(old('type', $product->type->id) == $type->id)
                                >{{$type->name}}</option>
                            @endforeach
                        </x-select-input>
                        <a href="{{route('types.index')}}" class="text-blue-400 underline">
                            Administrar Tipos
                        </a>
                        <!-- Name -->
                        <x-input-label for="name" :value="__('Nombre')" :required="true" />
                        <x-text-input id="name" name="name" required value="{{old('name', $product->name)}}" required maxlength="50" placeholder="..." />
                        <!-- Presentation -->
                        <x-input-label for="presentation" :value="__('Presentación (ml)')" :required="true" />
                        <x-select-input id="presentation" name="presentation" class="block" required>
                            <option value="">Selecciona</option>
                            @foreach($presentations as $presentation)
                                <option 
                                    value="{{$presentation->id}}"
                                    @selected(old('presentation', $product->presentation->id) == $presentation->id)
                                >{{$presentation->content . 'ml'}}</option>
                            @endforeach
                        </x-select-input>
                        <a href="{{route('presentations.index')}}" class="text-blue-400 underline">
                            Administrar Presentaciones
                        </a>
                        <!-- Error messages -->
                        <x-input-error :messages="$errors->get('type')" />
                        <x-input-error :messages="$errors->get('name')" />
                        <x-input-error :messages="$errors->get('presentation')" />

                        <!-- Minimun stock -->
                        <x-input-label for="minimunStock" :value="__('Stock Mínimo')" class="mt-6" :required="true" />
                        <x-number-input id="minimunStock" name="minimun_stock" min="1" max="9999" required value="{{old('minimun_stock', $product->minimun_stock)}}" />
                        <x-input-error :messages="$errors->get('minimun_stock')" />
                        
                        <!-- Sale prices -->
                        <x-input-label :value="__('Precios de Venta ($)')" :required="true" />
                        <x-number-input
                            name="sale_prices[]" min="0.01" step="0.01" max="999" required
                            placeholder="por 1 unidad" class="w-1/2 sm:w-1/4 block mb-1"
                            value="{{old('sale_prices.one', $product->salePrices[0]->price)}}"
                        />
                        <x-number-input
                            name="sale_prices[]" min="0.01" step="0.01" max="999" required
                            placeholder="por 6 unidades" class="w-1/2 sm:w-1/4 block mb-1"
                            value="{{old('sale_prices.six', $product->salePrices[1]->price)}}"
                        />
                        <x-number-input
                            name="sale_prices[]" min="0.01" step="0.01" max="999" required
                            placeholder="por 12 unidades" class="w-1/2 sm:w-1/4 block mb-1"
                            value="{{old('sale_prices.twelve', $product->salePrices[2]->price)}}"
                        />
                        <x-input-error :messages="$errors->get('sale_prices')" />
                        @foreach($errors->get('sale_prices.*') as $salePriceErrors)
                            <x-input-error :messages="$salePriceErrors" />
                        @endforeach

                        <!-- Save button -->
                        <x-primary-button class="mt-6">
                            Guardar
                        </x-primary-button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>