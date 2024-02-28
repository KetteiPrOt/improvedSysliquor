<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar roles de usuario') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl sm:mx-auto">

                    <p class="mb-3">
                        <strong>Usuario:</strong>
                        {{$user->name}}
                    </p>

                    <livewire:user-permissions.edit-roles
                        :$user
                        :roles="$user->roles"
                    />

                    <x-secondary-link-button
                        class="mt-4"
                        :href="route('user-permissions.users')"
                    >Volver</x-secondary-link-button>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>