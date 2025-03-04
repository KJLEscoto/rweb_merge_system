@props(['label', 'icon', 'routeName'])

@php
    $isActive = Request::routeIs($routeName . '*');
@endphp

<a href="{{ route($routeName) }}"
    class="p-2 flex gap-3 items-center rounded-sm select-none
          {{ $isActive ? 'bg-[#f58e12] cursor-pointer text-sm font-semibold shadow-xl' : 'hover:bg-gray-100 cursor-pointer text-sm font-medium' }}"
    data-tooltip="{{ $label }}">

    <div
        class="w-auto h-auto p-2 rounded-sm shadow-md text-nowrap 
                {{ $isActive ? 'bg-white' : 'bg-[#f56d11]' }}">
        <span class="{{ $icon }} w-5 h-5 {{ $isActive ? 'text-[#1f2835]' : 'text-white' }}"></span>
    </div>

    <p class="{{ $isActive ? 'text-white' : 'text-[#1f2835]' }}">{{ $label }}</p>

</a>
