<head>
    <title>{{ env('APP_NAME') }} | SMM | User Create</title>

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

        /* Styling for the checkboxes */
        .checkbox-label {
            margin-left: 8px;
            font-size: 0.875rem;
        }
    </style>
</head>

<x-main-layout breadcumb="SMM / Users" page="User Create">
    <div class="w-full px-6 py-10 mx-auto rounded-lg custom-shadow bg-white">
        <div>
            <a href="{{ route('admin.smm.users') }}">
                <div class="w-fit px-4 py-1 bg-[#fa7011] rounded-md text-white custom-shadow custom-hover-shadow">
                    Go Back
                </div>
            </a>
        </div>
        <form method="POST" class="relative" action="{{ route('admin.web.users.store') }}"
            enctype="multipart/form-data">
            @csrf
            <h1 class="mt-10 text-xl font-bold">Register User</h1>
            <div class="image-upload-container absolute -top-14 cursor-pointer right-0 size-24">
                <img id="image-preview" src="{{ asset('Assets/user-profile-profilepage.png') }}"
                    class="size-24 border-2 border-[#fa7011] rounded-full object-cover absolute top-0 right-0"
                    alt="Profile Picture" onclick="document.getElementById('file-input').click();">
                <input type="file" name="image" id="file-input" class="hidden" onchange="previewImage(event)">
                <div class="overlay" onclick="document.getElementById('file-input').click();">
                    <i class="fa-solid fa-camera"></i>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4 mt-4">
                <!-- Name -->
                <div class="w-full col-span-2 lg:col-span-1">
                    <p class="text-sm text-gray-600">Name</p>
                    <input type="text" name="name" value="{{ old('name') }}"
                        class="w-full border px-3 py-2  border-gray-200 rounded-lg" required>
                    @error('name')
                        <p class="text-red-600 text-sm">{{ $message }}</p>
                    @enderror
                </div>
                <!-- Email -->
                <div class="w-full col-span-2 lg:col-span-1">
                    <p class="text-sm text-gray-600">Email</p>
                    <input type="email" name="email" value="{{ old('email') }}"
                        class="w-full border px-3 py-2  border-gray-200 rounded-lg" required>
                    @error('email')
                        <p class="text-red-600 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                <div class="w-full col-span-2 lg:col-span-1">
                    <p class="text-sm text-gray-600">Role</p>
                    <select name="role_id" class="w-full border text-sm border-gray-200 rounded-lg !px-2 !py-1"
                        required>

                        <option value="">Select a role</option>

                        @php
                            $roles = [
                                'client' => ['id' => \App\Models\Role::where('position', 'like', '%client%')->value('id'), 'label' => 'Client'],
                                'operations_supervisor' => ['id' => \App\Models\Role::where('position', 'like', '%operations_supervisor%')->value('id'), 'label' => 'Operations Supervisor'],
                                'ui_ux' => ['id' => \App\Models\Role::where('position', 'like', '%ui_ux%')->value('id'), 'label' => 'Web Designer'],
                                'front_end' => ['id' => \App\Models\Role::where('position', 'like', '%front_end%')->value('id'), 'label' => 'Front-End Developer'],
                                'back_end' => ['id' => \App\Models\Role::where('position', 'like', '%back_end%')->value('id'), 'label' => 'Back-End Developer'],
                                'top_management' => ['id' => \App\Models\Role::where('position', 'like', '%top_management%')->value('id'), 'label' => 'Top Management'],
                                'assistant_supervisor' => ['id' => \App\Models\Role::where('position', 'like', '%assistant_supervisor%')->value('id'), 'label' => 'Assistant Supervisor'],
                            ];
                        @endphp

                        @foreach ($roles as $role)
                            <option value="{{ $role['id'] }}" {{ old('role_id') == $role['id'] ? 'selected' : '' }}>
                                {{ $role['label'] }}
                            </option>
                        @endforeach
                    </select>

                    @error('role_id')
                        <p class="text-red-600 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Phone -->
                <div class="w-full col-span-2 lg:col-span-1">
                    <p class="text-sm text-gray-600">Phone</p>
                    <input type="text" name="phone" value="{{ old('phone') }}"
                        class="w-full border px-3 py-2  border-gray-200 rounded-lg" required>
                    @error('phone')
                        <p class="text-red-600 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Role and Privileges -->


                <div class="w-full col-span-2">
                    <p class="text-sm text-gray-600">Assign Roles & Privileges</p>
                    <div>
                        <div class="overflow-x-auto bg-white rounded shadow-md overflow-y-auto max-h-[20rem]">
                            <table id="recordsTable" class="w-full border-collapse border border-gray-300">
                                <thead>
                                    {{-- <tr
                                        class="*:px-6 *:py-3 *:text-left *:text-sm *:font-semibold *:bg-gray-200 *:text-black *:text-nowrap">
                                        <th colspan="2">
                                            <button id="toggleSelection">
                                                <div
                                                    class="w-fit px-4 py-1 bg-[#fa7011] rounded-md text-white custom-shadow custom-hover-shadow">
                                                    Select All
                                                </div>
                                            </button>
                                        </th>
                                    </tr> --}}
                                    <tr
                                        class="*:px-6 *:py-3 *:text-left *:text-sm *:font-semibold *:bg-gray-200 *:text-black *:text-nowrap">
                                        <th>Page Access</th>
                                        <th>Privileges</th>
                                    </tr>
                                </thead>
                                <tbody id="recordsBody">
                                    @foreach ($pages as $page)
                                        <tr class="border border-gray-200">
                                            <td class="my-auto px-4 py-1">
                                                <div class="flex items-center">
                                                    <input id="pages{{ $page->id }}" type="checkbox" name="pages[]"
                                                        value="{{ $page->description }}"
                                                        class="h-6 bg-slate-700 page-checkbox">
                                                    <label class="checkbox-label"
                                                        for="pages{{ $page->id }}">{{ $page->description }}</label>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="grid grid-cols-2 px-4 py-1">
                                                    @foreach ($privileges as $privilege)
                                                        <div class="flex items-center space-x-2">
                                                            <input id="priv{{ $page->id }}_{{ $privilege->id }}" type="checkbox"
                                                                name="privileges[{{ $page->description }}][]"
                                                                value="{{ $privilege->description }}"
                                                                class="h-6 privilege-checkbox">
                                                            <label for="priv{{ $page->id }}_{{ $privilege->id }}"
                                                                class="checkbox-label">
                                                                {{ $privilege->description }}
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{-- @foreach ($pages as $page)
                        <div class="flex items-center space-x-2">
                            <input type="checkbox" name="pages[]" value="{{ $page->id }}" class="h-6 bg-slate-700">
                            <label class="checkbox-label">{{ $page->description }}</label>

                            <div class="ml-4">
                                <p class="font-semibold">Privileges for {{ $page->description }}:</p>
                                @foreach ($privileges as $privilege)
                                <div class="flex items-center space-x-2">
                                    <input type="checkbox" name="privileges[{{ $page->id }}][]"
                                        value="{{ $privilege->id }}" class="h-6">
                                    <label class="checkbox-label">{{ $privilege->description }}</label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endforeach --}}
                    </div>
                </div>

                <!-- Address -->
                <div class="w-full col-span-2 lg:col-span-2">
                    <p class="text-sm text-gray-600">Address</p>
                    <input type="text" name="address" value="{{ old('address') }}"
                        class="w-full border px-3 py-2  border-gray-200 rounded-lg" required>
                    @error('address')
                        <p class="text-red-600 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="w-full col-span-2 lg:col-span-1">
                    <p class="text-sm text-gray-600">Password</p>
                    <input type="password" name="password"
                        class="w-full rounded-lg border px-3 py-2  border-gray-200 focus:ring-0" required>
                    @error('password')
                        <p class="text-red-600 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="w-full col-span-2 lg:col-span-1">
                    <p class="text-sm text-gray-600">Confirm Password</p>
                    <input type="password" name="password_confirmation"
                        class="w-full rounded-lg border px-3 py-2  border-gray-200 focus:ring-0" required>
                    @error('password_confirmation')
                        <p class="text-red-600 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                    class="col-span-2 text-center py-4 w-full bg-[#fa7011] mt-10 rounded-lg custom-shadow custom-hover-shadow text-white font-bold">
                    Register
                </button>
            </div>
        </form>
    </div>
</x-main-layout>

<script>
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function () {
            const output = document.getElementById('image-preview');
            output.src = reader.result;
        }
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const pageCheckboxes = document.querySelectorAll('input[name="pages[]"]');

        pageCheckboxes.forEach(pageCheckbox => {
            pageCheckbox.addEventListener("change", function () {
                const pageId = this.value; // Get the page description (unique value)
                const privilegeCheckboxes = document.querySelectorAll(
                    `input[name="privileges[${pageId}][]"]`
                );
                const canReadCheckbox = document.querySelector(
                    `input[name="privileges[${pageId}][]"][value="can_read"]`
                );

                if (this.checked) {
                    // Enable all privileges
                    privilegeCheckboxes.forEach(privilegeCheckbox => {
                        privilegeCheckbox.disabled = false;
                    });

                    // Auto-check "can_read"
                    if (canReadCheckbox) {
                        canReadCheckbox.checked = true;
                    }
                } else {
                    // Uncheck and disable all privileges
                    privilegeCheckboxes.forEach(privilegeCheckbox => {
                        privilegeCheckbox.checked = false;
                        privilegeCheckbox.disabled = true;
                    });
                }
            });

            // Add event listener for each privilege checkbox
            document.querySelectorAll(`input[name="privileges[${pageCheckbox.value}][]"]`).forEach(
                privilegeCheckbox => {
                    privilegeCheckbox.addEventListener("change", function () {
                        const privilegeList = Array.from(document.querySelectorAll(
                            `input[name="privileges[${pageCheckbox.value}][]"]:checked`
                        ));
                        const canReadCheckbox = document.querySelector(
                            `input[name="privileges[${pageCheckbox.value}][]"][value="can_read"]`
                        );

                        // If "can_read" is unchecked and it's the last checked privilege, uncheck and disable the page checkbox
                        if (!canReadCheckbox.checked && privilegeList.length === 0) {
                            pageCheckbox.checked = false;
                            pageCheckbox.dispatchEvent(new Event("change")); // Trigger change event to disable everything
                        }
                    });
                }
            );

            // Trigger change event on page load to set the correct state
            pageCheckbox.dispatchEvent(new Event("change"));

            // Check if any privilege is already checked, if so, do not disable.
            const initialPrivilegeCheck = document.querySelectorAll(`input[name="privileges[${pageCheckbox.value}][]"]:checked`);
            if (initialPrivilegeCheck.length > 0) {
                document.querySelectorAll(`input[name="privileges[${pageCheckbox.value}][]"]`).forEach(privilegeCheckbox => {
                    privilegeCheckbox.disabled = false;
                });
            }

        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('toggleSelection').addEventListener('click', function (event) {
            event.preventDefault(); // Prevent form submission if inside a form

            let checkboxes = document.querySelectorAll('.page-checkbox, .privilege-checkbox');
            let allChecked = [...checkboxes].every(checkbox => checkbox.checked);

            checkboxes.forEach(checkbox => checkbox.checked = !allChecked);

            this.textContent = allChecked ? "Select All" : "Deselect All";
        });
    });
</script>