<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Crear Rol') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl sm:mx-auto">

                    <form action="{{route('roles.store')}}" method="post">
                        @csrf

                        <x-input-label :required="true">
                            Nombre
                        </x-input-label>
                        <x-text-input
                            placeholder="..."
                            name="name" required
                            minlength="2" maxlength="255"
                        />
                        <x-input-error
                            :messages="$errors->get('name')"
                        />

                        <p class="mt-3">
                            <strong>Permisos</strong>
                        </p>

                        <x-permissions.input
                            :$translator
                        />

                        <div class="flex justify-center mt-3 sm:justify-start">
                            <x-primary-button class="mr-1">
                                Guardar
                            </x-primary-button>
                            <x-secondary-link-button
                                class="ml-1"
                                :href="route('roles.index')"
                            >
                                Volver
                            </x-secondary-link-button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>