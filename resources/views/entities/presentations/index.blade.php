<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ver presentaciones') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl sm:mx-auto">

                    <div class="flex justify-center mb-6 sm:justify-end">
                        <x-secondary-link-button
                            :href="route('presentations.create')"
                        >
                            Agregar Presentación
                        </x-secondary-link-button>
                    </div>

                    <x-table>
                        <x-slot:thead>
                            <x-table.tr :hover="false">
                                <x-table.th>
                                    Presentación (click para editar)
                                </x-table.th>
                                <x-table.th>
                                    Estado
                                    <x-icons.info
                                        x-data="" class="w-4 h-4 ml-1 text-gray-700"
                                        x-on:click.prevent="$dispatch('open-modal', 'status-info')"
                                    /> <br>
                                    <x-modal name="status-info" focusable>
                                        <div class="p-5 text-gray-700">
                                            Cuando una presentación esta inactiva no se mostrará en las opciones a la hora de crear o editar un producto.
                                        </div>
                                    </x-modal>
                                </x-table.th>
                            </x-table.tr>
                        </x-slot:thead>
                        <x-slot:tbody>
                            @forelse($presentations as $presentation)
                                <x-table.tr
                                    :danger="!$presentation->active"
                                >
                                    <x-table.td>
                                        <a href="{{route('presentations.show', $presentation->id)}}">
                                            {{$presentation->content}} ML
                                        </a>
                                    </x-table.td>
                                    <x-table.td>
                                        {{
                                            $presentation->active
                                            ? 'Activo' : 'Inactivo'
                                        }}
                                    </x-table.td>
                                </x-table.tr>
                            @empty
                                <x-table.tr>
                                    <x-table.td>
                                        <span class="text-red-500">
                                            No hay presentaciones...
                                        </span>
                                    </x-table.td>
                                    <x-table.td></x-table.td>
                                </x-table.tr>
                            @endforelse
                        </x-slot:tbody>
                    </x-table>
                    {{$presentations->onEachSide(1)->links()}}

                </div>
            </div>
        </div>
    </div>
</x-app-layout>