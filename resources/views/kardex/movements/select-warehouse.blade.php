<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Seleccionar Bodega') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl sm:mx-auto">

                    <!-- Filter -->
                    <form 
                        class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6"
                        action="{{
                            request()->routeIs('sales.selectWarehouse')
                            ? route('sales.selectWarehouse')
                            : route('purchases.selectWarehouse')
                        }}"
                    >
                        @csrf
                        <div class="order-2 flex flex-col items-center sm:order-1 sm:block">
                            <!-- Search input -->
                            <x-input-label for="search" :value="__('Buscar:')" />
                            <x-text-input
                                id="search" name="search" class="mb-2 mr-2"
                                value="{{$search}}" maxlength="120"
                            />
                            <!-- Send button -->
                            <x-primary-button type="submit">
                                Buscar
                            </x-primary-button>

                            <x-input-error :messages="$errors->get('search')" />
                        </div>
                    </form>

                    <!-- Main Form -->
                    <form action="{{
                        request()->routeIs('sales.selectWarehouse')
                        ? route('sales.saveWarehouse')
                        : route('purchases.saveWarehouse')
                    }}" method="post">
                        @csrf
                        <!-- Table -->
                        <table class="border-collapse table-auto w-full text-sm mb-6">
                            <thead>
                                <tr>
                                    <th class="border-b dark:border-slate-600 font-medium p-4 pl-4 sm:pl-8 pt-0 pb-3 text-slate-400 dark:text-slate-200 text-left"
                                    >Bodega</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-slate-800">
                                <!-- Table rows -->
                                {{-- @if($warehouses) --}}
                                @foreach($warehouses as $warehouse)
                                    <tr>
                                        <!-- Input -->
                                        <td class="flex items-center border-b border-slate-100 dark:border-slate-700 p-2 sm:pr-4 pl-2 sm:pl-8 text-slate-500 dark:text-slate-400">
                                            <input
                                                id="warehouse{{$warehouse->id}}" type="radio"
                                                name="warehouse" value="{{$warehouse->id}}" required
                                                @checked($warehouse->id == old('warehouse'))
                                                class="mr-6"
                                            >
                                            <label for="warehouse{{$warehouse->id}}">
                                                {{$warehouse->name}}
                                            </label>
                                        </td>
                                    </tr>
                                @endforeach
                                {{-- @endif --}}
                            </tbody>
                        </table>
                        <!-- Pagination Links -->
                        {{-- @if($products) --}}
                        @if($warehouses->count() == 0)
                            <p class="text-red-400">No se encontraron resultados...</p>
                        @else
                            {{ $warehouses->onEachSide(1)->links() }}
                        @endif
                        {{-- @else
                            <p>Buscar...</p>
                        @endif --}}

                        <x-input-error :messages="$errors->get('warehouse')" />

                        <x-primary-button type="submit" class="mt-2">
                            Seleccionar
                        </x-primary-button> 
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>