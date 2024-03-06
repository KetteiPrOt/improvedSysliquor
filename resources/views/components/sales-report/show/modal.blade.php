@props(['sale', 'filters'])

<x-icons.info
    class="w-6 h-6"
    x-data=""
    x-on:click="$dispatch('open-modal', 'sale-{{$sale->id}}-info')"
/>
<x-modal name="sale-{{$sale->id}}-info" focusable>
    <div class="p-3">
        <h4
            class="text-lg font-bold"
        >Detalles de la venta</h4>
        <p>
            <strong>ID:</strong>
            {{$sale->id}}
        </p>
        <p>
            <strong>Fecha:</strong>
            {{$sale->date}}
        </p>
        <p>
            <strong>Bodega:</strong>
            {{$sale->warehouse_name}}
        </p>
        <p>
            <strong>Vendedor:</strong>
            {{$sale->seller_name}}
        </p>
        
        <p>
            <strong>Clienter:</strong>
            {{$sale->client_name ?? 'Consumidor Final'}}
        </p>
        <p>
            <strong>Producto:</strong>
            {{$sale->product_name}}
        </p>
        <x-table>
            <x-slot:thead>
                <x-table.th>
                    Cantidad
                </x-table.th>
                <x-table.th>
                    P. Unitario
                </x-table.th>
                <x-table.th>
                    P. Total
                </x-table.th>
            </x-slot:thead>
            <x-slot:tbody>
                <x-table.tr
                    :hover="false"
                >
                    <x-table.td>
                        {{$sale->amount}}
                    </x-table.td>
                    <x-table.td>
                        ${{$sale->unitary_price}}
                    </x-table.td>
                    <x-table.td>
                        ${{$sale->total_price}}
                    </x-table.td>
                </x-table.tr>
            </x-slot:tbody>
        </x-table>

        @if($filters['report_type'] == 'creditSales')
            <div class="mt-6 flex justify-center sm:justify-normal">
                <form action="{{route('sales-report.confirm', $sale->id)}}" method="post">
                    @csrf
                    <x-primary-button>
                        Confirmar Pago
                    </x-primary-button>
                </form>
            </div>
        @endif

        @if($sale->comment)
            <h4
                class="text-lg font-bold mt-6"
            >Comentario</h4>
            <p>
                {{$sale->comment}}
            </p>
        @endif
    </div>
</x-modal>