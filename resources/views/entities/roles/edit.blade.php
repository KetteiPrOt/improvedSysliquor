<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Rol') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl sm:mx-auto">

                    <p>
                        <strong>Rol:</strong>
                        {{$role->name}}
                    </p>

                    <form class="mt-3" action="{{route('roles.update', $role->id)}}" method="post">
                        @csrf
                        @method('put')
                        
                        <x-input-label :required="true">
                            Nuevo Nombre
                        </x-input-label>
                        <x-text-input
                            name="name"
                            value="{{old('name', $role->name)}}"
                        />
                        <x-input-error
                            :messages="$errors->get('name')"
                        />

                        <p class="mt-3">
                            <strong>Permisos</strong>
                        </p>

                        <x-permissions.input
                            :$translator
                            :permissions="$role->permissions->pluck('name')"
                        />

                        <div class="flex mt-3 justify-center sm:justify-start">
                            <x-primary-button
                                class="mr-1"
                            >
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