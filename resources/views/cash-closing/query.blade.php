<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Consultar Cierre de Caja') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl sm:mx-auto">

                    <form action="{{route('cash-closing.show')}}">
                        @csrf

                        <x-input-label>
                            Desde
                        </x-input-label>
                        <x-date-input
                            name="date_from"
                            value="{{old('date_from', date('Y-m-d'))}}"
                            max="{{date('Y-m-d')}}"
                        />
                        @error('date_from')
                            <p class="text-red-600">{{$message}}</p>
                        @enderror

                        <x-input-label>
                            Hasta
                        </x-input-label>
                        <x-date-input
                            name="date_to"
                            value="{{old('date_from', date('Y-m-d'))}}"
                            max="{{date('Y-m-d')}}"
                        />
                        @error('date_to')
                            <p class="text-red-600">{{$message}}</p>
                        @enderror

                        <livewire:entities.warehouses.search />

                        <livewire:entities.sellers.search />

                        <livewire:entities.products.search />

                        <x-primary-button class="mt-6">
                            Consultar
                        </x-primary-button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>