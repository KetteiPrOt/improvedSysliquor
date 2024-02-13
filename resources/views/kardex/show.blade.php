<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Kardex
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 xl:max-w-8xl space-y-6">
            <div class="py-4 bg-white shadow sm:rounded-lg">
                <div class="max-w-full">

                    <p class="px-4 mb-2">
                        <strong>Producto: </strong>
                        {{$product->productTag()}}
                    </p>

                    <p class="px-4 mb-4">
                        <strong>Fecha Inicial:</strong>
                        {{
                            date(
                                'd/m/Y',
                                strtotime($date_from)
                            )
                        }}
                        &nbsp;&nbsp;&nbsp; <br class="sm:hidden">
                        <strong>Fecha Final:</strong>
                        {{
                            date(
                                'd/m/Y',
                                strtotime($date_to)
                            )
                        }}
                    </p>

                    <!-- Primary Table -->
                    <x-kardex.table>
                        <x-kardex.table.header />                        
                        <x-kardex.table.body>
                            <!-- Table rows -->
                            @foreach($movements as $movement)
                                <tr class="hover:bg-slate-100">
                                    <!-- Movement information -->
                                    <x-kardex.table.triple-cell :textleft="true">
                                        <x-slot name="first">
                                            {{
                                                date(
                                                    'd/m/Y',
                                                    strtotime($movement->invoice->created_at)
                                                )
                                            }}
                                        </x-slot>
                                        <x-slot name="second">
                                            @php
                                                if(is_null($movement->invoice->person)){
                                                    if(
                                                        $movement->movementType->movementCategory->name
                                                        == $movementCategories['income']
                                                    ){
                                                        echo 'Desconocido';
                                                    } else {
                                                        echo 'Consumidor Final';
                                                    }
                                                } else {
                                                    echo $movement->invoice->person->name;
                                                }
                                            @endphp
                                        </x-slot>
                                        <x-slot name="third">
                                            {{$movement->movementType->name}}
                                            <a
                                                href="{{route('kardex.showMovement', $movement->id)}}"
                                                class="text-blue-400 underline"
                                            >
                                                (Detalles)
                                            </a>
                                        </x-slot>
                                    </x-kardex.table.triple-cell>
                                    <!-- Incomes -->
                                    @if(
                                        $movement->movementType->movementCategory->name
                                        == $movementCategories['income']
                                    )
                                        <x-kardex.table.movement
                                            :movement="$movement"
                                        />
                                    @else
                                        <x-kardex.table.triple-cell />
                                    @endif
                                    <!-- Expenses -->
                                    @if(
                                        $movement->movementType->movementCategory->name
                                        == $movementCategories['expense']
                                    )
                                        <x-kardex.table.movement
                                            :movement="$movement"
                                        />
                                    @else
                                        <x-kardex.table.triple-cell />
                                    @endif
                                    <!-- Balance -->
                                    <x-kardex.table.movement
                                        :movement="$movement->balance"
                                    />
                                </tr>
                            @endforeach
                        </x-kardex.table.body>
                    </x-kardex.table>

                    <!-- Responsive Table -->
                    <x-kardex.responsive-table>
                        @foreach($movements as $movement)
                            <!-- Table Rows  -->
                            <x-kardex.responsive-table.row
                                :data="compact(
                                    'movement',
                                    'movementCategories'
                                )"
                            >
                                <x-slot:tag>
                                    {{
                                        date(
                                            'd/m/Y',
                                            strtotime($movement->invoice->created_at)
                                        )
                                        . " | "
                                        . $movement->movementType->name
                                        . ' ('
                                        . $movement->movementType->movementCategory->name
                                        . ')'
                                    }}
                                </x-slot:tag>
                                <x-slot:body>
                                    <p class="mb-3">
                                        <strong>
                                            Información del {{
                                                $movement
                                                    ->movementType
                                                    ->movementCategory->name
                                            }}:
                                        </strong>
                                    </p>

                                    <x-kardex.responsive-table.subtable>
                                        <x-kardex.table.movement
                                            :movement="$movement"
                                        />
                                    </x-kardex.responsive-table.subtable>

                                    <p class="mb-3">
                                        <strong>Existencias:</strong>
                                    </p>

                                    <x-kardex.responsive-table.subtable>
                                        <x-kardex.table.movement
                                            :movement="$movement->balance"
                                        />
                                    </x-kardex.responsive-table.subtable>
                                </x-slot:body>
                            </x-kardex.responsive-table.row>
                        @endforeach
                    </x-kardex.responsive-table>

                    @if($movements->currentPage() == $movements->lastPage())
                        <!-- Start Delete button -->
                        <x-danger-button
                            class="m-6"
                            x-data=""
                            x-on:click.prevent="$dispatch('open-modal', 'confirm-movement-deletion')"
                        >Eliminar Movimiento</x-danger-button>

                        <x-modal name="confirm-movement-deletion" focusable>
                            <form method="post" action="{{route('kardex.popMovement', $product->id)}}" class="p-6">
                                @csrf
                                @method('delete')

                                <h2 class="text-lg font-medium text-gray-900">
                                    {{ __('¿Desea eliminar el ultimo movimiento?') }}
                                </h2>

                                <div class="mt-6 flex justify-end">
                                    <x-secondary-button x-on:click="$dispatch('close')">
                                        {{ __('Cancel') }}
                                    </x-secondary-button>

                                    <x-danger-button class="ml-3">
                                        {{ __('Eliminar') }}
                                    </x-danger-button>
                                </div>
                            </form>
                        </x-modal>
                        <!-- End Delete Button -->
                    @endif

                    <!-- Pagination Links -->
                    <div class="px-4">
                        {{ $movements->onEachSide(1)->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>