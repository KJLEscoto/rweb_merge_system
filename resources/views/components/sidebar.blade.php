@php
    $user = Auth::user();
    $user->role
@endphp

@props(['link' => $user->role->position])

<div class="space-y-4 z-10 h-full">
    @if ($link === 'operations')

        {{-- Operations Sidebar Menu --}}
        <div class="block px-6">
            <a href="{{ url('/dashboard') }}"
                class="p-2 flex items-center w-full gap-2 rounded-md {{ request()->is('dashboard', 'dashboard/*') ? 'bg-[#f68e12] text-white font-bold' : 'hover:bg-gray-400 transition-all' }}"
                style="{{ request()->is('dashboard', 'dashboard/*') ? 'box-shadow: 0 1px 10px rgba(0, 0, 0, 0.6);' : '' }}">
                <div class="size-10 flex items-center justify-center rounded-lg {{ request()->is('dashboard', 'dashboard/*') ? 'bg-black' : 'bg-[#f66d11]' }}">
                    <i class="fa-solid fa-chart-line text-sm" style="color: #ffffff;"></i>
                </div>
                <div class="flex justify-between w-full">
                    <p class="block">Dashboard</p>
                </div>
            </a>
        </div>

        <div class="block px-6">
            <a href="{{ url('/operation/task') }}"
                class="p-2 flex items-center w-full gap-2 rounded-md {{ request()->is('operation/task', 'operation/task/*') ? 'bg-[#f68e12] text-white font-bold' : 'hover:bg-gray-200 transition-all' }}"
                style="{{ request()->is('operation/task', 'operation/task/*') ? 'box-shadow: 0 1px 10px rgba(0, 0, 0, 0.6);' : '' }}">
                <div class="size-10 flex items-center justify-center rounded-lg {{ request()->is('operation/task', 'operation/task/*') ? 'bg-black' : 'bg-[#f66d11]' }}">
                    <i class="fa-solid fa-file text-sm" style="color: #ffffff;"></i>
                </div>
                <div class="flex justify-between w-full">
                    <p class="block">My Tasks</p>
                    <p class="mr-10 text-[.8rem] py-1 text-white px-2 rounded-lg bg-red-600 {{ $operationTaskCountContent + $operationTaskCountGraphic < 1 ? 'hidden' : '' }}">{{ $operationTaskCountContent + $operationTaskCountGraphic }}</p>
                </div>
            </a>
        </div>

        <div class="block px-6">
            <a href="{{ url('/operation/requests') }}"
                class="p-2 flex items-center w-full gap-2 rounded-md {{ request()->is('operation/requests', 'operation/request/*') ? 'bg-[#f68e12] text-white font-bold' : 'hover:bg-gray-200 transition-all' }}"
                style="{{ request()->is('operation/requests', 'operation/request/*') ? 'box-shadow: 0 1px 10px rgba(0, 0, 0, 0.6);' : '' }}">
                <div class="size-10 flex items-center justify-center rounded-lg {{ request()->is('operation/requests', 'operation/request/*') ? 'bg-black' : 'bg-[#f66d11]' }}">
                    <i class="fa-solid fa-code-pull-request text-sm" style="color: #ffffff;"></i>
                </div>
                <div class="flex justify-between w-full">
                    <p class="block">Incoming Requests</p>
                    <p class="mr-10 text-[.8rem] py-1 text-white px-2 rounded-lg bg-red-600 {{ $operationIncomingRequestCount < 1 ? 'hidden' : '' }}">{{ $operationIncomingRequestCount }}</p>
                </div>
            </a>
        </div>

        <div class="block px-6">
            <a href="{{ url('/joborder') }}"
                class="p-2 flex items-center w-full gap-2 rounded-md {{ request()->is('joborder', 'joborder/*') ? 'bg-[#f68e12] text-white font-bold' : 'hover:bg-gray-200 transition-all' }}"
                style="{{ request()->is('joborder', 'joborder/*') ? 'box-shadow: 0 1px 10px rgba(0, 0, 0, 0.6);' : '' }}">
                <div class="size-10 flex items-center justify-center rounded-lg {{ request()->is('joborder', 'joborder/*') ? 'bg-black' : 'bg-[#f66d11]' }}">
                    <i class="fa-solid fa-code-pull-request text-sm" style="color: #ffffff; transform: scaleY(-1);"></i>
                </div>
                <div class="flex justify-between w-full">
                    <p class="block">Outgoing Requests</p>
                </div>
            </a>
        </div>

        <div class="block px-6">
            <a href="{{ url('/operation') }}"
                class="p-2 flex items-center w-full gap-2 rounded-md {{ request()->is('operation', 'operation/show/*') ? 'bg-[#f68e12] text-white font-bold' : 'hover:bg-gray-200 transition-all' }}"
                style="{{ request()->is('operation', 'operation/show/*') ? 'box-shadow: 0 1px 10px rgba(0, 0, 0, 0.6);' : '' }}">
                <div class="size-10 flex items-center justify-center rounded-lg {{ request()->is('operation', 'operation/show/*') ? 'bg-black' : 'bg-[#f66d11]' }}">
                    <i class="fa-solid fa-list-check text-sm" style="color: #ffffff;"></i>
                </div>
                <div class="flex justify-between w-full">
                    <p class="block">Approvals</p>
                    <p class="mr-10 text-[.8rem] py-1 text-white px-2 rounded-lg bg-red-600 {{ $operationApprovalCount < 1 ? 'hidden' : '' }}">{{ $operationApprovalCount }}</p>
                </div>
            </a>
        </div>

        <div class="block px-6">
            <a href="{{ url('/revision') }}"
                class="p-2 flex items-center w-full gap-2 rounded-md {{ request()->is('revision', 'revisions', 'revision/*') ? 'bg-[#f68e12] text-white font-bold' : 'hover:bg-gray-200 transition-all' }}"
                style="{{ request()->is('revision', 'revisions', 'revision/*') ? 'box-shadow: 0 1px 10px rgba(0, 0, 0, 0.6);' : '' }}">
                <div class="size-10 flex items-center justify-center rounded-lg {{ request()->is('revision', 'revision/*') ? 'bg-black' : 'bg-[#f66d11]' }}">
                    <i class="fa-solid fa-pencil-alt text-sm" style="color: #ffffff;"></i>
                </div>
                <div class="flex justify-between w-full">
                    <p class="block">My Revisions</p>
                    <p class="mr-10 text-[.8rem] py-1 text-white px-2 rounded-lg bg-red-600 {{ $revisionCount < 1 ? 'hidden' : '' }}">{{ $revisionCount }}</p>
                </div>
            </a>
        </div>

        <div class="block px-6">
            <a href="{{ url('/operation/renewal') }}"
                class="p-2 flex items-center w-full gap-2 rounded-md {{ request()->is('operation/renewal', 'operation/renewal/*') ? 'bg-[#f68e12] text-white font-bold' : 'hover:bg-gray-200 transition-all' }}"
                style="{{ request()->is('operation/renewal', 'operation/renewal/*') ? 'box-shadow: 0 1px 10px rgba(0, 0, 0, 0.6);' : '' }}">
                <div class="size-10 flex items-center justify-center rounded-lg {{ request()->is('operation/renewal', 'operation/renewal/*') ? 'bg-black' : 'bg-[#f66d11]' }}">
                    <i class="fa-solid fa-sync-alt text-sm" style="color: #ffffff;"></i>
                </div>
                <h1 class="block">Renewal</h1>
            </a>
        </div>

        <div class="block px-6">
            <a href="{{ url('/track') }}"
                class="p-2 flex items-center w-full gap-2 rounded-md {{ request()->is('track', 'track/*') ? 'bg-[#f68e12] text-white font-bold' : 'hover:bg-gray-200 transition-all' }}"
                style="{{ request()->is('track', 'track/*') ? 'box-shadow: 0 1px 10px rgba(0, 0, 0, 0.6);' : '' }}">
                <div class="size-10 flex items-center justify-center rounded-lg {{ request()->is('track', 'track/*') ? 'bg-black' : 'bg-[#f66d11]' }}">
                    <i class="fa-regular fa-map" style="color: #ffffff;"></i>
                </div>
                <h1 class="block">Track Job Orders</h1>
            </a>
        </div>

        <div class="block px-6">
            <a href="{{ url('/profile/show') }}"
                class="p-2 flex items-center w-full gap-2 rounded-md {{ request()->is('profile', 'profile/*') ? 'bg-[#f68e12] text-white font-bold' : 'hover:bg-gray-200 transition-all' }}"
                style="{{ request()->is('profile', 'profile/*') ? 'box-shadow: 0 1px 10px rgba(0, 0, 0, 0.6);' : '' }}">
                <div class="size-10 flex items-center justify-center rounded-lg {{ request()->is('profile', 'profile/*') ? 'bg-black' : 'bg-[#f66d11]' }}">
                    <i class="fa-solid fa-user text-sm" style="color: #ffffff;"></i>
                </div>
                <h1 class="block">Profile</h1>
            </a>
        </div>

        <div class="block px-6">
            <a href="{{ url('/users') }}"
                class="p-2 flex items-center w-full gap-2 rounded-md {{ request()->is('users', 'register', 'users/*') ? 'bg-[#f68e12] text-white font-bold' : 'hover:bg-gray-200 transition-all' }}"
                style="{{ request()->is('users', 'register', 'users/*') ? 'box-shadow: 0 1px 10px rgba(0, 0, 0, 0.6);' : '' }}">
                <div class="size-10 flex items-center justify-center rounded-lg {{ request()->is('users', 'register', 'users/*') ? 'bg-black' : 'bg-[#f66d11]' }}">
                    <i class="fa-solid fa-user-plus text-sm" style="color: #ffffff;"></i>
                </div>
                <h1 class="block">Users</h1>
            </a>
        </div>

        <div class="block px-6">
            <a href="{{ url('/operation/history') }}"
                class="p-2 flex items-center w-full gap-2 rounded-md {{ request()->is('operation/history', 'operation/history/*') ? 'bg-[#f68e12] text-white font-bold' : 'hover:bg-gray-200 transition-all' }}"
                style="{{ request()->is('operation/history', 'operation/history/*') ? 'box-shadow: 0 1px 10px rgba(0, 0, 0, 0.6);' : '' }}">
                <div class="size-10 flex items-center justify-center rounded-lg {{ request()->is('operation/history', 'operation/history/*') ? 'bg-black' : 'bg-[#f66d11]' }}">
                    <i class="fa-solid fa-download text-sm" style="color: #ffffff;"></i>
                </div>
                <h1 class="block">Downloadables</h1>
            </a>
        </div>

        <div class="block lg:hidden px-6">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="p-2 flex items-center w-full gap-2 rounded-md bg-red-500 text-white font-bold hover:bg-red-600 transition duration-300">
                    <i class="fa-solid fa-power-off"></i>
                    <h1 class="block">Logout</h1>
                </button>
            </form>
        </div>

    @elseif ($link === 'client')
        {{-- Client Sidebar Menu --}}
        <div class="block px-6">
            <a href="{{ url('/dashboard') }}"
                class="p-2 flex items-center w-full gap-2 rounded-md {{ request()->is('dashboard', '/dashboard/*') ? 'bg-[#f68e12] text-white font-bold' : 'hover:bg-gray-200 transition-all' }}"
                style="{{ request()->is('dashboard', '/dashboard/*') ? 'box-shadow: 0 1px 10px rgba(0, 0, 0, 0.6);' : '' }}">
                <div class="size-10 flex items-center justify-center rounded-lg {{ request()->is('dashboard', '/dashboard/*') ? 'bg-black' : 'bg-[#f66d11]' }}">
                    <i class="fa-solid fa-chart-line text-sm" style="color: #ffffff;"></i>
                </div>
                <h1 class="block">Dashboard</h1>
            </a>
        </div>

        <div class="block px-6">
            <a href="{{ url('/client') }}"
                class="p-2 flex items-center w-full gap-2 rounded-md {{ request()->is('client', 'client/show/*') ? 'bg-[#f68e12] text-white font-bold' : 'hover:bg-gray-200 transition-all' }}"
                style="{{ request()->is('client', 'client/show/*') ? 'box-shadow: 0 1px 10px rgba(0, 0, 0, 0.6);' : '' }}">
                <div class="size-10 flex items-center justify-center rounded-lg {{ request()->is('client', 'client/show/*') ? 'bg-black' : 'bg-[#f66d11]' }}">
                    <i class="fa-solid fa-list-check text-sm" style="color: #ffffff;"></i>
                </div>
                <div class="flex justify-between w-full">
                    <p class="block">Approvals</p>
                    <p class="mr-10 text-[.8rem] py-1 text-white px-2 rounded-lg bg-red-600 {{ $clientDraftCount < 1 ? 'hidden' : '' }}">{{ $clientDraftCount }}</p>
                </div>
            </a>
        </div>

        <div class="block px-6">
            <a href="{{ url('/client/history') }}"
                class="p-2 flex items-center w-full gap-2 rounded-md {{ request()->is('client/history', 'client/history/*') ? 'bg-[#f68e12] text-white font-bold' : 'hover:bg-gray-200 transition-all' }}"
                style="{{ request()->is('client/history', 'client/history/*') ? 'box-shadow: 0 1px 10px rgba(0, 0, 0, 0.6);' : '' }}">
                <div class="size-10 flex items-center justify-center rounded-lg {{ request()->is('client/history', 'client/history/*') ? 'bg-black' : 'bg-[#f66d11]' }}">
                    <i class="fa-solid fa-download text-sm" style="color: #ffffff;"></i>
                </div>
                <h1 class="block">Downloadables</h1>
            </a>
        </div>

        <div class="block px-6">
            <a href="{{ url('/client/renewal') }}"
                class="p-2 flex items-center w-full gap-2 rounded-md {{ request()->is('client/renewal', 'client/renewal/*') ? 'bg-[#f68e12] text-white font-bold' : 'hover:bg-gray-200 transition-all' }}"
                style="{{ request()->is('client/renewal', 'client/renewal/*') ? 'box-shadow: 0 1px 10px rgba(0, 0, 0, 0.6);' : '' }}">
                <div class="size-10 flex items-center justify-center rounded-lg {{ request()->is('client/renewal', 'client/renewal/*') ? 'bg-black' : 'bg-[#f66d11]' }}">
                    <i class="fa-solid fa-sync-alt text-sm" style="color: #ffffff;"></i>
                </div>
                <h1 class="block">Renewal</h1>
            </a>
        </div>

        <div class="block px-6">
            <a href="{{ url('/track') }}"
                class="p-2 flex items-center w-full gap-2 rounded-md {{ request()->is('track', 'track/*') ? 'bg-[#f68e12] text-white font-bold' : 'hover:bg-gray-200 transition-all' }}"
                style="{{ request()->is('track', 'track/*') ? 'box-shadow: 0 1px 10px rgba(0, 0, 0, 0.6);' : '' }}">
                <div class="size-10 flex items-center justify-center rounded-lg {{ request()->is('track', 'track/*') ? 'bg-black' : 'bg-[#f66d11]' }}">
                    <i class="fa-regular fa-map" style="color: #ffffff;"></i>
                </div>
                <h1 class="block">Track Job Orders</h1>
            </a>
        </div>

        <div class="block px-6">
            <a href="{{ url('/profile/show') }}"
                class="p-2 flex items-center w-full gap-2 rounded-md {{ request()->is('profile', 'profile/*') ? 'bg-[#f68e12] text-white font-bold' : 'hover:bg-gray-200 transition-all' }}"
                style="{{ request()->is('profile', 'profile/*') ? 'box-shadow: 0 1px 10px rgba(0, 0, 0, 0.6);' : '' }}">
                <div class="size-10 flex items-center justify-center rounded-lg {{ request()->is('profile', 'profile/*') ? 'bg-black' : 'bg-[#f66d11]' }}">
                    <i class="fa-solid fa-user text-sm" style="color: #ffffff;"></i>
                </div>
                <h1 class="block">Profile</h1>
            </a>
        </div>

        <div class="block lg:hidden px-6">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="p-2 flex items-center w-full gap-2 rounded-md bg-red-500 text-white font-bold hover:bg-red-600 transition duration-300">
                    <i class="fa-solid fa-power-off"></i>
                    <h1 class="block">Logout</h1>
                </button>
            </form>
        </div>

    @elseif ($link === 'content_writer')
        {{-- Content Writer Sidebar Menu --}}
        <div class="block px-6">
            <a href="{{ url('/dashboard') }}"
                class="p-2 flex items-center w-full gap-2 rounded-md {{ request()->is('dashboard', 'dashboard/*') ? 'bg-[#f68e12] text-white font-bold' : 'hover:bg-gray-200 transition-all' }}"
                style="{{ request()->is('dashboard', 'dashboard/*') ? 'box-shadow: 0 1px 10px rgba(0, 0, 0, 0.6);' : '' }}">
                <div class="size-10 flex items-center justify-center rounded-lg {{ request()->is('dashboard', 'dashboard/*') ? 'bg-black' : 'bg-[#f66d11]' }}">
                    <i class="fa-solid fa-chart-line text-sm" style="color: #ffffff;"></i>
                </div>
                <h1 class="block">Dashboard</h1>
            </a>
        </div>

        <div class="block px-6">
            <a href="{{ url('/content') }}"
                class="p-2 flex items-center w-full gap-2 rounded-md {{ request()->is('content', 'content/edit/*') ? 'bg-[#f68e12] text-white font-bold' : 'hover:bg-gray-200 transition-all' }}"
                style="{{ request()->is('content', 'content/edit/*') ? 'box-shadow: 0 1px 10px rgba(0, 0, 0, 0.6);' : '' }}">
                <div class="size-10 flex items-center justify-center rounded-lg {{ request()->is('content', 'content/edit/*') ? 'bg-black' : 'bg-[#f66d11]' }}">
                    <i class="fa-solid fa-file text-sm" style="color: #ffffff;"></i>
                </div>
                <div class="flex justify-between w-full">
                    <p class="block">My Tasks</p>
                    <p class="mr-10 text-[.8rem] py-1 text-white px-2 rounded-lg bg-red-600 {{ $contentDraftCount < 1 ? 'hidden' : '' }}">{{ $contentDraftCount }}</p>
                </div>
            </a>
        </div>

        <div class="block px-6">
            <a href="{{ url('/track') }}"
                class="p-2 flex items-center w-full gap-2 rounded-md {{ request()->is('track', 'track/*') ? 'bg-[#f68e12] text-white font-bold' : 'hover:bg-gray-200 transition-all' }}"
                style="{{ request()->is('track', 'track/*') ? 'box-shadow: 0 1px 10px rgba(0, 0, 0, 0.6);' : '' }}">
                <div class="size-10 flex items-center justify-center rounded-lg {{ request()->is('track', 'track/*') ? 'bg-black' : 'bg-[#f66d11]' }}">
                    <i class="fa-regular fa-map" style="color: #ffffff;"></i>
                </div>
                <h1 class="block">Track Job Orders</h1>
            </a>
        </div>

        <div class="block px-6">
            <a href="{{ url('/profile/show') }}"
                class="p-2 flex items-center w-full gap-2 rounded-md {{ request()->is('profile', 'profile/*') ? 'bg-[#f68e12] text-white font-bold' : 'hover:bg-gray-200 transition-all' }}"
                style="{{ request()->is('profile', 'profile/*') ? 'box-shadow: 0 1px 10px rgba(0, 0, 0, 0.6);' : '' }}">
                <div class="size-10 flex items-center justify-center rounded-lg {{ request()->is('profile', 'profile/*') ? 'bg-black' : 'bg-[#f66d11]' }}">
                    <i class="fa-solid fa-user text-sm" style="color: #ffffff;"></i>
                </div>
                <h1 class="block">Profile</h1>
            </a>
        </div>

        <div class="block px-6">
            <a href="{{ url('/revision') }}"
                class="p-2 flex items-center w-full gap-2 rounded-md {{ request()->is('revision', 'revisions', 'revision/*') ? 'bg-[#f68e12] text-white font-bold' : 'hover:bg-gray-200 transition-all' }}"
                style="{{ request()->is('revision', 'revisions', 'revision/*') ? 'box-shadow: 0 1px 10px rgba(0, 0, 0, 0.6);' : '' }}">
                <div class="size-10 flex items-center justify-center rounded-lg {{ request()->is('revision', 'revision/*') ? 'bg-black' : 'bg-[#f66d11]' }}">
                    <i class="fa-solid fa-pencil-alt text-sm" style="color: #ffffff;"></i>
                </div>
                <div class="flex justify-between w-full">
                    <p class="block">My Revisions</p>
                    <p class="mr-10 text-[.8rem] py-1 text-white px-2 rounded-lg bg-red-600 {{ $contentRevisionCount < 1 ? 'hidden' : '' }}">{{ $contentRevisionCount }}</p>
                </div>
            </a>
        </div>

        <div class="block px-6">
            <a href="{{ url('/content/history') }}"
                class="p-2 flex items-center w-full gap-2 rounded-md {{ request()->is('content/history', 'content/history/*') ? 'bg-[#f68e12] text-white font-bold' : 'hover:bg-gray-200 transition-all' }}"
                style="{{ request()->is('content/history', 'content/history/*') ? 'box-shadow: 0 1px 10px rgba(0, 0, 0, 0.6);' : '' }}">
                <div class="size-10 flex items-center justify-center rounded-lg {{ request()->is('content/history', 'content/history/*') ? 'bg-black' : 'bg-[#f66d11]' }}">
                    <i class="fa-solid fa-download text-sm" style="color: #ffffff;"></i>
                </div>
                <h1 class="block">Downloadables</h1>
            </a>
        </div>

        <div class="block lg:hidden px-6">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="p-2 flex items-center w-full gap-2 rounded-md bg-red-500 text-white font-bold hover:bg-red-600 transition duration-300">
                    <i class="fa-solid fa-power-off"></i>
                    <h1 class="block">Logout</h1>
                </button>
            </form>
        </div>

    @elseif ($link === 'graphic_designer')
        {{-- Graphic Designer Sidebar Menu --}}
        <div class="block px-6">
            <a href="{{ url('/dashboard') }}"
                class="p-2 flex items-center w-full gap-2 rounded-md {{ request()->is('dashboard', 'dashboard/*') ? 'bg-[#f68e12] text-white font-bold' : 'hover:bg-gray-200 transition-all' }}"
                style="{{ request()->is('dashboard', 'dashboard/*') ? 'box-shadow: 0 1px 10px rgba(0, 0, 0, 0.6);' : '' }}">
                <div class="size-10 flex items-center justify-center rounded-lg {{ request()->is('dashboard', 'dashboard/*') ? 'bg-black' : 'bg-[#f66d11]' }}">
                    <i class="fa-solid fa-chart-line text-sm" style="color: #ffffff;"></i>
                </div>
                <h1 class="block">Dashboard</h1>
            </a>
        </div>

        <div class="block px-6">
            <a href="{{ url('/graphic') }}"
                class="p-2 flex items-center w-full gap-2 rounded-md {{ request()->is('graphic', 'graphic') ? 'bg-[#f68e12] text-white font-bold' : 'hover:bg-gray-200 transition-all' }}"
                style="{{ request()->is('graphic', 'graphic') ? 'box-shadow: 0 1px 10px rgba(0, 0, 0, 0.6);' : '' }}">
                <div class="size-10 flex items-center justify-center rounded-lg {{ request()->is('graphic', 'graphic') ? 'bg-black' : 'bg-[#f66d11]' }}">
                    <i class="fa-solid fa-file text-sm" style="color: #ffffff;"></i>
                </div>
                <div class="flex justify-between w-full">
                    <p class="block">My Tasks</p>
                    <p class="mr-10 text-[.8rem] py-1 text-white px-2 rounded-lg bg-red-600 {{ $graphicDraftCount < 1 ? 'hidden' : '' }}">{{ $graphicDraftCount }}</p>
                </div>
            </a>
        </div>

        <div class="block px-6">
            <a href="{{ url('/revision') }}"
                class="p-2 flex items-center w-full gap-2 rounded-md {{ request()->is('revision', 'revisions', 'revision/*') ? 'bg-[#f68e12] text-white font-bold' : 'hover:bg-gray-200 transition-all' }}"
                style="{{ request()->is('revision', 'revisions', 'revision/*') ? 'box-shadow: 0 1px 10px rgba(0, 0, 0, 0.6);' : '' }}">
                <div class="size-10 flex items-center justify-center rounded-lg {{ request()->is('revision', 'revision/*') ? 'bg-black' : 'bg-[#f66d11]' }}">
                    <i class="fa-solid fa-pencil-alt text-sm" style="color: #ffffff;"></i>
                </div>
                <div class="flex justify-between w-full">
                    <p class="block">My Revisions</p>
                    <p class="mr-10 text-[.8rem] py-1 text-white px-2 rounded-lg bg-red-600 {{ $graphicRevisionCount < 1 ? 'hidden' : '' }}">{{ $graphicRevisionCount }}</p>
                </div>
            </a>
        </div>

        <div class="block px-6">
            <a href="{{ url('/graphic/history') }}"
                class="p-2 flex items-center w-full gap-2 rounded-md {{ request()->is('graphic/history', 'graphic/history/*') ? 'bg-[#f68e12] text-white font-bold' : 'hover:bg-gray-200 transition-all' }}"
                style="{{ request()->is('graphic/history', 'graphic/history/*') ? 'box-shadow: 0 1px 10px rgba(0, 0, 0, 0.6);' : '' }}">
                <div class="size-10 flex items-center justify-center rounded-lg {{ request()->is('graphic/history', 'graphic/history/*') ? 'bg-black' : 'bg-[#f66d11]' }}">
                    <i class="fa-solid fa-download text-sm" style="color: #ffffff;"></i>
                </div>
                <h1 class="block">Downloadables</h1>
            </a>
        </div>

        <div class="block px-6">
            <a href="{{ url('/track') }}"
                class="p-2 flex items-center w-full gap-2 rounded-md {{ request()->is('track', 'track/*') ? 'bg-[#f68e12] text-white font-bold' : 'hover:bg-gray-200 transition-all' }}"
                style="{{ request()->is('track', 'track/*') ? 'box-shadow: 0 1px 10px rgba(0, 0, 0, 0.6);' : '' }}">
                <div class="size-10 flex items-center justify-center rounded-lg {{ request()->is('track', 'track/*') ? 'bg-black' : 'bg-[#f66d11]' }}">
                    <i class="fa-regular fa-map" style="color: #ffffff;"></i>
                </div>
                <h1 class="block">Track Job Orders</h1>
            </a>
        </div>

        <div class="block px-6">
            <a href="{{ url('/profile/show') }}"
                class="p-2 flex items-center w-full gap-2 rounded-md {{ request()->is('profile', 'profile/*') ? 'bg-[#f68e12] text-white font-bold' : 'hover:bg-gray-200 transition-all' }}"
                style="{{ request()->is('profile', 'profile/*') ? 'box-shadow: 0 1px 10px rgba(0, 0, 0, 0.6);' : '' }}">
                <div class="size-10 flex items-center justify-center rounded-lg {{ request()->is('profile', 'profile/*') ? 'bg-black' : 'bg-[#f66d11]' }}">
                    <i class="fa-solid fa-user text-sm" style="color: #ffffff;"></i>
                </div>
                <h1 class="block">Profile</h1>
            </a>
        </div>

        <div class="block lg:hidden px-6">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="p-2 flex items-center w-full gap-2 rounded-md bg-red-500 text-white font-bold hover:bg-red-600 transition duration-300">
                    <i class="fa-solid fa-power-off"></i>
                    <h1 class="block">Logout</h1>
                </button>
            </form>
        </div>

    @elseif ($link === 'top_manager')
        {{-- Top Manager Sidebar Menu --}}
        <div class="block px-6">
            <a href="{{ url('/dashboard') }}"
                class="p-2 flex items-center w-full gap-2 rounded-md {{ request()->is('dashboard', 'dashboard/*') ? 'bg-[#f68e12] text-white font-bold' : 'hover:bg-gray-200 transition-all' }}"
                style="{{ request()->is('dashboard', 'dashboard/*') ? 'box-shadow: 0 1px 10px rgba(0, 0, 0, 0.6);' : '' }}">
                <div class="size-10 flex items-center justify-center rounded-lg {{ request()->is('dashboard', 'dashboard/*') ? 'bg-black' : 'bg-[#f66d11]' }}">
                    <i class="fa-solid fa-chart-line text-sm" style="color: #ffffff;"></i>
                </div>
                <h1 class="block">Dashboard</h1>
            </a>
        </div>

        <div class="block px-6">
            <a href="{{ url('/topmanager') }}"
                class="p-2 flex items-center w-full gap-2 rounded-md {{ request()->is('topmanager', 'topmanager/*') ? 'bg-[#f68e12] text-white font-bold' : 'hover:bg-gray-200 transition-all' }}"
                style="{{ request()->is('topmanager', 'topmanager/*') ? 'box-shadow: 0 1px 10px rgba(0, 0, 0, 0.6);' : '' }}">
                <div class="size-10 flex items-center justify-center rounded-lg {{ request()->is('topmanager', 'topmanager/*') ? 'bg-black' : 'bg-[#f66d11]' }}">
                    <i class="fa-solid fa-list-check text-sm" style="color: #ffffff;"></i>
                </div>
                <div class="flex justify-between w-full">
                    <p class="block">Approval</p>
                    <p class="mr-10 text-[.8rem] py-1 text-white px-2 rounded-lg bg-red-600 {{ $topmanagerApprovalCount < 1 ? 'hidden' : '' }}">{{ $topmanagerApprovalCount }}</p>
                </div>
            </a>
        </div>

        <div class="block px-6">
            <a href="{{ url('/track') }}"
                class="p-2 flex items-center w-full gap-2 rounded-md {{ request()->is('track', 'track/*') ? 'bg-[#f68e12] text-white font-bold' : 'hover:bg-gray-200 transition-all' }}"
                style="{{ request()->is('track', 'track/*') ? 'box-shadow: 0 1px 10px rgba(0, 0, 0, 0.6);' : '' }}">
                <div class="size-10 flex items-center justify-center rounded-lg {{ request()->is('track', 'track/*') ? 'bg-black' : 'bg-[#f66d11]' }}">
                    <i class="fa-regular fa-map" style="color: #ffffff;"></i>
                </div>
                <h1 class="block">Track Job Orders</h1>
            </a>
        </div>

        <div class="block px-6">
            <a href="{{ url('/requestForm/history') }}"
                class="p-2 flex items-center w-full gap-2 rounded-md {{ request()->is('requestForm/create', 'requestForm/history', 'requestForm/create/*') ? 'bg-[#f68e12] text-white font-bold' : 'hover:bg-gray-200 transition-all' }}"
                style="{{ request()->is('requestForm/create', 'requestForm/history', 'requestForm/create/*') ? 'box-shadow: 0 1px 10px rgba(0, 0, 0, 0.6);' : '' }}">
                <div class="size-10 flex items-center justify-center rounded-lg {{ request()->is('requestForm/create', 'requestForm/history', 'requestForm/create/*') ? 'bg-black' : 'bg-[#f66d11]' }}">
                    <i class="fa-solid fa-file-export text-sm" style="color: #ffffff;"></i>
                </div>
                <h1 class="block">Request Form</h1>
            </a>
        </div>

        <div class="block px-6">
            <a href="{{ url('/profile/show') }}"
                class="p-2 flex items-center w-full gap-2 rounded-md {{ request()->is('profile', 'profile/*') ? 'bg-[#f68e12] text-white font-bold' : 'hover:bg-gray-200 transition-all' }}"
                style="{{ request()->is('profile', 'profile/*') ? 'box-shadow: 0 1px 10px rgba(0, 0, 0, 0.6);' : '' }}">
                <div class="size-10 flex items-center justify-center rounded-lg {{ request()->is('profile', 'profile/*') ? 'bg-black' : 'bg-[#f66d11]' }}">
                    <i class="fa-solid fa-user text-sm" style="color: #ffffff;"></i>
                </div>
                <h1 class="block">Profile</h1>
            </a>
        </div>

        <div class="block px-6">
            <a href="{{ url('/users') }}"
                class="p-2 flex items-center w-full gap-2 rounded-md {{ request()->is('users', 'users/*') ? 'bg-[#f68e12] text-white font-bold' : 'hover:bg-gray-200 transition-all' }}"
                style="{{ request()->is('users', 'users/*') ? 'box-shadow: 0 1px 10px rgba(0, 0, 0, 0.6);' : '' }}">
                <div class="size-10 flex items-center justify-center rounded-lg {{ request()->is('users', 'users/*') ? 'bg-black' : 'bg-[#f66d11]' }}">
                    <i class="fa-solid fa-user-plus text-sm" style="color: #ffffff;"></i>
                </div>
                <h1 class="block">Users</h1>
            </a>
        </div>

        <div class="block lg:hidden px-6">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="p-2 flex items-center w-full gap-2 rounded-md bg-red-500 text-white font-bold hover:bg-red-600 transition duration-300">
                    <i class="fa-solid fa-power-off"></i>
                    <h1 class="block">Logout</h1>
                </button>
            </form>
        </div>

    @elseif ($link == 'supervisor')

        {{-- Supervisor Sidebar Menu --}}
        <div class="block px-6">
            <a href="{{ url('/dashboard') }}"
                class="p-2 flex items-center w-full gap-2 rounded-md {{ request()->is('dashboard', 'dashboard/*') ? 'bg-[#f68e12] text-white font-bold' : 'hover:bg-gray-200 transition-all' }}"
                style="{{ request()->is('dashboard', 'dashboard/*') ? 'box-shadow: 0 1px 10px rgba(0, 0, 0, 0.6);' : '' }}">
                <div class="size-10 flex items-center justify-center rounded-lg {{ request()->is('dashboard', 'dashboard/*') ? 'bg-black' : 'bg-[#f66d11]' }}">
                    <i class="fa-solid fa-chart-line text-sm" style="color: #ffffff;"></i>
                </div>
                <h1 class="block">Dashboard</h1>
            </a>
        </div>

        <div class="block px-6">
            <a href="{{ url('/supervisor/joborder') }}"
                class="p-2 flex items-center w-full gap-2 rounded-md {{ request()->is('supervisor/joborder', 'supervisor/joborder/*') ? 'bg-[#f68e12] text-white font-bold' : 'hover:bg-gray-200 transition-all' }}"
                style="{{ request()->is('supervisor/joborder', 'supervisor/joborder/*') ? 'box-shadow: 0 1px 10px rgba(0, 0, 0, 0.6);' : '' }}">
                <div class="size-10 flex items-center justify-center rounded-lg {{ request()->is('supervisor/joborder', 'supervisor/joborder/*') ? 'bg-black' : 'bg-[#f66d11]' }}">
                    <i class="fa-solid fa-code-pull-request text-sm" style="color: #ffffff; transform: scaleY(-1);"></i>
                </div>
                <div class="flex justify-between w-full">
                    <p class="block">Operation Job Order</p>
                    {{-- <p class="mr-10 text-[.8rem] py-1 text-white px-2 rounded-lg bg-red-600 {{ $supervisorDraftCount < 1 ? 'hidden' : '' }}">{{ $supervisorDraftCount }}</p> --}}
                </div>
            </a>
        </div>

        <div class="block px-6">
            <a href="{{ url('/supervisor/directjob') }}"
                class="p-2 flex items-center w-full gap-2 rounded-md {{ request()->is('supervisor/directjob', 'supervisor/directjob/*') ? 'bg-[#f68e12] text-white font-bold' : 'hover:bg-gray-200 transition-all' }}"
                style="{{ request()->is('supervisor/directjob', 'supervisor/directjob/*') ? 'box-shadow: 0 1px 10px rgba(0, 0, 0, 0.6);' : '' }}">
                <div class="size-10 flex items-center justify-center rounded-lg {{ request()->is('supervisor/directjob', 'supervisor/directjob/*') ? 'bg-black' : 'bg-[#f66d11]' }}">
                    <i class="fa-solid fa-briefcase text-sm" style="color: #ffffff;"></i>
                </div>
                <h1 class="block">Direct Job Orders</h1>
            </a>
        </div>

        <div class="block px-6">
            <a href="{{ url('/supervisor/task') }}"
                class="p-2 flex items-center w-full gap-2 rounded-md {{ request()->is('supervisor/task', 'supervisor/task/*') ? 'bg-[#f68e12] text-white font-bold' : 'hover:bg-gray-200 transition-all' }}"
                style="{{ request()->is('supervisor/task', 'supervisor/task/*') ? 'box-shadow: 0 1px 10px rgba(0, 0, 0, 0.6);' : '' }}">
                <div class="size-10 flex items-center justify-center rounded-lg {{ request()->is('supervisor/task', 'supervisor/task/*') ? 'bg-black' : 'bg-[#f66d11]' }}">
                    <i class="fa-solid fa-file text-sm" style="color: #ffffff;"></i>
                </div>
                <div class="flex justify-between w-full">
                    <p class="block">My Tasks</p>
                    <p class="mr-10 text-[.8rem] py-1 text-white px-2 rounded-lg bg-red-600 {{ ($supervisorTaskCountContent + $supervisorTaskCountGraphic) < 1 ? 'hidden' : '' }}">{{ $supervisorTaskCountContent + $supervisorTaskCountGraphic}}</p>
                </div>
            </a>
        </div>

        <div class="block px-6">
            <a href="{{ url('/revision') }}"
                class="p-2 flex items-center w-full gap-2 rounded-md {{ request()->is('revision', 'revisions', 'revision/*') ? 'bg-[#f68e12] text-white font-bold' : 'hover:bg-gray-200 transition-all' }}"
                style="{{ request()->is('revision', 'revisions', 'revision/*') ? 'box-shadow: 0 1px 10px rgba(0, 0, 0, 0.6);' : '' }}">
                <div class="size-10 flex items-center justify-center rounded-lg {{ request()->is('revision', 'revision/*') ? 'bg-black' : 'bg-[#f66d11]' }}">
                    <i class="fa-solid fa-pencil-alt text-sm" style="color: #ffffff;"></i>
                </div>
                <div class="flex justify-between w-full">
                    <p class="block">My Revisions</p>
                    <p class="mr-10 text-[.8rem] py-1 text-white px-2 rounded-lg bg-red-600 {{ $revisionCount < 1 ? 'hidden' : '' }}">{{ $revisionCount }}</p>
                </div>
            </a>
        </div>

        <div class="block px-6">
            <a href="{{ url('/supervisor/approve') }}"
                class="p-2 flex items-center w-full gap-2 rounded-md {{ request()->is('supervisor/approve', 'supervisor/approve/*') ? 'bg-[#f68e12] text-white font-bold' : 'hover:bg-gray-200 transition-all' }}"
                style="{{ request()->is('supervisor/approve', 'supervisor/approve/*') ? 'box-shadow: 0 1px 10px rgba(0, 0, 0, 0.6);' : '' }}">
                <div class="size-10 flex items-center justify-center rounded-lg {{ request()->is('supervisor/approve', 'supervisor/approve/*') ? 'bg-black' : 'bg-[#f66d11]' }}">
                    <i class="fa-solid fa-list-check text-sm" style="color: #ffffff;"></i>
                </div>
                <div class="flex justify-between w-full">
                    <p class="block">Approvals</p>
                    <p class="mr-10 text-[.8rem] py-1 text-white px-2 rounded-lg bg-red-600 {{ $supervisorApprovalCount < 1 ? 'hidden' : '' }}">{{ $supervisorApprovalCount }}</p>
                </div>
            </a>
        </div>


        <div class="block px-6">
            <a href="{{ url('/track') }}"
                class="p-2 flex items-center w-full gap-2 rounded-md {{ request()->is('track', 'track/*') ? 'bg-[#f68e12] text-white font-bold' : 'hover:bg-gray-200 transition-all' }}"
                style="{{ request()->is('track', 'track/*') ? 'box-shadow: 0 1px 10px rgba(0, 0, 0, 0.6);' : '' }}">
                <div class="size-10 flex items-center justify-center rounded-lg {{ request()->is('track', 'track/*') ? 'bg-black' : 'bg-[#f66d11]' }}">
                    <i class="fa-regular fa-map" style="color: #ffffff;"></i>
                </div>
                <h1 class="block">Track Job Orders</h1>
            </a>
        </div>

        <div class="block px-6">
            <a href="{{ url('/supervisor/renewal') }}"
                class="p-2 flex items-center w-full gap-2 rounded-md {{ request()->is('supervisor/renewal', 'supervisor/renewal/*') ? 'bg-[#f68e12] text-white font-bold' : 'hover:bg-gray-200 transition-all' }}"
                style="{{ request()->is('supervisor/renewal', 'supervisor/renewal/*') ? 'box-shadow: 0 1px 10px rgba(0, 0, 0, 0.6);' : '' }}">
                <div class="size-10 flex items-center justify-center rounded-lg {{ request()->is('supervisor/renewal', 'supervisor/renewal/*') ? 'bg-black' : 'bg-[#f66d11]' }}">
                    <i class="fa-solid fa-sync-alt text-sm" style="color: #ffffff;"></i>
                </div>
                <h1 class="block">Renewal</h1>
            </a>
        </div>

        <div class="block px-6">
            <a href="{{ url('/users') }}"
                class="p-2 flex items-center w-full gap-2 rounded-md {{ request()->is('users', 'register', 'users/*') ? 'bg-[#f68e12] text-white font-bold' : 'hover:bg-gray-200 transition-all' }}"
                style="{{ request()->is('users', 'register', 'users/*') ? 'box-shadow: 0 1px 10px rgba(0, 0, 0, 0.6);' : '' }}">
                <div class="size-10 flex items-center justify-center rounded-lg {{ request()->is('users', 'register', 'users/*') ? 'bg-black' : 'bg-[#f66d11]' }}">
                    <i class="fa-solid fa-user-plus text-sm" style="color: #ffffff;"></i>
                </div>
                <h1 class="block">Users</h1>
            </a>
        </div>

        <div class="block px-6">
            <a href="{{ url('/profile/show') }}"
                class="p-2 flex items-center w-full gap-2 rounded-md {{ request()->is('profile', 'profile/*') ? 'bg-[#f68e12] text-white font-bold' : 'hover:bg-gray-200 transition-all' }}"
                style="{{ request()->is('profile', 'profile/*') ? 'box-shadow: 0 1px 10px rgba(0, 0, 0, 0.6);' : '' }}">
                <div class="size-10 flex items-center justify-center rounded-lg {{ request()->is('profile', 'profile/*') ? 'bg-black' : 'bg-[#f66d11]' }}">
                    <i class="fa-solid fa-user text-sm" style="color: #ffffff;"></i>
                </div>
                <h1 class="block">Profile</h1>
            </a>
        </div>

        <div class="block px-6">
            <a href="{{ url('/requestForm/create') }}"
                class="p-2 flex items-center w-full gap-2 rounded-md {{ request()->is('requestForm/create', 'requestForm/history', 'requestForm/create/*') ? 'bg-[#f68e12] text-white font-bold' : 'hover:bg-gray-200 transition-all' }}"
                style="{{ request()->is('requestForm/create', 'requestForm/history', 'requestForm/create/*') ? 'box-shadow: 0 1px 10px rgba(0, 0, 0, 0.6);' : '' }}">
                <div class="size-10 flex items-center justify-center rounded-lg {{ request()->is('requestForm/create', 'requestForm/history', 'requestForm/create/*') ? 'bg-black' : 'bg-[#f66d11]' }}">
                    <i class="fa-solid fa-file-export text-sm" style="color: #ffffff;"></i>
                </div>
                <h1 class="block">Request Form</h1>
            </a>
        </div>

        <div class="block px-6">
            <a href="{{ url('/supervisor/history') }}"
                class="p-2 flex items-center w-full gap-2 rounded-md {{ request()->is('supervisor/history', 'supervisor/history/*') ? 'bg-[#f68e12] text-white font-bold' : 'hover:bg-gray-200 transition-all' }}"
                style="{{ request()->is('supervisor/history', 'supervisor/history/*') ? 'box-shadow: 0 1px 10px rgba(0, 0, 0, 0.6);' : '' }}">
                <div class="size-10 flex items-center justify-center rounded-lg {{ request()->is('supervisor/history', 'supervisor/history/*') ? 'bg-black' : 'bg-[#f66d11]' }}">
                    <i class="fa-solid fa-download text-sm" style="color: #ffffff;"></i>
                </div>
                <h1 class="block">Downloadables</h1>
            </a>
        </div>

        <div class="block lg:hidden px-6">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="p-2 flex items-center w-full gap-2 rounded-md bg-red-500 text-white font-bold hover:bg-red-600 transition duration-300">
                    <i class="fa-solid fa-power-off"></i>
                    <h1 class="block">Logout</h1>
                </button>
            </form>
        </div>

        @elseif ($link == 'accounting')

        {{-- Supervisor Sidebar Menu --}}
        <div class="block px-6">
            <a href="{{ url('/dashboard') }}"
                class="p-2 flex items-center w-full gap-2 rounded-md {{ request()->is('dashboard', 'dashboard/*') ? 'bg-[#f68e12] text-white font-bold' : 'hover:bg-gray-200 transition-all' }}"
                style="{{ request()->is('dashboard', 'dashboard/*') ? 'box-shadow: 0 1px 10px rgba(0, 0, 0, 0.6);' : '' }}">
                <div class="size-10 flex items-center justify-center rounded-lg {{ request()->is('dashboard', 'dashboard/*') ? 'bg-black' : 'bg-[#f66d11]' }}">
                    <i class="fa-solid fa-chart-line text-sm" style="color: #ffffff;"></i>
                </div>
                <h1 class="block">Dashboard</h1>
            </a>
        </div>

        <div class="block px-6">
            <a href="{{ url('/profile/show') }}"
                class="p-2 flex items-center w-full gap-2 rounded-md {{ request()->is('profile', 'profile/*') ? 'bg-[#f68e12] text-white font-bold' : 'hover:bg-gray-200 transition-all' }}"
                style="{{ request()->is('profile', 'profile/*') ? 'box-shadow: 0 1px 10px rgba(0, 0, 0, 0.6);' : '' }}">
                <div class="size-10 flex items-center justify-center rounded-lg {{ request()->is('profile', 'profile/*') ? 'bg-black' : 'bg-[#f66d11]' }}">
                    <i class="fa-solid fa-user text-sm" style="color: #ffffff;"></i>
                </div>
                <h1 class="block">Profile</h1>
            </a>
        </div>

        <div class="block px-6">
            <a href="{{ url('/requestForm/history') }}"
                class="p-2 flex items-center w-full gap-2 rounded-md {{ request()->is('requestForm/create', 'requestForm/history', 'requestForm/create/*') ? 'bg-[#f68e12] text-white font-bold' : 'hover:bg-gray-200 transition-all' }}"
                style="{{ request()->is('requestForm/create', 'requestForm/history', 'requestForm/create/*') ? 'box-shadow: 0 1px 10px rgba(0, 0, 0, 0.6);' : '' }}">
                <div class="size-10 flex items-center justify-center rounded-lg {{ request()->is('requestForm/create', 'requestForm/history', 'requestForm/create/*') ? 'bg-black' : 'bg-[#f66d11]' }}">
                    <i class="fa-solid fa-file-export text-sm" style="color: #ffffff;"></i>
                </div>
                <h1 class="block">Request Form</h1>
            </a>
        </div>

        <div class="block lg:hidden px-6">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="p-2 flex items-center w-full gap-2 rounded-md bg-red-500 text-white font-bold hover:bg-red-600 transition duration-300">
                    <i class="fa-solid fa-power-off"></i>
                    <h1 class="block">Logout</h1>
                </button>
            </form>
        </div>
    @endif
</div>
