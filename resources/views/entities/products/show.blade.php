<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ver Producto') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <article class="max-w-xl sm:mx-auto">

                    <x-card-frame>
                        <!-- General Information -->
                        <x-card-title>
                            Información
                        </x-card-title>
                        <x-card-item :tag="__('Tipo')">
                            {{$product->type->name}}
                        </x-card-item>                        
                        <x-card-item :tag="__('Nombre')">
                            {{$product->name}}
                        </x-card-item>                        
                        <x-card-item :tag="__('Presentación')">
                            {{$product->presentation->content}}
                        </x-card-item>
                        <x-card-item :tag="__('Stock Mínimo:')">
                            {{$product->minimun_stock}}
                        </x-card-item>
                        <!-- Sale Prices -->
                        <x-card-title>
                            Precios de Venta
                        </x-card-title>
                        @foreach($product->salePrices as $salePrice)
                            @php
                                if($salePrice->unitsNumber->units == 1){
                                    $salePriceTag = 'Por ' . $salePrice->unitsNumber->units . ' unidad';
                                } else {
                                    $salePriceTag = 'Por ' . $salePrice->unitsNumber->units . ' unidades';
                                }
                            @endphp
                            <x-card-item :tag="$salePriceTag">
                                {{$salePrice->price}}
                            </x-card-item>
                        @endforeach        
                    </x-card-frame>

                    <div class="mt-3 flex justify-center sm:justify-start">
                        <x-secondary-link-button href="{{route('products.edit', $product->id)}}" class="mr-3">
                            Editar
                        </x-secondary-link-button>

                        <!-- Start Delete button -->
                        <x-danger-button
                            x-data=""
                            x-on:click.prevent="$dispatch('open-modal', 'confirm-product-deletion')"
                        >{{ __('Eliminar') }}</x-danger-button>

                        <x-modal name="confirm-product-deletion" focusable>
                            <form method="post" action="{{route('products.destroy', $product->id)}}" class="p-6">
                                @csrf
                                @method('delete')

                                <h2 class="text-lg font-medium text-gray-900">
                                    {{ __('¿Estás seguro que deseas eliminar el producto?') }}
                                </h2>

                                <p class="mt-1 text-sm text-gray-600">
                                    {{ __('Una vez elimines el producto todos los registros asociados de movimientos y stock serán borrados del sistema, ten mucho cuidado porque podrías perder información MUY IMPORTANTE.') }}
                                </p>

                                <div class="mt-6 flex justify-end">
                                    <x-secondary-button x-on:click="$dispatch('close')">
                                        {{ __('Cancel') }}
                                    </x-secondary-button>

                                    <x-danger-button class="ml-3">
                                        {{ __('Eliminar Producto') }}
                                    </x-danger-button>
                                </div>
                            </form>
                        </x-modal>
                        <!-- End Delete Button -->
                    </div>

                </article>
            </div>
        </div>
    </div>
</x-app-layout>