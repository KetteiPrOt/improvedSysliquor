<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Cliente') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl sm:mx-auto">

                    <!-- Create form -->
                    <form action="{{route('clients.update', $client->id)}}" method="POST">
                        @csrf
                        @method('put')
                        <!-- Name -->
                        <x-input-label for="name" :value="__('Nombre')" />
                        <x-text-input id="name" name="name" required value="{{old('name', $client->person->name)}}" required maxlength="75" placeholder="..." />
                        <x-input-error :messages="$errors->get('name')" />
                        <!-- Phone -->
                        <x-input-label for="phone_number" :value="__('Celular')" />
                        <x-text-input id="phone_number" name="phone_number" value="{{old('phone_number', $client->person->phone_number ?? null)}}" minlength="10" maxlength="10" placeholder="..." />
                        <x-input-error :messages="$errors->get('phone_number')" />
                        <!-- Email -->
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" name="email" value="{{old('email', $client->person->email ?? null)}}" type="email" maxlength="320" placeholder="..." />
                        <x-input-error :messages="$errors->get('email')" />
                        <!-- Identification Card -->
                        <x-input-label for="identification_card" :value="__('Cedula')" />
                        <x-text-input id="identification_card" name="identification_card" value="{{old('identification_card', $client->identification_card ?? null)}}" maxlength="10" placeholder="..." />
                        <x-input-error :messages="$errors->get('identification_card')" />
                        <!-- RUC -->
                        <x-input-label for="ruc" :value="__('RUC')" />
                        <x-text-input id="ruc" name="ruc" value="{{old('ruc', $client->ruc ?? null)}}" maxlength="13" placeholder="..." />
                        <x-input-error :messages="$errors->get('ruc')" />
                        <!-- Social Reason -->
                        <x-input-label for="social_reason" :value="__('Razón Social')" />
                        <x-text-input id="social_reason" name="social_reason" value="{{old('social_reason', $client->social_reason ?? null)}}" maxlength="10" placeholder="..." />
                        <x-input-error :messages="$errors->get('social_reason')" />
                        <!-- Address -->
                        <x-input-label for="address" :value="__('Dirección')" />
                        <x-textarea-input 
                            id="address" name="address" maxlength="200" class="mt-1 block"
                        >{{old('address', $client->person->address ?? null)}}</x-textarea-input>
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