<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar permisos de usuario') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl sm:mx-auto">

                    <form action="{{route('user-permissions.update', $user->id)}}" method="post">
                        @method('put')
                        @csrf

                        <p>
                            <strong>Usuario:</strong>
                            {{$user->name}}
                        </p>

                        <p class="mt-3">
                            <strong>Permisos Directos</strong>
                        </p>

                        <x-permissions.input
                            :permissions="$user->getDirectPermissions()->pluck('name')"
                            :$translator
                        />

                        <div class="mt-6 flex justify-center sm:justify-start">
                            <x-primary-button class="mr-1">
                                Guardar
                            </x-primary-button>
                            <x-secondary-link-button
                                class="ml-1"
                                :href="route('user-permissions.users')"
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