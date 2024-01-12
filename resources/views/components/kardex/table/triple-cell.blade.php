@props(['xBorders' => true, 'textleft' => false])
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
    if($textleft){
        $textAlign = 'text-left pl-4';
    } else {
        $textAlign = 'text-center';
    }
@endphp
<td class="border-b {{$borders['l']}} border-slate-100 dark:border-slate-700 p-2 text-slate-500 dark:text-slate-400 {{$textAlign}}"
>{{$first ?? null}}</td>
<td class="border-b border-slate-100 dark:border-slate-700 p-2 text-slate-500 dark:text-slate-400 {{$textAlign}}"
>{{$second ?? null}}</td>
<td class="border-b {{$borders['r']}} border-slate-100 dark:border-slate-700 p-2 text-slate-500 dark:text-slate-400 {{$textAlign}} overflow-hidden"
>{{$third ?? null}}</td>