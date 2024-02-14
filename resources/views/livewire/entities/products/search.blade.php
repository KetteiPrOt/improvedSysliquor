<div>
    <x-input-label class="mt-4">
        Filtrar por Producto
    </x-input-label>

    <x-text-input wire:model.live.debounce="search" placeholder="Buscar..." />

    <x-secondary-button wire:click.prevent="removeFilter" class="mt-1">
        Remover filtro
    </x-secondary-button>

    @error('product') <p class="text-red-600">{{$message}}</p> @enderror

    @if($products)
        <x-table>
            <x-slot:thead></x-slot:thead>
            <x-slot:tbody>
                @foreach($products as $product)
                    <x-table.tr wire:key="{{$product->id}}">
                        <x-table.td>
                            <input
                                type="radio" class="input-product mr-4"
                                name="product" value="{{$product->id}}"
                                id="product{{$product->id}}"
                            >
                            <label for="product{{$product->id}}">
                                {{$product->productTag()}}
                            </label>
                        </x-table.td>
                    </x-table.tr>
                @endforeach
            </x-slot:tbody>
        </x-table>

        @if($products)
            {{$products->onEachSide(1)->links(data: ['scrollTo' => false])}}
        @endif
    @endif
</div>
