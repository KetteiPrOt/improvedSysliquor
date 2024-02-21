<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Consultar Inventario') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl sm:mx-auto">

                    <form action="{{route('inventory.show')}}">
                        @csrf

                        {{-- Warehouse --}}
                        <livewire:inventory.search-warehouses :show="true" />


                        {{-- Date --}}
                        <x-input-label value="Fecha" class="inline" />
                        <x-icons.info
                            x-data="" class="w-4 h-4 ml-1 text-gray-700"
                            x-on:click.prevent="$dispatch('open-modal', 'date-info')"
                        /> <br>
                        <x-modal name="date-info" focusable>
                            <div class="p-5">
                                Si especifica una fecha pasada verá el inventario que había ese día a las 23h:59min.
                            </div>
                        </x-modal>
                        <x-date-input
                            id="dateInput"
                            value="{{date('Y-m-d')}}" name="date"
                            max="{{date('Y-m-d')}}" required
                        />
                        <br>
                        <x-input-error :messages="$errors->get('date')" />

                        <x-primary-button class="mt-6">
                            Consultar
                        </x-primary-button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>