@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full rounded-md ps-4 pe-4 py-2 text-start text-base font-semibold tracking-wide text-white bg-emerald-600 shadow focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition-colors duration-150'
            : 'block w-full rounded-md ps-4 pe-4 py-2 text-start text-base font-semibold tracking-wide text-gray-600 hover:text-emerald-600 hover:bg-emerald-50 focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:ring-offset-2 transition-colors duration-150';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
