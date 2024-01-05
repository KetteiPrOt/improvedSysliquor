<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ver Cliente') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <article class="max-w-xl sm:mx-auto">

                    <x-card-frame>
                        <x-card-title>
                            Información
                        </x-card-title>
                        @if(isset($client->ruc))
                            <x-card-item :tag="__('RUC')">
                                {{$client->ruc}}
                            </x-card-item>
                        @endif
                        @if(isset($client->social_reason))
                            <x-card-item :tag="__('Razón Social')">
                                {{$client->social_reason}}
                            </x-card-item>
                        @endif
                        @if(isset($client->identification_card))
                            <x-card-item :tag="__('Cédula')">
                                {{$client->identification_card}}
                            </x-card-item>
                        @endif  
                        <x-card-item :tag="__('Nombre')">
                            {{$client->person->name}}
                        </x-card-item>
                        @if(isset($client->person->phone_number))
                            <x-card-item :tag="__('Celular')">
                                {{$client->person->phone_number}}
                            </x-card-item>
                        @endif
                        @if(isset($client->person->email))
                            <x-card-item :tag="__('Email')">
                                {{$client->person->email}}
                            </x-card-item>
                        @endif
                        @if(isset($client->person->address))
                            <x-card-item :tag="__('Dirección')">
                                {{$client->person->address}}
                            </x-card-item>
                        @endif
                    </x-card-frame>

                    <div class="mt-3 flex justify-center sm:justify-start">
                        @if($client->id != $finalConsumer->id)
                            <x-secondary-link-button href="{{route('clients.edit', $client->id)}}" class="mr-3">
                                Editar
                            </x-secondary-link-button>
                        @endif

                        {{-- <!-- Start Delete button -->
                        <x-danger-button
                            x-data=""
                            x-on:click.prevent="$dispatch('open-modal', 'confirm-product-deletion')"
                        >{{ __('Eliminar') }}</x-danger-button>

                        <x-modal name="confirm-product-deletion" focusable>
                            <form method="post" action="#" class="p-6">
                                @csrf
                                @method('delete')

                                <h2 class="text-lg font-medium text-gray-900">
                                    {{ __('¿Estás seguro que deseas eliminar el cliente?') }}
                                </h2>

                                <p class="mt-1 text-sm text-gray-600">
                                    {{ __('Una vez elimines el cliente estableceremos al consumidor final (cliente por defecto) en todos los registros asociados de movimientos y stock, ten mucho cuidado porque podrías perder información MUY IMPORTANTE.') }}
                                </p>

                                <div class="mt-6 flex justify-end">
                                    <x-secondary-button x-on:click="$dispatch('close')">
                                        {{ __('Cancel') }}
                                    </x-secondary-button>

                                    <x-danger-button class="ml-3">
                                        {{ __('Eliminar Cliente') }}
                                    </x-danger-button>
                                </div>
                            </form>
                        </x-modal>
                        <!-- End Delete Button --> --}}
                    </div>

                </article>
            </div>
        </div>
    </div>
</x-app-layout>