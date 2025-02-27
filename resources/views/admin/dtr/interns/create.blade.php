<head>
    <title>{{ env('APP_NAME') }} | DTR | Add Intern</title>
</head>

<x-main-layout breadcumb="DTR / Interns" page="Add Intern">

    {{-- <x-form.container routeName="admin.dtr.interns.create.post" method="POST"
        className="h-auto w-full flex flex-col gap-5 px-10 pt-10">
        @csrf
        @method('POST')

        @if (session('success'))
            <x-modal.flash-msg msg="success" />
        @endif

        <div
            class="w-full flex items-center justify-between gap-5 bg-white p-3 border border-gray-200 shadow-lg sticky top-[125px] z-30 rounded-full">
            <x-button routePath="admin.dtr.interns" label="Back" tertiary button leftIcon="eva--arrow-back-fill"
                className="px-8" />
            <x-button primary label="Create Account" submit className="px-6" />
        </div>

        <div class="flex flex-col lg:!gap-7 gap-5 h-full">
            <section class="flex flex-col gap-5 w-full p-7 border border-gray-200 rounded-lg bg-white">

                <x-form.section-title title="Personal Information" vectorClass="lg:h-5 h-3" />
                <div class="grid sm:grid-cols-3 w-full gap-5">
                    <x-form.input label="First Name" type="text" name_id="firstname" placeholder="John"
                        labelClass="text-lg font-medium" small />
                    <x-form.input label="Last Name" type="text" name_id="lastname" placeholder="Doe"
                        labelClass="text-lg font-medium" small />
                    <x-form.input label="Middle Name" type="text" name_id="middlename" placeholder="Watson"
                        labelClass="text-lg font-medium" small />
                </div>
                <div class="grid grid-cols-2 w-full gap-5">
                    <x-form.input label="Gender" name_id="gender" placeholder="Select" small type="select"
                        :options="['male' => 'Male', 'female' => 'Female']" />
                    @php
                        $schools = \App\Models\School::get();

                        $school_options = [];
                        foreach ($schools as $school) {
                            if (strpos(strtolower($school['description']), 'rweb') !== 0) {
        $school_options[] = $school['description'];
                            }
                        }
                    @endphp
                    <x-form.input label="School" name_id="school" placeholder="Select" small type="select"
                        :options="$school_options" />
                </div>
                <div class="grid grid-cols-2 w-full gap-5">
                    <x-form.input label="Address" type="text" name_id="address" placeholder="Davao City"
                        labelClass="text-lg font-medium" small />

                    <x-form.input label="Student No" type="text" name_id="student_no" placeholder="02-0002-60001"
                        labelClass="text-lg font-medium" small />
                </div>
            </section>

            <section class="flex flex-col gap-5 w-full p-7 border border-gray-200 rounded-lg bg-white">

                <x-form.section-title title="Account Information" vectorClass="lg:h-5 h-3" />
                <div class="grid grid-cols-2 w-full gap-5">
                    <x-form.input label="Email" name_id="email" placeholder="example@gmail.com"
                        labelClass="text-lg font-medium" small />
                    <x-form.input label="Phone" type="text" name_id="phone" placeholder="+63"
                        labelClass="text-lg font-medium" small />
                </div>
                <div class="grid grid-cols-2 w-full gap-5">
                    <x-form.input label="Password" type="password" name_id="password" placeholder="••••••••"
                        labelClass="text-lg font-medium" small />
                    <x-form.input label="Confirm Password" type="password" name_id="password_confirmation"
                        placeholder="••••••••" labelClass="text-lg font-medium" small />
                </div>
            </section>

            <section class="flex flex-col gap-5 w-full p-7 border border-gray-200 rounded-lg bg-white">

                <x-form.section-title title="Emergency Contact" vectorClass="lg:h-5 h-3" />
                <div class="grid sm:grid-cols-3 w-full gap-5">
                    <x-form.input label="Full Name" type="text" name_id="emergency_contact_fullname"
                        placeholder="Johny Doe" labelClass="text-lg font-medium" small />
                    <x-form.input label="Contact No." type="text" name_id="emergency_contact_number"
                        placeholder="+63" labelClass="text-lg font-medium" small />
                    <x-form.input label="Address" type="text" name_id="emergency_contact_address"
                        placeholder="Davao City" labelClass="text-lg font-medium" small />
                </div>
            </section>
        </div>
    </x-form.container> --}}

    @if (session('success'))
        <x-modal.flash-msg msg="success" />
    @endif

    <main class="h-auto w-full flex flex-col gap-5 px-10 py-10">
        <div class="rounded bg-white border-l-8 border-[#f56d11] h-auto w-full flex flex-col gap-5 p-5">

            <div class="p-10 border border-gray-300 rounded grid grid-cols-3 gap-7">

                <div class="col-span-3">
                    <p class="font-semibold text-red-500">Personal Information</p>
                </div>

                <div class="space-y-1">
                    <h1 class="font-bold text-xs">First Name</h1>
                    <input type="text" name="first_name" id="first_name" placeholder="Gabby"
                        class="border px-2 py-1 rounded-sm w-full outline-none focus:ring-2 focus:ring-[#f56d11]">
                </div>

                <div class="space-y-1">
                    <h1 class="font-bold text-xs">Last Name</h1>
                    <input type="text" name="last_name" id="last_name" placeholder="Doe"
                        class="border border-gray-300 px-2 py-1 rounded-sm w-full outline-none focus:ring-2 focus:ring-[#f56d11] focus:outline-none">
                </div>

                <div class="space-y-1">
                    <h1 class="font-bold text-xs">Middle Name</h1>
                    <input type="text" name="middle_name" id="middle_name" placeholder="Watson"
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

                <div class="space-y-1">
                    <h1 class="font-bold text-xs">School</h1>
                    <input type="email" name="school" id="school" placeholder="Select"
                        class="border border-gray-300 px-2 py-1 rounded-sm w-full outline-none focus:ring-2 focus:ring-[#f56d11] focus:outline-none">
                </div>

                <div class="space-y-1">
                    <h1 class="font-bold text-xs">Student No.</h1>
                    <input type="password" name="student_no" id="student_no" placeholder="02-0002-362671"
                        class="border border-gray-300 px-2 py-1 rounded-sm w-full outline-none focus:ring-2 focus:ring-[#f56d11] focus:outline-none">
                </div>
                <div class="space-y-1">
                    <h1 class="font-bold text-xs">Phone</h1>
                    <input type="text" name="phone" id="phone" placeholder="09123456789"
                        class="border border-gray-300 px-2 py-1 rounded-sm w-full outline-none focus:ring-2 focus:ring-[#f56d11] focus:outline-none">
                </div>
                <div class="space-y-1">
                    <h1 class="font-bold text-xs">Email</h1>
                    <input type="email" name="email_address" id="email_address" placeholder="gabby@email.com"
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
                    <input type="text" name="emergency_full_name" id="emergency_full_name" placeholder="John Doe"
                        class="border border-gray-300 px-2 py-1 rounded-sm w-full outline-none focus:ring-2 focus:ring-[#f56d11] focus:outline-none">
                </div>
                <div class="space-y-1">
                    <h1 class="font-bold text-xs">Address</h1>
                    <input type="text" name="emergency_address" id="emergency_address" placeholder="Davao City"
                        class="border border-gray-300 px-2 py-1 rounded-sm w-full outline-none focus:ring-2 focus:ring-[#f56d11] focus:outline-none">
                </div>
                <div class="space-y-1">
                    <h1 class="font-bold text-xs">Contact No.</h1>
                    <input type="text" name="emergency_contact_no" id="emergency_contact_no"
                        placeholder="09123456789"
                        class="border border-gray-300 px-2 py-1 rounded-sm w-full outline-none focus:ring-2 focus:ring-[#f56d11] focus:outline-none">
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('admin.dtr.interns') }}"
                    class="border hover:border-[#f56d11] text-[#f56d11] transition flex items-center gap-1 px-3 py-2 text-sm rounded font-semibold w-fit">
                    <span class="eva--arrow-back-fill w-4 h-4"></span>
                    Back
                </a>
                <button
                    class="bg-[#f56d11] hover:scale-105 transition text-white px-3 py-2 text-sm rounded font-semibold shadow-md w-fit">
                    Create Account
                </button>
            </div>
        </div>
    </main>
</x-main-layout>
