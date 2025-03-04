@extends('layouts.application')

@section('title', 'Register')
@section('header', 'User Registration')

@section('content')
<script src="https://cdn.tailwindcss.com"></script>


<style>
    .custom-shadow {
        box-shadow: 0 2px 4px rgba(0, 0, 0, .3), 0 1px 3px rgba(0, 0, 0, .3);
    }
    .custom-hover-shadow:hover {
        box-shadow: 0 10px 15px rgba(0, 0, 0, 0), 0 4px 6px rgba(0, 0, 0, 0);
        transition: box-shadow 0.3s ease;
    }
    .custom-focus-ring:focus {
        outline: none;
        box-shadow: 0 0 0 1px #545454;
        transition: box-shadow 0.3s ease;
    }
    .image-upload-container {
        display: inline-block;
    }
    .image-upload-container:hover .overlay {
        display: flex;
    }
    .overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: none;
        justify-content: center;
        align-items: center;
        border-radius: 50%;
    }
    .overlay i {
        color: white;
        font-size: 1.5rem;
    }
</style>

<div class="container mx-auto p-6 ">
    <div class="w-full px-6 py-10 mx-auto rounded-lg custom-shadow bg-white">
        <div>
            <a href="{{ url('users') }}">
                <div class="w-fit px-4 py-1 bg-[#fa7011] rounded-md text-white custom-shadow custom-hover-shadow">
                    Go Back
                </div>
            </a>
        </div>
        <form method="POST" class="relative" action="{{ url('register') }}" enctype="multipart/form-data">
            @csrf
                <h1 class="mt-10 text-xl font-bold">Register User</h1>
                <div class="image-upload-container absolute -top-14 cursor-pointer right-0 size-24">
                    <input type="file" name="image" id="file-input" class="hidden" onchange="previewImage(event)">
                    <img id="image-preview" src="{{ asset('Assets/user-profile-profilepage.png') }}" class="size-24 border-2 border-[#fa7011] rounded-full object-cover absolute top-0 right-0" alt="Profile Picture" onclick="document.getElementById('file-input').click();">
                    <div class="overlay" onclick="document.getElementById('file-input').click();">
                        <i class="fa-solid fa-camera"></i>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4 mt-4">
                    <!-- Name -->
                    <div class="w-full col-span-2 lg:col-span-1">
                        <p class="text-sm text-gray-600">Name</p>
                        <input type="text" name="name" value="{{ old('name') }}" class="w-full border px-3 py-2  border-gray-200 rounded-lg" required>
                        @error('name')
                            <p class="text-red-600 text-sm">{{ $message }}</p>
                        @enderror
                    </div>
                    <!-- Email -->
                    <div class="w-full col-span-2 lg:col-span-1">
                        <p class="text-sm text-gray-600">Email</p>
                        <input type="email" name="email" value="{{ old('email') }}" class="w-full border px-3 py-2  border-gray-200 rounded-lg" required>
                        @error('email')
                            <p class="text-red-600 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Role -->
                    <div class="w-full col-span-2 lg:col-span-1">
                        <p class="text-sm text-gray-600">Role</p>
                        <select name="role_id" class="w-full border border-gray-200 rounded-lg px-2 py-2" required>
                            <option value="">Select a role</option>
                            <option value="1" {{ old('role_id') == 1 ? 'selected' : '' }}>Client</option>
                            <option value="2" {{ old('role_id') == 2 ? 'selected' : '' }}>Operations Manager</option>
                            <option value="3" {{ old('role_id') == 3 ? 'selected' : '' }}>Content Writer</option>
                            <option value="4" {{ old('role_id') == 4 ? 'selected' : '' }}>Graphic Designer</option>
                            <option value="5" {{ old('role_id') == 5 ? 'selected' : '' }}>Top Manager</option>
                            <option value="5" {{ old('role_id') == 6 ? 'selected' : '' }}>Supervisor</option>
                            <option value="5" {{ old('role_id') == 7 ? 'selected' : '' }}>Accounting</option>
                        </select>
                        @error('role_id')
                            <p class="text-red-600 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div class="w-full col-span-2 lg:col-span-1">
                        <p class="text-sm text-gray-600">Phone</p>
                        <input type="text" name="phone" value="{{ old('phone') }}" class="w-full border px-3 py-2  border-gray-200 rounded-lg" required>
                        @error('phone')
                            <p class="text-red-600 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Address -->
                    <div class="w-full col-span-2">
                        <p class="text-sm text-gray-600">Address</p>
                        <input type="text" name="address" value="{{ old('address') }}" class="w-full border px-3 py-2  border-gray-200 rounded-lg" required>
                        @error('address')
                            <p class="text-red-600 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="w-full col-span-2 lg:col-span-1">
                        <p class="text-sm text-gray-600">Password</p>
                        <input type="password" name="password" class="w-full rounded-lg border px-3 py-2  border-gray-200 focus:ring-0" required>
                        @error('password')
                            <p class="text-red-600 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="w-full col-span-2 lg:col-span-1">
                        <p class="text-sm text-gray-600">Confirm Password</p>
                        <input type="password" name="password_confirmation" class="w-full rounded-lg border px-3 py-2  border-gray-200 focus:ring-0" required>
                        @error('password_confirmation')
                            <p class="text-red-600 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="col-span-2 text-center py-4 w-full bg-[#fa7011] mt-10 rounded-lg custom-shadow custom-hover-shadow text-white font-bold">
                        Register
                    </button>
                </div>
        </form>
    </div>
</div>

<script>
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function() {
            const output = document.getElementById('image-preview');
            output.src = reader.result;
        }
        reader.readAsDataURL(event.target.files[0]);
    }
</script>

@endsection
