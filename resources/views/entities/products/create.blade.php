<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Agregar Producto') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl sm:mx-auto">

                    <!-- Create form -->
                    <form action="{{route('products.store')}}" method="POST">
                        @csrf
                        <!-- Type -->
                        <x-input-label for="type" :value="__('Tipo')" required="true" />
                        <x-select-input id="type" name="type" class="block" required>
                            <option value="">Seleccionar</option>
                            @foreach($types as $type)
                                <option 
                                    value="{{$type->id}}"
                                    @selected(old('type') == $type->id)
                                >{{$type->name}}</option>
                            @endforeach
                        </x-select-input>
                        <a href="{{route('types.index')}}" class="text-blue-400 underline">
                            Administrar Tipos
                        </a>
                        <!-- Name -->
                        <x-input-label for="name" :value="__('Nombre')" required="true" />
                        <x-text-input id="name" name="name" required value="{{old('name')}}" required maxlength="50" placeholder="..." />
                        <!-- Presentation -->
                        <x-input-label for="presentation" :value="__('Presentación')" required="true" />
                        <x-select-input id="presentation" name="presentation" class="block" required>
                            <option value="">Seleccionar</option>
                            @foreach($presentations as $presentation)
                                <option 
                                    value="{{$presentation->id}}"
                                    @selected(old('presentation') == $presentation->id)
                                >{{$presentation->content . 'ml'}}</option>
                            @endforeach
                        </x-select-input>
                        <!-- Error messages -->
                        <x-input-error :messages="$errors->get('type')" />
                        <x-input-error :messages="$errors->get('name')" />
                        <x-input-error :messages="$errors->get('presentation')" />

                        <!-- Minimun stock -->
                        <x-input-label for="minimunStock" :value="__('Stock Mínimo')" required="true" />
                        <x-number-input id="minimunStock" name="minimun_stock" min="1" max="9999" required value="{{old('minimun_stock')}}" />
                        <x-input-error :messages="$errors->get('minimun_stock')" />
                        
                        <!-- Sale prices -->
                        <x-input-label :value="__('Precios de Venta ($)')" required="true" />
                        <x-number-input
                            name="sale_prices[]" min="0.01" step="0.01" max="999" required
                            placeholder="por 1 unidad" value="{{old('sale_prices.0')}}"
                            class="w-1/2 sm:w-1/4 block mb-1"
                        />
                        <x-number-input
                            name="sale_prices[]" min="0.01" step="0.01" max="999"
                            placeholder="por 6 unidades" value="{{old('sale_prices.1')}}"
                            class="w-1/2 sm:w-1/4 block mb-1"
                        />
                        <x-number-input
                            name="sale_prices[]" min="0.01" step="0.01" max="999"
                            placeholder="por 12 unidades" value="{{old('sale_prices.2')}}"
                            class="w-1/2 sm:w-1/4 block mb-1"
                        />
                        <x-input-error :messages="$errors->get('sale_prices')" />
                        @foreach($errors->get('sale_prices.*') as $salePriceErrors)
                            <x-input-error :messages="$salePriceErrors" />
                        @endforeach

                        <!-- Save button -->
                        <x-primary-button class="mt-2">
                            Guardar
                        </x-primary-button>

                        @if($success && !$errors->any())
                            <p class="text-green-400">El producto fue guardado correctamente!</p>
                        @endif
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>