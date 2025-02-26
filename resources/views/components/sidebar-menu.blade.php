@props(['label', 'icon', 'routeName'])

<a href="{{ route($routeName) }}"
    class="{{ Request::routeIs($routeName . '*') ? 'p-2 flex gap-3 items-center bg-light-orange cursor-pointer text-sm font-semibold rounded-sm shadow-xl select-none' : 'p-2 flex gap-3 items-center hover:bg-gray-100 cursor-pointer text-sm font-medium rounded-sm select-none' }}">

    <div
        class="{{ Request::routeIs($routeName . '*') ? 'w-auto h-auto p-2 bg-white rounded-sm shadow-md text-nowrap' : 'w-auto h-auto p-2 bg-custom-orange rounded-sm shadow-md text-nowrap' }}">
        <span
            class="{{ $icon }} w-6 h-6 {{ Request::routeIs($routeName . '*') ? 'text-dim-blue' : 'text-white' }}"></span>
    </div>

    <p class="{{ Request::routeIs($routeName . '*') ? 'text-white' : 'text-dim-blue' }}">{{ $label }}</p>
</a>
