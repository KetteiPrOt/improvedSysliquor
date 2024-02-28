<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ver permisos de usuarios') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl sm:mx-auto">

                    <form action="{{route('user-permissions.users')}}">
                        <x-text-input
                            name="search" minlength="2" maxlength="255"
                            value="{{old('search', $search ?? null)}}"
                        />
                        <x-primary-button class="mt-1 mb-3">
                            Buscar
                        </x-primary-button>
                        <x-input-error
                            :messages="$errors->get('search')"
                        />
                    </form>

                    <x-table>
                        <x-slot:thead>
                            <x-table.tr :hover="false">
                                <x-table.th>
                                    Usuario (click para inspeccionar)
                                </x-table.th>
                            </x-table.tr>
                        </x-slot:thead>
                        <x-slot:tbody>
                            @foreach($users as $user)
                                <x-table.tr>
                                    <x-table.td>
                                        <x-user-permissions.users.modal
                                            :$user
                                            :$translator
                                        />
                                    </x-table.td>
                                </x-table.tr>
                            @endforeach
                        </x-slot:tbody>
                    </x-table>
                    @if($users->isNotEmpty())
                        {{$users->onEachSide(1)->links()}}
                    @else
                        <p class="text-red-500">
                            No se encontraron usuarios...
                        </p>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>