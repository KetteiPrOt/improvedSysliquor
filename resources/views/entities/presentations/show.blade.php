<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ver presentación') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl sm:mx-auto">

                    <p>
                        <strong>Contenido:</strong>
                        {{$presentation->content}} ML
                    </p>
                    <p>
                        <strong>Estado:</strong>
                        <span 
                            class="{{
                                $presentation->active ? 'text-green-500' : 'text-red-500'
                            }}"
                        >{{$presentation->active ? 'Activo' : 'Inactivo'}}</span>
                    </p>

                    <x-secondary-link-button
                        class="mt-4"
                        :href="route('presentations.edit', $presentation->id)"
                    >
                        Editar
                    </x-secondary-link-button>

                    <x-secondary-link-button
                        class="mt-4"
                        :href="route('presentations.index')"
                    >
                        Volver
                    </x-secondary-link-button>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>