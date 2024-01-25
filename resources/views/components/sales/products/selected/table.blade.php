@props(['data'])
@php
    extract($data);
@endphp

<span id="productsCount" class="hidden">{{count($selectedProducts)}}</span>
<table class="border-collapse table-auto w-full text-sm mb-6">
    <thead>
        <tr>
            <th class="table-cell lg:hidden border-b dark:border-slate-600 text-lg sm:text-sm font-bold sm:font-medium p-4 pl-4 sm:pl-8 pt-0 pb-3 text-slate-400 dark:text-slate-200 text-center sm:text-left"
            >Productos Seleccionados</th>
            <th class="hidden lg:table-cell border-b dark:border-slate-600 font-medium p-4 pl-4 sm:pl-8 pt-0 pb-3 text-slate-400 dark:text-slate-200 text-center"
            >Seleccionados</th>
            <th class="hidden lg:table-cell border-b dark:border-slate-600 font-medium p-4 pl-4 sm:pl-8 pt-0 pb-3 text-slate-400 dark:text-slate-200 text-center"
            >Cantidad</th>
            <th class="hidden lg:table-cell border-b dark:border-slate-600 font-medium p-4 pl-4 sm:pl-8 pt-0 pb-3 text-slate-400 dark:text-slate-200 text-center"
            >P. Unitario</th>
            <th class="hidden lg:table-cell border-b dark:border-slate-600 font-medium p-4 pl-4 sm:pl-8 pt-0 pb-3 text-slate-400 dark:text-slate-200 text-center"
            >Movimiento</th>
            <th class="hidden lg:table-cell border-b dark:border-slate-600 font-medium p-4 pl-4 sm:pl-8 pt-0 pb-3 text-slate-400 dark:text-slate-200 text-center"
            >Total</th>
            <th class="hidden lg:table-cell border-b dark:border-slate-600 font-medium p-4 pl-4 sm:pl-8 pt-0 pb-3 text-slate-400 dark:text-slate-200 text-center"
            ></th>
        </tr>
    </thead>
    <tbody class="bg-white dark:bg-slate-800" id="selectedProductsTableBody">
        <!-- Product rows -->
        @if($selectedProducts)
            @foreach($selectedProducts as $key => $product)
                <tr wire:key="{{$product->id}}" class="grid grid-cols-4 grid-rows-3 lg:table-row">
                    <td class="row-span-1 col-span-4 text-lg sm:text-sm text-center lg:text-left font-bold sm:font-medium border-b border-slate-100 dark:border-slate-700 p-2 sm:pr-4 pl-2 sm:pl-8 text-slate-500 dark:text-slate-400">
                        <input
                            type="number" hidden
                            name="products[{{$key}}]"
                            value="{{$product->id}}"
                        >
                        {{$product->productTag()}}
                    </td>
                    <td class="col-span-2 flex flex-col lg:table-cell border-b border-slate-100 text-center dark:border-slate-700 p-2 sm:pr-4 pl-2 sm:pl-8 text-slate-500 dark:text-slate-400">
                        <p class="block lg:hidden">Cantidad</p>
                        <x-number-input
                            name="amounts[{{$key}}]" min="1" max="{{
                                $product->movements()
                                    ->orderBy('id', 'desc')
                                    ->first()->balance->amount
                            }}" required 
                            value="{{old('amounts.'.$key, 1)}}"
                            id="amount{{$key}}" class="without-arrows-number-input"
                            wire:keyup="syncInputs()"
                        />
                    </td>
                    <td class="col-span-2 flex flex-col lg:table-cell border-b border-slate-100 text-center dark:border-slate-700 p-2 sm:pr-4 pl-2 sm:pl-8 text-slate-500 dark:text-slate-400">
                        <p class="block lg:hidden">P. Unitario</p>
                        <x-select-input
                            name="sale_prices[]" class="block" required
                            wire:change="updateTotalPrice()"
                            id="unitaryPrices{{$key}}"
                        >
                            @foreach($product->salePrices as $salePriceKey => $salePrice)
                                <option
                                    @if($salePriceKey > 0) hidden @endif
                                    id="{{
                                        'salePrice'
                                        .$salePrice->unitsNumber->units
                                        .'Units'.$key.'Product'
                                    }}"
                                    value="{{$salePrice->id}}"
                                >{{$salePrice->price}}</option>
                            @endforeach
                        </x-select-input>
                    </td>
                    <td class="col-span-2 flex flex-col lg:table-cell border-b border-slate-100 text-center dark:border-slate-700 p-2 sm:pr-4 pl-2 sm:pl-8 text-slate-500 dark:text-slate-400">
                        <p class="block lg:hidden">Tipo</p>
                        @if($product->started_inventory)
                            <x-select-input name="movement_types[]" class="block" required>
                                @foreach($movementTypes as $movementType)
                                    <option 
                                        value="{{$movementType->id}}"
                                        @selected(old('movement_types.'.$key, $saleType->id) == $movementType->id)
                                    >{{$movementType->name}}</option>
                                @endforeach
                            </x-select-input>
                        @else
                            <x-select-input name="movement_types[]" class="block" required>
                                <option 
                                    value="{{$initialInventoryType->id}}" selected
                                >{{$initialInventoryType->name}}</option>
                            </x-select-input>
                        @endif
                    </td>
                    <td class="col-span-2 flex flex-col lg:table-cell border-b border-slate-100 text-center dark:border-slate-700 p-2 sm:pr-4 pl-2 sm:pl-8 text-slate-500 dark:text-slate-400">
                        <p class="block lg:hidden">Total</p>
                        <x-number-input
                            disabled id="{{'totalPrice'.$key}}"
                            value="{{$product->salePrices[0]->price}}"
                        />
                    </td>
                    <td class="col-span-4 flex flex-col items-center justify-center lg:table-cell border-b border-slate-100 text-center dark:border-slate-700 p-2 sm:pr-4 pl-2 sm:pl-8 text-slate-500 dark:text-slate-400">
                        <p class="block lg:hidden">Remover</p>
                        <x-secondary-button wire:click="dropProduct({{$product->id}})" class="text-red-600 hover:bg-red-600 hover:text-white">
                            X
                        </x-secondary-button>
                    </td>
                </tr>
            @endforeach
            <tr>
                <td class="hidden lg:table-cell text-lg sm:text-sm text-center lg:text-left font-bold sm:font-medium border-b border-slate-100 dark:border-slate-700 p-2 sm:pr-4 pl-2 sm:pl-8 text-slate-500 dark:text-slate-400">
                    
                </td>
                <td class="hidden lg:table-cell text-lg sm:text-sm text-center lg:text-left font-bold sm:font-medium border-b border-slate-100 dark:border-slate-700 p-2 sm:pr-4 pl-2 sm:pl-8 text-slate-500 dark:text-slate-400">
                    
                </td>
                <td class="hidden lg:table-cell text-lg sm:text-sm text-center lg:text-left font-bold sm:font-medium border-b border-slate-100 dark:border-slate-700 p-2 sm:pr-4 pl-2 sm:pl-8 text-slate-500 dark:text-slate-400">
                    
                </td>
                <td class="block lg:table-cell text-lg sm:text-sm text-center font-bold sm:font-medium border-b border-slate-100 dark:border-slate-700 p-2 sm:pr-4 pl-2 sm:pl-8 text-slate-500 dark:text-slate-400">
                    Total a pagar: $<span id="totalPricesSummation">???</span>
                    <br>
                    <x-sales.products.selected.reload-button />
                </td>
            </tr>
        @endif
    </tbody>
</table>
