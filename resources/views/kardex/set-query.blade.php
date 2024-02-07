<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Consultar Kardex') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl sm:mx-auto">

                    <!-- Select Product Filter form -->
                    <form action="{{route('kardex.setQuery')}}" class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
                        @csrf
                        <div class="order-2 flex flex-col items-center sm:order-1 sm:block">
                            <!-- Search input -->
                            <x-input-label for="search" :value="__('Buscar producto:')" />
                            <x-text-input id="search" name="search" value="{{$formBag['search'] ?? null}}" placeholder="..." class="mb-2 mr-2" />
                            <!-- Send button -->
                            <x-primary-button type="submit">
                                Buscar
                            </x-primary-button>
                        </div>
                    </form>

                    <form action="{{route('kardex.show')}}">
                        @csrf
                        <!-- Products Table -->
                        <table class="border-collapse table-auto w-full text-sm mb-6">
                            <thead>
                                <tr>
                                    <th class="border-b dark:border-slate-600 font-medium p-4 pl-4 sm:pl-8 pt-0 pb-3 text-slate-400 dark:text-slate-200 text-left"
                                    ></th>
                                    <th class="border-b dark:border-slate-600 font-medium p-4 pl-4 sm:pl-8 pt-0 pb-3 text-slate-400 dark:text-slate-200 text-left"
                                    >Producto</th>
                                    <th class="border-b dark:border-slate-600 font-medium p-4 pl-4 sm:pl-8 pt-0 pb-3 text-slate-400 dark:text-slate-200 text-left"
                                    >Inventario Iniciado</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-slate-800">
                                <!-- Products Table rows -->
                                @if($products)
                                    @foreach($products as $product)
                                        <tr>
                                            <!-- Input product -->
                                            <td class="border-b border-slate-100 dark:border-slate-700 p-2 sm:pr-4 pl-2 sm:pl-8 text-slate-500 dark:text-slate-400"
                                            ><input
                                                id="product{{$product->id}}" type="radio"
                                                name="product" value="{{$product->id}}" required
                                                @checked($product->id == old('product'))
                                            ></td>
                                            <!-- Product Name -->
                                            <td class="border-b border-slate-100 dark:border-slate-700 p-2 sm:pr-4 pl-2 sm:pl-8 text-slate-500 dark:text-slate-400">
                                                <label for="product{{$product->id}}">
                                                    {{$product->productTag()}}
                                                </label>
                                            </td>
                                            <!-- Started Inventory -->
                                            <td class="border-b border-slate-100 text-center dark:border-slate-700 p-2 sm:pr-4 pl-2 sm:pl-8 text-slate-500 dark:text-slate-400">
                                                @if($product->started_inventory)
                                                    <span class="text-green-400">SI</span>
                                                @else
                                                    <span class="text-red-400">No</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                        <!-- Products Pagination Links -->
                        @if($products)
                            @if($products->count() == 0)
                                <p class="text-red-400">No se encontraron productos...</p>
                            @else
                                {{ $products->onEachSide(1)->links() }}
                            @endif
                        @else
                            <p>Busca un producto...</p>
                        @endif

                        <x-input-error :messages="$errors->get('product')" />

                        <!-- Date from -->
                        <x-input-label for="dateFrom" :value="__('Desde')" class="mt-6" />
                        <x-date-input max="{{date('Y-m-d', strtotime('-1 month'))}}" id="dateFrom" name="date_from" value="{{old('date_from', date( 'Y-m-d', strtotime('-1 month')))}}" required />
                        <x-input-error :messages="$errors->get('date_from')" />
                        <!-- Date to -->
                        <x-input-label for="dateTo" :value="__('Hasta')" class="mt-6" />
                        <x-date-input max="{{date('Y-m-d')}}" id="dateTo" name="date_to" value="{{old('date', date('Y-m-d'))}}" required />
                        <x-input-error :messages="$errors->get('date_to')" />

                        <br>
                        <x-primary-button type="submit" class="mt-2">
                            Consultar
                        </x-primary-button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>