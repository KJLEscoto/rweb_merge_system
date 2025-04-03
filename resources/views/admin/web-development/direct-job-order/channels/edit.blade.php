<head>
    <title>{{ env('APP_NAME') }} | Web Development | Edit Direct Job Order</title>
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
    <style>
        /* Ensure CKEditor is scrollable with max height */
        .ck-editor__editable {
            max-height: 500px !important;
            overflow-y: auto !important;
        }
    </style>
</head>

<x-main-layout breadcumb="Web Development / Direct Job Order" page="Edit Direct Job Order">
    <form id="mainForm" action="{{ route('admin.web.direct-job-order.edit.post', ['id' => $web_project->id]) }}"
        method="POST" class="bg-white p-6 rounded-lg shadow-md flex flex-col gap-6">
        @csrf
        @method('PUT')

        <div class="flex gap-3 items-center">
            <a href="{{ route('admin.web.direct-job-order.showProjectChannels', $web_project->id) }}"
                class="border hover:border-[#f56d11] text-[#f56d11] transition flex items-center gap-1 px-3 py-2 text-sm rounded font-semibold w-fit">
                <span class="eva--arrow-back-fill w-4 h-4 mr-2"></span>
                Back
            </a>
            <button type="submit"
                class="bg-[#f56d11] hover:scale-105 transition text-white px-3 py-2 text-sm rounded font-semibold shadow-md w-fit">
                Submit
            </button>
        </div>

        <div class="space-y-6 p-6 border rounded-lg">
            <div class="space-y-2">
                <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                <input type="text" name="title" id="title" value="{{ old('title', $web_project->title) }}"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">Assigned Users</label>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Type</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Current User</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody id="assignedUsersTable" class="bg-white divide-y divide-gray-200">
                            @php
                                $assignedUsers = [];
                                foreach ($web_project->web_project_channels as $channel) {
                                    if ($channel->user_id) {
                                        $user = \App\Models\User::where('id', $channel->user_id)->first();
                                        if ($user) {
                                            $assignedUsers[$channel->type][] = [
                                                'id' => $user->id,
                                                'name' => $user->name,
                                                'channel_id' => $channel->id,
                                            ];
                                        }
                                    }
                                }
                            @endphp
                            @if (!empty($assignedUsers))
                                @foreach ($assignedUsers as $type => $users)
                                    @foreach ($users as $user)
                                        @if ($type != 'client')
                                            <tr id="row_{{ $user['channel_id'] }}">
                                                <td class="px-6 py-4 whitespace-nowrap">{{ str_replace('_', ' ', $type) }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <select name="users[{{ $user['channel_id'] }}][{{ $type }}]"
                                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                        @foreach ($employee as $emp)
                                                            @if ($emp->roles->position != 'client')
                                                                <option value="{{ $emp->id }}" {{ $emp->id == $user['id'] ? 'selected' : '' }}>
                                                                    {{ $emp->name }}
                                                                </option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <button type="button" class="text-red-600 hover:text-red-800"
                                                        onclick="deleteRow('{{ $user['channel_id'] }}')">Remove</button>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="3" class="px-6 py-4 whitespace-nowrap text-center">No users assigned to
                                        this
                                        project.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    <button type="button"
                        class="bg-[#f56d11] hover:scale-105 transition text-white px-3 py-2 text-sm rounded font-semibold shadow-md w-fit"
                        onclick="addNewUserRow()">Add User</button>
                </div>
            </div>

            <div class="space-y-2">
                <label for="client_id" class="block text-sm font-medium text-gray-700">Client</label>
                <select name="client_id" id="client_id"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                    required>
                    <option value="">Select a client</option>
                    @foreach ($employee as $client)
                        @if ($client->roles->position == 'client')
                            <option value="{{ $client->id }}" @if (old('client_id', $web_project->client->id) == $client->id)
                            selected @endif>
                                {{ $client->name }}
                            </option>
                        @endif
                    @endforeach
                </select>
                @error('role_id')
                    <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label for="date_started" class="block text-sm font-medium text-gray-700">Date Started</label>
                    <input type="date" name="date_started" id="date_started"
                        value="{{ old('date_started', $web_project->web_project_channels[0]->date_started ?? '') }}"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        required>
                </div>
                <div class="space-y-2">
                    <label for="date_target" class="block text-sm font-medium text-gray-700">Date Target</label>
                    <input type="date" name="date_target" id="date_target"
                        value="{{ old('date_target', $web_project->web_project_channels[0]->date_targeted ?? '') }}"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        required>
                </div>
            </div>

            <div class="space-y-2">
                <label for="editor" class="block text-sm font-medium text-gray-700">Instructions</label>
                <textarea name="instructions" id="editor"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">{{ old('instructions', $web_project->instructions) }}</textarea>
            </div>
        </div>
    </form>

    @php
        $types = ['web_designer', 'front_end', 'back_end'];
    @endphp

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let newUserCount = 0;
            const availableTypes = @json($types); // Use the $types variable from PHP

            window.addNewUserRow = function () {
                newUserCount++;
                const tableBody = document.getElementById("assignedUsersTable");
                const newRowId = `newUser_${newUserCount}`;
                const newRow = document.createElement("tr");
                newRow.id = newRowId;

                let optionsType = '<option value="">Select Type</option>';
                availableTypes.forEach(type => {
                    optionsType += `<option value="${type}">${type.replace('_', ' ')}</option>`;
                });

                let optionsUser = '<option value="">Select User</option>';
                @foreach ($employee as $user)
                    @if ($user->roles->position != 'client')
                        optionsUser += `<option value="{{ $user->id }}">{{ $user->name }}</option>`;
                    @endif
                @endforeach

                newRow.innerHTML = `
                    <td class="px-6 py-4 whitespace-nowrap">
                        <select name="newUsers[${newRowId}][type]" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            ${optionsType}
                        </select>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <select name="newUsers[${newRowId}][user]" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            ${optionsUser}
                        </select>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <button type="button" class="text-red-600 hover:text-red-800" onclick="removeNewUser('${newRowId}')">Remove</button>
                    </td>
                `;
                tableBody.appendChild(newRow);
            };

            window.removeNewUser = function (rowId) {
                const row = document.getElementById(rowId);
                if (row) {
                    row.remove();
                }
            };

            window.deleteRow = function (channelId) {
                const row = document.getElementById(`row_${channelId}`);
                row.parentNode.removeChild(row);
            };
        });

        ClassicEditor
            .create(document.querySelector('#editor'))
            .then(editor => {
                console.log('CKEditor initialized');
            })
            .catch(error => {
                console.error(error);
            });
    </script>
</x-main-layout>