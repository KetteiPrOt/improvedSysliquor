<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Proveedor') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl sm:mx-auto">

                    <!-- Create form -->
                    <form action="{{route('providers.update', $provider->id)}}" method="POST">
                        @csrf
                        @method('put')
                        <!-- Name -->
                        <x-input-label for="name">
                            Nombre <span class="text-red-400">*</span>
                        </x-input-label>
                        <x-text-input
                            id="name" name="name" value="{{old('name', $provider->person->name)}}" 
                            required maxlength="75" placeholder="..."
                        />
                        <x-input-error :messages="$errors->get('name')" />
                        <!-- Phone -->
                        <x-input-label for="phone_number" :value="__('Celular')" />
                        <x-text-input id="phone_number" name="phone_number" value="{{old('phone_number', $provider->person->phone_number ?? null)}}" minlength="10" maxlength="10" placeholder="..." />
                        <x-input-error :messages="$errors->get('phone_number')" />
                        <!-- Email -->
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" name="email" value="{{old('email', $provider->person->email ?? null)}}" type="email" maxlength="320" placeholder="..." />
                        <x-input-error :messages="$errors->get('email')" />
                        <!-- RUC -->
                        <x-input-label for="ruc">
                            RUC <span class="text-red-400">*</span>
                        </x-input-label>
                        <x-text-input id="ruc" name="ruc" value="{{old('ruc', $provider->ruc)}}" maxlength="13" placeholder="..." />
                        <x-input-error :messages="$errors->get('ruc')" />
                        <!-- Social Reason -->
                        <x-input-label for="social_reason">
                            Razón Social <span class="text-red-400">*</span>
                        </x-input-label>
                        <x-text-input id="social_reason" name="social_reason" value="{{old('social_reason', $provider->social_reason)}}" maxlength="10" placeholder="..." />
                        <x-input-error :messages="$errors->get('social_reason')" />
                        <!-- Address -->
                        <x-input-label for="address" :value="__('Dirección')" />
                        <x-textarea-input 
                            id="address" name="address" maxlength="200" class="mt-1 block"
                        >{{old('address', $provider->person->address ?? null)}}</x-textarea-input>
                        <x-input-error :messages="$errors->get('address')" />

                        <!-- Save button -->
                        <x-primary-button class="mt-6">
                            Guardar
                        </x-primary-button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>