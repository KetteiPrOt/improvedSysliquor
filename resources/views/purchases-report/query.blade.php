<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Consultar reporte de compras') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl sm:mx-auto">

                    <form action="{{route('purchases-report.show')}}">
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
                        <x-input-error :messages="$errors->get('date_from')" />

                        <x-input-label>
                            Hasta
                        </x-input-label>
                        <x-date-input
                            name="date_to"
                            value="{{old('date_from', date('Y-m-d'))}}"
                            max="{{date('Y-m-d')}}"
                        />
                        <x-input-error :messages="$errors->get('date_to')" />

                        <div class="mt-2">
                            <input
                                id="onlyUnpaidInput" class="rounded"
                                type="checkbox"
                                name="only_unpaid"
                            />
                            <label for="onlyUnpaidInput">Solo facturas sin pagar.</label>
                        </div>

                        <x-primary-button class="mt-2">
                            Consultar
                        </x-primary-button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>