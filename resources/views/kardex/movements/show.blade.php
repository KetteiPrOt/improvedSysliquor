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
                            {{
                                date(
                                    'd/m/Y',
                                    strtotime($movement->invoice->created_at)
                                )
                            }}
                        </x-card-item>              
                        <x-card-item :tag="__('Cliente/Proveedor')">
                            @if(is_null($movement->invoice->person))
                                {{
                                    $movement->invoice->movementCategory->name
                                    === $incomeName
                                    ? "Desconocido"
                                    : "Consumidor Final"
                                }}
                            @else
                                {{$movement->invoice->person?->name}}
                            @endif
                        </x-card-item>          
                        <x-card-item :tag="__('Tipo')">
                            {{
                                $movement->movementType->name .
                                ' (' . $movement->movementType->movementCategory->name . ')'
                            }}
                        </x-card-item>
                        <x-card-item :tag="__('Bodega')">
                            {{
                                $movement->invoice->warehouse->name
                            }}
                        </x-card-item>
                        <x-card-item :tag="__('Usuario')">
                            {{$movement->invoice->user->name}}
                        </x-card-item>
                        @if($movement->invoice->number)
                            <x-card-item :tag="__('Factura')">
                                {{$movement->invoice->showInvoiceNumber()}}
                            </x-card-item>                
                            <x-card-item :tag="__('Fecha de Factura')">
                                {{
                                    date(
                                        'd/m/Y',
                                        strtotime($movement->invoice->date)
                                    )
                                }}
                            </x-card-item>
                        @endif
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
                            {{
                                '$'
                                . number_format($movement->unitary_price, 2, '.', ' ')
                            }}
                        </x-card-item> 
                        <x-card-item :tag="__('Precio Total')">
                            @php
                                $totalPrice = round(
                                    $movement->amount
                                    * $movement->unitary_price,
                                    2,
                                    PHP_ROUND_HALF_UP
                                )
                            @endphp
                            {{
                                '$'
                                . number_format($totalPrice, 2, '.', ' ')
                            }}
                        </x-card-item>   
                    </x-card-frame>

                </article>
            </div>
        </div>
    </div>
</x-app-layout>