<head>
    <title>{{ env('APP_NAME') }} | SMM | Downloadables</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @php
        $roles = [
            1 => 'Client',
            2 => 'Assistant Supervisor',
            3 => 'Content Writer',
            4 => 'Graphic Designer',
            5 => 'Top Management',
            6 => 'Operation Manager',
            7 => 'Accounting',
            12 => 'Sales Assistant',
        ];
    @endphp
</head>

<x-main-layout breadcumb="SMM" page="Profile">
    <div class=" text-white">
        <div class="grid mt-10 grid-cols-3 h-80 gap-6 text-black">
            <div class="space-y-10 col-span-3 lg:col-span-1">
                <div class="px-10 col-span-3 lg:col-span-1 bg-white shadow-md rounded-md pt-10 py-10">
                    <div class="w-full flex justify-center items-center">
                        @if ($user->profile && $user->profile->file && $user->profile->file->path)
                            <img class="rounded-full w-32 h-32 object-cover" src="{{ asset($user->profile->file->path) }}"
                                alt="User Image">
                        @else
                            <img class="rounded-full w-32 h-32 object-cover"
                                src="{{ asset('/Assets/user-profile-profilepage.png') }}" alt="Default User Image">
                        @endif
                    </div>
                    <div class="text-center mt-4">
                        <h1 class="font-semibold">{{ $user->name }}</h1>
                    </div>
                    <div class="text-center">
                        <h1 class="text-gray-500">{{ $user->address }}</h1>
                    </div>
                    <div class="text-center w-full flex items-center justify-center">
                        <h1 class="text-[#fa7011] font-bold">{{ $roles[$user->role_id] }}</h1>
                    </div>
                </div>

                <div class="px-10 col-span-3 w-full lg:col-span-3 bg-white shadow-md rounded-md pt-10 py-10">
                    <div class="w-full flex justify-center items-center ">
                        @if ($user->signature)
                            <img class="object-fill w-full" src="{{ asset($user->signature) }}" alt="User Signature">
                        @else
                            <p>No Signature Added.</p>
                        @endif
                    </div>
                    <div class="text-center w-full flex items-center justify-center">
                        <h1 class="text-[#fa7011] font-bold">Signature</h1>
                    </div>
                </div>
            </div>

            <div class="col-span-3 lg:col-span-2 h-fit bg-white shadow-md rounded-md p-5">
                <div class="flex justify-between">
                    <h1 class="text-sm font-semibold">User Information</h1>
                    <div class="px-4 py-1 bg-[#f68e12] cursor-pointer text-white rounded-md hover:bg-[#e57f0f]"
                        onclick="window.location.assign('{{ route('admin.smm.profile.edit') }}')">Edit</div>
                </div>
                <div class="space-y-4 mt-4">
                    <div>
                        <div class="flex space-x-2 items-center">
                            <img src="{{ asset('/Assets/name.png') }}" class="w-5 h-5" alt="">
                            <h1 class="font-medium">Name</h1>
                        </div>
                        <p class="pl-7 text-gray-700">{{ $user->name }}</p>
                    </div>
                    <div>
                        <div class="flex space-x-2 items-center">
                            <img src="{{ asset('/Assets/email.png') }}" class="w-5 h-5" alt="">
                            <h1 class="font-medium">Email</h1>
                        </div>
                        <p class="pl-7 text-gray-700">{{ $user->email }}</p>
                    </div>
                    <div>
                        <div class="flex space-x-2 items-center">
                            <img src="{{ asset('/Assets/phone-number.png') }}" class="w-5 h-5" alt="">
                            <h1 class="font-medium">Phone</h1>
                        </div>
                        <p class="pl-7 text-gray-700">{{ $user->phone }}</p>
                    </div>
                    <div>
                        <div class="flex space-x-2 items-center">
                            <img src="{{ asset('/Assets/address.png') }}" class="w-5 h-5" alt="">
                            <h1 class="font-medium">Address</h1>
                        </div>
                        <p class="pl-7 text-gray-700">{{ $user->address }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-main-layout>