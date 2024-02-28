<div>
    
    {{-- Current Roles --}}
    <x-table>
        <x-slot:thead>
            <x-table.tr :hover="false">
                <x-table.th>
                    Roles
                </x-table.th>
                <x-table.th class="text-center">
                    Quitar
                </x-table.th>
            </x-table.tr>
        </x-slot:thead>
        <x-slot:tbody>
            @forelse($currentRoles as $id => $currentRole)
                <x-table.tr>
                    <x-table.td>
                        {{$currentRole}}
                    </x-table.td>
                    <x-table.td class="flex justify-center">
                        <x-remove-button
                            class="w-6 h-6"
                            wire:click="removeRole({{$id}}, {{$user->id}})"
                        />
                    </x-table.td>
                </x-table.tr>
            @empty
                <x-table.tr>
                    <x-table.td>
                        <span class="text-red-500">
                            No hay roles asignados...
                        </span>
                    </x-table.td>
                    <x-table.td></x-table.td>
                </x-table.tr>
            @endforelse
        </x-slot:tbody>
    </x-table>

    {{-- Add Roles --}}
    <p class="mt-3 mb-1">
        <strong>Agregar Rol</strong>
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
                    Roles
                </x-table.th>
                <x-table.th class="text-center">
                    Agregar
                </x-table.th>
            </x-table.tr>
        </x-slot:thead>
        <x-slot:tbody>
            @forelse($roles as $role)
                <x-table.tr>
                    <x-table.td>
                        {{$role->name}}
                    </x-table.td>
                    <x-table.td class="flex justify-center">
                        <x-add-button
                            class="w-6 h-6"
                            wire:click="addRole({{$role->id}}, {{$user->id}})"
                        />
                    </x-table.td>
                </x-table.tr>
            @empty
                <x-table.tr>
                    <x-table.td>
                        <span class="text-red-500">
                            No se encontraron roles...
                        </span>
                    </x-table.td>
                    <x-table.td></x-table.td>
                </x-table.tr>
            @endforelse
        </x-slot:tbody>
    </x-table>
    {{$roles->onEachSide(1)->links(data: ['scrollTo' => false])}}

</div>
