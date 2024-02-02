<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ver Productos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl sm:mx-auto">
                    
                    <!-- Filter form -->
                    <form action="{{route('products.index')}}" class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
                        @csrf
                        <div class="order-2 flex flex-col items-center sm:order-1 sm:block">
                            <!-- Search input -->
                            <x-input-label for="search" :value="__('Buscar producto:')" />
                            <x-text-input id="search" name="search" value="{{$formBag['search'] ?? null}}" placeholder="..." class="mb-2" />
                            <div>
                                <!-- Send button -->
                                <x-primary-button type="submit">
                                    Buscar
                                </x-primary-button>
                                <!-- Clear Button -->
                                <x-secondary-link-button href="{{route('products.index')}}" class="bg-red-400 hover:bg-red-600 text-white">
                                    X
                                </x-secondary-link-button>
                            </div>
                        </div>
                        
                        <div class="order-1 flex flex-col items-center sm:order-2">
                            <!-- New product button -->
                            <x-secondary-link-button href="{{route('products.create')}}" class="order-1 mb-3 sm:mb-0 sm:order-3">
                                Agregar Producto
                            </x-secondary-link-button>
                        </div>
                    </form>

                    <!-- Table -->
                    <table class="border-collapse table-auto w-full text-sm mb-6">
                        <thead>
                            <tr>
                                <th class="border-b dark:border-slate-600 font-medium p-4 pl-4 sm:pl-8 pt-0 pb-3 text-slate-400 dark:text-slate-200 text-left"
                                >No</th>
                                <th class="border-b dark:border-slate-600 font-medium p-4 pl-4 sm:pl-8 pt-0 pb-3 text-slate-400 dark:text-slate-200 text-left"
                                >Producto</th>
                                <th class="border-b dark:border-slate-600 font-medium p-4 pl-4 sm:pl-8 pt-0 pb-3 text-slate-400 dark:text-slate-200 text-center"
                                >Inventario Iniciado</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-slate-800">
                            <!-- Product rows -->
                            @if($products)
                                @php
                                    // Obtain the number of the first product in this page
                                    $product_count = $products->perPage() *
                                                        $products->currentPage() -
                                                        ($products->perPage() - 1);
                                @endphp
                                @foreach($products as $product)
                                    <tr>
                                        <td class="border-b border-slate-100 dark:border-slate-700 p-2 sm:pr-4 pl-2 sm:pl-8 text-slate-500 dark:text-slate-400"
                                        >{{$product_count}}</td>
                                        <td class="border-b border-slate-100 dark:border-slate-700 p-2 sm:pr-4 pl-2 sm:pl-8 text-slate-500 dark:text-slate-400"
                                        ><a href="{{route('products.show', $product->id)}}">
                                            {{$product->productTag()}}
                                        </a></td>
                                        <td class="border-b border-slate-100 text-center dark:border-slate-700 p-2 sm:pr-4 pl-2 sm:pl-8 text-slate-500 dark:text-slate-400">
                                            @if($product->started_inventory)
                                                <span class="text-green-400">SI</span>
                                            @else
                                                <span class="text-red-400">No</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @php
                                        $product_count++;
                                    @endphp
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

                </div>
            </div>
        </div>
    </div>
</x-app-layout>