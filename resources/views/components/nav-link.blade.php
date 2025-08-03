@props(['active'])

@php
    $classes =
        $active ?? false
            ? 'inline-flex gap-x-4 items-center px-5 pt-1 border-l-8 border-sky-400 whitespace-nowrap text-sm font-medium leading-5 text-gray-900 transition duration-150 ease-in-out'
            : 'inline-flex gap-x-4 items-center px-5 pt-1 border-l-8 border-transparent whitespace-nowrap text-sm font-medium leading-5 text-gray-500 hover:text-gray-800 hover:border-sky-200 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
