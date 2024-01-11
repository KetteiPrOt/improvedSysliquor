<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Agregar Compra') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl sm:mx-auto">

                    <!-- Create form -->
                    <form action="{{route('purchases.store')}}" method="POST">
                        @csrf
                        <!-- Providers -->
                        <x-input-label :value="__('Proveedor')" />
                        <x-select-input name="provider" class="block" required>
                            <option value="">Selecciona</option>
                            @foreach($providers as $provider)
                                <option 
                                    value="{{$provider->id}}"
                                    @selected(old('provider') == $provider->id)
                                >{{$provider->person->name}}</option>
                            @endforeach
                        </x-select-input>
                        <x-input-error :messages="$errors->get('provider')" />
                        <!-- Invoice Number --> 
                        <x-input-label :value="__('Número de Factura')" />
                        <x-number-input
                            name="invoice_number[]" min="1" max="999" required class="invoice-number-input" id="invoiceNumberFirstInput"
                            value="{{old('invoice_number.0')}}" placeholder="000"
                        /> <p class="inline">-</p>
                        <x-number-input
                            name="invoice_number[]" min="1" max="999" required class="invoice-number-input" id="invoiceNumberSecondInput"
                            value="{{old('invoice_number.1')}}" placeholder="000"
                        /> <p class="inline">-</p>
                        <x-number-input
                            name="invoice_number[]" min="1" max="999999999" required class="invoice-number-input w-32" id="invoiceNumberThirdInput"
                            value="{{old('invoice_number.2')}}" placeholder="000000000"
                        /> 
                        <x-input-error :messages="$errors->get('invoice_number')" />
                        @foreach($errors->get('invoice_number.*') as $error)
                            <x-input-error :messages="$error" />
                        @endforeach
                        @vite([
                            'resources/js/components/InvoiceNumberInput/main.js',
                            'resources/css/components/InvoiceNumberInput/styles.css',
                        ])
                        <!-- Date -->
                        <x-input-label :value="__('Fecha')" />
                        <x-date-input max="{{date('Y-m-d')}}" name="date" value="{{old('date', date('Y-m-d'))}}" required />
                        <x-input-error :messages="$errors->get('date')" />

                        <!-- Select Products -->
                        <livewire:purchases.select-products />

                        <x-input-error :messages="$errors->get('products')" />
                        @foreach($errors->get('products.*') as $error)
                            <x-input-error :messages="$error" />
                        @endforeach

                        <x-input-error :messages="$errors->get('amounts')" />
                        @foreach($errors->get('amounts.*') as $error)
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
                            <p class="text-green-400">La compra fue guardada correctamente!</p>
                        @endif
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>