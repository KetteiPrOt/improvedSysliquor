@props(['user', 'translator'])

<div
    class="w-full h-full cursor-pointer"
    x-data=""
    x-on:click="$dispatch('open-modal', 'user-{{$user->id}}-info')"
>
    {{$user->name}}
</div>
<x-modal name="user-{{$user->id}}-info" focusable>
    <div class="p-2 sm:p-4">
        <h3 class="text-lg font-bold">
            <strong>{{$user->name}}</strong>
        </h3>

        <h4 class="text-lg font-bold">
            <strong>Roles:</strong>
        </h4>
        <p>
            @forelse($user->roles as $role)
                @if($loop->last)
                    {{$role->name}}.
                @else
                    {{$role->name}}, 
                @endif
            @empty
                Ninguno.
            @endforelse
        </p>

        <x-secondary-link-button
            class="mt-2 mb-2"
            :href="route('user-permissions.edit-roles', $user->id)"
        >
            Modificar Roles
        </x-secondary-link-button>

        <h4 class="text-lg font-bold">
            <strong>Permisos Directos:</strong>
        </h4>
        <p>
            @forelse($user->getDirectPermissions() as $permission)
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
        </p>

        <x-secondary-link-button
            class="mt-2"
            :href="route('user-permissions.edit', $user->id)"
        >
            Modificar permisos
        </x-secondary-link-button>
    </div>
</x-modal>