<head>
    <title>{{ env('APP_NAME') }} | DTR | Edit Interns</title>
</head>

<x-main-layout breadcumb="DTR / Inters / Intern Details" page="Edit Interns">
    <x-modal.forgot-password id="forgot-password-modal" />
    <x-modal.confirmation-email id="confirmation-email-modal" />

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
        <form action="{{ route('users.settings.update', $user->id) }}" method="POST"
            class="rounded bg-white border-l-8 border-[#f56d11] h-auto w-full flex flex-col gap-5 p-5">
            @csrf
            @method('PUT')

            <input type="hidden" name="user_id" id="user_id" value="{{ $user->id }}">

            <div class="flex items-start gap-10">
                <section class="flex items-end gap-5">
                    <div class="w-auto h-auto">
                        <div class="w-32 h-32 overflow-hidden rounded-full">
                            <img id="imagePreview" class="w-full h-full object-cover"
                                src="{{ optional(\App\Models\File::find(optional(\App\Models\Profile::find($user->profile_id))->file_id))->path .
                                    '?t=' .
                                    time() ??
                                    'resources/img/default-male.png' }}"
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

                <section class="flex items-center gap-3">
                    <input type="file" id="uploadButton" name="file" class="hidden" accept="image/*">
                    <label for="uploadButton"
                        class="bg-[#f56d11] hover:scale-105 cursor-pointer transition text-white px-3 py-2 text-sm rounded font-semibold shadow-md w-fit">
                        Upload Image</label>
                    <button type="submit" name="type" value="removeProfile"
                        class="bg-red-500 hover:scale-105 transition text-white px-3 py-2 text-sm rounded font-semibold shadow-md w-fit">
                        Remove Image
                    </button>
                </section>
            </div>

            <div class="p-10 border border-gray-300 rounded grid grid-cols-3 gap-7">

                <div class="col-span-3">
                    <p class="font-semibold text-red-500">Personal Information</p>
                </div>

                <div class="space-y-1">
                    <h1 class="font-bold text-xs">First Name</h1>
                    <input type="text" name="firstname" id="first_name" placeholder="Gabby"
                        value="{{ $user->firstname }}"
                        class="border px-2 py-1 rounded-sm w-full outline-none focus:ring-2 focus:ring-[#f56d11]">
                </div>

                <div class="space-y-1">
                    <h1 class="font-bold text-xs">Last Name</h1>
                    <input type="text" name="lastname" id="last_name" placeholder="Doe"
                        value="{{ $user->lastname }}"
                        class="border border-gray-300 px-2 py-1 rounded-sm w-full outline-none focus:ring-2 focus:ring-[#f56d11] focus:outline-none">
                </div>

                <div class="space-y-1">
                    <h1 class="font-bold text-xs">Middle Name</h1>
                    <input type="text" name="middlename" id="middle_name" placeholder="Watson"
                        value="{{ $user->middlename }}"
                        class="border px-2 py-1 rounded-sm w-full outline-none focus:ring-2 focus:ring-[#f56d11]">
                </div>

                <div class="space-y-1">
                    <h1 class="font-bold text-xs">Gender</h1>
                    <div class="flex items-center gap-4">
                        <!-- Female Option (Set as Default) -->
                        <label for="female" class="flex items-center gap-1 cursor-pointer">
                            <input type="radio" id="female" name="gender" value="female" class="peer hidden"
                                {{ $user->gender === 'female' ? 'checked' : '' }}>
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
                            <input type="radio" id="male" name="gender" value="male" class="peer hidden"
                                {{ $user->gender === 'male' ? 'checked' : '' }}>
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
                        value="{{ $user->address }}"
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
                    <option value="" disabled>Select a school</option>
                    @foreach ($school_options as $school)
                        <option value="{{ $school }}" {{ $user->school == $school ? 'selected' : '' }}>
                            {{ $school }}
                        </option>
                    @endforeach
                </select>
            </div>



                <div class="space-y-1">
                    <h1 class="font-bold text-xs">Student No.</h1>
                    <input type="text" name="student_no" id="student_no" placeholder="02-0002-362671"
                        value="{{ $user->student_no }}"
                        class="border border-gray-300 px-2 py-1 rounded-sm w-full outline-none focus:ring-2 focus:ring-[#f56d11] focus:outline-none">
                </div>
                <div class="space-y-1">
                    <h1 class="font-bold text-xs">Phone</h1>
                    <input type="text" name="phone" id="phone" placeholder="09123456789"
                        value="{{ $user->phone }}"
                        class="border border-gray-300 px-2 py-1 rounded-sm w-full outline-none focus:ring-2 focus:ring-[#f56d11] focus:outline-none">
                </div>
                <div class="space-y-1">
                    <h1 class="font-bold text-xs">Email</h1>
                    <input type="email" name="email" id="email_address" placeholder="gabby@email.com"
                        value="{{ $user->email }}"
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

                <div class="space-y-1">
                    <h1 class="font-bold text-xs">Starting Date</h1>
                    <input type="date" name="starting_date" id="starting_date" placeholder="MMM DD, YYY"
                        value="{{ $user->starting_date }}"
                        class="border border-gray-300 px-2 py-1 rounded-sm w-full outline-none focus:ring-2 focus:ring-[#f56d11] focus:outline-none">
                </div>
                <div class="space-y-1">
                    <h1 class="font-bold text-xs">Account Expiration</h1>
                    <input type="date" name="expiry_date" id="expiry_date" placeholder="MMM DD, YYY"
                        value="{{ $user->expiry_date }}"
                        class="border border-gray-300 px-2 py-1 rounded-sm w-full outline-none focus:ring-2 focus:ring-[#f56d11] focus:outline-none">
                </div>
                <div class="space-y-1">
                    <h1 class="font-bold text-xs">Status</h1>
                    <div class="flex items-center gap-4">
                        <!-- Active (Set as Default) -->
                        <label for="active" class="flex items-center gap-1 cursor-pointer">
                            <input type="radio" id="active" name="status" value="active" class="peer hidden"
                                {{ $user->status === 'active' ? 'checked' : '' }}>
                            <div
                                class="w-4 h-4 border border-gray-300 rounded-full flex items-center justify-center 
                peer-checked:bg-[#f56d11] peer-checked:border-[#f56d11]">
                                <svg class="peer-checked:block w-2.5 h-2.5 text-white" fill="currentColor"
                                    viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="6"></circle>
                                </svg>
                            </div>
                            <p class="text-gray-600">Active</p>
                        </label>

                        <!-- Inactive Option -->
                        <label for="inactive" class="flex items-center gap-1 cursor-pointer"
                            {{ $user->status === 'inactive' ? 'checked' : '' }}>
                            <input type="radio" id="inactive" name="status" value="inactive"
                                class="peer hidden">
                            <div
                                class="w-4 h-4 border border-gray-300 rounded-full flex items-center justify-center 
                peer-checked:bg-[#f56d11] peer-checked:border-[#f56d11]">
                                <svg class="peer-checked:block w-2.5 h-2.5 text-white" fill="currentColor"
                                    viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="6"></circle>
                                </svg>
                            </div>
                            <p class="text-gray-600">Inactive</p>
                        </label>
                    </div>
                </div>

                <div class="col-span-3">
                    <p class="font-semibold text-red-500">Emergency Contact</p>
                </div>

                <div class="space-y-1">
                    <h1 class="font-bold text-xs">Full Name</h1>
                    <input type="text" name="emergency_contact_fullname" id="emergency_full_name"
                        value="{{ $user->emergency_contact_fullname }}" placeholder="John Doe"
                        class="border border-gray-300 px-2 py-1 rounded-sm w-full outline-none focus:ring-2 focus:ring-[#f56d11] focus:outline-none">
                </div>
                <div class="space-y-1">
                    <h1 class="font-bold text-xs">Address</h1>
                    <input type="text" name="emergency_contact_number" id="emergency_address"
                        placeholder="Davao City" value="{{ $user->emergency_contact_address }}"
                        class="border border-gray-300 px-2 py-1 rounded-sm w-full outline-none focus:ring-2 focus:ring-[#f56d11] focus:outline-none">
                </div>
                <div class="space-y-1">
                    <h1 class="font-bold text-xs">Contact No.</h1>
                    <input type="text" name="emergency_contact_address" id="emergency_contact_no"
                        placeholder="09123456789" value="{{ $user->emergency_contact_number }}"
                        class="border border-gray-300 px-2 py-1 rounded-sm w-full outline-none focus:ring-2 focus:ring-[#f56d11] focus:outline-none">
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('admin.dtr.interns.details', $user->id) }}"
                    class="border hover:border-[#f56d11] text-[#f56d11] transition flex items-center gap-1 px-3 py-2 text-sm rounded font-semibold w-fit">
                    <span class="eva--arrow-back-fill w-4 h-4"></span>
                    Back
                </a>
                <button
                    class="bg-[#f56d11] hover:scale-105 transition text-white px-3 py-2 text-sm rounded font-semibold shadow-md w-fit">
                    Save Changes
                </button>
            </div>

        </form>
    </main>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const uploadButton = document.querySelector("#uploadButton");
            const imagePreview = document.querySelector("#imagePreview");

            uploadButton.addEventListener("change", function(event) {
                const file = event.target.files[0];

                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        imagePreview.src = e.target.result;
                        imagePreview.style.display = "block"; // Show the image when selected
                    };
                    reader.readAsDataURL(file);
                } else {
                    imagePreview.src = "";
                    imagePreview.style.display = "none"; // Hide preview if no file is selected
                }
            });
        });
    </script>
</x-main-layout>
