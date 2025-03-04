@props(['label', 'icon', 'routeName'])

@php
    $isActive = Request::routeIs($routeName . '*');
@endphp

<a href="{{ route($routeName) }}"
    class="group relative p-2 flex gap-3 items-center rounded-sm select-none
          {{ $isActive ? 'bg-[#f58e12] cursor-pointer text-sm font-semibold shadow-xl' : 'hover:bg-gray-100 cursor-pointer text-sm font-medium' }}"
    data-tooltip="{{ $label }}">

    <div
        class="sidebar-icon w-auto h-auto p-2 rounded-sm shadow-md text-nowrap 
                {{ $isActive ? 'bg-white' : 'bg-[#f56d11]' }}">
        <span class="{{ $icon }} w-5 h-5 {{ $isActive ? 'text-[#1f2835]' : 'text-white' }}"></span>
    </div>

    <p class="sidebar-label {{ $isActive ? 'text-white' : 'text-[#1f2835]' }}">{{ $label }}</p>

    <!-- Tooltip -->
    <div
        class="absolute left-full top-1/2 -translate-y-1/2 ml-3 px-3 py-1 rounded bg-gray-800 text-white text-xs shadow-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 
                z-[100] whitespace-nowrap">
        {{ $label }}
    </div>
</a>
