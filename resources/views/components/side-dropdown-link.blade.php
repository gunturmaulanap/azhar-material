@props(['active'])

@php
    $classes =
        $active ?? false
            ? 'flex items-center gap-x-2 px-3 py-2 rounded-lg text-primary bg-accent/50 font-semibold text-sm transition-all duration-200 w-full'
            : 'flex items-center gap-x-2 px-3 py-2 rounded-lg text-gray-600 hover:bg-accent/30 hover:text-primary text-sm transition-all duration-200 w-full';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    <div class="w-2 h-2 rounded-full bg-current opacity-50"></div>
    {{ $slot }}
</a>
