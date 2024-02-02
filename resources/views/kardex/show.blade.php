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

                    <!-- Normal Table -->
                    <table class="hidden xl:table border-collapse table-auto w-full text-sm mb-2">
                        <thead>
                            <tr>
                                <x-kardex.table.triple-header />
                                <x-kardex.table.triple-header>
                                    <x-slot name="second">
                                        Ingresos
                                    </x-slot>
                                </x-kardex.table.triple-header>
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
                                @for($i = 0; $i < 3; $i++)
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
                        <tbody class="bg-white dark:bg-slate-800">
                            <!-- Table rows -->
                            @foreach($movements as $movement)
                                <tr class="hover:bg-slate-100">
                                    <!-- Movement information -->
                                    <x-kardex.table.triple-cell :textleft="true">
                                        <x-slot name="first">
                                            {{
                                                date(
                                                    'd/m/Y',
                                                    strtotime($movement->invoice->date)
                                                )
                                            }}
                                        </x-slot>
                                        <x-slot name="second">
                                            {{$movement->invoice->person->name}}
                                        </x-slot>
                                        <x-slot name="third">
                                            {{$movement->movementType->name}}
                                            <a href="{{route('kardex.showMovement', $movement->id)}}" class="text-blue-400 underline">
                                                (Detalles)
                                            </a>
                                        </x-slot>
                                    </x-kardex.table.triple-cell>
                                    <!-- Incomes -->
                                    @if($movement->movementType->movementCategory->name == $movementCategories['income'])
                                        <x-kardex.table.triple-cell>
                                            <x-slot name="first">
                                                {{$movement->amount}}
                                            </x-slot>
                                            <x-slot name="second">
                                                {{$movement->unitary_price}}
                                            </x-slot>
                                            <x-slot name="third">
                                                {{round($movement->amount * $movement->unitary_price, 2, PHP_ROUND_HALF_UP)}}
                                            </x-slot>
                                        </x-kardex.table.triple-cell>
                                    @else
                                        <x-kardex.table.triple-cell />
                                    @endif
                                    <!-- Expenses -->
                                    @if($movement->movementType->movementCategory->name == $movementCategories['expense'])
                                        <x-kardex.table.triple-cell>
                                            <x-slot name="first">
                                                {{$movement->amount}}
                                            </x-slot>
                                            <x-slot name="second">
                                                {{$movement->unitary_price}}
                                            </x-slot>
                                            <x-slot name="third">
                                                {{round($movement->amount * $movement->unitary_price, 2, PHP_ROUND_HALF_UP)}}
                                            </x-slot>
                                        </x-kardex.table.triple-cell>
                                    @else
                                        <x-kardex.table.triple-cell />
                                    @endif
                                    <!-- Balance -->
                                    <x-kardex.table.triple-cell>
                                        <x-slot name="first">
                                            {{$movement->balance->amount}}
                                        </x-slot>
                                        <x-slot name="second">
                                            {{$movement->balance->unitary_price}}
                                        </x-slot>
                                        <x-slot name="third">
                                            {{round($movement->balance->amount * $movement->balance->unitary_price, 2,  PHP_ROUND_HALF_UP)}}
                                        </x-slot>
                                    </x-kardex.table.triple-cell>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <!-- Responsive Table -->
                    <div id="responsiveTable" class="xl:hidden mb-2">
                        @foreach($movements as $movement)
                            <!-- Item  -->
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
                                        {{
                                            $movement->date . " | " .
                                            $movement->movementType->name .
                                            ' (' . $movement->movementType->movementCategory->name . ')'
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
                                        <p>
                                            <a href="{{route('kardex.showMovement', $movement->id)}}" class="text-blue-400 underline">
                                                Ver detalles
                                            </a>
                                        </p>
                                        <p class="mt-3">
                                            <strong>Cliente/Proveedor: </strong>
                                            {{$movement->invoice->person->name}}
                                        </p>
                                        <p class="mt-3">
                                            <strong>Información del Movimiento:</strong>
                                        </p>
                                        <table class="border-collapse table-auto w-full text-sm">
                                            <thead>
                                                <tr>
                                                    <x-kardex.table.triple-header :xBorders="false">
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
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <x-kardex.table.triple-cell :xBorders="false">
                                                        <x-slot name="first">
                                                            {{$movement->amount}}
                                                        </x-slot>
                                                        <x-slot name="second">
                                                            {{$movement->unitary_price}}
                                                        </x-slot>
                                                        <x-slot name="third">
                                                            {{round($movement->amount * $movement->unitary_price, 2, PHP_ROUND_HALF_UP)}}
                                                        </x-slot>
                                                    </x-kardex.table.triple-cell>
                                                </tr>
                                            </tbody>
                                        </table>                                        
                                        <p>
                                            <strong>Existencias:</strong>
                                        </p>
                                        <table class="border-collapse table-auto w-full text-sm">
                                            <thead>
                                                <tr>
                                                    <x-kardex.table.triple-header :xBorders="false">
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
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <x-kardex.table.triple-cell :xBorders="false">
                                                        <x-slot name="first">
                                                            {{$movement->balance->amount}}
                                                        </x-slot>
                                                        <x-slot name="second">
                                                            {{$movement->balance->unitary_price}}
                                                        </x-slot>
                                                        <x-slot name="third">
                                                            {{round($movement->balance->amount * $movement->balance->unitary_price, 2,  PHP_ROUND_HALF_UP)}}
                                                        </x-slot>
                                                    </x-kardex.table.triple-cell>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <!-- Delete last movement button -->
                    {{-- @can('pop movement')
                        @if($movements->currentPage() == $movements->lastPage())
                            <form action="{{route('kardex.popMovement')}}" method="POST" class="px-4 mb-6 flex justify-center xl:justify-normal">
                                @csrf
                                @method('delete')
                                <input hidden type="number" name="product" value="{{$product->id}}">
                                <input hidden type="date" name="date" value="{{$date}}">
                                <x-danger-button>
                                    Eliminar Movimiento
                                </x-danger-button>
                            </form>
                        @endif
                    @endcan --}}
                    <!-- Pagination Links -->
                    <div class="px-4">
                        {{ $movements->onEachSide(1)->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>