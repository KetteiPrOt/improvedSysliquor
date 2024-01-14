<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ver Proveedores') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl sm:mx-auto">
                    
                    {{--
                    <!-- Filter form -->
                    <form action="{{route('clients.index')}}" class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
                        @csrf
                        <div class="order-2 flex flex-col items-center sm:order-1 sm:block">
                            <!-- Search input -->
                            <x-input-label for="search" :value="__('Buscar cliente:')" />
                            <x-text-input id="search" name="search" value="{{$formBag['search'] ?? null}}" placeholder="..." class="mb-2" />
                            <div>
                                <!-- Send button -->
                                <x-primary-button type="submit">
                                    Buscar
                                </x-primary-button>
                                <!-- Clear Button -->
                                <x-secondary-link-button href="{{route('clients.index')}}" class="bg-red-400 hover:bg-red-600 text-white">
                                    X
                                </x-secondary-link-button>
                            </div>
                        </div>
                        
                        <div class="order-1 flex flex-col items-center sm:order-2">
                            <!-- New client button -->
                            <x-secondary-link-button href="{{route('clients.create')}}" class="order-1 mb-3 sm:mb-0 sm:order-3">
                                Agregar Cliente
                            </x-secondary-link-button>
                        </div>
                    </form>
                    --}}

                    <!-- New provider button -->
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
                        <div class="order-1 flex flex-col items-center sm:order-2">
                            <x-secondary-link-button href="{{route('providers.create')}}" class="order-1 mb-3 sm:mb-0 sm:order-3">
                                Agregar Proveedor
                            </x-secondary-link-button>
                        </div>
                    </div>

                    <!-- Table -->
                    <table class="border-collapse table-auto w-full text-sm mb-6">
                        <thead>
                            <tr>
                                <th class="border-b dark:border-slate-600 font-medium p-4 pl-4 sm:pl-8 pt-0 pb-3 text-slate-400 dark:text-slate-200 text-left"
                                >No</th>
                                <th class="border-b dark:border-slate-600 font-medium p-4 pl-4 sm:pl-8 pt-0 pb-3 text-slate-400 dark:text-slate-200 text-left"
                                >Proveedor</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-slate-800">
                            <!-- Table rows -->
                            @php
                                // Obtain the number of the first item in this page
                                $provider_count = $providers->perPage() * $providers->currentPage() - ($providers->perPage() - 1);
                            @endphp
                            @foreach($providers as $provider)
                                <tr>
                                    <td class="border-b border-slate-100 dark:border-slate-700 p-2 sm:pr-4 pl-2 sm:pl-8 text-slate-500 dark:text-slate-400"
                                    >{{$provider_count}}</td>
                                    <td class="border-b border-slate-100 dark:border-slate-700 p-2 sm:pr-4 pl-2 sm:pl-8 text-slate-500 dark:text-slate-400"
                                    ><a href="#">{{$provider->person->name}}</a></td>
                                </tr>
                                @php
                                    $provider_count++;
                                @endphp
                            @endforeach
                        </tbody>
                    </table>
                    <!-- providers Pagination Links -->
                    {{ $providers->onEachSide(1)->links() }}

                </div>
            </div>
        </div>
    </div>
</x-app-layout>