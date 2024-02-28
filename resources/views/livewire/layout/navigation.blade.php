<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" wire:navigate>
                        <x-application-logo :small="true" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 lg:-my-px lg:ms-10 lg:flex">

                    @can('products')
                        <x-nav-link :href="route('products.index')" :active="request()->routeIs('products.index')">
                            {{ __('Productos') }}
                        </x-nav-link>
                    @endcan

                    @can('kardex')
                        <x-nav-link :href="route('kardex.setQuery')" :active="request()->routeIs('kardex.setQuery')">
                            {{ __('Kardex') }}
                        </x-nav-link>
                    @endcan

                    <!-- Registrer Movements -->
                    @php
                        $canRegisterPurchases = auth()->user()->can('purchases');
                        $canRegisterSales = auth()->user()->can('sales');
                    @endphp
                    @if($canRegisterPurchases && $canRegisterSales)
                        <div class="hidden sm:flex sm:items-center sm:ml-6">
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button class="inline-flex items-center border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                        <div>Registrar Movimientos</div>
            
                                        <div class="ml-1">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </button>
                                </x-slot>
            
                                <x-slot name="content">
                                    <x-dropdown-link :href="route('purchases.create')">
                                        {{ __('Compras') }}
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('sales.create')">
                                        {{ __('Ventas') }}
                                    </x-dropdown-link>
                                </x-slot>
                            </x-dropdown>
                        </div>
                    @else
                        @if($canRegisterPurchases)
                            <x-nav-link :href="route('purchases.create')" :active="request()->routeIs('purchases.create')">
                                {{ __('Compras') }}
                            </x-nav-link>
                        @endif
                        @if($canRegisterSales)
                            <x-nav-link :href="route('sales.create')" :active="request()->routeIs('sales.create')">
                                {{ __('Ventas') }}
                            </x-nav-link>
                        @endif
                    @endif

                    <!-- Admin Actors -->
                    @if(
                        auth()->user()->can('providers') ||
                        auth()->user()->can('clients') ||
                        auth()->user()->can('sellers')
                    )
                        <div class="hidden sm:flex sm:items-center sm:ml-6">
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button class="inline-flex items-center border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                        <div>Administrar Actores</div>
            
                                        <div class="ml-1">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </button>
                                </x-slot>
            
                                <x-slot name="content">
                                    @can('providers')
                                        <x-dropdown-link :href="route('providers.index')">
                                            {{ __('Proveedores') }}
                                        </x-dropdown-link>
                                    @endcan
                                    @can('clients')
                                        <x-dropdown-link :href="route('clients.index')">
                                            {{ __('Clientes') }}
                                        </x-dropdown-link>
                                    @endcan
                                    @can('sellers')
                                        <x-dropdown-link :href="route('sellers.index')">
                                            {{ __('Vendedores') }}
                                        </x-dropdown-link>
                                    @endcan
                                </x-slot>
                            </x-dropdown>
                        </div>
                    @endif

                    <!-- See Reports -->
                    @if(
                        // auth()->user()->can('...') ||
                        // auth()->user()->can('...') ||
                        // auth()->user()->can('...')
                        auth()->user()->can('cash-closing')
                        || auth()->user()->can('inventory')
                    )
                        <div class="hidden sm:flex sm:items-center sm:ml-6">
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button class="inline-flex items-center border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                        <div>Ver Reportes</div>
            
                                        <div class="ml-1">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </button>
                                </x-slot>
            
                                <x-slot name="content">
                                    <x-dropdown-link :href="__('#')">
                                        {{ __('Cuentas por cobrar') }}
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="__('#')">
                                        {{ __('Cuentas por pagar') }}
                                    </x-dropdown-link>
                                    @can('cash-closing')
                                        <x-dropdown-link :href="route('cash-closing.query')">
                                            {{ __('Cierre de caja') }}
                                        </x-dropdown-link>
                                    @endcan
                                    @can('inventory')
                                        <x-dropdown-link :href="route('inventory.query')">
                                            {{ __('Reporte de Stock') }}
                                        </x-dropdown-link>
                                    @endcan
                                </x-slot>
                            </x-dropdown>
                        </div>
                    @endif

                    <!-- Admin Permissions -->
                    @can('permissions')
                        <div class="hidden sm:flex sm:items-center sm:ml-6">
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button class="inline-flex items-center border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                        <div>Permisos</div>
            
                                        <div class="ml-1">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </button>
                                </x-slot>
            
                                <x-slot name="content">
                                    <x-dropdown-link :href="route('user-permissions.users')">
                                        {{ __('Usuarios') }}
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('roles.index')">
                                        {{ __('Roles') }}
                                    </x-dropdown-link>
                                </x-slot>
                            </x-dropdown>
                        </div>
                    @endcan
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden lg:flex lg:items-center lg:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                            <div x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile')" wire:navigate>
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <button wire:click="logout" class="w-full text-start">
                            <x-dropdown-link>
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </button>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center lg:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden lg:hidden">
        <div class="pt-2 pb-3 space-y-1">

            @can('products')
                <x-responsive-nav-link :href="route('products.index')" :active="request()->routeIs('products.index')">
                    {{ __('Productos') }}
                </x-responsive-nav-link>
            @endcan

            @can('kardex')
                <x-responsive-nav-link :href="route('kardex.setQuery')" :active="request()->routeIs('kardex.setQuery')">
                    {{ __('Kardex') }}
                </x-responsive-nav-link>
            @endcan
            
            <!-- Registrer Movements -->
            @if($canRegisterPurchases && $canRegisterSales)
                <div class="flex items-center">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <x-responsive-dropdown-button>
                                Registrar Movimientos
                            </x-responsive-dropdown-button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('purchases.create')">
                                {{ __('Compras') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('sales.create')">
                                {{ __('Ventas') }}
                            </x-dropdown-link>
                        </x-slot>
                    </x-dropdown>
                </div>
            @else
                @if($canRegisterPurchases)
                    <x-responsive-nav-link :href="route('purchases.create')" :active="request()->routeIs('purchases.create')">
                        {{ __('Compras') }}
                    </x-responsive-nav-link>
                @endif
                @if($canRegisterSales)
                    <x-responsive-nav-link :href="route('sales.create')" :active="request()->routeIs('sales.create')">
                        {{ __('Ventas') }}
                    </x-responsive-nav-link>
                @endif
            @endif

            <!-- Admin Actors -->
            @if(
                auth()->user()->can('providers') ||
                auth()->user()->can('clients') ||
                auth()->user()->can('sellers')
            )
                <div class="flex items-center">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <x-responsive-dropdown-button {{--:active="
                                request()->routeIs('#') || request()->routeIs('#')
                            "--}}>
                                Administrar Actores
                            </x-responsive-dropdown-button>
                        </x-slot>

                        <x-slot name="content">
                            @can('providers')
                                <x-dropdown-link :href="route('providers.index')">
                                    {{ __('Proveedores') }}
                                </x-dropdown-link>
                            @endcan
                            @can('clients')
                                <x-dropdown-link :href="route('clients.index')">
                                    {{ __('Clientes') }}
                                </x-dropdown-link>
                            @endcan
                            @can('sellers')
                                <x-dropdown-link :href="route('sellers.index')">
                                    {{ __('Vendedores') }}
                                </x-dropdown-link>
                            @endcan
                        </x-slot>
                    </x-dropdown>
                </div>
            @endif

            <!-- See reports -->
            @if(
                // auth()->user()->can('...') ||
                // auth()->user()->can('...') ||
                // auth()->user()->can('...')
                auth()->user()->can('cash-closing')
                || auth()->user()->can('inventory')
            )
                <div class="flex items-center">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <x-responsive-dropdown-button>
                                Consultar Reportes
                            </x-responsive-dropdown-button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="__('#')">
                                {{ __('Cuentas por cobrar') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="__('#')">
                                {{ __('Cuentas por pagar') }}
                            </x-dropdown-link>
                            @can('cash-closing')
                                <x-dropdown-link :href="route('cash-closing.query')">
                                    {{ __('Cierre de caja') }}
                                </x-dropdown-link>
                            @endcan
                            @can('inventory')
                                <x-dropdown-link :href="route('inventory.query')">
                                    {{ __('Reporte de Stock') }}
                                </x-dropdown-link>
                            @endcan
                        </x-slot>
                    </x-dropdown>
                </div>
            @endif

            <!-- Admin Permissions -->
            @can('permissions')
                <div class="flex items-center">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <x-responsive-dropdown-button>
                                Permisos
                            </x-responsive-dropdown-button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('user-permissions.users')">
                                {{ __('Usuarios') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('roles.index')">
                                {{ __('Roles') }}
                            </x-dropdown-link>
                        </x-slot>
                    </x-dropdown>
                </div>
            @endcan
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800 dark:text-gray-200" x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>
                <div class="font-medium text-sm text-gray-500">{{ auth()->user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile')" wire:navigate>
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <button wire:click="logout" class="w-full text-start">
                    <x-responsive-nav-link>
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </button>
            </div>
        </div>
    </div>
</nav>
