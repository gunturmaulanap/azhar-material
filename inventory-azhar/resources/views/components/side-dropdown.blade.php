@props(['active'])

@php
    $classes =
        $active ?? false
            ? 'inline-flex gap-x-4 items-center px-5 py-1 border-l-8 border-sky-400 whitespace-nowrap text-sm font-bold leading-5 text-gray-950 transition duration-150 ease-in-out'
            : 'inline-flex gap-x-4 items-center px-5 py-1 border-l-8 border-transparent whitespace-nowrap text-sm font-medium leading-5 text-gray-600 hover:text-gray-800 hover:border-sky-200 transition duration-150 ease-in-out';
@endphp

<div class="flex flex-col" x-data="{ openDropdown: {{ $active ? 'true' : 'false' }} }">
    <div class="flex items-center justify-between">
        <button @click="openDropdown = ! openDropdown" {{ $attributes->merge(['class' => $classes]) }}>
            {{ $trigger }}
        </button>
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-3 mx-4">
            <path fill-rule="evenodd"
                d="M12.53 16.28a.75.75 0 0 1-1.06 0l-7.5-7.5a.75.75 0 0 1 1.06-1.06L12 14.69l6.97-6.97a.75.75 0 1 1 1.06 1.06l-7.5 7.5Z"
                clip-rule="evenodd" />
        </svg>
    </div>
    <div x-show="openDropdown" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95" class="text-sm text-gray-600 mx-20">
        {{ $content }}
    </div>
</div>
