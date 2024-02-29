@props(['role', 'translator'])

<div
    class="w-full h-full cursor-pointer"
    x-data=""
    x-on:click="$dispatch('open-modal', 'role-{{$role->id}}-info')"
>
    {{$role->name}}
</div>
<x-modal name="role-{{$role->id}}-info" focusable>
    <div class="p-2 sm:p-4">
        <h3 class="text-lg font-bold">
            <strong>{{$role->name}}</strong>
        </h3>

        <h4 class="text-lg font-bold">
            <strong>Permisos:</strong>
        </h4>
        <p>
            @if($role->name == 'Administrador')
                <p>Todos.</p>
            @else
                @forelse($role->permissions as $permission)
                    @if($loop->last)
                        {{$translator->translate(
                            $permission->name
                        )}}.
                    @else
                        {{$translator->translate(
                            $permission->name
                        )}}, 
                    @endif
                @empty
                    Ninguno.
                @endforelse
            @endif
        </p>

        @if($role->name != 'Administrador')
            <x-secondary-link-button
                class="mt-2"
                :href="__('#')"
            >
                Modificar
            </x-secondary-link-button>

            <x-danger-button
                class="ml-1"
                x-data=""
                x-on:click="
                    $dispatch('close-modal', 'role-{{$role->id}}-info');
                    $dispatch('open-modal', 'role-{{$role->id}}-delete');
                "
            >
                Eliminar Rol
            </x-danger-button>
        @endif

        <h4 class="text-lg font-bold">
            <strong>Usuarios:</strong>
        </h4>
        <p>
            @forelse($role->users as $user)
                @if($loop->first)
                <ul class="list-inside list-disc"> @endif
                    <li>{{$user->name}}</li>
                @if($loop->last)
                </ul> @endif
            @empty
                Ninguno.
            @endforelse
        </p>

        <x-secondary-link-button
            class="mt-2 mb-2"
            :href="__('#')"
        >
            Modificar
        </x-secondary-link-button>
    </div>
</x-modal>
