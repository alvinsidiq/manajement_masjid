@props(['href' => '#', 'active' => false])
<a href="{{ $href }}" class="block px-3 py-2 rounded {{ $active ? 'bg-gray-200 font-semibold' : 'hover:bg-gray-100' }}">
    {{ $slot }}
</a>

