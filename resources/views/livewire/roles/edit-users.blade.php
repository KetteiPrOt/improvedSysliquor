<div>

    {{-- Current Users --}}
    <x-table>
        <x-slot:thead>
            <x-table.tr :hover="false">
                <x-table.th>
                    Usuarios
                </x-table.th>
                <x-table.th class="text-center">
                    Quitar
                </x-table.th>
            </x-table.tr>
        </x-slot:thead>
        <x-slot:tbody>
            @forelse($currentUsers as $id => $currentUser)
                <x-table.tr>
                    <x-table.td>
                        {{$currentUser}}
                    </x-table.td>
                    <x-table.td class="flex justify-center">
                        <x-remove-button
                            class="w-6 h-6"
                            wire:click="removeUser({{$id}}, {{$role->id}})"
                        />
                    </x-table.td>
                </x-table.tr>
            @empty
                <x-table.tr>
                    <x-table.td>
                        <span class="text-red-500">
                            No hay usuarios con este rol...
                        </span>
                    </x-table.td>
                    <x-table.td></x-table.td>
                </x-table.tr>
            @endforelse
        </x-slot:tbody>
    </x-table>

    {{-- Add Users --}}
    <p class="mt-3 mb-1">
        <strong>Agregar Usuario</strong>
    </p>
    <x-text-input
        class="mb-3"
        placeholder="buscar..."
        wire:model.live="search"
    />
    <x-table>
        <x-slot:thead>
            <x-table.tr :hover="false">
                <x-table.th>
                    Usuarios
                </x-table.th>
                <x-table.th class="text-center">
                    Agregar
                </x-table.th>
            </x-table.tr>
        </x-slot:thead>
        <x-slot:tbody>
            @forelse($users as $user)
                <x-table.tr>
                    <x-table.td>
                        {{$user->name}}
                    </x-table.td>
                    <x-table.td class="flex justify-center">
                        <x-add-button
                            class="w-6 h-6"
                            wire:click="addUser({{$user->id}}, {{$role->id}})"
                        />
                    </x-table.td>
                </x-table.tr>
            @empty
                <x-table.tr>
                    <x-table.td>
                        <span class="text-red-500">
                            No se encontraron usuarios...
                        </span>
                    </x-table.td>
                    <x-table.td></x-table.td>
                </x-table.tr>
            @endforelse
        </x-slot:tbody>
    </x-table>
    {{$users->onEachSide(1)->links(data: ['scrollTo' => false])}}
</div>
