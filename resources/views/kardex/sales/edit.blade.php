<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Movimiento') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl sm:mx-auto">

                    <!-- Create form -->
                    <form action="{{route('sales.update', $movement->id)}}" method="POST">
                        @csrf
                        @method('put')

                        <input
                            type="number"
                            class="hidden"
                            value="{{$warehouseId}}"
                            name="warehouse"
                        >
                        @php
                            $unitsAvailable = $warehousesExistence->amount + $movement->amount;
                        @endphp
                        <x-input-label for="amount" :value="__(
                            'Cantidad (hay ' .
                            $unitsAvailable .
                            ' unidades disponibles)'
                        )" />
                        <x-number-input
                            id="amount" name="amount" min="1" max="{{$unitsAvailable}}" required 
                            value="{{old('amount', $movement->amount)}}"
                        />
                        <x-input-error :messages="$errors->get('amount')" />

                        <x-input-label :value="__('Precio Unitario')" class="mt-6" />
                        <x-select-input
                            name="sale_price" class="block" required
                        >
                            @foreach($movement->product->salePrices as $salePriceKey => $salePrice)
                                <option
                                    value="{{$salePrice->id}}"
                                    id="salePrice{{$salePriceKey}}"
                                >${{$salePrice->price}}</option>
                            @endforeach
                        </x-select-input>
                        <x-input-error :messages="$errors->get('sale_price')" />

                        <script type="text/javascript">
                            const salePrices = {
                                one: document.getElementById('salePrice0'),
                                six: document.getElementById('salePrice1'),
                                twelve: document.getElementById('salePrice2')
                            };
                            const amount = document.getElementById('amount');
                            const updateSalePrices = () => {
                                if(amount.value >= 6){
                                    salePrices.six.disabled = false;
                                } else {
                                    salePrices.six.checked = false;
                                    salePrices.six.disabled = true;
                                }
                                if(amount.value >= 12){
                                    salePrices.twelve.disabled = false;
                                } else {
                                    salePrices.twelve.checked = false;
                                    salePrices.twelve.disabled = true;
                                }
                            }
                            amount.addEventListener('keyup', updateSalePrices);
                            updateSalePrices();
                        </script>

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