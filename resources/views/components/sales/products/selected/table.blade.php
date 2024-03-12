@props(['data'])
@php
    extract($data);
@endphp

<span id="productsCount" class="hidden">{{count($selectedProducts)}}</span>

<table class="border-collapse table-auto w-full text-sm mb-6">
    <thead>
        <tr>
            <th 
                class="
                    text-lg lg:text-sm font-bold lg:font-medium
                    border-b p-4 pl-4 sm:pl-8 pt-0 pb-3 text-slate-400 text-center
                "
            >Seleccionados</th>
            <th class="hidden lg:table-cell border-b font-medium p-4 pl-4 sm:pl-8 pt-0 pb-3 text-slate-400 text-center"
            >Cantidad</th>
            <th class="hidden lg:table-cell border-b font-medium p-4 pl-4 sm:pl-8 pt-0 pb-3 text-slate-400 text-center"
            >P. Unitario</th>
            <th class="hidden lg:table-cell border-b font-medium p-4 pl-4 sm:pl-8 pt-0 pb-3 text-slate-400 text-center"
            >Movimiento</th>
            <th class="hidden lg:table-cell border-b font-medium p-4 pl-4 sm:pl-8 pt-0 pb-3 text-slate-400 text-center"
            >Total</th>
            <th class="hidden lg:table-cell border-b font-medium p-4 pl-4 sm:pl-8 pt-0 pb-3 text-slate-400 text-center"
            ></th>
            <th class="hidden lg:table-cell border-b font-medium p-4 pl-4 sm:pl-8 pt-0 pb-3 text-slate-400 text-center"
            ></th>
        </tr>
    </thead>
    <tbody class="bg-white dark:bg-slate-800" id="selectedProductsTableBody">
        <!-- Product rows -->
        @if($selectedProducts)
            @foreach($selectedProducts as $key => $product)
                <tr wire:key="{{$product->id}}" class="grid grid-cols-4 grid-rows-3 lg:table-row">
                    {{-- Name --}}
                    <td class="row-span-1 col-span-4 text-lg sm:text-sm text-center lg:text-left font-bold sm:font-medium border-b border-slate-100 dark:border-slate-700 p-2 sm:pr-4 pl-2 sm:pl-8 text-slate-500 dark:text-slate-400">
                        <input
                            type="number" hidden
                            name="products[]"
                            value="{{$product->id}}"
                        >
                        {{$product->productTag()}}
                    </td>
                    {{-- Amount Input --}}
                    <td class="col-span-2 flex flex-col lg:table-cell border-b border-slate-100 text-center dark:border-slate-700 p-2 sm:pr-4 pl-2 sm:pl-8 text-slate-500 dark:text-slate-400">
                        <p class="block lg:hidden">Cantidad</p>
                        <x-number-input
                            name="amounts[]" min="1" max="{{
                                $product->warehousesExistences()
                                        ->where('warehouse_id', $warehouse->id)
                                        ->first()->amount
                            }}" required 
                            value="{{old('amounts.'.$key, 1)}}"
                            id="amount{{$key}}" class="without-arrows-number-input"
                            wire:keyup="syncCountAndTotal()"
                        />
                    </td>
                    {{-- Unitary Price Input --}}
                    <td class="col-span-2 flex flex-col lg:table-cell border-b border-slate-100 text-center dark:border-slate-700 p-2 sm:pr-4 pl-2 sm:pl-8 text-slate-500 dark:text-slate-400">
                        <p class="block lg:hidden">P. Unitario</p>
                        <x-select-input
                            name="sale_prices[]" class="block" required
                            wire:change="syncUnitaryPriceAndTotal()"
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
                                >${{$salePrice->price}}</option>
                            @endforeach
                        </x-select-input>
                    </td>
                    {{-- Movement Type Input --}}
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
                    {{-- Total --}}
                    <td class="col-span-2 flex flex-col lg:table-cell border-b border-slate-100 text-center dark:border-slate-700 p-2 sm:pr-4 pl-2 sm:pl-8 text-slate-500 dark:text-slate-400">
                        <p class="block lg:hidden">Total</p>
                        <div class="flex items-center">
                            <span class="w-1/12 mr-1">$</span>
                            <x-number-input
                                disabled id="{{'totalPrice'.$key}}"
                                value="{{$product->salePrices[0]->price}}"
                                class="w-11/12"
                            />
                        </div>
                    </td>
                    {{-- Note Button --}}
                    <td
                        class="
                            col-span-2
                            flex flex-col items-center justify-center lg:table-cell 
                            text-center text-slate-500 border-b border-slate-100
                            p-2 sm:pr-4 pl-2 sm:pl-8
                        "
                    >
                        <p class="block lg:hidden">
                            Nota
                        </p>
                        <x-secondary-button
                            x-data=""
                            x-on:click.prevent="$dispatch('open-modal', 'product-{{$product->id}}-note')"
                        >
                            <x-icons.note
                                class="w-5 h-5"
                            />
                        </x-secondary-button>
                        <x-modal name="product-{{$product->id}}-note" focusable>
                            <div class="p-5 text-left">
                                <x-input-label>
                                    Comentario
                                </x-input-label>
                                <x-textarea-input
                                    name="comments[]"
                                    class="w-full"
                                    placeholder="..."
                                />
                                <div
                                    class="flex items-center mt-4"
                                >
                                    <input
                                        class="rounded"
                                        id="credit-{{$product->id}}-input"
                                        name="credits[{{$product->id}}]"
                                        type="checkbox"
                                        wire:change="handleCreditInputChange()"
                                    />
                                    <label
                                        class="ml-2 text-md font-normal text-black"
                                        for="credit-{{$product->id}}-input"
                                    >
                                        Compra a crédito
                                    </label>
                                </div>
                                <div
                                    id="due-date-{{$product->id}}"
                                    class="mt-4 hidden"
                                >
                                    <x-input-label>
                                        Fecha de vencimiento
                                    </x-input-label>
                                    @php
                                        $nextMonthTime = 
                                            strtotime('now') + (60 * 60 * 24 * 25);
                                    @endphp
                                    <x-date-input
                                        id="due-date-{{$product->id}}-input"
                                        name="due_dates[{{$product->id}}]"
                                        value="{{date('Y-m-d', $nextMonthTime)}}"
                                    />
                                    <p class="text-red-500">
                                        No puede ser mayor al {{
                                            date(
                                                'd/m/Y', 
                                                strtotime('now') + (60 * 60 * 24 * 30)
                                            )
                                        }} (30 días).
                                    </p>
                                </div>
                            </div>
                        </x-modal>
                    </td>
                    {{-- Remove Button --}}
                    <td 
                        class="
                            col-span-2
                            flex flex-col items-center justify-center lg:table-cell border-b border-slate-100 text-center dark:border-slate-700 p-2 sm:pr-4 pl-2 sm:pl-8 text-slate-500 dark:text-slate-400
                        "
                    >
                        <p class="block lg:hidden">Remover</p>
                        <x-secondary-button wire:click="dropProduct({{$product->id}})" class="text-red-600 hover:bg-red-600 hover:text-white">
                            X
                        </x-secondary-button>
                    </td>
                </tr>
            @endforeach
            <tr>
                <td class="hidden lg:table-cell text-lg sm:text-sm text-center lg:text-left font-bold sm:font-medium border-b border-slate-100 dark:border-slate-700 p-2 sm:pr-4 pl-2 sm:pl-8 text-slate-500 dark:text-slate-400"></tr>
                <td class="hidden lg:table-cell text-lg sm:text-sm text-center lg:text-left font-bold sm:font-medium border-b border-slate-100 dark:border-slate-700 p-2 sm:pr-4 pl-2 sm:pl-8 text-slate-500 dark:text-slate-400"></tr>
                <td class="hidden lg:table-cell text-lg sm:text-sm text-center lg:text-left font-bold sm:font-medium border-b border-slate-100 dark:border-slate-700 p-2 sm:pr-4 pl-2 sm:pl-8 text-slate-500 dark:text-slate-400"></tr>               
                <td class="hidden lg:table-cell text-lg sm:text-sm text-center lg:text-left font-bold sm:font-medium border-b border-slate-100 dark:border-slate-700 p-2 sm:pr-4 pl-2 sm:pl-8 text-slate-500 dark:text-slate-400"></tr>
                <td class="block lg:table-cell text-lg sm:text-sm text-center font-bold sm:font-medium border-b border-slate-100 dark:border-slate-700 p-2 sm:pr-4 pl-2 sm:pl-8 text-slate-500 dark:text-slate-400">
                    Total a pagar: $<span id="totalPricesSummation">???</span>
                </td>
                <td class="hidden lg:table-cell text-lg sm:text-sm text-center lg:text-left font-bold sm:font-medium border-b border-slate-100 dark:border-slate-700 p-2 sm:pr-4 pl-2 sm:pl-8 text-slate-500 dark:text-slate-400"></tr>
        @endif
    </tbody>
</table>