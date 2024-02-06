<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Ver Venta
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 xl:max-w-8xl space-y-6">
            <div class="py-4 bg-white shadow sm:rounded-lg">
                <div class="max-w-full">

                    <p class="px-4 mb-4">
                        <strong>Fecha:</strong>
                        {{
                            date(
                                'd/m/Y',
                                strtotime($invoice->date)
                            )
                        }}
                    </p>

                    <p class="px-4 mb-4">
                        <strong>Movimientos:</strong>
                    </p>

                    <!-- Primary Table -->
                    <x-kardex.table>
                        <thead>
                            <tr>
                                <x-kardex.table.triple-header />
                                <x-kardex.table.triple-header>
                                    <x-slot name="second">
                                        Egresos
                                    </x-slot>
                                </x-kardex.table.triple-header>
                                <x-kardex.table.triple-header>
                                    <x-slot name="second">
                                        Existencias
                                    </x-slot>
                                </x-kardex.table.triple-header>
                            </tr>
                            <tr>
                                <x-kardex.table.triple-header>
                                    <x-slot name="first">
                                        Fecha
                                    </x-slot>
                                    <x-slot name="second">
                                        Cliente/<br>Proveedor
                                    </x-slot>
                                    <x-slot name="third">
                                        Tipo
                                    </x-slot>
                                </x-kardex.table.triple-header>
                                @for($i = 0; $i < 2; $i++)
                                    <x-kardex.table.triple-header>
                                        <x-slot name="first">
                                            Cantidad
                                        </x-slot>
                                        <x-slot name="second">
                                            Precio Unitario
                                        </x-slot>
                                        <x-slot name="third">
                                            Total
                                        </x-slot>
                                    </x-kardex.table.triple-header>
                                @endfor
                            </tr>
                        </thead>
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
                                                    strtotime($invoice->date)
                                                )
                                            }}
                                        </x-slot>
                                        <x-slot name="second">
                                            {{
                                                $invoice->person
                                                ? $invoice->person->name
                                                : 'Desconocido'
                                            }}
                                        </x-slot>
                                        <x-slot name="third">
                                            {{$movement->movementType->name}}
                                            @if($movement->isLast())
                                                <a
                                                    href="{{route('sales.edit', $movement->id)}}"
                                                    class="text-blue-400 underline"
                                                >
                                                    (Editable)
                                                </a>

                                                <!-- Start Delete button -->
                                                <x-danger-button-link
                                                    x-data=""
                                                    x-on:click.prevent="$dispatch('open-modal', 'confirm-movement-deletion')"
                                                >{{ __('(Eliminable)') }}</x-danger-button-link>

                                                <x-modal name="confirm-movement-deletion" focusable>
                                                    <form method="post" action="{{route('sales.destroy', $movement->id)}}" class="p-6">
                                                        @csrf
                                                        @method('delete')

                                                        <h2 class="text-lg font-medium text-gray-900">
                                                            {{ __('¿Estás seguro que deseas eliminar la compra?') }}
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
                                        </x-slot>
                                    </x-kardex.table.triple-cell>
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
                            <div
                                class="border border-neutral-200 bg-white dark:border-neutral-600 dark:bg-neutral-800"
                            >
                                <h2 class="mb-0" id="heading{{$movement->id}}">
                                    <button
                                        class="group relative flex w-full items-center rounded-none border-0 bg-white px-5 py-4 text-left text-base text-neutral-800 transition [overflow-anchor:none] hover:z-[2] focus:z-[3] focus:outline-none dark:bg-neutral-800 dark:text-white [&:not([data-te-collapse-collapsed])]:bg-white [&:not([data-te-collapse-collapsed])]:text-primary [&:not([data-te-collapse-collapsed])]:[box-shadow:inset_0_-1px_0_rgba(229,231,235)] dark:[&:not([data-te-collapse-collapsed])]:bg-neutral-800 dark:[&:not([data-te-collapse-collapsed])]:text-primary-400 dark:[&:not([data-te-collapse-collapsed])]:[box-shadow:inset_0_-1px_0_rgba(75,85,99)]"
                                        type="button"
                                        data-te-collapse-init
                                        data-te-collapse-collapsed
                                        data-te-target="#movement{{$movement->id}}"
                                        aria-expanded="false"
                                        aria-controls="movement{{$movement->id}}"
                                    >
                                        <!-- Tag -->
                                        {{
                                            date(
                                                'd/m/Y',
                                                strtotime($movement->invoice->date)
                                            )
                                            . " | "
                                            . $movement->movementType->name
                                        }}
                                        <span
                                            class="-mr-1 ml-auto h-5 w-5 shrink-0 rotate-[-180deg] fill-[#336dec] transition-transform duration-200 ease-in-out group-[[data-te-collapse-collapsed]]:mr-0 group-[[data-te-collapse-collapsed]]:rotate-0 group-[[data-te-collapse-collapsed]]:fill-[#212529] motion-reduce:transition-none dark:fill-blue-300 dark:group-[[data-te-collapse-collapsed]]:fill-white">
                                            <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke-width="1.5"
                                            stroke="currentColor"
                                            class="h-6 w-6">
                                            <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                            </svg>
                                        </span>
                                    </button>
                                </h2>

                                <div
                                    id="movement{{$movement->id}}"
                                    class="!visible hidden"
                                    data-te-collapse-item
                                    aria-labelledby="headingTwo"
                                    data-te-parent="#responsiveTable"
                                >
                                    <div class="px-5 py-4">
                                        
                                        @if($movement->isLast())
                                            <p class="mb-3">
                                                <a
                                                    href="{{route('sales.edit', $movement->id)}}"
                                                    class="text-blue-400 underline"
                                                >
                                                    (Editable)
                                                </a>
                                            </p>
                                            <!-- Start Delete button -->
                                            <x-danger-button-link
                                                x-data=""
                                                x-on:click.prevent="$dispatch('open-modal', 'confirm-movement-deletion')"
                                            >{{ __('(Eliminable)') }}</x-danger-button-link>

                                            <x-modal name="confirm-movement-deletion" focusable>
                                                <form method="post" action="{{route('sales.destroy', $movement->id)}}" class="p-6">
                                                    @csrf
                                                    @method('delete')

                                                    <h2 class="text-lg font-medium text-gray-900">
                                                        {{ __('¿Estás seguro que deseas eliminar la compra?') }}
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
                                        
                                        <p class="mb-3">
                                            @if(
                                                $movement->movementType->movementCategory->name
                                                == $movementCategories['income']
                                            )
                                                <strong>Proveedor: </strong>
                                            @else
                                                <strong>Cliente: </strong>
                                            @endif
                                            {{
                                                $movement->invoice->person
                                                ? $movement->invoice->person->name
                                                : 'Desconocido'
                                            }}
                                        </p>

                                        <!-- Body -->
                                        <p class="mb-3">
                                            <strong>
                                                Información:
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
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </x-kardex.responsive-table>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>