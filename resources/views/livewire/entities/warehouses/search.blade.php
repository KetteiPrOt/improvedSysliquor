<div>
    
    <x-input-label class="mt-4">
        Filtrar por Bodega
    </x-input-label>

    <x-text-input wire:model.live.debounce="search" placeholder="Buscar..." />
    
    <x-secondary-button wire:click.prevent="removeFilter" class="mt-1">
        Remover filtro
    </x-secondary-button>

    @error('warehouse') <p class="text-red-600">{{$message}}</p> @enderror
    
    @if($currentWarehouse || $warehouses)
        <x-table>
            <x-slot:thead></x-slot:thead>
            <x-slot:tbody>
                @if($warehouses)
                    @foreach($warehouses as $warehouse)
                        <x-table.tr wire:key="{{$warehouse->id}}">
                            <x-table.td>
                                <input
                                    type="radio" class="input-warehouse mr-4"
                                    name="warehouse" value="{{$warehouse->id}}"
                                    id="warehouse{{$warehouse->id}}"
                                >
                                <label for="warehouse{{$warehouse->id}}">
                                    {{$warehouse->name}}
                                </label>
                            </x-table.td>
                        </x-table.tr>
                    @endforeach
                @else
                    <x-table.tr>
                        <x-table.td>
                            <input
                                type="radio" class="input-warehouse mr-4"
                                name="warehouse" value="{{$currentWarehouse->id}}"
                                checked id="warehouse{{$currentWarehouse->id}}"
                            >
                            <label for="warehouse{{$currentWarehouse->id}}">
                                {{$currentWarehouse->name}}
                            </label>
                        </x-table.td>
                    </x-table.tr>
                @endif
            </x-slot:tbody>
        </x-table>
    @endif
    @if($warehouses)
        {{$warehouses->onEachSide(1)->links(data: ['scrollTo' => false])}}
    @endif

</div>
