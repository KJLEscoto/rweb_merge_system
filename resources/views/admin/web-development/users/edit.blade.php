<head>
    <title>{{ env('APP_NAME') }} | Web Development | Edit Users</title>
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

        .checkbox-label {
            margin-left: 8px;
            font-size: 0.875rem;
        }
    </style>
</head>
<x-main-layout breadcumb="Web Development / Users" page="Edit Users">

    @if (session('Status'))
        <div id="success-message" class="bg-green-500 text-white p-4 rounded-md mb-4">
            {{ session('Status') }}
        </div>
    @endif

    <div class="h-auto">
        <div class=" text-white">
            <div class="w-full flex justify-end items-end mb-4 cursor-pointer"
                onclick="window.location.assign('{{ url('admin/smm/users') }}')">
                <div class="w-fit px-4 py-1 bg-[#f68e12] rounded-md">Go Back</div>
            </div>

            <form action="{{ route('admin.web.users.edit.post', $user->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-3 h-auto gap-6 text-black">
                    <div
                        class="col-span-3 px-4 lg:col-span-1 h-fit pb-10 bg-white shadow-md rounded-md pt-10 border border-[#e1e1e1]">
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
                            <div id="changeProfileBtn"
                                class="px-4 py-1 bg-[#fa7011] rounded-md cursor-pointer text-nowrap text-sm">Change
                                Profile
                            </div>
                            <div id="removeProfileBtn"
                                class="px-4 py-1 bg-red-500 rounded-md cursor-pointer text-nowrap text-sm">Remove
                                Profile
                            </div>
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
                                @error('name')
                                    <p class="text-sm text-red-700">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="gap-4 items-center">
                                <h1 class="text-slate-500 font-bold">Email</h1>
                                <input class="pl-4 w-full border rounded-md py-1 border-[#e1e1e1]"
                                    value="{{ old('email', $user->email) }}" name="email" required>
                                @error('email')
                                    <p class="text-sm text-red-700">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="gap-4 items-center">
                                <h1 class="text-slate-500 font-bold">Role</h1>
                                <select name="role_id"
                                    class="w-full border-gray-200 px-3 py-1 rounded-lg custom-shadow custom-focus-ring"
                                    required>
                                    @php
                                        $roles = [
                                            'client' => ['id' => \App\Models\Role::where('position', 'like', '%client%')->value('id'), 'label' => 'Client'],
                                            'operations_supervisor' => ['id' => \App\Models\Role::where('position', 'like', '%operations_supervisor%')->value('id'), 'label' => 'Operations Supervisor'],
                                            'ui_ux' => ['id' => \App\Models\Role::where('position', 'like', '%ui_ux%')->value('id'), 'label' => 'Web Designer'],
                                            'front_end' => ['id' => \App\Models\Role::where('position', 'like', '%front_end%')->value('id'), 'label' => 'Front-End Developer'],
                                            'back_end' => ['id' => \App\Models\Role::where('position', 'like', '%back_end%')->value('id'), 'label' => 'Back-End Developer'],
                                            'top_management' => ['id' => \App\Models\Role::where('position', 'like', '%top_management%')->value('id'), 'label' => 'Top Management'],
                                            'assistant_supervisor' => ['id' => \App\Models\Role::where('position', 'like', '%assistant_supervisor%')->value('id'), 'label' => 'Assistant Supervisor'],
                                            'accounting' => ['id' => \App\Models\Role::where('position', 'like', '%accounting%')->value('id'), 'label' => 'Accounting'],
                                        ];
                                    @endphp

                                    @foreach ($roles as $role)
                                        <option value="{{ $role['id'] }}" {{ old('role_id', $user->role_id) == $role['id'] ? 'selected' : '' }}>
                                            {{ $role['label'] }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('role_id')
                                    <p class="text-sm text-red-700">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="w-full col-span-2">
                                <p class="text-sm text-gray-600">Assign Roles & Privileges</p>
                                <div>
                                    <div
                                        class="overflow-x-auto bg-white rounded shadow-md overflow-y-auto max-h-[20rem]">
                                        <table id="recordsTable" class="w-full border-collapse border border-gray-300">
                                            <thead>
                                                <tr
                                                    class="*:px-6 *:py-3 *:text-left *:text-sm *:font-semibold *:bg-gray-200 *:text-black *:text-nowrap">
                                                    <th colspan="2">
                                                        <button id="toggleSelection">
                                                            <div
                                                                class="w-fit px-4 py-1 bg-[#fa7011] rounded-md text-white custom-shadow custom-hover-shadow">
                                                                Select All
                                                            </div>
                                                        </button>
                                                        {{-- <button id="toggleSelection"
                                                            class="px-4 py-2 bg-blue-500 text-white rounded">
                                                            Select All
                                                        </button> --}}
                                                    </th>
                                                </tr>
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
                                                                <input id="pages{{ $page->id }}" type="checkbox"
                                                                    name="pages[]" value="{{ $page->description }}"
                                                                    class="h-6 bg-slate-700 page-checkbox">
                                                                <label class="checkbox-label"
                                                                    for="pages{{ $page->id }}">{{ $page->description }}</label>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="grid grid-cols-2 px-4 py-1">
                                                                @foreach ($privileges as $privilege)
                                                                    <div class="flex items-center space-x-2">
                                                                        <input id="priv{{ $page->id }}_{{ $privilege->id }}"
                                                                            type="checkbox"
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
                                        <input type="checkbox" name="pages[]" value="{{ $page->id }}"
                                            class="h-6 bg-slate-700">
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

                            <div class="gap-4 items-center">
                                <h1 class="text-slate-500 font-bold">Phone</h1>
                                <input class="pl-4 w-full border rounded-md py-1 border-[#e1e1e1]"
                                    value="{{ old('phone', $user->phone) }}" name="phone" required>
                                @error('phone')
                                    <p class="text-sm text-red-700">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="gap-4 items-center">
                                <h1 class="text-slate-500 font-bold">Address</h1>
                                <input class="pl-4 w-full border rounded-md py-1 border-[#e1e1e1]"
                                    value="{{ old('address', $user->address) }}" name="address" required>
                                @error('address')
                                    <p class="text-sm text-red-700">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="gap-4 items-center">
                                <h1 class="text-slate-500 font-bold">Password</h1>
                                <input class="pl-4 w-full border rounded-md py-1 border-[#e1e1e1]" type="password"
                                    name="password" placeholder="Leave blank to keep current password">
                                @error('password')
                                    <p class="text-sm text-red-700">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="gap-4 items-center">
                                <h1 class="text-slate-500 font-bold">Confirm Password</h1>
                                <input class="pl-4 w-full border rounded-md py-1 border-[#e1e1e1]" type="password"
                                    name="password_confirmation" placeholder="Leave blank to keep current password">
                                @error('password_confirmation')
                                    <p class="text-sm text-red-700">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="gap-4 items-center">
                                <h1 class="text-slate-500 font-bold">Status</h1>
                                <select name="status"
                                    class="w-full border-gray-200 px-3 py-1 rounded-lg custom-shadow custom-focus-ring"
                                    required>
                                    <option value="1" {{ old('status', $user->status) == 1 ? 'selected' : '' }}>Active
                                    </option>
                                    <option value="0" {{ old('status', $user->status) == 0 ? 'selected' : '' }}>Inactive
                                    </option>
                                </select>
                                @error('status')
                                    <p class="text-sm text-red-700">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex items-center mt-4">
                                <input type="checkbox" id="is_client" name="is_client" value="1" {{ old('is_client', $user->is_client) ? 'checked' : '' }}>
                                <label for="is_client" class="checkbox-label">Is Client</label>
                            </div>

                        </div>
                        <div class="w-full flex justify-end items-end mt-4">
                            <button class="px-4 py-1 bg-[#f68e12] rounded-md text-white">Save Changes</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const successMessage = document.getElementById('success-message');
            if (successMessage) {
                setTimeout(function () {
                    successMessage.style.display = 'none';
                }, 5000);
            }

            const changeProfileBtn = document.getElementById('changeProfileBtn');
            const removeProfileBtn = document.getElementById('removeProfileBtn');
            const profileImageInput = document.getElementById('profileImageInput');
            const profileImage = document.getElementById('profileImage');

            changeProfileBtn.addEventListener('click', () => {
                profileImageInput.click();
            });

            removeProfileBtn.addEventListener('click', () => {
                profileImage.src = "{{ asset('/Assets/user-profile-profilepage.png') }}";
                profileImageInput.value = '';
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'remove_image';
                hiddenInput.value = '1';
                document.querySelector('form').appendChild(hiddenInput);
            });

            profileImageInput.addEventListener('change', (event) => {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        profileImage.src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            });
        });

    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const pageCheckboxes = document.querySelectorAll('input[name="pages[]"]');

            pageCheckboxes.forEach(pageCheckbox => {
                pageCheckbox.addEventListener("change", function () {
                    const pageId = this.value; // Get the page description (unique value)
                    const privilegeCheckboxes = document.querySelectorAll(
                        `input[name="privileges[${pageId}][]"]`);
                    const canReadCheckbox = document.querySelector(
                        `input[name="privileges[${pageId}][]"][value="can_read"]`);

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
                                pageCheckbox.dispatchEvent(new Event(
                                    "change")); // Trigger change event to disable everything
                            }
                        });
                    });

                // Trigger change event on page load to set the correct state
                pageCheckbox.dispatchEvent(new Event("change"));
            });
        });
    </script>
    <script>
        document.getElementById('toggleSelection').addEventListener('click', function (event) {
            event.preventDefault(); // Prevent form submission if inside a form

            let checkboxes = document.querySelectorAll('.page-checkbox, .privilege-checkbox');
            let allChecked = [...checkboxes].every(checkbox => checkbox.checked);

            checkboxes.forEach(checkbox => checkbox.checked = !allChecked);

            this.textContent = allChecked ? "Select All" : "Deselect All";


        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const pages = @json($pages);
            const privileges = @json($privileges);
            const selectedPages = @json($myPages);
            const selectedPrivileges = @json($myRoleChannels);

            debugger;

            console.log("Pages:", pages);
            console.log("Privileges:", privileges);
            console.log("Selected Pages:", selectedPages);
            console.log("Selected Privileges:", selectedPrivileges);

            const pageCheckboxes = document.querySelectorAll('.page-checkbox');
            const privilegeCheckboxes = document.querySelectorAll('.privilege-checkbox');

            pageCheckboxes.forEach(checkbox => {
                const pageDescription = checkbox.value;
                console.log(`Checking page: ${pageDescription}`);

                if (selectedPages && selectedPages.some(item => item.description === pageDescription)) {
                    checkbox.checked = true;
                    console.log(`Page: ${pageDescription} is checked.`);
                } else {
                    checkbox.checked = false;
                    console.log(`Page: ${pageDescription} is not checked.`);
                }
            });

            debugger;
            privilegeCheckboxes.forEach(checkbox => {
                const pageDescription = checkbox.name.match(/privileges\[(.*?)\]/)[1];
                const privilegeDescription = checkbox.value;

                console.log("Page Description:", pageDescription);
                console.log("Selected Privileges:", selectedPrivileges);
                console.log("selectedPrivileges[pageDescription]:", selectedPrivileges[pageDescription]);

                console.log(`Checking privilege: ${pageDescription} - ${privilegeDescription}`);

                // Find matching page and privilege dynamically
                const page = pages.find(p => p.description === pageDescription);
                const privilege = privileges.find(p => p.description === privilegeDescription);

                // if (page && privilege) {
                //     let matchingRoleChannel = false;

                //     // Loop through all pages and privileges dynamically
                //     for (const currentPage of pages) {
                //         for (const currentPrivilege of privileges) {
                //             if (currentPage.id === page.id && currentPrivilege.id === privilege.id) {
                //                 console.log(`Matching role_channel found for Page ID: ${page.id}, Privilege ID: ${privilege.id}`);
                //                 matchingRoleChannel = true;
                //                 break; // Stop checking once found
                //             }
                //         }
                //         if (matchingRoleChannel) break;
                //     }

                //     checkbox.checked = matchingRoleChannel;
                //     console.log(`Privilege: ${pageDescription} - ${privilegeDescription} is ${matchingRoleChannel ? "checked" : "not checked"}`);
                // } else {
                //     checkbox.checked = false;
                //     console.log(`Privilege: ${pageDescription} - ${privilegeDescription} is not checked (page or privilege not found).`);
                // }

                if (page && privilege) {
                    // Check if there's an entry in the dataset that matches the page_id and privilege_id
                    const hasAccess = selectedPrivileges.some(entry =>
                        entry.page_id === page.id && entry.privilege_id === privilege.id
                    );

                    // Set checkbox state dynamically
                    checkbox.checked = hasAccess;
                }
            });

            // Toggle Select All
            const toggleSelection = document.getElementById('toggleSelection');
            toggleSelection.addEventListener('click', () => {
                const allChecked = Array.from(pageCheckboxes).every(checkbox => checkbox.checked);

                pageCheckboxes.forEach(checkbox => {
                    checkbox.checked = !allChecked;
                });

                privilegeCheckboxes.forEach(checkbox => {
                    checkbox.checked = !allChecked;
                });
            });
        });
    </script>
</x-main-layout>