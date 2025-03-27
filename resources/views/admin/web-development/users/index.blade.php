<head>
    <title>{{ env('APP_NAME') }} | Web Development | Users</title>
</head>

@php
    // $user = [
    //     'id' => 1,
    //     'name' => 'sample name',
    //     'role' => 'sample role',
    // ];
@endphp

<x-main-layout breadcumb="Web Development" page="Users">

    {{-- @if ($users->whereNotIn('role', $not_intern_roles)->first()) --}}

    @if ($user)
        <div class="space-y-5">
            <section class="flex md:flex-row flex-col-reverse items-center lg:justify-between w-full gap-5">

                <section class="lg:w-1/2 w-full">
                    <div class="w-full relative flex items-center">
                        <span class="meteor-icons--search w-5 h-5 absolute left-3 text-gray-500"></span>
                        <input type="text" name="search" id="search"
                            class="pl-10 py-2 pr-4 rounded-lg border border-gray-300 w-full outline-none focus:ring-2 focus:ring-[#f56d11]"
                            placeholder="Search...">
                    </div>
                </section>

                <span class="flex md:justify-end w-full">
                    <a href="{{ route('admin.web.users.create') }}"
                        class="bg-[#f56d11] hover:scale-105 transition text-white px-3 py-2 text-sm rounded font-semibold shadow-md w-fit flex items-center gap-1">
                        <span class="ic--round-add w-5 h-5"></span>
                        Add User
                    </a>
                </span>
            </section>

            <section class="grid lg:!grid-cols-4 md:grid-cols-3 grid-cols-2 gap-5" id="user-container">
                @foreach ($user as $usr)
                    {{-- @if (!in_array($user->roles->position, $not_intern_roles)) --}}
                    <a href="{{ route('admin.web.users.show', $usr['id']) }}"
                        class="p-5 border border-gray-200 rounded-xl cursor-pointer group animate-transition hover:border-[#F57D11] flex flex-col gap-5 items-center justify-center h-auto w-full bg-white user-card">

                        <div class="w-auto h-auto">
                            <div class="w-24 h-24 rounded-full border border-[#F57D11] overflow-hidden">
                                {{-- <x-image className="w-full h-full"
                                    path="{{ optional(\App\Models\File::find(optional(\App\Models\Profile::find($user->profile_id))->file_id))->path .
                                                                                                '?t=' .
                                                                                                time() ??
                                                                                                'resources/img/default-male.png' }}" />
                                --}}
                                <img src="{{ asset('resources/img/male-profile.jpg') }}" class="w-full h-full"
                                    alt="profile pic">
                            </div>
                        </div>

                        <div class="text-center mx-auto w-full">
                            <h1 class="text-sm font-semibold group-hover:text-[#F57D11] animate-transition truncate capitalize">
                                {{ $usr['name'] }}
                            </h1>
                            <p class="text-gray-500 truncate">
                                {{-- {{ \App\Models\School::where('id', $user->school_id)->first()->description ?? 'No school'
                                }} --}}
                                {{ $usr['role'] }}
                            </p>
                        </div>
                    </a>
                    {{-- @endif --}}
                    {{-- @endforeach --}}
                @endforeach
            </section>


            <!-- Pagination Controls -->
            {{-- <section class="flex lg:flex-row flex-col gap-3 items-center justify-between w-full">
                <p class="text-sm text-gray-500">
                    Showing <span id="first-item">1</span> - <span id="last-item">10</span> of <span id="total-items">{{
                        count($users) }}</span>
                </p>

                <div class="flex gap-3 items-center">
                    <button id="prev-page"
                        class="px-4 py-2 bg-gray-300 rounded disabled:opacity-50 hover:bg-[#F57D11] hover:text-white animate-transition disabled:hover:bg-gray-300 disabled:hover:text-current"
                        disabled>Prev</button>
                    <span id="page-info">Page 1 of </span>
                    <button id="next-page"
                        class="px-4 py-2 bg-gray-300 rounded disabled:opacity-50 hover:bg-[#F57D11] hover:text-white animate-transition disabled:hover:bg-gray-300 disabled:hover:text-current">Next</button>
                </div>
            </section> --}}
        </div>
    @else
        <div class="w-full h-auto flex items-center justify-center flex-col gap-10">
            <h1 class="text-3xl italic font-semibold">No Users Yet.</h1>
            <img draggable="false" class="w-auto h-80" src="{{ asset('image/revisions_empty.png') }}">
            <a href="#"
                class="bg-[#f56d11] hover:scale-105 transition text-white px-3 py-2 text-sm rounded font-semibold shadow-md w-fit flex items-center gap-1">
                <span class="ic--round-add w-5 h-5"></span>
                Add User
            </a>
        </div>
    @endif
</x-main-layout>