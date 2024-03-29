@props(['data'])

@php
    extract($data);
@endphp

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
                $tag
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
            <p class="mb-3">
                <a href="{{route('kardex.showMovement', $movement->id)}}" class="text-blue-400 underline">
                    Ver detalles
                </a>
            </p>
            <p class="mb-3">
                @if(
                    $movement->movementType->movementCategory->name
                    == $movementCategories['income']
                )
                    <strong>Proveedor: </strong>
                    {{
                        $movement->invoice->person
                        ? $movement->invoice->person->name
                        : 'Desconocido'
                    }}
                @else
                    <strong>Cliente: </strong>
                    {{
                        $movement->invoice->person
                        ? $movement->invoice->person->name
                        : 'Consumidor Final'
                    }}
                @endif
            </p>

            <!-- Body -->
            {{$body}}
        </div>
    </div>
</div>