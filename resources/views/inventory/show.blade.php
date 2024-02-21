<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ver Inventario') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-8xl xl:max-w-8xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="py-4 bg-white shadow sm:rounded-lg">
                <div class="max-w-8xl sm:mx-auto">

                    <div class="p-2">
                        <p>
                            <strong>Bodega:</strong>
                            @if(isset($filters['warehouse']))
                                {{$filters['warehouse']->name}}
                            @else
                                Todas
                            @endif
                        </p>
                        <p>
                            <strong>Fecha:</strong>
                            @if(isset($filters['date']))
                                {{
                                    date('d/m/Y', strtotime($filters['date']))
                                }}
                            @else
                                {{date('d/m/Y')}}
                            @endif
                        </p>
                        <p>
                            <strong>Hora:</strong>
                            @if(isset($filters['date']))
                                23:59:59
                            @else
                                {{date('H:i:s')}}
                            @endif
                        </p>
                    </div>

                    {{-- Primary Table --}}
                    <x-table class="hidden lg:table">
                        <x-slot:thead>
                            <x-table.tr :hover="false">
                                <x-table.th>
                                    <a
                                        href="{{request()->fullUrlWithQuery([
                                            'orderBy' => 'product_name',
                                            'order' => !$orderBy['order']
                                        ])}}"
                                        class="flex justify-between w-full h-full hover:bg-slate-100 rounded"
                                    >
                                        Producto
                                        @if($orderBy['column'] != 'product_name')
                                            <x-icons.order
                                                class="w-5 h-5"
                                            />
                                        @else
                                            @if($orderBy['order'])
                                                <x-icons.order.ascending
                                                    class="w-5 h-5"
                                                />
                                            @else
                                                <x-icons.order.descending
                                                    class="w-5 h-5"
                                                />
                                            @endif
                                        @endif
                                    </a>
                                </x-table.th>
                                @if($products->first()?->status)
                                    <x-table.th>
                                        <a
                                            href="{{request()->fullUrlWithQuery([
                                                'orderBy' => 'status',
                                                'order' => !$orderBy['order']
                                            ])}}"
                                            class="flex justify-between w-full h-full hover:bg-slate-100 rounded"
                                        >
                                            Estado
                                            @if($orderBy['column'] != 'status')
                                                <x-icons.order
                                                    class="w-5 h-5"
                                                />
                                            @else
                                                @if($orderBy['order'])
                                                    <x-icons.order.ascending
                                                        class="w-5 h-5"
                                                    />
                                                @else
                                                    <x-icons.order.descending
                                                        class="w-5 h-5"
                                                    />
                                                @endif
                                            @endif
                                        </a>
                                    </x-table.th>
                                    <x-table.th>
                                        Stock Mínimo
                                    </x-table.th>
                                @endif
                                <x-table.th>
                                    <a
                                        href="{{request()->fullUrlWithQuery([
                                            'orderBy' => 'amount',
                                            'order' => !$orderBy['order']
                                        ])}}"
                                        class="flex justify-between w-full h-full hover:bg-slate-100 rounded"
                                    >
                                        Cantidad
                                        @if($orderBy['column'] != 'amount')
                                            <x-icons.order
                                                class="w-5 h-5"
                                            />
                                        @else
                                            @if($orderBy['order'])
                                                <x-icons.order.ascending
                                                    class="w-5 h-5"
                                                />
                                            @else
                                                <x-icons.order.descending
                                                    class="w-5 h-5"
                                                />
                                            @endif
                                        @endif
                                    </a>
                                </x-table.th>
                                <x-table.th>
                                    Precio Unitario
                                </x-table.th>
                                <x-table.th>
                                    <a
                                        href="{{request()->fullUrlWithQuery([
                                            'orderBy' => 'total_price',
                                            'order' => !$orderBy['order']
                                        ])}}"
                                        class="flex justify-between w-full h-full hover:bg-slate-100 rounded"
                                    >
                                        Precio Total
                                        @if($orderBy['column'] != 'total_price')
                                            <x-icons.order
                                                class="w-5 h-5"
                                            />
                                        @else
                                            @if($orderBy['order'])
                                                <x-icons.order.ascending
                                                    class="w-5 h-5"
                                                />
                                            @else
                                                <x-icons.order.descending
                                                    class="w-5 h-5"
                                                />
                                            @endif
                                        @endif
                                    </a>
                                </x-table.th>
                            </x-table.tr>
                        </x-slot:thead>
                        <x-slot:tbody>
                            @foreach($products as $product)
                                <x-table.tr
                                    danger="{{$product->status == 'Bajo'}}"
                                >
                                    <x-table.td>
                                        {{$product->product_name}}
                                    </x-table.td>
                                    @if($product->status)
                                        <x-table.td>
                                            {{$product->status}}
                                        </x-table.td>
                                        <x-table.td>
                                            {{$product->minimun_stock}}
                                        </x-table.td>
                                    @endif
                                    <x-table.td>
                                        {{$product->amount}}
                                    </x-table.td>
                                    <x-table.td>
                                        {{$product->unitary_price}}
                                    </x-table.td>
                                    <x-table.td>
                                        {{$product->total_price}}
                                    </x-table.td>
                                </x-table.tr>
                            @endforeach
                            @if(
                                $products->isNotEmpty()
                                && $products->onLastPage()
                            )
                                <x-table.tr>
                                    @if($products->first()?->status)
                                        <x-table.td></x-table.td>
                                        <x-table.td></x-table.td>
                                    @endif
                                    <x-table.td></x-table.td>
                                    <x-table.td></x-table.td>
                                    <x-table.td>
                                        <strong>Total:</strong>
                                    </x-table.td>
                                    <x-table.td>
                                        <strong>{{$total_prices_summation}}</strong>
                                    </x-table.td>
                                </x-table.tr>
                            @endif
                        </x-slot:tbody>
                    </x-table>

                    {{-- Responsive Accordion --}}
                    <x-table class="lg:hidden">
                        <x-slot:thead></x-slot:thead>
                        <x-slot:tbody>
                            <x-table.tr :hover="false">
                                <x-table.td>
                                    {{-- Status --}}
                                    @if($products->first()?->status)
                                        <a
                                            href="{{request()->fullUrlWithQuery([
                                                'orderBy' => 'status',
                                                'order' => !$orderBy['order']
                                            ])}}"
                                            class="flex justify-between w-full h-full hover:bg-slate-100 rounded"
                                        >
                                            Estado
                                            @if($orderBy['column'] != 'status')
                                                <x-icons.order
                                                    class="w-5 h-5"
                                                />
                                            @else
                                                @if($orderBy['order'])
                                                    <x-icons.order.ascending
                                                        class="w-5 h-5"
                                                    />
                                                @else
                                                    <x-icons.order.descending
                                                        class="w-5 h-5"
                                                    />
                                                @endif
                                            @endif
                                        </a>
                                    @else
                                        Estado
                                    @endif
                                </x-table.td>
                                <x-table.td>
                                    {{-- Total Price --}}
                                    <a
                                        href="{{request()->fullUrlWithQuery([
                                            'orderBy' => 'total_price',
                                            'order' => !$orderBy['order']
                                        ])}}"
                                        class="flex justify-center w-full h-full hover:bg-slate-100 rounded"
                                    >
                                        Precio Total
                                        @if($orderBy['column'] != 'total_price')
                                            <x-icons.order
                                                class="w-5 h-5"
                                            />
                                        @else
                                            @if($orderBy['order'])
                                                <x-icons.order.ascending
                                                    class="w-5 h-5"
                                                />
                                            @else
                                                <x-icons.order.descending
                                                    class="w-5 h-5"
                                                />
                                            @endif
                                        @endif
                                    </a>
                                </x-table.td>
                            </x-table.tr>
                            <x-table.tr :hover="false">
                                <x-table.td class="text-center">
                                    {{-- Product --}}
                                    <a
                                        href="{{request()->fullUrlWithQuery([
                                            'orderBy' => 'product_name',
                                            'order' => !$orderBy['order']
                                        ])}}"
                                        class="flex justify-between w-full h-full hover:bg-slate-100 rounded"
                                    >
                                        Producto
                                        @if($orderBy['column'] != 'product_name')
                                            <x-icons.order
                                                class="w-5 h-5"
                                            />
                                        @else
                                            @if($orderBy['order'])
                                                <x-icons.order.ascending
                                                    class="w-5 h-5"
                                                />
                                            @else
                                                <x-icons.order.descending
                                                    class="w-5 h-5"
                                                />
                                            @endif
                                        @endif
                                    </a>
                                </x-table.td>
                                <x-table.td class="text-center">
                                    {{-- Amount --}}
                                    <a
                                        href="{{request()->fullUrlWithQuery([
                                            'orderBy' => 'amount',
                                            'order' => !$orderBy['order']
                                        ])}}"
                                        class="flex justify-center w-full h-full hover:bg-slate-100 rounded"
                                    >
                                        Cantidad
                                        @if($orderBy['column'] != 'amount')
                                            <x-icons.order
                                                class="w-5 h-5"
                                            />
                                        @else
                                            @if($orderBy['order'])
                                                <x-icons.order.ascending
                                                    class="w-5 h-5"
                                                />
                                            @else
                                                <x-icons.order.descending
                                                    class="w-5 h-5"
                                                />
                                            @endif
                                        @endif
                                    </a>
                                </x-table.td>
                            </x-table.tr>
                        </x-slot:tbody>
                    </x-table>
                    <x-accordion
                        id="accordion"
                        class="block lg:hidden"
                    >
                        @foreach($products as $product)
                            <x-accordion.item
                                :parentid="__('accordion')"
                                :key="$product->id"
                            >
                                <x-slot:heading>
                                    <x-table>
                                        <x-slot:thead></x-slot:thead>
                                        <x-slot:tbody>
                                            <x-table.tr 
                                                danger="{{$product->status == 'Bajo'}}"
                                            >
                                                <x-table.td>
                                                    {{$product->product_name}}
                                                </x-table.td>
                                                <x-table.td>
                                                    {{$product->amount}}
                                                </x-table.td>
                                            </x-table.tr>
                                        </x-slot:tbody>
                                    </x-table>
                                </x-slot:heading>
                                <x-slot:content>
                                    @if($product->status)
                                        <p>
                                            <strong>Stock Mínimo:</strong>
                                            {{$product->minimun_stock}}
                                        </p>
                                    @endif
                                    <p>
                                        <strong>Cantidad:</strong>
                                        {{$product->amount}}
                                    </p>
                                    <p>
                                        <strong>Precio Unitario:</strong>
                                        {{$product->unitary_price}}
                                    </p>
                                    <p>
                                        <strong>Precio Total:</strong>
                                        {{$product->total_price}}
                                    </p>
                                </x-slot:content>
                            </x-accordion.item>
                        @endforeach
                    </x-accordion>
                    @if(
                        $products->isNotEmpty()
                        && $products->onLastPage()
                    )
                        <x-table class="lg:hidden">
                            <x-slot:thead></x-slot:thead>
                            <x-slot:tbody>
                                <x-table.tr>
                                    <x-table.td class="text-center">
                                        Total:
                                    </x-table.td>
                                    <x-table.td class="text-center">
                                        {{$total_prices_summation}}
                                    </x-table.td>
                                </x-table.tr>
                            </x-slot:tbody>
                        </x-table>
                    @endif
                    @if($products->isEmpty())
                        <p class="text-red-500 p-2">No se encontraron registros...</p>
                    @else
                        <div class="p-2">
                            {{$products->onEachSide(2)->links()}}
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>