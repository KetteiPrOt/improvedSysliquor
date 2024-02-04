<thead>
    <tr>
        <x-kardex.table.triple-header />
        <x-kardex.table.triple-header>
            <x-slot name="second">
                Ingresos
            </x-slot>
        </x-kardex.table.triple-header>
        <x-kardex.table.triple-header>
            <x-slot name="second">
                Egresos
            </x-slot>
        </x-kardex.table.triple-header>
        <x-kardex.table.triple-header>
            <x-slot name="second">
                Existencias
            </x-slot>
        </x-kardex.table.triple-header>
    </tr>
    <tr>
        <x-kardex.table.triple-header>
            <x-slot name="first">
                Fecha
            </x-slot>
            <x-slot name="second">
                Cliente/<br>Proveedor
            </x-slot>
            <x-slot name="third">
                Tipo
            </x-slot>
        </x-kardex.table.triple-header>
        @for($i = 0; $i < 3; $i++)
            <x-kardex.table.triple-header>
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
        @endfor
    </tr>
</thead>