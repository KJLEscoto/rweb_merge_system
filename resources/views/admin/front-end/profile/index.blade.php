<head>
    <title>{{ env('APP_NAME') }} | Front-end | Profile</title>
</head>

<x-main-layout breadcumb="Front-end" page="Profile">
    <main class="h-auto w-full flex flex-col gap-5 px-10 py-10">
        <div class="rounded bg-white border-l-8 border-[#f56d11] h-auto w-full flex flex-col gap-5 p-5">
            <div class="flex items-start justify-between gap-5">
                <section class="flex items-end gap-5">
                    <div class="w-auto h-auto">
                        <div class="w-32 h-32 overflow-hidden rounded-full">
                            <img class="w-full h-full object-center" src="{{ asset('image/user.png') }}"
                                alt="user profile">
                        </div>
                    </div>

                    <div>
                        <h1 class="text-lg font-medium">Gabby Doe</h1>
                        <p class="text-sm font-medium text-gray-600">Client 1</p>
                        <p>Makati City</p>
                    </div>

                </section>

                <section class="hover:scale-105 transition">
                    <a class="text-sm text-white bg-[#f56d11] px-5 py-2 rounded font-medium"
                        href="{{ route('admin.front-end.profile.edit') }}">Edit Profile</a>
                </section>
            </div>

            <div class="p-10 border border-gray-300 rounded grid grid-cols-3 gap-7">
                <div class="space-y-1">
                    <h1 class="font-bold text-xs">First Name</h1>
                    <p class="text-gray-600">Gabby</p>
                </div>
                <div class="space-y-1">
                    <h1 class="font-bold text-xs">Last Name</h1>
                    <p class="text-gray-600">Doe</p>
                </div>
                <div class="space-y-1">
                    <h1 class="font-bold text-xs">Middle Name</h1>
                    <p class="text-gray-600">Watson</p>
                </div>
                <div class="space-y-1">
                    <h1 class="font-bold text-xs">Gender</h1>
                    <div class="flex items-center gap-1">
                        <p class="text-gray-600">Female</p>
                        <div class="w-auto h-auto">
                            <span class="ion--female w-4 h-4 text-[#f56d11]"></span>
                        </div>
                    </div>
                </div>
                <div class="space-y-1">
                    <h1 class="font-bold text-xs">Email</h1>
                    <div class="flex items-center gap-1">
                        <p class="text-gray-600">gabby@email.com</p>
                        <div class="w-auto h-auto">
                            <span class="mdi--email-outline w-4 h-4 text-[#f56d11]"></span>
                        </div>
                    </div>
                </div>
                <div class="space-y-1">
                    <h1 class="font-bold text-xs">Phone</h1>
                    <div class="flex items-center gap-1">
                        <p class="text-gray-600">09123456789</p>
                        <div class="w-auto h-auto">
                            <span class="mdi--phone w-4 h-4 text-[#f56d11]"></span>
                        </div>
                    </div>
                </div>
                <div class="space-y-1">
                    <h1 class="font-bold text-xs">Country</h1>
                    <div class="flex items-center gap-2">
                        <p class="text-gray-600">Philippines</p>
                        <div class="w-auto h-auto">
                            <span class="flag--ph-4x3 w-4 h-4"></span>
                        </div>
                    </div>
                </div>
                <div class="space-y-1">
                    <h1 class="font-bold text-xs">City</h1>
                    <div class="flex items-center gap-1">
                        <p class="text-gray-600">Makati</p>
                        <div class="w-auto h-auto">
                            <span class="fluent--location-16-regular w-4 h-4 text-[#f56d11]"></span>
                        </div>
                    </div>
                </div>
                <div class="space-y-1">
                    <h1 class="font-bold text-xs">Postal Code</h1>
                    <div class="flex items-center gap-1">
                        <p class="text-gray-600">1200</p>
                        <div class="w-auto h-auto">
                            <span class="oui--number w-4 h-4 text-[#f56d11]"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</x-main-layout>
