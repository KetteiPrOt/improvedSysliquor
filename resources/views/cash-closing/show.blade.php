<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Cierre de Caja') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-8xl xl:max-w-8xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="py-4 bg-white shadow sm:rounded-lg">
                <div class="max-w-8xl sm:mx-auto">

                    <div class="flex">
                        <p class="ml-4 mb-2">
                            <strong>Desde:</strong>
                            {{$filters['date_from']}}
                        </p>

                        <p class="ml-4 mb-2">
                            <strong>Hasta:</strong>
                            {{$filters['date_to']}}
                        </p>
                    </div>

                    <div class="flex flex-wrap">
                        @if(isset($filters['warehouse']))
                            <p class="ml-4 mb-2">
                                <strong>Bodega:</strong>
                                {{$filters['warehouse']->name}}
                            </p>
                        @endif

                        @if(isset($filters['seller']))
                            <p class="ml-4 mb-2">
                                <strong>Vendedor:</strong>
                                {{$filters['seller']->person->name}}
                            </p>
                        @endif

                        @if(isset($filters['product']))
                            <p class="ml-4 mb-2">
                                <strong>Producto:</strong>
                                {{$filters['product']->productTag()}}
                            </p>
                        @endif
                    </div>

                    <!-- Primary Table -->
                    <x-table class="hidden lg:table">
                        <x-slot:thead>
                            <x-table.tr>
                                <x-table.th>Fecha</x-table.th>
                                @if(!isset($filters['warehouse']))
                                    <x-table.th>Bodega</x-table.th>
                                @endif
                                @if(!isset($filters['seller']))
                                    <x-table.th>Vendedor</x-table.th>
                                @endif
                                @if(!isset($filters['product']))
                                    <x-table.th>Producto</x-table.th>
                                @endif
                                <x-table.th>Cantidad</x-table.th>
                                <x-table.th>Precio Unitario</x-table.th>
                                <x-table.th>Precio Total</x-table.th>
                                <x-table.th></x-table.th>
                            </x-table.tr>
                        </x-slot:thead>
                        <x-slot:tbody>
                            @foreach($revenues as $revenue)
                                <x-table.tr wire:key="{{$revenue->id}}">
                                    <x-table.td>
                                        {{$revenue->date}}
                                    </x-table.td>
                                    @if(!isset($filters['warehouse']))
                                        <x-table.td>
                                            {{$revenue->warehouse_name}}
                                        </x-table.td>
                                    @endif
                                    @if(!isset($filters['seller']))
                                        <x-table.td>
                                            {{$revenue->seller_name}}
                                        </x-table.td>
                                    @endif
                                    @if(!isset($filters['product']))
                                        <x-table.td>
                                            {{$revenue->product_name}}
                                        </x-table.td>
                                    @endif                                    
                                    <x-table.td>
                                        {{$revenue->amount}}
                                    </x-table.td>
                                    <x-table.td>
                                        {{'$' . $revenue->unitary_price}}
                                    </x-table.td>
                                    <x-table.td>
                                        {{'$' . $revenue->total_price}}
                                    </x-table.td>
                                    <x-table.td>
                                        <a
                                            href="{{route('kardex.showMovement', $revenue->movement_id)}}"
                                            class="text-blue-400 underline"
                                        >
                                            Detalles
                                        </a>
                                    </x-table.td>
                                </x-table.tr>
                            @endforeach
                            @if(
                                $revenues->isNotEmpty()
                                && $revenues->onLastPage()
                            )
                                <x-table.tr>
                                    @if(!isset($filters['warehouse']))
                                        <x-table.td></x-table.td>
                                    @endif
                                    @if(!isset($filters['seller']))
                                        <x-table.td></x-table.td>
                                    @endif
                                    @if(!isset($filters['product']))
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

                    <!-- Responsive Accordion -->
                    <x-table class="lg:hidden">
                        <x-slot:thead></x-slot:thead>
                        <x-slot:tbody>
                            <x-table.tr>
                                <x-table.td class="text-center">
                                    @if(isset($filters['product']))
                                        Cantidad
                                    @else
                                        Producto
                                    @endif
                                </x-table.td>
                                <x-table.td class="text-center">
                                    Total
                                </x-table.td>
                            </x-table.tr>
                        </x-slot:tbody>
                    </x-table>
                    <x-accordion
                        id="accordion"
                        class="block lg:hidden"
                    >
                        @foreach($revenues as $revenue)
                            <x-accordion.item
                                :parentid="__('accordion')"
                                :key="$revenue->id"
                            >
                                <x-slot:heading>
                                    <x-table>
                                        <x-slot:thead></x-slot:thead>
                                        <x-slot:tbody>
                                            <x-table.tr>
                                                <x-table.td>
                                                    @if(isset($filters['product']))
                                                        {{$revenue->amount}}
                                                    @else
                                                        {{
                                                            $revenue->product_name
                                                        }}
                                                    @endif
                                                </x-table.td>
                                                <x-table.td>
                                                    {{
                                                        '$' . $revenue->total_price
                                                    }}
                                                </x-table.td>
                                            </x-table.tr>
                                        </x-slot:tbody>
                                    </x-table>
                                </x-slot:heading>
                                <x-slot:content>
                                    <p>
                                        <strong>Fecha:</strong>
                                        {{$revenue->date}}
                                    </p>
                                    @if(!isset($filters['warehouse']))
                                        <p>
                                            <strong>Bodega:</strong>
                                            {{$revenue->warehouse_name}}
                                        </p>
                                    @endif
                                    @if(!isset($filters['seller']))
                                        <p>
                                            <strong>Vendedor:</strong>
                                            {{$revenue->seller_name}}
                                        </p>
                                    @endif
                                    @if(!isset($filters['product']))
                                        <p>
                                            <strong>Producto:</strong>
                                            {{$revenue->product_name}}
                                        </p>
                                    @endif                                    
                                    <p>
                                        <strong>Cantidad:</strong>
                                        {{$revenue->amount}}
                                    </p>
                                    <p>
                                        <strong>Precio Unitario:</strong>
                                        {{'$' . $revenue->unitary_price}}
                                    </p>
                                    <p>
                                        <strong>Precio Total</strong>
                                        {{'$' . $revenue->total_price}}
                                    </p>
                                    <p>
                                        <a
                                            href="{{route('kardex.showMovement', $revenue->movement_id)}}"
                                            class="text-blue-400 underline"
                                        >
                                            Más Detalles
                                        </a>
                                    </p>
                                </x-slot:content>
                            </x-accordion.item>
                        @endforeach 
                    </x-accordion>
                    @if(
                        $revenues->isNotEmpty()
                        && $revenues->onLastPage()
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

                    @if($revenues->isEmpty())
                        <p class="text-red-600 ml-4 mb-2 mt-2">
                            No se encontraron ventas...
                        </p>
                    @else
                        <div class="text-red-600 ml-4 mr-4 mb-2 mt-2">
                            {{$revenues->onEachSide(1)->links()}}
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>