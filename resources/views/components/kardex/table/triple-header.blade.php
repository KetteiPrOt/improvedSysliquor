@props(['xBorders' => true])
@php
    if($xBorders){
        $borders = [
            'l' => 'border-l',
            'r' => 'border-r'
        ];
    } else {
        $borders = [
            'l' => null,
            'r' => null
        ];
    }
@endphp
<th class="border-b {{$borders['l']}} dark:border-slate-600 font-medium p-4 pl-4 sm:pl-8 pb-3 text-slate-400 dark:text-slate-200 text-left"
>{{$first ?? null}}</th>
<th class="border-b dark:border-slate-600 font-medium p-4 pl-4 sm:pl-8 pb-3 text-slate-400 dark:text-slate-200 text-left"
>{{$second ?? null}}</th>
<th class="border-b {{$borders['r']}} dark:border-slate-600 font-medium p-4 pl-4 sm:pl-8 pb-3 text-slate-400 dark:text-slate-200 text-left"
>{{$third ?? null}}</th>