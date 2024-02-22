<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Tipo') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl sm:mx-auto">

                    <form action="{{route('types.update', $type->id)}}" method="post">
                        @method('put')
                        @csrf

                        {{-- Active --}}
                        <x-input-label :required="true">
                            Estado
                        </x-input-label>
                        <input
                            class="rounded mr-1"
                            type="checkbox" name="active" id="active"
                            @checked(old('active', $type->active))
                        />
                        <label for="active">Activo</label>
                        <x-icons.info
                            x-data="" class="align-middle w-4 h-4 ml- text-gray-700"
                            x-on:click.prevent="$dispatch('open-modal', 'status-info')"
                        />
                        <x-modal name="status-info" focusable>
                            <div class="p-5">
                                Cuando un tipo esta inactivo no se mostrará en las opciones a la hora de crear o editar un producto.
                            </div>
                        </x-modal>
                        <x-input-error :messages="$errors->get('active')" />

                        {{-- Name --}}
                        <x-input-label :required="true">
                            Nombre
                        </x-input-label>
                        <x-text-input
                            name="name" value="{{old('name', $type->name)}}"
                            required minlength="2" maxlength="20"
                        />
                        <x-input-error :messages="$errors->get('name')" />

                        <br>
                        <x-primary-button class="mt-2">
                            Guardar
                        </x-primary-button>
                        <x-secondary-link-button :href="route('types.show', $type->id)">
                            Volver
                        </x-secondary-link-button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>