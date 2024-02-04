<table class="mb-3 border-collapse table-auto w-full text-sm">
    <thead>
        <tr>
            <x-kardex.table.triple-header :xBorders="false">
                <x-slot name="first">
                    Cantidad
                </x-slot>
                <x-slot name="second">
                    Precio Unitario
                </x-slot>
                <x-slot name="third">
                    Total
                </x-slot>
            </x-kardex.table.triple-header>
        </tr>
    </thead>
    <tbody>
        <tr>
            {{$slot}}
        </tr>
    </tbody>
</table>   