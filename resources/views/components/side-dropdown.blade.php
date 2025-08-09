@props(['active'])

@php
    $classes =
        $active ?? false
            ? 'flex gap-x-3 items-center px-4 py-3 rounded-lg bg-primary text-white font-semibold text-sm shadow-md transition-all duration-200 w-full'
            : 'flex gap-x-3 items-center px-4 py-3 rounded-lg text-gray-600 hover:bg-gray-50 hover:text-primary font-medium text-sm transition-all duration-200 w-full';
@endphp

<div class="flex flex-col" x-data="{ openDropdown: {{ $active ? 'true' : 'false' }} }">
    <button @click="openDropdown = ! openDropdown" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $trigger }}
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" 
             class="size-4 ml-auto transition-transform duration-200" 
             :class="openDropdown ? 'rotate-180' : ''">
            <path fill-rule="evenodd"
                d="M12.53 16.28a.75.75 0 0 1-1.06 0l-7.5-7.5a.75.75 0 0 1 1.06-1.06L12 14.69l6.97-6.97a.75.75 0 1 1 1.06 1.06l-7.5 7.5Z"
                clip-rule="evenodd" />
        </svg>
    </button>
    <div x-show="openDropdown" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-2" class="mt-2 ml-8 space-y-1">
        {{ $content }}
    </div>
</div>
