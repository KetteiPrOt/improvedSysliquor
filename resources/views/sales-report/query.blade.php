<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Consultar reporte de ventas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl sm:mx-auto">

                    <form action="{{route('sales-report.show')}}">
                        <x-input-label>
                            Desde
                        </x-input-label>
                        <x-date-input
                            name="date_from"
                            value="{{
                                old('date_from',
                                    date(
                                        'Y-m-d', 
                                        strtotime('now') - (60 * 60 * 24 * 7)
                                    )
                                )
                            }}"
                            max="{{date('Y-m-d')}}"
                        />
                        <x-input-error
                            :messages="$errors->get('date_from')"
                        />

                        <x-input-label>
                            Hasta
                        </x-input-label>
                        <x-date-input
                            name="date_to"
                            value="{{old('date_from', date('Y-m-d'))}}"
                            max="{{date('Y-m-d')}}"
                        />
                        <x-input-error
                            :messages="$errors->get('date_to')"
                        />

                        <livewire:entities.sellers.search
                            :default="false"
                        />

                        <livewire:entities.warehouses.search
                            :show="true"
                        />

                        <livewire:entities.products.search />

                        <x-input-label class="mt-6">
                            Tipo de reporte
                        </x-input-label>
                        <x-table>
                            <x-slot:thead></x-slot:thead>
                            <x-slot:tbody>
                                <x-table.tr>
                                    <x-table.td>
                                        <input
                                            name="report_type"
                                            class="rounded mr-2"
                                            id="cashSales"
                                            type="radio"
                                            value="cashSales"
                                            @checked(
                                                old('report_type', 'cashSales') == 'cashSales'
                                            )
                                        />
                                        <label for="cashSales">
                                            Ventas de contado
                                        </label>
                                    </x-table.td>
                                </x-table.tr>
                                <x-table.tr>
                                    <x-table.td>
                                        <input
                                            name="report_type"
                                            class="rounded mr-2"
                                            id="creditSales"
                                            type="radio"
                                            value="creditSales"
                                            @checked(
                                                old('report_type') == 'creditSales'
                                            )
                                        />
                                        <label for="creditSales">
                                            Ventas a credito
                                        </label>
                                    </x-table.td>
                                </x-table.tr>
                            </x-slot:tbody>
                        </x-table>
                        <x-input-error
                            :messages="$errors->get('report_type')"
                        />

                        <div class="pt-6 flex justify-center sm:justify-normal">
                            <x-primary-button>
                                Consultar
                            </x-primary-button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>