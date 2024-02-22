<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ver Tipos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl sm:mx-auto">

                    <x-secondary-link-button :href="route('types.create')">
                        Agregar Nuevo
                    </x-secondary-link-button>

                    <x-table class="mt-4">
                        <x-slot:thead>
                            <x-table.tr :hover="false">
                                <x-table.th>
                                    Tipos (click para ver detalles)
                                </x-table.th>
                                <x-table.th>
                                    <div class="w-full h-full flex justify-center items-center">
                                        Estado
                                        <x-icons.info
                                            x-data="" class="w-4 h-4 ml-1 text-gray-700"
                                            x-on:click.prevent="$dispatch('open-modal', 'status-info')"
                                        /> <br>
                                        <x-modal name="status-info" focusable>
                                            <div class="p-5 text-gray-700">
                                                Cuando un tipo esta inactivo no se mostrará en las opciones a la hora de crear o editar un producto.
                                            </div>
                                        </x-modal>
                                    </div>
                                </x-table.th>
                            </x-table.tr>
                        </x-slot:thead>
                        <x-slot:tbody>
                            @foreach($types as $type)
                                <x-table.tr :danger="!$type->active">
                                    <x-table.td>
                                        <a href="{{route('types.show', $type->id)}}">
                                            {{$type->name}}
                                        </a>
                                    </x-table.td>
                                    <x-table.td>
                                        {{
                                            $type->active
                                            ? 'Activo' : 'Inactivo'
                                        }}
                                    </x-table.td>
                                </x-table.tr>
                            @endforeach
                        </x-slot:tbody>
                    </x-table>
                    @if($types->isNotEmpty())
                        {{$types->links()}}
                    @else
                        <p class="text-red-500">
                            No se encontraron tipos...
                        </p>
                    @endif
                
                </div>
            </div>
        </div>
    </div>
</x-app-layout>