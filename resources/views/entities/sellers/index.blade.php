<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ver Vendedores') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl sm:mx-auto">
                    
                    <!-- Filter form -->
                    <form action="{{route('sellers.index')}}" class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
                        @csrf
                        <div class="order-2 flex flex-col items-center sm:order-1 sm:block">
                            <!-- Search input -->
                            <x-input-label for="search" :value="__('Buscar vendedor:')" />
                            <x-text-input id="search" name="search" value="{{$search}}" placeholder="..." class="mb-2" />
                            <div>
                                <!-- Send button -->
                                <x-primary-button type="submit">
                                    Buscar
                                </x-primary-button>
                                <!-- Clear Button -->
                                <x-secondary-link-button href="{{route('sellers.index')}}" class="bg-red-400 hover:bg-red-600 text-white">
                                    X
                                </x-secondary-link-button>
                            </div>
                        </div>
                        
                        <div class="order-1 flex flex-col items-center sm:order-2">
                            <x-secondary-link-button href="{{route('sellers.create')}}" class="order-1 mb-3 sm:mb-0 sm:order-3">
                                Agregar Vendedor
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
                                >Vendedor</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-slate-800">
                            <!-- Table rows -->
                            @if($sellers)
                                @php
                                    // Obtain the number of the first item in this page
                                    $seller_count = $sellers->perPage() * 
                                                    $sellers->currentPage() - 
                                                    ($sellers->perPage() - 1);
                                @endphp
                                @foreach($sellers as $seller)
                                    <tr>
                                        <td class="border-b border-slate-100 dark:border-slate-700 p-2 sm:pr-4 pl-2 sm:pl-8 text-slate-500 dark:text-slate-400"
                                        >
                                            {{$seller_count}}
                                        </td>
                                        <td class="border-b border-slate-100 dark:border-slate-700 p-2 sm:pr-4 pl-2 sm:pl-8 text-slate-500 dark:text-slate-400"
                                        >
                                            {{-- <a href="{{route('sellers.show', $seller->id)}}">
                                                {{$seller->person->name}}
                                            </a> --}}
                                            {{$seller->person->name}}
                                        </td>
                                    </tr>
                                    @php
                                        $seller_count++;
                                    @endphp
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                    <!-- Pagination Links -->
                    @if($sellers)
                        @if($sellers->count() > 0)
                            {{$sellers->onEachSide(1)->links()}}
                        @else
                            <p class="text-red-400">No se encontraron vendedores...</p>
                        @endif
                    @else
                        <p>Busca un vendedor...</p>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>