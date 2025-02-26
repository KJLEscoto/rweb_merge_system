@props(['label', 'icon', 'routeName'])

<a href="{{ route($routeName) }}"
    class="{{ Request::routeIs($routeName . '*') ? 'p-2 flex gap-3 items-center bg-[#f58e12] cursor-pointer text-sm font-semibold rounded-sm shadow-xl select-none' : 'p-2 flex gap-3 items-center hover:bg-gray-100 cursor-pointer text-sm font-medium rounded-sm select-none' }}">

    <div
        class="{{ Request::routeIs($routeName . '*') ? 'w-auto h-auto p-2 bg-white rounded-sm shadow-md text-nowrap' : 'w-auto h-auto p-2 bg-[#f56d11] rounded-sm shadow-md text-nowrap' }}">
        <span
            class="{{ $icon }} w-5 h-5 {{ Request::routeIs($routeName . '*') ? 'text-[#1f2835]' : 'text-white' }}"></span>
    </div>

    <p class="{{ Request::routeIs($routeName . '*') ? 'text-white' : 'text-[#1f2835]' }}">{{ $label }}</p>
</a>
