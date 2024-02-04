@props(['xBorders' => true, 'textleft' => false])
@php
    if($textleft){
        $textAlign = 'text-left pl-4';
    } else {
        $textAlign = 'text-center';
    }
@endphp
<td class="
    {{$textAlign}} text-slate-500 dark:text-slate-400 text-xs
    p-1 border-b @if($xBorders) border-l @endif border-slate-100 dark:border-slate-700 
"
>
    {{$first ?? null}}
</td>
<td class="
    {{$textAlign}} text-slate-500 dark:text-slate-400 text-xs
    p-1 border-b border-slate-100 dark:border-slate-700
"
>
    {{$second ?? null}}
</td>
<td class="
    {{$textAlign}} text-slate-500 dark:text-slate-400 text-xs
    p-1 border-b @if($xBorders) border-r @endif border-slate-100 dark:border-slate-700
"
>
    {{$third ?? null}}
</td>