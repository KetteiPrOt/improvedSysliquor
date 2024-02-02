@props(['products'])

<!-- Table -->
@if($products->count() > 0)
    <table class="border-collapse table-auto w-full text-sm mb-6">
        <thead>
            <tr>
                <th class="border-b dark:border-slate-600 font-medium p-4 pl-4 sm:pl-8 pt-0 pb-3 text-slate-400 dark:text-slate-200 text-left"
                >Producto</th>
                <th class="border-b dark:border-slate-600 font-medium p-4 pl-4 sm:pl-8 pt-0 pb-3 text-slate-400 dark:text-slate-200 text-center"
                >Inventario Iniciado</th>
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
                            <a href="{{route('products.show', $product->id)}}" target="_blank">
                                {{$product->productTag()}}
                            </a>
                        </td>
                        <td class="border-b border-slate-100 text-center dark:border-slate-700 p-2 sm:pr-4 pl-2 sm:pl-8 text-slate-500 dark:text-slate-400">
                            @if($product->started_inventory)
                                <span class="text-green-400">SI</span>
                            @else
                                <span class="text-red-400">No</span>
                            @endif
                        </td>
                        <td>
                            <x-purchases.products.select.pickup-button :id="$product->id" />
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
    <!-- Products Pagination Links -->
    {{ $products->onEachSide(1)->links(data: ['scrollTo' => false]) }}
@else
    <p class="text-red-400 mb-3">No se encontraron productos...</p>
@endif