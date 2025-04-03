<head>
    <title>{{ env('APP_NAME') }} | SMM | Show User</title>

    <script src="https://cdn.tailwindcss.com"></script>

    @php
        $roles = [
            1 => 'Client',
            2 => 'Assistant Supervisor',
            3 => 'Content Writer',
            4 => 'Graphic Designer',
            5 => 'Top Management',
            6 => 'Operations Supervisor',
            7 => 'Accounting',
            12 => 'Sales Assistant',
        ];
    @endphp
</head>

<x-main-layout breadcumb="SMM / Users" page="Show User">

    {{-- Middle Part --}}

    <div class=" text-white">
        <div class="w-full flex justify-end items-end mb-4 cursor-pointer"
            onclick="window.location.assign('{{ url('admin/smm/users') }}')">
            <div class="w-fit px-4 py-1 bg-[#f68e12] rounded-md">Go Back</div>
        </div>
        <div class="grid mt-5 grid-cols-3 h-80 gap-6 text-black">
            <div class="px-10 col-span-3 lg:col-span-1 bg-white shadow-md rounded-md pt-10 py-10">
                <div class="w-full flex justify-center items-center">
                    {{-- <img class="rounded-full w-32 h-32 object-cover"
                        src="{{ file_exists(public_path($user->image)) && $user->image ? asset($user->image) : asset('/Assets/user-profile-profilepage.png') }}"
                        alt="User Image"> --}}

                    <img class="rounded-full w-32 h-32 object-cover"
                        src="{{ file_exists(public_path($user->image)) && $user->image ? asset($user->image) : asset('/Assets/user-profile-profilepage.png') }}"
                        alt="User Image">
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

            <div class="col-span-3 lg:col-span-2 bg-white shadow-md rounded-md p-5">
                <div class="flex justify-between">
                    <h1 class="text-sm font-semibold">User Information</h1>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="">
                            <div
                                class="px-4 py-2 bg-[#f68e12] cursor-pointer text-white rounded-md hover:bg-[#e57f0f] text-center">
                                <a href="{{ url('admin/smm/users/edit/' . $user->id) }}" class="block w-full">Edit</a>
                            </div>
                        </div>
                        <form action="{{ url('admin/smm/users/destroy/' . $user->id) }}" method="POST"
                            onsubmit="return confirm('Are you sure you want to delete this user?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="px-4 py-2 bg-red-700 cursor-pointer text-white rounded-md hover:bg-red-800 w-full">
                                Delete
                            </button>
                        </form>
                    </div>
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
{{-- @endsection --}}