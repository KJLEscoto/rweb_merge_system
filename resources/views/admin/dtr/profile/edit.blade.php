<head>
    <title>{{ env('APP_NAME') }} | DTR | Edit Profile</title>
</head>

<x-main-layout breadcumb="DTR / Profile" page="Edit Profile">
    <main class="h-auto w-full flex flex-col gap-5 px-10 py-10">
        <form action="{{ route('admin.dtr.settings.update') }}" method="POST" class="rounded bg-white border-l-8 border-[#f56d11] h-auto w-full flex flex-col gap-5 p-5"
        enctype="multipart/form-data">
           @csrf
           @method('PUT')

           @if (session('success'))
            <x-modal.flash-msg msg="success" />
            @elseif (session('update'))
                <x-modal.flash-msg msg="update" />
            @elseif ($errors->has('invalid'))
                <x-modal.flash-msg msg="invalid" />
            @elseif (session('invalid'))
                <x-modal.flash-msg msg="invalid" />
            @endif

            <div class="flex items-start gap-10">
                <section class="flex items-end gap-5">
                    <div class="w-auto h-auto">
                        <div class="w-32 h-32 overflow-hidden rounded-full">
                            <img
                            id="imagePreview"
                            class="w-full h-full object-center" src="{{ \App\Models\File::where('id', 
                                    $user->profiles->file_id
                                )->first()->path }}
                            "
                                alt="user profile">
                        </div>
                    </div>

                    <div>
                        <h1 class="text-lg font-medium">
                            {{ $user->firstname }}
                            {{ $user->lastname }}
                        </h1>
                        <p class="text-sm font-medium text-gray-600">
                            {{ $user->role }}
                        </p>
                        <p>
                            {{ $user->status }}
                        </p>
                    </div>
                </section>

                <section class="flex items-center gap-3">
                    <input type="file" id="uploadButton" name="file" class="hidden" accept="image/*">
                        <label for="uploadButton"
                        class="bg-[#f56d11] hover:scale-105 transition text-white px-3 py-2 text-sm rounded font-semibold shadow-md w-fit">
                        Upload Image</label>
                        <button
                            type="submit"
                            name="type"
                            value="removeProfile"
                            class="bg-red-500 hover:scale-105 transition text-white px-3 py-2 text-sm rounded font-semibold shadow-md w-fit">
                            Remove Image
                        </button>
                    </section>
                        
                    </div>
                    
            <div class="p-10 border border-gray-300 rounded grid grid-cols-3 gap-7">
                <div class="space-y-1">
                    <h1 class="font-bold text-xs">First Name</h1>
                    <input type="text" name="firstname" id="firstname" value="{{ $user->firstname }}"
                        class="border px-2 py-1 rounded-sm w-full outline-none focus:ring-2 focus:ring-[#f56d11]">
                </div>

                <div class="space-y-1">
                    <h1 class="font-bold text-xs">Last Name</h1>
                    <input type="text" name="lastname" id="lastname" value="{{ $user->lastname }}"
                        class="border border-gray-300 px-2 py-1 rounded-sm w-full outline-none focus:ring-2 focus:ring-[#f56d11] focus:outline-none">
                </div>

                <div class="space-y-1">
                    <h1 class="font-bold text-xs">Middle Name</h1>
                    <input type="text" name="middlename" id="middlename" value="{{ $user->middlename }}"
                        class="border px-2 py-1 rounded-sm w-full outline-none focus:ring-2 focus:ring-[#f56d11]">
                </div>

                <div class="space-y-1">
                    <h1 class="font-bold text-xs">Gender</h1>
                    <div class="flex items-center gap-4">
                        <!-- Female Option -->
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
                    <h1 class="font-bold text-xs">Email</h1>
                    <input type="email" name="email" id="email" value="{{ $user->email }}"
                        class="border border-gray-300 px-2 py-1 rounded-sm w-full outline-none focus:ring-2 focus:ring-[#f56d11] focus:outline-none">
                </div>
                <div class="space-y-1">
                    <h1 class="font-bold text-xs">Phone</h1>
                    <input type="text" name="phone" id="phone" value="{{ $user->phone }}"
                        class="border border-gray-300 px-2 py-1 rounded-sm w-full outline-none focus:ring-2 focus:ring-[#f56d11] focus:outline-none">
                </div>
                {{-- <div class="space-y-1 hidden">
                    <h1 class="font-bold text-xs">Country</h1>
                    <input type="text" name="country" id="country" value="Philippines"
                        class="border border-gray-300 px-2 py-1 rounded-sm w-full outline-none focus:ring-2 focus:ring-[#f56d11] focus:outline-none">
                </div>
                <div class="space-y-1 hidden disabled">
                    <h1 class="font-bold text-xs">City</h1>
                    <input type="text" name="city" id="city" value="Makati"
                        class="border border-gray-300 px-2 py-1 rounded-sm w-full outline-none focus:ring-2 focus:ring-[#f56d11] focus:outline-none">
                </div>
                <div class="space-y-1 hidden">
                    <h1 class="font-bold text-xs">Postal Code</h1>
                    <input type="text" name="postal_code" id="postal_code" value="1200"
                        class="border border-gray-300 px-2 py-1 rounded-sm w-full outline-none focus:ring-2 focus:ring-[#f56d11] focus:outline-none">
                </div> --}}

                <div class="space-y-1">
                    <h1 class="font-bold text-xs">Address</h1>
                    <input type="text" name="address" id="address" value="{{ $user->address }}"
                        class="border border-gray-300 px-2 py-1 rounded-sm w-full outline-none focus:ring-2 focus:ring-[#f56d11] focus:outline-none">
                </div>

                <div class="space-y-1">
                    <h1 class="font-bold text-xs">New Password</h1>
                    <input type="password" name="password" id="password" placeholder="••••••••"
                        class="border border-gray-300 px-2 py-1 rounded-sm w-full outline-none focus:ring-2 focus:ring-[#f56d11] focus:outline-none">
                </div>
                <div class="space-y-1">
                    <h1 class="font-bold text-xs">Confirm Password</h1>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                        placeholder="••••••••"
                        class="border border-gray-300 px-2 py-1 rounded-sm w-full outline-none focus:ring-2 focus:ring-[#f56d11] focus:outline-none">
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('admin.dtr.profile') }}"
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
                debugger
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        imagePreview.src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            });
        });
    </script>
</x-main-layout>