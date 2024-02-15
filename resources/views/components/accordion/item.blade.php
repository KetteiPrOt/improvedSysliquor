@props(['parentid', 'key'])
@php $parentId = $parentid; @endphp

<div
    class="rounded-none border-b border-neutral-200 bg-white"
>
    <h2 class="mb-0" id="{{$parentId}}Item{{$key}}Heading">
        <button
            class="group relative flex w-full items-center rounded-none border-0 bg-white px-5 py-4 text-left text-base text-neutral-800 transition [overflow-anchor:none] hover:z-[2] focus:z-[3] focus:outline-none dark:bg-neutral-800 dark:text-white [&:not([data-te-collapse-collapsed])]:bg-white [&:not([data-te-collapse-collapsed])]:text-primary [&:not([data-te-collapse-collapsed])]:[box-shadow:inset_0_-1px_0_rgba(229,231,235)] dark:[&:not([data-te-collapse-collapsed])]:bg-neutral-800 dark:[&:not([data-te-collapse-collapsed])]:text-primary-400 dark:[&:not([data-te-collapse-collapsed])]:[box-shadow:inset_0_-1px_0_rgba(75,85,99)]"
            type="button"
            data-te-collapse-init
            data-te-collapse-collapsed
            data-te-target="#{{$parentId}}Item{{$key}}"
            aria-expanded="false"
            aria-controls="{{$parentId}}Item{{$key}}"
        >
            {{ $heading }}
            <x-accordion.toggle-icon />
        </button>
    </h2>
    <div
        id="{{$parentId}}Item{{$key}}"
        class="!visible hidden border-0"
        data-te-collapse-item
        aria-labelledby="{{$parentId}}Item{{$key}}Heading"
        data-te-parent="#{{$parentId}}"
    >
        <div class="px-5 py-4">
            {{ $content }}
        </div>
    </div>
</div>
