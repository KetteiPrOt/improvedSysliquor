<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ver Tipo') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl sm:mx-auto">

                    <p>
                        <strong>Nombre:</strong>
                        {{$type->name}}
                    </p>
                    <p>
                        <strong>Estado:</strong>
                        @if($type->active)
                            <span class="text-green-500">
                                Activo
                            </span>
                        @else
                            <span class="text-red-500">
                                Inactivo
                            </span>
                        @endif
                    </p>

                    <br>
                    <x-secondary-link-button :href="route('types.edit', $type->id)">
                        Editar
                    </x-secondary-link-button>
                    <x-secondary-link-button :href="route('types.index')">
                        Volver
                    </x-secondary-link-button>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>