<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ver Movimiento') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <article class="max-w-xl sm:mx-auto">

                    <x-card-frame>
                        <!-- General Information -->
                        <x-card-title>
                            Información General
                        </x-card-title>                 
                        <x-card-item :tag="__('Fecha')">
                            {{$movement->invoice->date}}
                        </x-card-item>              
                        <x-card-item :tag="__('Cliente/Proveedor')">
                            {{$movement->invoice->person->name}}
                        </x-card-item>                 
                        <x-card-item :tag="__('Tipo')">
                            {{
                                $movement->movementType->name .
                                ' (' . $movement->movementType->movementCategory->name . ')'
                            }}
                        </x-card-item>
                        <x-card-item :tag="__('Usuario Responsable')">
                            {{$movement->invoice->user->name}}
                        </x-card-item>
                        <x-card-item :tag="__('Factura')">
                            {{$movement->invoice->number}}
                        </x-card-item>
                        <!-- Detailed Information -->
                        <x-card-title>
                            Detalles
                        </x-card-title>  
                        <x-card-item :tag="__('Producto')">
                            {{$movement->product->productTag()}}
                        </x-card-item>                  
                        <x-card-item :tag="__('Cantidad')">
                            {{$movement->amount}}
                        </x-card-item>   
                        <x-card-item :tag="__('Precio Unitario')">
                            {{$movement->unitary_price}}
                        </x-card-item> 
                        <x-card-item :tag="__('Precio Total')">
                            {{round($movement->amount * $movement->unitary_price, 2, PHP_ROUND_HALF_UP)}}
                        </x-card-item>   
                    </x-card-frame>

                </article>
            </div>
        </div>
    </div>
</x-app-layout>