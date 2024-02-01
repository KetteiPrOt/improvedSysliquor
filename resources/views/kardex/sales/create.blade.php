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
                        <!-- Clients -->
                        <x-input-label :value="__('Cliente')" />
                        <x-select-input name="client" class="block" required>
                            @foreach($clients as $client)
                                <option 
                                    value="{{$client->id}}"
                                    @selected(old('client', $finalConsumer->id) == $client->id)
                                >{{$client->person->name}}</option>
                            @endforeach
                        </x-select-input>
                        <x-input-error :messages="$errors->get('client')" />
                        
                        <!-- Select Products -->
                        <livewire:sales.select-products :success="$success" />

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