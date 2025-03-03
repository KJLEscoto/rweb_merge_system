<head>
    <title>{{ env('APP_NAME') }} | DTR | Add Intern</title>
</head>

<x-main-layout breadcumb="DTR / Interns" page="Add Intern">

    @if (session('success'))
        <x-modal.flash-msg msg="success" />
    @elseif (session('update'))
        <x-modal.flash-msg msg="update" />
    @elseif ($errors->has('invalid'))
        <x-modal.flash-msg msg="invalid" />
    @elseif (session('invalid'))
        <x-modal.flash-msg msg="invalid" />
    @endif

    <main class="h-auto w-full flex flex-col gap-5 px-10 py-10">
        <form action="{{ route('admin.dtr.interns.create.post') }}" method="POST"
            class="rounded bg-white border-l-8 border-[#f56d11] h-auto w-full flex flex-col gap-5 p-5">
            @csrf

            <div class="flex items-center gap-3">
                <a href="{{ route('admin.dtr.interns') }}"
                    class="border hover:border-[#f56d11] text-[#f56d11] transition flex items-center gap-1 px-3 py-2 text-sm rounded font-semibold w-fit">
                    <span class="eva--arrow-back-fill w-4 h-4"></span>
                    Back
                </a>
                <button type="submit"
                    class="bg-[#f56d11] hover:scale-105 transition text-white px-3 py-2 text-sm rounded font-semibold shadow-md w-fit">
                    Create Account
                </button>
            </div>

            <div class="p-10 border border-gray-300 rounded grid grid-cols-3 gap-7">

                <div class="col-span-3">
                    <p class="font-semibold text-red-500">Personal Information</p>
                </div>

                <div class="space-y-1">
                    <h1 class="font-bold text-xs">First Name</h1>
                    <input type="text" name="firstname" id="first_name" placeholder="Gabby"
                        class="border px-2 py-1 rounded-sm w-full outline-none focus:ring-2 focus:ring-[#f56d11]">
                </div>

                <div class="space-y-1">
                    <h1 class="font-bold text-xs">Last Name</h1>
                    <input type="text" name="lastname" id="last_name" placeholder="Doe"
                        class="border border-gray-300 px-2 py-1 rounded-sm w-full outline-none focus:ring-2 focus:ring-[#f56d11] focus:outline-none">
                </div>

                <div class="space-y-1">
                    <h1 class="font-bold text-xs">Middle Name</h1>
                    <input type="text" name="middlename" id="middle_name" placeholder="Watson"
                        class="border px-2 py-1 rounded-sm w-full outline-none focus:ring-2 focus:ring-[#f56d11]">
                </div>

                <div class="space-y-1">
                    <h1 class="font-bold text-xs">Gender</h1>
                    <div class="flex items-center gap-4">
                        <!-- Female Option (Set as Default) -->
                        <label for="female" class="flex items-center gap-1 cursor-pointer">
                            <input type="radio" id="female" name="gender" value="female" class="peer hidden">
                            <div
                                class="w-4 h-4 border border-gray-300 rounded-full flex items-center justify-center 
                peer-checked:bg-[#f56d11] peer-checked:border-[#f56d11]">
                                <svg class="peer-checked:block w-2.5 h-2.5 text-white" fill="currentColor"
                                    viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="6"></circle>
                                </svg>
                            </div>
                            <p class="text-gray-600">Female</p>
                        </label>

                        <!-- Male Option -->
                        <label for="male" class="flex items-center gap-1 cursor-pointer">
                            <input type="radio" id="male" name="gender" value="male" class="peer hidden">
                            <div
                                class="w-4 h-4 border border-gray-300 rounded-full flex items-center justify-center 
                peer-checked:bg-[#f56d11] peer-checked:border-[#f56d11]">
                                <svg class="peer-checked:block w-2.5 h-2.5 text-white" fill="currentColor"
                                    viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="6"></circle>
                                </svg>
                            </div>
                            <p class="text-gray-600">Male</p>
                        </label>
                    </div>
                </div>

                <div class="space-y-1">
                    <h1 class="font-bold text-xs">Address</h1>
                    <input type="text" name="address" id="address" placeholder="Davao City"
                        class="border border-gray-300 px-2 py-1 rounded-sm w-full outline-none focus:ring-2 focus:ring-[#f56d11] focus:outline-none">
                </div>

                <div class="col-span-3">
                    <p class="font-semibold text-red-500">Account Information</p>
                </div>

                @php
                    $schools = \App\Models\School::get();
                    $school_options = [];

                    foreach ($schools as $school) {
                        if (strpos(strtolower($school['description']), 'rweb') !== 0) {
                            $school_options[] = $school['description'];
                        }
                    }
                @endphp

                <div class="space-y-1">
                    <h1 class="font-bold text-xs">School</h1>
                    <select name="school" id="school"
                        class="border border-gray-300 px-2 py-1 rounded-sm w-full outline-none focus:ring-2 focus:ring-[#f56d11] focus:outline-none">
                        <option value="" disabled selected>Select a school</option>
                        @foreach ($school_options as $school)
                            <option value="{{ $school }}">{{ $school }}</option>
                        @endforeach
                    </select>
                </div>


                <div class="space-y-1">
                    <h1 class="font-bold text-xs">Student No.</h1>
                    <input type="text" name="student_no" id="student_no" placeholder="02-0002-362671"
                        class="border border-gray-300 px-2 py-1 rounded-sm w-full outline-none focus:ring-2 focus:ring-[#f56d11] focus:outline-none">
                </div>
                <div class="space-y-1">
                    <h1 class="font-bold text-xs">Phone</h1>
                    <input type="text" name="phone" id="phone" placeholder="09123456789"
                        class="border border-gray-300 px-2 py-1 rounded-sm w-full outline-none focus:ring-2 focus:ring-[#f56d11] focus:outline-none">
                </div>
                <div class="space-y-1">
                    <h1 class="font-bold text-xs">Email</h1>
                    <input type="email" name="email" id="email_address" placeholder="gabby@email.com"
                        class="border border-gray-300 px-2 py-1 rounded-sm w-full outline-none focus:ring-2 focus:ring-[#f56d11] focus:outline-none">
                </div>

                <div class="space-y-1">
                    <h1 class="font-bold text-xs">Password</h1>
                    <input type="password" name="password" id="password" placeholder="••••••••"
                        class="border border-gray-300 px-2 py-1 rounded-sm w-full outline-none focus:ring-2 focus:ring-[#f56d11] focus:outline-none">
                </div>
                <div class="space-y-1">
                    <h1 class="font-bold text-xs">Confirm Password</h1>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                        placeholder="••••••••"
                        class="border border-gray-300 px-2 py-1 rounded-sm w-full outline-none focus:ring-2 focus:ring-[#f56d11] focus:outline-none">
                </div>

                <div class="col-span-3">
                    <p class="font-semibold text-red-500">Emergency Contact</p>
                </div>

                <div class="space-y-1">
                    <h1 class="font-bold text-xs">Full Name</h1>
                    <input type="text" name="emergency_contact_fullname" id="emergency_full_name"
                        placeholder="John Doe"
                        class="border border-gray-300 px-2 py-1 rounded-sm w-full outline-none focus:ring-2 focus:ring-[#f56d11] focus:outline-none">
                </div>
                <div class="space-y-1">
                    <h1 class="font-bold text-xs">Address</h1>
                    <input type="text" name="emergency_contact_number" id="emergency_address"
                        placeholder="Davao City"
                        class="border border-gray-300 px-2 py-1 rounded-sm w-full outline-none focus:ring-2 focus:ring-[#f56d11] focus:outline-none">
                </div>
                <div class="space-y-1">
                    <h1 class="font-bold text-xs">Contact No.</h1>
                    <input type="text" name="emergency_contact_address" id="emergency_contact_no"
                        placeholder="09123456789"
                        class="border border-gray-300 px-2 py-1 rounded-sm w-full outline-none focus:ring-2 focus:ring-[#f56d11] focus:outline-none">
                </div>
            </div>
        </form>
    </main>
</x-main-layout>
