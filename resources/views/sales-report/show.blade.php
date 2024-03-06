<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ver reporte de ventas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-3 space-y-6">
            <div class="p-4 sm:p-6 bg-white shadow sm:rounded-lg">
                <div class="sm:mx-auto">

                    <x-input-error
                        :messages="$errors->get('order')"
                    />
                    
                    <x-input-error
                        :messages="$errors->get('column')"
                    />

                    <x-card-frame
                        class="mb-6"
                    >
                        <x-card-item>
                            <strong>Desde:</strong>
                            {{$filters['date_from']}}
                            <br>
                            <strong>Hasta:</strong>
                            {{$filters['date_to']}}
                        </x-card-item>
                        @if(
                            $filters['warehouse'] || $filters['seller'] || $filters['product']
                        )
                            <x-card-title>
                                Filtros
                            </x-card-title>
                            <x-card-item>
                                @if($filters['warehouse'])
                                <p>
                                    <strong>Bodega:</strong> {{$filters['warehouse'] ?? 'Todas'}}
                                </p>
                                @endif
                                @if($filters['seller'])
                                <p>
                                    <strong>Vendedor:</strong> <br class="sm:hidden">
                                    {{$filters['seller'] ?? 'Todos'}}
                                </p>
                                @endif
                                @if($filters['product'])
                                <p>
                                    <strong>Producto:</strong> <br class="sm:hidden">
                                    {{$filters['product'] ?? 'Todos'}}
                                </p>
                                @endif
                            </x-card-item>  
                        @endif
                        <x-card-item>
                            <p>
                                <strong>Tipo de reporte:</strong> <br class="sm:hidden">
                                {{
                                    $filters['report_type'] == 'cashSales' 
                                        ? 'Ventas de contado'
                                        : 'Ventas a crédito'
                                }}
                            </p>
                            <p>
                                <strong>Fecha y hora de consulta:</strong> <br class="sm:hidden">
                                {{date('d/m/Y H:i')}}
                            </p>
                        </x-card-item>
                    </x-card-frame>

                    <x-table>
                        <x-slot:thead>
                            <x-table.tr :hover="false">
                                <x-table.th class="hidden lg:table-cell">
                                    <x-table.order-link
                                        name="id"
                                        :column="$filters['column']"
                                        :order="$filters['order']"
                                    >
                                        ID
                                    </x-table.order-link>
                                </x-table.th>
                                <x-table.th>
                                    <x-table.order-link
                                        name="date"
                                        :column="$filters['column']"
                                        :order="$filters['order']"
                                    >
                                        Fecha
                                    </x-table.order-link>
                                </x-table.th>
                                <x-table.th class="hidden lg:table-cell">
                                    <x-table.order-link
                                        name="client_name"
                                        :column="$filters['column']"
                                        :order="$filters['order']"
                                    >
                                        Cliente
                                    </x-table.order-link>
                                </x-table.th>
                                @if($filters['product'])
                                    <x-table.th class="lg:hidden">
                                        <x-table.order-link
                                            name="client_name"
                                            :column="$filters['column']"
                                            :order="$filters['order']"
                                        >
                                            Cliente
                                        </x-table.order-link>
                                    </x-table.th>
                                @else
                                    <x-table.th>
                                        <x-table.order-link
                                            name="product_name"
                                            :column="$filters['column']"
                                            :order="$filters['order']"
                                        >
                                            Producto
                                        </x-table.order-link>
                                    </x-table.th>
                                @endif
                                <x-table.th class="hidden lg:table-cell">
                                    <x-table.order-link
                                        name="amount"
                                        :column="$filters['column']"
                                        :order="$filters['order']"
                                    >
                                        Cantidad
                                    </x-table.order-link>
                                </x-table.th>
                                <x-table.th class="hidden lg:table-cell">
                                    <x-table.order-link
                                        name="unitary_price"
                                        :column="$filters['column']"
                                        :order="$filters['order']"
                                    >
                                        P. Unitario
                                    </x-table.order-link>
                                </x-table.th>
                                <x-table.th class="hidden lg:table-cell">
                                    <x-table.order-link
                                        name="total_price"
                                        :column="$filters['column']"
                                        :order="$filters['order']"
                                    >
                                        P. Total
                                    </x-table.order-link>
                                </x-table.th>
                                @if($filters['report_type'] == 'creditSales')
                                    <x-table.th class="hidden lg:table-cell">
                                        <x-table.order-link
                                            name="due_date"
                                            :column="$filters['column']"
                                            :order="$filters['order']"
                                        >
                                            Vencimiento
                                        </x-table.order-link>
                                    </x-table.th>
                                @endif
                                <x-table.th>
                                    Detalles
                                </x-table.th>
                            </x-table.tr>
                        </x-slot:thead>
                        <x-slot:tbody>
                            @foreach($sales as $sale)
                                <x-table.tr
                                    :danger="$sale->expired"
                                >
                                    <x-table.td class="hidden lg:table-cell">
                                        {{$sale->id}}
                                    </x-table.td>
                                    <x-table.td>
                                        {{$sale->date}}
                                    </x-table.td>
                                    <x-table.td class="hidden lg:table-cell">
                                        {{$sale->client_name ?? 'Consumidor Final'}}
                                    </x-table.td>
                                    @if($filters['product'])
                                        <x-table.td class="lg:hidden">
                                            {{$sale->client_name ?? 'Consumidor Final'}}
                                        </x-table.td>
                                    @else
                                        <x-table.td>
                                            {{$sale->product_name}}
                                        </x-table.td>
                                    @endif
                                    <x-table.td class="hidden lg:table-cell">
                                        {{$sale->amount}}
                                    </x-table.td>
                                    <x-table.td class="hidden lg:table-cell">
                                        ${{$sale->unitary_price}}
                                    </x-table.td>
                                    <x-table.td class="hidden lg:table-cell">
                                        ${{$sale->total_price}}
                                    </x-table.td>
                                    @if($filters['report_type'] == 'creditSales')
                                        <x-table.td class="hidden lg:table-cell">
                                            {{$sale->due_date}}
                                        </x-table.td>
                                    @endif
                                    <x-table.td>
                                        <div class="flex w-full h-full justify-center items-center">
                                            {{-- Modal --}}
                                            <x-sales-report.show.modal
                                                :$sale
                                                :$filters
                                            />
                                        </div>
                                    </x-table.td>
                                </x-table.tr>
                            @endforeach
                            @if($sales->isNotEmpty() && $sales->onLastPage())
                                <x-table.tr>
                                    <x-table.td class="hidden lg:table-cell"></x-table.td>
                                    <x-table.td></x-table.td>
                                    <x-table.td class="hidden lg:table-cell"></x-table.td>
                                    @if($filters['product'])
                                        <x-table.td class="lg:hidden"></x-table.td>
                                    @else
                                        <x-table.td></x-table.td>
                                    @endif
                                    <x-table.td class="hidden lg:table-cell"></x-table.td>
                                    <x-table.td class="hidden lg:table-cell">
                                        <span class="text-lg font-bold">
                                            Total:
                                        </span>
                                    </x-table.td>
                                    <x-table.td class="hidden lg:table-cell">
                                        ${{$total_prices_summation}}
                                    </x-table.td>
                                    @if($filters['report_type'] == 'creditSales')
                                        <x-table.td class="hidden lg:table-cell"></x-table.td>
                                    @endif
                                    <x-table.td></x-table.td>
                                </x-table.tr>
                            @endif
                        </x-slot:tbody>
                    </x-table>
                    @if($sales->isNotEmpty() && $sales->onLastPage())
                        <p class="mt-3 text-center lg:hidden">
                            <strong>Total:</strong>
                            ${{$total_prices_summation}}
                        </p>
                    @endif
                    @if($sales->isEmpty())
                        <p class="text-red-500">
                            No se encontraron ventas...
                        </p>
                    @endif
                    {{$sales->onEachSide(1)->links()}}

                </div>
            </div>
        </div>
    </div>
</x-app-layout>