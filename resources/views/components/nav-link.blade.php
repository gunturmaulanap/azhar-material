@props(['active'])

@php
    $classes =
        $active ?? false
            ? 'flex gap-x-3 items-center px-4 py-3 rounded-lg bg-primary text-white font-semibold text-sm shadow-md transition-all duration-200'
            : 'flex gap-x-3 items-center px-4 py-3 rounded-lg text-gray-600 hover:bg-gray-50 hover:text-primary font-medium text-sm transition-all duration-200';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
