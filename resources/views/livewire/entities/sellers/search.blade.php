<div>
    <x-input-label class="mt-4">
        Filtrar por Vendedor
    </x-input-label>

    <x-text-input wire:model.live.debounce="search" placeholder="Buscar..." />

    <x-secondary-button wire:click.prevent="removeFilter" class="mt-1">
        Remover filtro
    </x-secondary-button>

    @error('seller') <p class="text-red-600">{{$message}}</p> @enderror

    <x-table>
        <x-slot:thead></x-slot:thead>
        <x-slot:tbody>
            @if($sellers)
                @foreach($sellers as $seller)
                    <x-table.tr wire:key="{{$seller->id}}">
                        <x-table.td>
                            <input
                                type="radio" class="input-seller mr-4"
                                name="seller" value="{{$seller->id}}"
                                id="seller{{$seller->id}}"
                            >
                            <label for="seller{{$seller->id}}">
                                {{$seller->person->name}}
                            </label>
                        </x-table.td>
                    </x-table.tr>
                @endforeach
            @else
                <x-table.tr>
                    <x-table.td>
                        <input
                            type="radio" class="input-seller mr-4"
                            name="seller" value="{{$currentSeller->id}}"
                            checked id="seller{{$currentSeller->id}}"
                        >
                        <label for="seller{{$currentSeller->id}}">
                            {{$currentSeller->person->name}}
                        </label>
                    </x-table.td>
                </x-table.tr>
            @endif
        </x-slot:tbody>
    </x-table>

    @if($sellers)
        {{$sellers->onEachSide(1)->links(data: ['scrollTo' => false])}}
    @endif
</div>
