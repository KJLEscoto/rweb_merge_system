<head>
    <title>{{ env('APP_NAME') }} | DTR | Interns Details</title>
</head>

<x-main-layout breadcumb="DTR / Interns" page="Intern Details">
    <main class="h-auto w-full flex flex-col gap-5 px-10 pt-10">
        <div class="rounded bg-white border-l-8 border-[#f56d11] h-auto w-full flex flex-col gap-5 p-5">

            <section class="flex w-full justify-between items-center">
                <a href="{{ route('admin.dtr.interns') }}"
                    class="border hover:border-[#f56d11] text-[#f56d11] transition flex items-center gap-1 px-3 py-2 text-sm rounded font-semibold w-fit">
                    <span class="eva--arrow-back-fill w-4 h-4"></span>
                    Back
                </a>

                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.dtr.interns.dtr', $user->id) }}"
                        class="border hover:border-[#f56d11] text-[#f56d11] transition flex items-center gap-1 px-3 py-2 text-sm rounded font-semibold w-fit">
                        View DTR
                    </a>
                    <a href="{{ route('admin.dtr.interns.details.edit', $user->id) }}"
                        class="bg-[#f56d11] hover:scale-105 transition text-white px-3 py-2 text-sm rounded font-semibold shadow-md w-fit">
                        Edit Intern
                    </a>
                </div>
            </section>

            <section class="flex items-end gap-5 mt-3">
                <div class="w-auto h-auto">
                    <div class="w-32 h-32 overflow-hidden rounded-full">
                        <img class="w-full h-full object-center"
                            src="
                                {{ optional(\App\Models\File::find(optional(\App\Models\Profile::find($user->profile_id))->file_id))->path .
                                    '?t=' .
                                    time() ??
                                    'resources/img/default-male.png' }}
                            "
                            alt="user profile">
                    </div>
                </div>

                <div>
                    <h1 class="text-lg font-medium capitalize">
                        {{ $user->firstname }} {{ substr($user->middlename, 0, 1) }}.
                        {{ $user->lastname }}
                    </h1>
                    @if ($user->status === 'active')
                        <p class="text-sm font-medium text-green-500">
                            {{ $user->status }}
                        </p>
                    @else
                        <p class="text-sm font-medium text-red-500">
                            {{ $user->status }}
                        </p>
                    @endif

                    <p>
                        {{ $user->role }}
                    </p>
                </div>
            </section>


            <div class="p-10 border border-gray-300 rounded grid grid-cols-3 gap-7">
                <div class="space-y-1">
                    <h1 class="font-bold text-xs">First Name</h1>
                    <p class="text-gray-600 capitalize">
                        {{ $user->firstname }}
                    </p>
                </div>
                <div class="space-y-1">
                    <h1 class="font-bold text-xs">Last Name</h1>
                    <p class="text-gray-600 capitalize">
                        {{ $user->lastname }}
                    </p>
                </div>
                <div class="space-y-1">
                    <h1 class="font-bold text-xs">Middle Name</h1>
                    <p class="text-gray-600 capitalize">
                        {{ $user->middlename }}
                    </p>
                </div>
                <div class="space-y-1">
                    <h1 class="font-bold text-xs">Gender</h1>
                    <div class="flex items-center gap-1">
                        <p class="text-gray-600">
                            {{ $user->gender }}
                        </p>
                        <div class="w-auto h-auto">
                            @if ($user->gender === 'female')
                                <span class="ion--female w-4 h-4 text-[#f56d11]"></span>
                            @else
                                <span class="ic--round-male w-4 h-4 text-[#f56d11]"></span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="space-y-1">
                    <h1 class="font-bold text-xs">Address</h1>
                    <div class="flex items-center gap-1">
                        <p class="text-gray-600">
                            {{ $user->address }}
                        </p>
                        <div class="w-auto h-auto">
                            <span class="fluent--location-16-regular w-4 h-4 text-[#f56d11]"></span>
                        </div>
                    </div>
                </div>
                <div class="space-y-1">
                    <h1 class="font-bold text-xs">School</h1>
                    <div class="flex items-center gap-1">
                        <p class="text-gray-600">
                            {{ $user->school }}
                        </p>
                        <div class="w-auto h-auto">
                            <span class="tabler--school w-4 h-4 text-[#f56d11]"></span>
                        </div>
                    </div>
                </div>
                <div class="space-y-1">
                    <h1 class="font-bold text-xs">Student No.</h1>
                    <div class="flex items-center gap-1">
                        <p class="text-gray-600">
                            {{ $user->student_no }}
                        </p>
                        <div class="w-auto h-auto">
                            <span class="oui--number w-4 h-4 text-[#f56d11]"></span>
                        </div>
                    </div>
                </div>
                <div class="space-y-1">
                    <h1 class="font-bold text-xs">Email</h1>
                    <div class="flex items-center gap-1">
                        <p class="text-gray-600">
                            {{ $user->email }}
                        </p>
                        <div class="w-auto h-auto">
                            <span class="mdi--email-outline w-4 h-4 text-[#f56d11]"></span>
                        </div>
                    </div>
                </div>
                <div class="space-y-1">
                    <h1 class="font-bold text-xs">Phone</h1>
                    <div class="flex items-center gap-1">
                        <p class="text-gray-600">
                            {{ $user->phone }}
                        </p>
                        <div class="w-auto h-auto">
                            <span class="mdi--phone w-4 h-4 text-[#f56d11]"></span>
                        </div>
                    </div>
                </div>

                <hr class="col-span-3 w-full">

                <div class="space-y-1">
                    <h1 class="font-bold text-xs">Emergency Contact Name</h1>
                    <div class="flex items-center gap-1">
                        <p class="text-gray-600">
                            {{ $user->emergency_contact_fullname }}
                        </p>
                    </div>
                </div>
                <div class="space-y-1">
                    <h1 class="font-bold text-xs">Emergency Contact Number</h1>
                    <div class="flex items-center gap-1">
                        <p class="text-gray-600">
                            {{ $user->emergency_contact_number }}
                        </p>
                    </div>
                </div>
                <div class="space-y-1">
                    <h1 class="font-bold text-xs">Emergency Contact Address</h1>
                    <div class="flex items-center gap-1">
                        <p class="text-gray-600">
                            {{ $user->emergency_contact_address }}
                        </p>
                    </div>
                </div>

                <hr class="col-span-3 w-full">

                <div class="space-y-1">
                    <h1 class="font-bold text-xs">Account Started</h1>
                    <div class="flex items-center gap-1">
                        <p class="text-gray-600">
                            {{ $user->starting_date }}
                        </p>
                    </div>
                </div>
                <div class="space-y-1">
                    <h1 class="font-bold text-xs">Emergency Expiration</h1>
                    <div class="flex items-center gap-1">
                        <p class="text-gray-600">
                            {{ $user->expiry_date }}
                        </p>
                    </div>
                </div>
            </div>

            <section class="h-auto w-full border border-gray-200 rounded overflow-hidden">
                <div
                    class="flex items-center gap-1 px-7 py-3 bg-gradient-to-r from-[#F57D11] via-[#F57D11]/90 to-[#F53C11] text-white shadow-md w-full">
                    <h1 class="font-semibold">Logged History</h1>
                </div>

                <div class="h-60 w-full bg-white overflow-auto">
                    <div class="text-black flex flex-col h-full items-start justify-start">
                        @forelse ($histories as $history)
                            <section
                                class="px-7 py-5 w-full h-fit border-b border-gray-200 hover:bg-gray-100 flex flex-wrap gap-2 justify-between items-center">
                                <div>
                                    <section class="font-bold">{{ $history['timeFormat'] ?? 'N/A' }}</section>
                                    <p class="text-sm font-medium text-gray-700">
                                        {{ $history['datetime'] ?? 'No date available' }}
                                    </p>
                                </div>
                                @if (!empty($history['description']) && $history['description'] === 'time in')
                                    <div class="flex items-center gap-1 select-none text-sm font-semibold">
                                        <p
                                            class="{{ isset($history['extra_description']) && $history['extra_description'] === 'late' ? 'text-red-500 font-bold' : 'text-green-500' }}">
                                            Time
                                            in{{ isset($history['extra_description']) && $history['extra_description'] === 'late' ? ' | Late' : '' }}
                                        </p>
                                    </div>
                                @else
                                    <div
                                        class="text-red-500 flex items-center gap-1 select-none text-sm font-semibold">
                                        <p>Time out</p>
                                    </div>
                                @endif
                            </section>
                        @empty
                            <div class="h-full w-full flex items-center justify-center">
                                <p class="text-center text-sm font-semibold text-gray-600">
                                    User has no logged history.
                                </p>
                            </div>
                        @endforelse
                    </div>
                </div>


            </section>
        </div>
    </main>
</x-main-layout>
