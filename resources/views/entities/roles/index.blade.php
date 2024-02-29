<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ver Roles') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl sm:mx-auto">

                    <form class="
                        flex flex-col items-center
                        sm:flex-row sm:justify-between
                    ">
                        <x-secondary-link-button
                            :href="route('roles.create')"
                            class="mb-4 sm:order-2 sm:m-0"
                        >
                            Agregar Nuevo Rol
                        </x-secondary-link-button>

                        <div class="
                            flex flex-col items-center
                            sm:flex-row sm:order-1
                        ">
                            <x-text-input
                                name="search"
                                placeholder="Buscar un rol..."
                                minlength="2" maxlength="255"
                                value="{{old('search', $search ?? null)}}"
                            />
                            <x-primary-button class="mt-1 ml-1 sm:mt-0">
                                Buscar
                            </x-primary-button>
                        </div>
                    </form>
                    <x-input-error
                        :messages="$errors->get('search')"
                    />

                    <x-table class="mt-6">
                        <x-slot:thead>
                            <x-table.tr :hover="false">
                                <x-table.th>
                                    Rol (click para inspeccionar)
                                </x-table.th>
                            </x-table.tr>
                        </x-slot:thead>
                        <x-slot:tbody>
                            @forelse($roles as $role)
                                <x-table.tr>
                                    <x-table.td>
                                        <x-roles.index.modal
                                            :$role
                                            :$translator
                                        />
                                    </x-table.td>
                                </x-table.tr>
                                <x-modal name="role-{{$role->id}}-delete" focusable>
                                    <div class="p-2 sm:p-4">
                                        <form action="{{route('roles.destroy', $role->id)}}" method="post">
                                            @csrf
                                            @method('delete')
                                            
                                            <h2 class="text-lg font-medium text-gray-900">
                                                {{ __('¿Seguro que deseas eliminar el rol?') }}
                                            </h2>
            
                                            <p class="mt-1 text-sm text-gray-600">
                                                {{ __('Una vez elimines el rol todos los usuarios asociados perderán los permisos del mismo.') }}
                                            </p>
            
                                            <div class="mt-6 flex justify-end">
                                                <x-secondary-button x-on:click="$dispatch('close')">
                                                    {{ __('Cancelar') }}
                                                </x-secondary-button>
            
                                                <x-danger-button class="ml-3">
                                                    {{ __('Eliminar') }}
                                                </x-danger-button>
                                            </div>
                                        </form>
                                    </div>
                                </x-modal>
                            @empty
                                <x-table.tr>
                                    <x-table.td>
                                        <span class="text-red-500">
                                            No se encontraron roles...
                                        </span>
                                    </x-table.td>
                                </x-table.tr>
                            @endforelse
                        </x-slot:tbody>
                    </x-table>
                    {{$roles->onEachSide(1)->links()}}

                </div>
            </div>
        </div>
    </div>
</x-app-layout>