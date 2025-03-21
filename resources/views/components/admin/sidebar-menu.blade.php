@props(['label' => '', 'icon' => '', 'routeName' => ''])

@php
    $isActive = Request::routeIs($routeName . '*');
@endphp

{{-- if not collapsed --}}
<a href="{{ route($routeName) }}"
    class="label-expand p-4 flex flex-row gap-3 items-center rounded-sm select-none
          {{ $isActive ? 'bg-[#f58e12] cursor-pointer text-sm font-semibold shadow-xl' : 'hover:bg-gray-100 cursor-pointer text-sm font-medium' }}"
    data-tooltip="{{ $label }}">

    <div
        class="sidebar-icon w-auto h-auto p-2 rounded-sm shadow-md text-nowrap 
                {{ $isActive ? 'bg-white' : 'bg-[#f56d11]' }}">
        <span class="{{ $icon }} w-5 h-5 {{ $isActive ? 'text-[#1f2835]' : 'text-white' }}"></span>
    </div>

    <p class="{{ $isActive ? 'text-white' : 'text-[#1f2835]' }}">{{ $label }}
    </p>
</a>

{{-- if collapsed --}}
<a href="{{ route($routeName) }}"
    class="label-collapsed hidden px-2 py-3 flex flex-col gap-1 items-center rounded-sm select-none
          {{ $isActive ? 'bg-[#f58e12] cursor-pointer text-sm font-semibold shadow-xl' : 'hover:bg-gray-100 cursor-pointer text-sm font-medium' }}"
    data-tooltip="{{ $label }}">

    <div
        class="sidebar-icon w-auto h-auto p-2 rounded-sm shadow-md text-nowrap 
                {{ $isActive ? 'bg-white' : 'bg-[#f56d11]' }}">
        <span class="{{ $icon }} w-5 h-5 {{ $isActive ? 'text-[#1f2835]' : 'text-white' }}"></span>
    </div>

    <p class="text-wrap text-center text-xs {{ $isActive ? 'text-white' : 'text-[#1f2835]' }}">{{ $label }}
    </p>
</a>
