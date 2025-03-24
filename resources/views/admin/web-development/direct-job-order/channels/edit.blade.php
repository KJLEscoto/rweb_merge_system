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
        method="POST" class="bg-white p-6 rounded border-l-8 border-[#F57D11] shadow-md flex flex-col gap-5">
        @csrf
        @method('PUT')

        <div class="flex items-center gap-3">
            <a href="{{ route('admin.web.direct-job-order.showProjectChannels', $web_project->id) }}"
                class="border hover:border-[#f56d11] text-[#f56d11] transition flex items-center gap-1 px-3 py-2 text-sm rounded font-semibold w-fit">
                <span class="eva--arrow-back-fill w-4 h-4"></span>
                Back
            </a>
            <button type="submit"
                class="bg-[#f56d11] hover:scale-105 transition text-white px-3 py-2 text-sm rounded font-semibold shadow-md w-fit">
                Submit
            </button>
        </div>

        <div class="space-y-5 lg:p-10 p-7 border rounded">
            <div class="space-y-1 w-full">
                <h1 class="font-bold text-xs">Title</h1>
                <input type="text" name="title" id="title" value="{{ old('title', $web_project->title) }}"
                    class="border px-2 py-1 rounded-sm w-full outline-none focus:ring-2 focus:ring-[#f56d11]">
            </div>
            <div class="space-y-1 w-full">
                <h1 class="font-bold text-xs">Assigned Users</h1>
                <table class="w-full">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Current User</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="assignedUsersTable">
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
                                    <tr id="row_{{ $user['channel_id'] }}">
                                        <td>{{ str_replace('_', ' ', $type) }}</td>
                                        <td>
                                            <select name="users[{{ $user['channel_id'] }}][{{ $type }}]"
                                                class="border p-1 rounded w-full">
                                                @foreach ($employee as $emp)
                                                    <option value="{{ $emp->id }}" {{ $emp->id == $user['id'] ? 'selected' : '' }}>
                                                        {{ $emp->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <button type="button" class="text-red-500"
                                                onclick="deleteRow('{{ $user['channel_id'] }}')">Remove</button>
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        @else
                            <tr>
                                <td colspan="3">No users assigned to this project.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>

                <div class="mt-4">
                    <button type="button" class="bg-green-500 text-white px-4 py-2 rounded"
                        onclick="addNewUserRow()">Add User</button>
                </div>
            </div>

            <div class="w-full col-span-2 lg:col-span-1">
                <p class="text-sm text-gray-600">Client</p>
                <select name="client_id" class="w-full border text-sm border-gray-200 rounded-lg !px-2 !py-1" required>
                    <option value="">Select a client</option>
                    @foreach ($employee as $client)
                        <option value="{{ $client->id }}" @if (old('client_id', $web_project->client->id) == $client->id)
                        selected @endif>
                            {{ $client->name }}
                        </option>
                    @endforeach
                </select>
                @error('role_id')
                    <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <section class="flex lg:flex-row flex-col gap-5 w-full">
                <div class="space-y-1 w-full">
                    <h1 class="font-bold text-xs">Date Started</h1>
                    <input type="date" name="date_started" id="date_started"
                        value="{{ old('date_started', $web_project->web_project_channels[0]->date_started ?? '') }}"
                        class="border px-2 py-1 rounded-sm w-full outline-none focus:ring-2 focus:ring-[#f56d11]"
                        required>
                </div>
                <div class="space-y-1 w-full">
                    <h1 class="font-bold text-xs">Date Target</h1>
                    <input type="date" name="date_target" id="date_target"
                        value="{{ old('date_target', $web_project->web_project_channels[0]->date_targeted ?? '') }}"
                        class="border px-2 py-1 rounded-sm w-full outline-none focus:ring-2 focus:ring-[#f56d11]"
                        required>
                </div>
            </section>
            <div class="space-y-1 w-full">
                <h1 class="font-bold text-xs">Instructions</h1>
                <textarea name="instructions" id="editor"
                    class="w-full border-gray-200 rounded-lg">{{ old('instructions', $web_project->instructions) }}</textarea>
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
                    optionsUser += `<option value="{{ $user->id }}">{{ $user->name }}</option>`;
                @endforeach

                newRow.innerHTML = `
                    <td>
                        <select name="newUsers[${newRowId}][type]" class="border p-1 rounded w-full">
                            ${optionsType}
                        </select>
                    </td>
                    <td>
                        <select name="newUsers[${newRowId}][user]" class="border p-1 rounded w-full">
                            ${optionsUser}
                        </select>
                    </td>
                    <td>
                        <button type="button" class="text-red-500" onclick="removeNewUser('${newRowId}')">Remove</button>
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