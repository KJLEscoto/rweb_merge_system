@extends('layouts.application')

@section('title', 'Edit User')
@section('header', 'Edit User')

@section('content')
<script src="https://cdn.tailwindcss.com"></script>
<div class="mx-auto max-w-screen-2xl">

    @if (session('Status'))
    <div id="toast" class="fixed top-4 right-4 z-50">
        <div id="success-message" class="bg-green-500 text-white p-4 rounded-md shadow-lg">
            {{ session('Status') }}
        </div>
    </div>
    @endif

    <div class="h-auto">
        <div class="px-10 text-white">
            <div class="w-full flex justify-end items-end mb-4 cursor-pointer" onclick="window.location.assign('{{ url('profile/show') }}')">
                <div class="w-fit px-4 py-1 bg-[#f68e12] rounded-md">Go Back</div>
            </div>

            <form action="{{ url('profile/update/') }}" method="POST" enctype="multipart/form-data" id="userForm">
                @csrf
                @method('PATCH')

                <div class="grid grid-cols-3 h-auto gap-6 text-black">
                    <div class="space-y-10 col-span-3 lg:col-span-1">
                        <div class="col-span-3 px-4 lg:col-span-1 h-fit pb-10 bg-white shadow-md rounded-md pt-10 border border-[#e1e1e1]">
                            <div class="w-full flex justify-center items-center">
                                <img id="profileImage" class="rounded-full w-32 h-32 object-cover"
                                    src="{{ $user->image ? asset($user->image) : asset('/Assets/user-profile-profilepage.png') }}"
                                    alt="Profile Picture">
                            </div>
                            <div class="text-center">
                                <h1>{{ $user->name }}</h1>
                                <h1>{{ $user->address }}</h1>
                            </div>

                            <div class="flex items-center justify-center gap-2 text-white mt-4">
                                <div id="changeProfileBtn" class="px-4 py-1 bg-[#fa7011] rounded-md cursor-pointer text-nowrap text-sm">Change Profile</div>
                            </div>
                            <input type="file" name="image" id="profileImageInput" accept="image/*" class="hidden">
                        </div>

                        {{-- Signature Implementation --}}
                        <div class="px-10 col-span-3 w-full lg:col-span-3 bg-white shadow-md rounded-md pt-10 py-10">
                            <div class="text-center">
                                <h1 class="text-[#fa7011] font-bold">Signature</h1>
                            </div>
                            <div class="w-full h-full flex justify-center items-center">
                                @if ($user->signature)
                                    <img class="object-fill w-full" src="{{ asset($user->signature) }}" alt="User Signature">
                                @else
                                    <p>No Signature Added</p>
                                @endif
                            </div>
                            <div class="flex items-center justify-center gap-2 text-white mt-4">
                                <button type="button" id="usePad" class="px-4 py-1 bg-[#fa7011] rounded-md text-sm">Draw Signature</button>
                                <button type="button" id="useUpload" class="px-4 py-1 bg-[#fa7011] rounded-md text-sm">Upload Signature</button>
                            </div>

                            <div id="padSection" class="hidden mt-4">
                                <canvas id="signature-pad" class="w-full col-span-1 border-2 border-red-400" style="height:200px;"></canvas>
                                <div class="mt-2 flex">
                                    <button type="button" id="clearPad" class="bg-gray-500 text-white px-2 py-1 rounded mr-2">Clear</button>
                                </div>
                                <input type="hidden" name="signature_pad" id="signaturePadData">
                            </div>

                            <div id="uploadSection" class="hidden mt-4">
                                <input type="file" name="signature" id="signatureInput" accept="image/*" class="border p-2 w-full rounded-md">
                                <div class="mt-4 w-52 h-32 border border-gray-300 rounded-md overflow-hidden flex items-center justify-center bg-gray-100">
                                    <img id="imagePreview" src="#" alt="Selected Image" class="hidden w-full h-full object-cover">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-span-3 lg:col-span-2 bg-white shadow-md h-fit rounded-md p-5 border border-[#e1e1e1]">
                        <div class="text-slate-500">
                            <h1 class="text-sm">User Information</h1>
                        </div>

                        <div class="space-y-2">
                            <div class="gap-4 items-center">
                                <h1 class="text-slate-500 font-bold">Name</h1>
                                <input class="pl-4 w-full border rounded-md py-1 border-[#e1e1e1]"
                                    value="{{ old('name', $user->name) }}" name="name" required>
                                @error('name') <p class="text-sm text-red-700">{{ $message }}</p> @enderror
                            </div>

                            <div class="gap-4 items-center">
                                <h1 class="text-slate-500 font-bold">Email</h1>
                                <input class="pl-4 w-full border rounded-md py-1 border-[#e1e1e1]"
                                    value="{{ old('email', $user->email) }}" name="email" required>
                                @error('email') <p class="text-sm text-red-700">{{ $message }}</p> @enderror
                            </div>

                            <div class="gap-4 items-center">
                                <h1 class="text-slate-500 font-bold">Phone</h1>
                                <input class="pl-4 w-full border rounded-md py-1 border-[#e1e1e1]"
                                    value="{{ old('phone', $user->phone) }}" name="phone" required>
                                @error('phone') <p class="text-sm text-red-700">{{ $message }}</p> @enderror
                            </div>

                            <div class="gap-4 items-center pb-4">
                                <h1 class="text-slate-500 font-bold">Address</h1>
                                <input class="pl-4 w-full border rounded-md py-1 border-[#e1e1e1]"
                                    value="{{ old('address', $user->address) }}" name="address" required>
                                @error('address') <p class="text-sm text-red-700">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <hr>

                        <div class="text-slate-500">
                            <h1 class="text-sm">Password</h1>
                        </div>
                        <div class="gap-4 items-center text-slate-500 font-bold">
                            <div class="flex space-x-2 items-center">
                              <h1>Current Password</h1>
                            </div>
                            <input type="password" name="current_password"
                              class="pl-4 col-span-2 w-full border rounded-md py-1 border-[#e1e1e1]">
                            @if(session('errors'))
                              @if(session('errors')->has('current_password'))
                                <span class="text-red-700">{{ session('errors')->first('current_password') }}</span>
                              @endif
                            @endif
                          </div>
                        <div class="gap-4 items-center">
                            <h1 class="text-slate-500 font-bold">New Password</h1>
                            <input type="password" name="password" class="pl-4 w-full border rounded-md py-1 border-[#e1e1e1]">
                            @error('password') <p class="text-sm text-red-700">{{ $message }}</p> @enderror
                        </div>
                        <div class="gap-4 items-center">
                            <h1 class="text-slate-500 font-bold">Confirm Password</h1>
                            <input type="password" name="password_confirmation" class="pl-4 w-full border rounded-md py-1 border-[#e1e1e1]">
                            @error('password_confirmation') <p class="text-sm text-red-700">{{ $message }}</p> @enderror
                        </div>

                        <div class="pt-4">
                            <button type="submit" class="px-4 py-1 bg-[#f68e12] w-fit cursor-pointer text-white">Update</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/signature_pad"></script>
<script>
    // Function to display toast notifications
    function showToast(message, type = 'success') {
        let toastContainer = document.getElementById('toast-container');
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.id = 'toast-container';
            toastContainer.className = 'fixed top-20 right-4 z-50 space-y-2';
            document.body.appendChild(toastContainer);
        }
        let toast = document.createElement('div');
        toast.className = 'p-4 rounded shadow-lg text-white ' + (type === 'error' ? 'bg-red-500' : 'bg-green-500');
        toast.innerText = message;
        toastContainer.appendChild(toast);
        // Auto-remove toast after 3 seconds
        setTimeout(() => {
            toast.style.transition = 'opacity 0.5s';
            toast.style.opacity = '0';
            setTimeout(() => {
                toast.remove();
            }, 500);
        }, 3000);
    }

    // Auto-hide the session toast (if present) after 3 seconds
    setTimeout(function() {
        var toast = document.getElementById('toast');
        if (toast) {
            toast.style.transition = "opacity 0.5s";
            toast.style.opacity = "0";
            setTimeout(() => toast.style.display = "none", 500);
        }
    }, 3000);

    // Trigger file input when "Change Profile" is clicked
    document.getElementById("changeProfileBtn").addEventListener("click", function() {
        document.getElementById("profileImageInput").click();
    });

        // Preview the selected image and validate its file size
        document.getElementById("profileImageInput").addEventListener("change", function(event) {
        let file = event.target.files[0];
        const maxFileSize = 2097152; // 2MB in bytes
        if (file) {
            if (file.size > maxFileSize) {
                showToast("File size is too big. Maximum allowed is 2MB.", "error");
                // Clear the file input
                document.getElementById("profileImageInput").value = "";
                return;
            }
            let reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById("profileImage").src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });
document.addEventListener("DOMContentLoaded", () => {
    const canvas = document.getElementById("signature-pad");
    const signaturePad = new SignaturePad(canvas);

    // ✅ Set Canvas Width and Height Correctly
    function resizeCanvas() {
        const ratio = Math.max(window.devicePixelRatio || 1, 1); // Handle high DPI screens
        canvas.width = canvas.offsetWidth * ratio;  // Scale width
        canvas.height = canvas.offsetHeight * ratio; // Scale height
        canvas.getContext("2d").scale(ratio, ratio); // Scale context
        signaturePad.clear(); // Clear canvas on resize
    }

    // Call resizeCanvas() once page loads
    resizeCanvas();

    // Re-adjust on window resize
    window.addEventListener("resize", resizeCanvas);

    // Show signature pad section
    document.getElementById("usePad").addEventListener("click", () => {
        document.getElementById("padSection").classList.remove("hidden");
        document.getElementById("uploadSection").classList.add("hidden");
        resizeCanvas(); // Ensure correct size when toggled
    });

    // Show upload section
    document.getElementById("useUpload").addEventListener("click", () => {
        document.getElementById("uploadSection").classList.remove("hidden");
        document.getElementById("padSection").classList.add("hidden");
    });

    // Clear signature pad
    document.getElementById("clearPad").addEventListener("click", () => {
        signaturePad.clear();
    });

    // Preview uploaded image
    document.getElementById("signatureInput").addEventListener("change", (event) => {
        const file = event.target.files[0];
        const preview = document.getElementById("imagePreview");
        if (file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                preview.src = e.target.result;
                preview.classList.remove("hidden");
            };
            reader.readAsDataURL(file);
        }
    });

    // Form submission with validation
    document.getElementById("userForm").addEventListener("submit", (event) => {
        event.preventDefault(); // Prevent default form submission

        const uploadSection = document.getElementById("uploadSection");
        const padSection = document.getElementById("padSection");

        if (!uploadSection.classList.contains("hidden")) {
            // Validate upload section
            if (!document.getElementById("signatureInput").value) {
                alert("Please upload an image before submitting.");
                return;
            }
        } else if (!padSection.classList.contains("hidden")) {
            // Validate signature pad
            if (signaturePad.isEmpty()) {
                alert("Please sign before submitting.");
                return;
            } else {
                document.getElementById("signaturePadData").value = signaturePad.toDataURL("image/png");
            }
        }

        event.target.submit(); // Submit form after validation
    });
});

</script>
@endsection
