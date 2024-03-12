<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ver Compra') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl sm:mx-auto">

                    <p>
                        <strong>Fecha y hora:</strong>
                        {{date('d/m/Y H:i:s', strtotime($invoice->created_at))}}.
                    </p>
                    <p>
                        <strong>Factura:</strong>
                        {{$invoice->number ?? 'No Registrada'}}.
                    </p>
                    <hr class="my-2">
                    <p>
                        <strong>Proveedor</strong> <br>
                        {{$invoice->person?->name ?? 'Desconocido'}}.
                    </p>
                    <p>
                        <strong>Fecha de Pago:</strong>
                        {{$invoice->paid_date 
                            ? date('d/m/Y', strtotime($invoice->paid_date))
                            : ($invoice->paid ? 'Ninguna' : 'Sin pagar')}}
                    </p>
                    <p>
                        <strong>Fecha de Vencimiento:</strong>
                        {{$invoice->payment_due_date 
                            ? date('d/m/Y', strtotime($invoice->payment_due_date))
                            :'Ninguna'}}
                    </p>
                    <p>
                        <strong>Estado</strong> <br>
                        @if($invoice->paid)
                            <span class="text-green-500">
                                Pagado.
                            </span>
                        @else
                            <span class="text-red-500">
                                Sin pagar.
                            </span>
                        @endif
                    </p>
                    <hr class="my-2">
                    <p>
                        <strong>Movimientos:</strong>
                    </p>

                    <x-table>
                        <x-slot:thead>
                            <x-table.tr :hover="false">
                                <x-table.th>
                                    Producto
                                </x-table.th>
                                <x-table.th class="hidden lg:table-cell">
                                    Cantidad
                                </x-table.th>
                                <x-table.th class="hidden lg:table-cell">
                                    Precio Unitario
                                </x-table.th>
                                <x-table.th>
                                    Precio Total
                                </x-table.th>
                                {{-- Primary Movement Link --}}
                                @can('kardex')
                                    <x-table.th class="hidden lg:table-cell">
                                        Detalles
                                    </x-table.th>
                                @endcan
                                {{-- Responsive Movement Modal --}}
                                <x-table.th class="lg:hidden">
                                    Detalles
                                </x-table.th>
                            </x-table.tr>
                        </x-slot:thead>
                        <x-slot:tbody>
                            @php $invoice_total_price = 0; @endphp
                            @foreach($invoice->movements as $movement)
                                <x-table.tr>
                                    <x-table.td>
                                        {{$movement->product->productTag()}}
                                    </x-table.td>
                                    <x-table.td class="hidden lg:table-cell">
                                        {{$movement->amount}}
                                    </x-table.td>
                                    <x-table.td class="hidden lg:table-cell">
                                        ${{$movement->unitary_price}}
                                    </x-table.td>
                                    <x-table.td>
                                        ${{$movement->total_price}}
                                        @php $invoice_total_price += $movement->total_price; @endphp
                                    </x-table.td>
                                    @can('kardex')
                                        {{-- Primary Movement Link --}}
                                        <x-table.td
                                            class="hidden lg:table-cell text-center"
                                        >
                                            <a
                                                href="{{route('kardex.showMovement', $movement->id)}}"
                                                class="w-5 h-5"
                                            >
                                                <x-icons.info
                                                    class="w-5 h-5"
                                                />
                                            </a>
                                        </x-table.td>
                                    @endcan
                                    {{-- Responsive Movement Modal --}}
                                    <x-table.td
                                        class="lg:hidden text-center"
                                    >
                                        <x-icons.info
                                            class="w-5 h-5"
                                            x-data
                                            x-on:click="$dispatch('open-modal', 'movement-{{$movement->id}}-modal')"
                                        />
                                    </x-table.td>
                                </x-table.tr>
                                <x-modal name="movement-{{$movement->id}}-modal">
                                    <div class="p-2">
                                        <p>
                                            <strong>Producto</strong> <br>
                                            {{$movement->product->productTag()}}
                                        </p>
                                        <p>
                                            <strong>Cantidad:</strong>
                                            {{$movement->amount}}
                                        </p>
                                        <p>
                                            <strong>Precio unitario</strong> <br>
                                            ${{$movement->unitary_price}}
                                        </p>
                                        <p>
                                            <strong>Precio total</strong> <br>
                                            ${{$movement->total_price}}
                                        </p>
                                        @can('kardex')
                                            <x-primary-link-button
                                                class="mt-2"
                                                href="{{route('kardex.showMovement', $movement->id)}}"
                                            >
                                                Ver Movimiento
                                            </x-primary-link-button>
                                        @endcan
                                    </div>
                                </x-modal>
                            @endforeach
                            <x-table.tr>
                                <x-table.td class="text-right">
                                    <span class="lg:hidden font-bold">
                                        Total:
                                    </span>
                                </x-table.td>
                                <x-table.td class="hidden lg:table-cell"></x-table.td>
                                <x-table.td class="hidden lg:table-cell">
                                    <span class="font-bold">
                                        Total:
                                    </span>
                                </x-table.td>
                                <x-table.td>
                                    {{'$' . number_format(
                                        $invoice_total_price, 2, '.', ' '
                                    )}}
                                </x-table.td>
                                @can('kardex')
                                    {{-- Primary Movement Link --}}
                                    <x-table.td class="hidden lg:table-cell text-center"></x-table.td>
                                @endcan
                                {{-- Responsive Movement Modal --}}
                                <x-table.td class="lg:hidden text-center"></x-table.td>
                            </x-table.tr>
                        </x-slot:tbody>
                    </x-table>

                    @if(!$invoice->paid)
                        <form
                            class="mt-4 text-center sm:text-left"
                            action="{{route('purchases.confirm-pay', $invoice->id)}}" method="post"
                        >
                            @csrf
                            @method('put')

                            <x-primary-button>
                                Confirmar Pago
                            </x-primary-button>
                        </form>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>