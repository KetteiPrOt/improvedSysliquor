<div>
    <x-input-label>
        Cliente
    </x-input-label>

    <x-text-input
        placeholder="Buscar..."
        wire:model.live.debounce="search"
    />

    <x-table>
        <x-slot:thead></x-slot:thead>
        <x-slot:tbody>
            <x-table.tr>
                <x-table.td>
                    <div class="flex items-center">
                        <input
                            value=""
                            name="client"
                            type="radio"
                            id="client_0" required
                            @checked(is_null(old('client')))
                        >
                        <label for="client_0" class="ml-2">
                            Consumidor final
                        </label>
                    </div>
                </x-table.td>
            </x-table.tr>
            @if($clients)
                @foreach($clients as $client)
                    <x-table.tr>
                        <x-table.td>
                            <div class="flex items-center">
                                <input
                                    value="{{$client->id}}"
                                    name="client"
                                    type="radio" required
                                    id="client_{{$client->id}}"
                                    @checked(old('client') == $client->id)
                                >
                                <label for="client_{{$client->id}}" class="ml-2">
                                    {{$client->name}}
                                </label>
                            </div>
                        </x-table.td>
                    </x-table.tr>
                @endforeach
            @endif
        </x-slot:tbody>
    </x-table>
    @if($clients)
        {{$clients->onEachSide(1)->links(data: ['scrollTo' => false])}}
    @endif
</div>
