@php
    $user = Auth::user();
@endphp

@props(['header' => ""])

<div class="container mx-auto max-w-screen-xl overflow-hidden">
<div class="grid grid-cols-3">
    <x-pagetitle header="{{ $header }}" />
    <div class="hidden md:block">
        <div class="bg-[#fa7011] rounded-bl-[40px] flex items-center justify-center gap-8 h-fit px-2 py-4">
            <div class="flex flex-col items-center justify-center">
                <h1 class="text-white hidden lg:block truncate max-w-[150px] sm:max-w-[200px] md:max-w-[250px] lg:max-w-[300px] xl:max-w-[350px] break-words">
                    Hi, {{ $user->name ?? 'Guest' }}
                </h1>
                <h1 class="text-gray-300 text-[.7rem]">{{ ucwords(str_replace('_', ' ', $user->role->position)) }}</h1>
            </div>


            {{-- Proper way to check if user has an image --}}
            <img src="{{ $user->image ? asset($user->image) : asset('/Assets/user-profile-profilepage.png') }}"
                 class="w-14 h-14 rounded-full object-cover"
                 alt="User Profile"
                 draggable="false">

            {{-- Logout Button --}}
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit">
                    <i class="fa-solid fa-power-off text-xl bg-white px-2 py-1 rounded-lg" style="color: #fa7011;"></i>
                </button>
            </form>
        </div>
    </div>
</div>
</div>
