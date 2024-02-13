<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Agregar Venta') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-4xl sm:mx-auto">

                    <!-- Create form -->
                    <form action="{{route('sales.store')}}" method="POST">
                        @csrf

                        <div class="flex flex-col sm:flex-row">
                            <div class="order-2 sm:order-1 w-full sm:w-1/2">
                                <!-- Warehouse -->
                                <x-input-label :value="__('Bodega')" />
                                <x-number-input
                                    class="hidden" name="warehouse"
                                    value="{{$warehouse->id}}"
                                />
                                <x-text-input
                                    class="inline mr-42opacity-70" disabled
                                    value="{{$warehouse->name}}"
                                />
                                <x-secondary-link-button
                                    href="{{route('sales.selectWarehouse')}}"
                                    class="mt-1 sm:mt-0"
                                >
                                    cambiar
                                </x-secondary-link-button>
                            </div>
                            @if($lastSale)
                                @if($warehouse->id == $lastSale->warehouse->id)
                                    <div class="mb-6 sm:mb-0 justify-center order-1 sm:order-2 w-full sm:w-1/2 flex sm:justify-end items-start">
                                        <x-secondary-link-button
                                            :href="route('sales.show', $lastSale->id)"
                                        >
                                            Ver Ultima Venta
                                        </x-secondary-link-button>
                                    </div>
                                @endif
                            @endif
                        </div>

                        <!-- Clients -->
                        <x-input-label :value="__('Cliente')" />
                        <x-select-input name="client" class="block">
                            <option 
                                value=""
                                @selected(is_null(old('client')))
                            >Consumidor Final</option>
                            @foreach($clients as $client)
                                <option 
                                    value="{{$client->id}}"
                                    @selected(old('client') == $client->id)
                                >{{$client->person->name}}</option>
                            @endforeach
                        </x-select-input>
                        <x-input-error :messages="$errors->get('client')" />
                        
                        <!-- Select Products -->
                        <livewire:sales.select-products
                            :warehouse="$warehouse"
                            :success="$success"
                        />

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

                        <!-- Save button -->
                        <x-primary-button>
                            Guardar
                        </x-primary-button>

                        @if($success && !$errors->any())
                            <p class="text-green-400">La venta fue guardada correctamente!</p>
                        @endif
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>