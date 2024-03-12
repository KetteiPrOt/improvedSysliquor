<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ver reporte de compras') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-3 space-y-6">
            <div class="p-4 sm:p-6 bg-white shadow sm:rounded-lg">
                <div class="sm:mx-auto">

                    <p>
                        <strong>Desde:</strong>
                        {{date('d/m/Y', strtotime($filters['date_from']))}}.
                    </p>
                    <p>
                        <strong>Hasta:</strong>
                        {{date('d/m/Y', strtotime($filters['date_to']))}}.
                    </p>

                    <x-table>
                        <x-slot:thead>
                            <x-table.tr :hover="false">
                                <x-table.th>
                                    Fecha y Hora
                                </x-table.th>
                                <x-table.th
                                    class="hidden lg:table-cell"
                                >
                                    Proveedor
                                </x-table.th>
                                <x-table.th>
                                    Factura
                                    <span class="lg:hidden">(click para inspeccionar)</span>
                                </x-table.th>
                                <x-table.th
                                    class="hidden lg:table-cell"
                                >
                                    Precio total
                                </x-table.th>
                                <x-table.th
                                    class="hidden lg:table-cell"
                                >
                                    Fecha de pago
                                </x-table.th>
                                <x-table.th
                                    class="hidden lg:table-cell"
                                >
                                    Fecha de Vencimiento
                                </x-table.th>
                                <x-table.th
                                    class="hidden lg:table-cell"
                                >
                                    Detalles
                                </x-table.th>
                            </x-table.tr>
                        </x-slot:thead>
                        <x-slot:tbody>
                            @foreach($invoices as $invoice)
                                <x-table.tr :danger="!$invoice->paid">
                                    <x-table.td>
                                        {{date('d/m/Y H:i:s', strtotime($invoice->created_at))}}
                                    </x-table.td>
                                    <x-table.td
                                        class="hidden lg:table-cell"
                                    >
                                        {{$invoice->provider_name ?? 'Desconocido'}}
                                    </x-table.td>
                                    {{-- Primary facture number column --}}
                                    <x-table.td
                                        class="hidden lg:table-cell"
                                    >
                                        {{$invoice->number ?? 'No registrada'}}
                                    </x-table.td>
                                    {{-- Responsive facture number column --}}
                                    <x-table.td
                                        class="lg:hidden"
                                    >
                                        <span
                                            x-data
                                            x-on:click="$dispatch('open-modal', 'invoice-{{$invoice->id}}-modal')"
                                        >
                                            {{$invoice->number ?? 'No registrada'}}
                                        </span>
                                    </x-table.td>
                                    <x-table.td
                                        class="hidden lg:table-cell"
                                    >
                                        {{$invoice->total_price}}
                                    </x-table.td>
                                    <x-table.td
                                        class="hidden lg:table-cell"
                                    >
                                        {{$invoice->paid_date ?? ($invoice->paid ? 'Ninguna' : 'Sin pagar')}}
                                    </x-table.td>
                                    <x-table.td
                                        class="hidden lg:table-cell"
                                    >
                                        {{$invoice->payment_due_date ?? 'Ninguna'}}
                                    </x-table.td>
                                    <x-table.td
                                        class="hidden lg:table-cell text-center"
                                    >
                                        <x-icons.info
                                            class="w-5 h-5"
                                            x-data
                                            x-on:click="$dispatch('open-modal', 'invoice-{{$invoice->id}}-modal')"
                                        />
                                    </x-table.td>
                                </x-table.tr>
                                <x-modal name="invoice-{{$invoice->id}}-modal">
                                    <div class="p-3">
                                        <p>
                                            <strong>Fecha y hora:</strong>
                                            {{date('d/m/Y H:i:s', strtotime($invoice->created_at))}}.
                                        </p>
                                        <p>
                                            <strong>Factura:</strong>
                                            {{$invoice->number ?? 'No registrada.'}}
                                        </p>
                                        <hr class="my-2">
                                        <p>
                                            <strong>Proveedor</strong> <br>
                                            {{$invoice->provider_name ?? 'Desconocido.'}}
                                        </p>
                                        <p>
                                            <strong>Precio total:</strong>
                                            {{$invoice->total_price}}
                                        </p>
                                        <p>
                                            <strong>Fecha de pago:</strong>
                                            {{$invoice->paid_date ?? ($invoice->paid ? 'Ninguna' : 'Sin pagar')}}
                                        </p>
                                        <p>
                                            <strong>Fecha de vencimiento:</strong>
                                            {{$invoice->payment_due_date ?? 'Ninguna'}}
                                        </p>
                                        <p>
                                            <strong>Estado:</strong> <br>
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
                                        @can('purchases')
                                            <x-primary-link-button
                                                class="mt-2"
                                                href="{{route('purchases.show', $invoice->id)}}"
                                            >
                                                Ver Factura
                                            </x-primary-link-button>
                                        @endcan
                                    </div>
                                </x-modal>
                            @endforeach
                        </x-slot:tbody>
                    </x-table>
                    {{$invoices->onEachSide(1)->links()}}
                    @if($invoices->isEmpty())
                        <p class="text-red-500">
                            No se encontraron compras...
                        </p>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>