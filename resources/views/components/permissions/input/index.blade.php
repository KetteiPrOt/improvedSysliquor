@props(['permissions' => collect([]), 'translator'])

@php
    $visualLayout = false;
@endphp

@vite([
    'resources/css/components/permissions/input/styles.css',
])

<div>
    <div class="{{$visualLayout ? 'bg-green-300' : ''}} flex justify-start">
        
        <div class="
            {{$visualLayout ? 'bg-red-400' : ''}}
            flex justify-center items-center
        ">
            <div class="
                {{$visualLayout ? 'bg-yellow-200' : ''}}
                {{-- Main Line Height --}}
                h-[91%]
                flex justify-center items-center
                border-r-2 border-black
            ">
                <span
                    class="p-2 border-2 border-black rounded-full"
                >Panel</span>
                <div
                    class="w-5 h-0.5 bg-black"
                ></div>
            </div>
        </div>
        <div class="{{$visualLayout ? 'bg-blue-400' : ''}}">
            {{-- Permission Buttons --}}
            @foreach($translator->directPermissions as $name)
                <x-permissions.input.button
                    id="{{$name}}Btn"
                    class="{{!$loop->first ? 'mt-2' : ''}}"
                    :status="
                        $permissions->contains($name)
                        || !is_null(old($name))
                    "
                >
                    {{$translator->translate($name)}}
                </x-permissions.input.button>
            @endforeach
        </div>

        {{-- True HTML Inputs --}}
        @foreach($translator->directPermissions as $name)
            <input
                class="hidden" type="checkbox"
                name="{{$name}}" id="{{$name}}Input"
                @checked(old($name, $permissions->contains($name)))
            />
        @endforeach

        @vite([
            'resources/js/components/permissions/input/script.js',
        ])

    </div>

    @foreach($translator->directPermissions as $name)
        <x-input-error :messages="$errors->get($name)"/>
    @endforeach
</div>
