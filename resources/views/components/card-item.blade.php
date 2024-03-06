@props(['tag' => null])
<li
    class="w-full border-b-2 border-neutral-100 border-opacity-100 p-4 dark:border-opacity-50">
    @if($tag) <span class="font-semibold">{{$tag}}:</span> @endif
    {{$slot}}
</li>