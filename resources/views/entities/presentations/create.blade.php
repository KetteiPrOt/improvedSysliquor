<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Crear presentación') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl sm:mx-auto">

                    <form action="{{route('presentations.store')}}" method="post">
                        @csrf

                        <x-input-label :required="true">
                            Contenido (ml)
                        </x-input-label>
                        <x-number-input
                            name="content" required
                            min="1" max="65500"
                        />
                        <x-input-error
                            :messages="$errors->get('content')"
                        />

                        <br>
                        <x-primary-button class="mt-2">
                            Guardar
                        </x-primary-button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>