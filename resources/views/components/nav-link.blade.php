@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center rounded-full px-4 py-2 text-sm font-semibold tracking-wide text-white bg-emerald-600 shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition-colors duration-150'
            : 'inline-flex items-center rounded-full px-4 py-2 text-sm font-semibold tracking-wide text-gray-600 hover:text-emerald-600 hover:bg-emerald-50 focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:ring-offset-2 transition-colors duration-150';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
