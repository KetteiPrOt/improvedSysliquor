@props(['products'])

<!-- Table -->
@if($products->count() > 0)
    <table class="border-collapse table-auto w-full text-sm mb-6">
        <thead>
            <tr>
                <th class="border-b dark:border-slate-600 font-medium p-4 pl-4 sm:pl-8 pt-0 pb-3 text-slate-400 dark:text-slate-200 text-left"
                >Producto</th>
                <th class="border-b dark:border-slate-600 font-medium p-4 pl-4 sm:pl-8 pt-0 pb-3 text-slate-400 dark:text-slate-200 text-center"
                >Unidades Disponibles</th>
                <th class="border-b dark:border-slate-600 font-medium p-4 pl-4 sm:pl-8 pt-0 pb-3 text-slate-400 dark:text-slate-200 text-center"
                ></th>
            </tr>
        </thead>
        <tbody class="bg-white dark:bg-slate-800">
            <!-- Product rows -->
            @if($products)
                @foreach($products as $product)
                    <tr wire:key="{{$product->id}}">
                        <td class="border-b border-slate-100 dark:border-slate-700 p-2 sm:pr-4 pl-2 sm:pl-8 text-slate-500 dark:text-slate-400">
                            <a
                                @if(auth()->user()->can('products'))
                                    href="{{route('products.show', $product->id)}}" target="_blank"
                                @endif
                            >
                                {{$product->productTag()}}
                            </a>
                        </td>
                        @php
                            if($product->started_inventory){
                                $unitsAvailable = $product->movements()
                                        ->orderBy('id', 'desc')
                                        ->first()->balance->amount;
                            } else {
                                $unitsAvailable = 0;
                            }
                        @endphp
                        <td class="border-b border-slate-100 text-center dark:border-slate-700 p-2 sm:pr-4 pl-2 sm:pl-8 text-slate-500 dark:text-slate-400">
                            <span
                                class="{{$unitsAvailable > 0 ? 'text-green-400' : 'text-red-400'}}"
                            >
                                {{$unitsAvailable}}
                            </span>
                        </td>
                        <td>
                            <x-sales.products.select.pickup-button
                                :id="$product->id"
                                :disabled="$unitsAvailable == 0"
                            />
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
    <!-- Products Pagination Links -->
    {{ $products->onEachSide(1)->links() }}
@else
    <p>No se encontraron productos...</p>
@endif