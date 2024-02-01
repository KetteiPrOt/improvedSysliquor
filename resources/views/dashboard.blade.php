<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                @can('sales')
                    <a class="mx-auto sm:mx-6 m-2 my-6 w-1/2 sm:w-1/4 flex flex-col items-center p-2 border rounded" href="{{route('sales.create')}}">
                        <svg class="w-24 h-24" fill="#000000" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" 
                            viewBox="0 0 512 512" xml:space="preserve">
                            <g>
                                <g>
                                    <path d="M396.243,328.286c-4.308-8.781-13.411-14.454-23.19-14.454h-16.777c-4.747-14.11-18.102-24.3-33.791-24.3H189.516
                                        c-15.689,0-29.044,10.19-33.791,24.3h-16.777c-9.78,0-18.883,5.674-23.192,14.46c-4.305,8.783-3.217,19.454,2.771,27.184
                                        L229.779,499.14c6.235,8.053,16.037,12.86,26.221,12.86c10.185,0,19.987-4.808,26.22-12.859l111.255-143.665
                                        C399.463,347.745,400.55,337.074,396.243,328.286z M256.001,478.037L154.903,347.485h15.8c9.293,0,16.827-7.534,16.827-16.827
                                        v-5.485c0-1.096,0.892-1.987,1.987-1.987h132.969c1.096,0,1.987,0.892,1.987,1.987v5.485c0,9.293,7.534,16.827,16.826,16.827
                                        h15.801L256.001,478.037z"/>
                                </g>
                            </g>
                            <g>
                                <g>
                                    <path d="M270.411,107.078V59.705c21.091,1.622,27.257,12.006,37.964,12.006c14.278,0,20.119-17.847,20.119-26.608
                                        c0-22.389-37.965-27.581-58.082-28.23V8.761c0-4.543-6.165-8.761-12.33-8.761c-7.139,0-12.33,4.218-12.33,8.761v9.085
                                        c-34.394,4.867-64.896,24.661-64.896,65.87c0,41.533,35.043,55.486,64.896,66.844v52.566
                                        c-24.014-4.218-35.046-23.363-48.673-23.363c-12.33,0-22.065,16.224-22.065,27.257c0,20.767,31.8,40.885,70.737,42.183v8.111
                                        c0,4.543,5.192,8.761,12.33,8.761c6.165,0,12.33-4.218,12.33-8.761v-9.41c37.964-6.165,63.923-30.501,63.923-70.737
                                        C334.334,133.361,299.938,118.11,270.411,107.078z M248.994,99.292c-12.33-5.192-20.767-11.033-20.767-20.442
                                        c0-7.787,6.165-15.25,20.767-18.172V99.292z M267.166,202.803v-43.482c11.681,5.516,19.794,12.33,19.794,23.039
                                        C286.959,194.042,278.197,200.207,267.166,202.803z"/>
                                </g>
                            </g>
                        </svg>
                        <p class="text-lg">Ventas</p>
                    </a>
                @endcan
                <p class="m-6">¡Hola Mundo!</p>
            </div>
        </div>
    </div>
</x-app-layout>
