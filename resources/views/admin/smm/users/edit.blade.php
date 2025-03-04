@extends('layouts.application')

@section('title', 'Edit User')
@section('header', 'Edit User')

@section('content')
<script src="https://cdn.tailwindcss.com"></script>
<div class="mx-auto max-w-screen-2xl">

    @if (session('status'))
        <div id="success-message" class="bg-green-500 text-white p-4 rounded-md mb-4">
            {{ session('status') }}
        </div>
    @endif

    <div class="h-auto">
        <div class="px-10 text-white">
            <div class="w-full flex justify-end items-end mb-4 cursor-pointer" onclick="window.location.assign('{{ url('users') }}')">
                <div class="w-fit px-4 py-1 bg-[#f68e12] rounded-md">Go Back</div>
            </div>

            <form action="{{ url('users/update/' . $user->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-3 h-auto gap-6 text-black">
                    <div class="col-span-3 px-4 lg:col-span-1 h-fit pb-10 bg-white shadow-md rounded-md pt-10 border border-[#e1e1e1]">
                        <div class="w-full flex justify-center items-center">
                            <img id="profileImage" class="rounded-full w-32 h-32 object-cover" 
                                src="{{ $user->image ? asset($user->image) : asset('/Assets/user-profile-profilepage.png') }}" 
                                alt="Profile Picture">
                        </div>
                        <div class="text-center">
                            <h1>{{ old('name', $user->name) }}</h1>
                            <h1>{{ old('address', $user->address) }}</h1>
                        </div>

                        <div class="flex items-center justify-center gap-2 text-white mt-4">
                            <div id="changeProfileBtn" class="px-4 py-1 bg-[#fa7011] rounded-md cursor-pointer text-nowrap text-sm">Change Profile</div>
                            <div id="removeProfileBtn" class="px-4 py-1 bg-red-500 rounded-md cursor-pointer text-nowrap text-sm">Remove Profile</div>
                        </div>
                        <input type="file" name="image" id="profileImageInput" accept="image/*" class="hidden">
                    </div>

                    <div class="col-span-3 lg:col-span-2 bg-white shadow-md rounded-md p-5 border border-[#e1e1e1]">
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
                                <h1 class="text-slate-500 font-bold">Role</h1>
                                <select name="role_id" class="w-full border-gray-200 px-3 py-1 rounded-lg custom-shadow custom-focus-ring" required>
                                    <option value="1" {{ old('role_id', $user->role_id) == 1 ? 'selected' : '' }}>Client</option>
                                    <option value="2" {{ old('role_id', $user->role_id) == 2 ? 'selected' : '' }}>Operations Manager</option>
                                    <option value="3" {{ old('role_id', $user->role_id) == 3 ? 'selected' : '' }}>Content Writer</option>
                                    <option value="4" {{ old('role_id', $user->role_id) == 4 ? 'selected' : '' }}>Graphic Designer</option>
                                    <option value="5" {{ old('role_id', $user->role_id) == 5 ? 'selected' : '' }}>Top Manager</option>
                                    <option value="6" {{ old('role_id', $user->role_id) == 6 ? 'selected' : '' }}>Supervisor</option>
                                    <option value="6" {{ old('role_id', $user->role_id) == 6 ? 'selected' : '' }}>Accounting</option>
                                </select>
                                @error('role_id') <p class="text-sm text-red-700">{{ $message }}</p> @enderror
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

<script>
    setTimeout(function() {
        var message = document.getElementById('success-message');
        if (message) {
            message.style.transition = "opacity 0.5s";
            message.style.opacity = "0";
            setTimeout(() => message.style.display = "none", 500);
        }
    }, 3000);

    document.getElementById("changeProfileBtn").addEventListener("click", function() {
    document.getElementById("profileImageInput").click();
});

document.getElementById("profileImageInput").addEventListener("change", function(event) {
    let file = event.target.files[0];
    if (file) {
        let reader = new FileReader();
        reader.onload = function(e) {
            // Update only the image inside the form
            document.getElementById("profileImage").src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
});

document.getElementById("removeProfileBtn").addEventListener("click", function() {
    document.getElementById("profileImageInput").value = "";
    document.getElementById("profileImage").src = "{{ asset('/Assets/user-profile-profilepage.png') }}";
});

</script>
@endsection
