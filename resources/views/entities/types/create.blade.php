<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Agregar Tipo') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl sm:mx-auto">

                    <form action="{{route('types.store')}}" method="post">
                        @csrf

                        <x-input-label :required="true">
                            Nombre
                        </x-input-label>
                        <x-text-input
                            name="name" value="{{old('name')}}"
                            required minlength="2" maxlength="20"
                        />
                        <x-input-error :messages="$errors->get('name')" />

                        <br>
                        <x-primary-button class="mt-2">
                            Guardar
                        </x-primary-button>
                        <x-secondary-link-button :href="route('types.index')">
                            Volver
                        </x-secondary-link-button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>