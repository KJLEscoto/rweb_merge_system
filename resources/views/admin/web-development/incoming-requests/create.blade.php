<head>
    <title>{{ env('APP_NAME') }} | Web Development | Create Job Order</title>

    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>

    <style>
        /* Ensure CKEditor is scrollable with max height */
        .ck-editor__editable {
            max-height: 500px !important;
            overflow-y: auto !important;
        }
    </style>
</head>

<x-main-layout breadcumb="Web Development / Job Order" page="Create Direct Job Order">
    <form action="{{ route('admin.web.incoming-requests.store', ['id' => $web_request->id]) }}" method="POST"
        class="bg-white p-6 rounded border-l-8 border-[#F57D11] shadow-md flex flex-col gap-5">
        @csrf
        @method('POST')

        <div class="flex items-center gap-3">
            <a href="{{ route('admin.web.incoming-requests') }}"
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
                <input type="text" name="title" id="title" value="{{ old('title') }}"
                    class="border px-2 py-1 rounded-sm w-full outline-none focus:ring-2 focus:ring-[#f56d11]">
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="space-y-3">
                    <h1 class="font-semibold text-lg text-gray-800">Web Designer</h1>
                    <input type="text" id="webDesignerSearch" placeholder="Search Web Designers..." class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
                    <div class="space-y-2 w-full overflow-y-auto max-h-[300px] border rounded-md p-3" id="webDesignerList">
                        @foreach ($employee as $developer)
                            @if ($developer->roles->position != 'client')
                                <label class="flex items-center space-x-2 web-designer-item">
                                    <input type="checkbox" name="developers[web_designer][]" value="{{ $developer->id }}"
                                        {{ in_array($developer->id, old('developers.web_designer', [])) ? 'checked' : '' }}
                                        class="form-checkbox text-indigo-600 rounded">
                                    <span class="text-sm text-gray-700">{{ $developer->name }}</span>
                                </label>
                            @endif
                        @endforeach
                    </div>
                </div>

                <div class="space-y-3">
                    <h1 class="font-semibold text-lg text-gray-800">Front-End Developer</h1>
                    <input type="text" id="frontEndSearch" placeholder="Search Front-End Developers..." class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
                    <div class="space-y-2 w-full overflow-y-auto max-h-[300px] border rounded-md p-3" id="frontEndList">
                        @foreach ($employee as $developer)
                            @if ($developer->roles->position != 'client')
                                <label class="flex items-center space-x-2 front-end-item">
                                    <input type="checkbox" name="developers[front_end][]" value="{{ $developer->id }}"
                                        {{ in_array($developer->id, old('developers.front_end', [])) ? 'checked' : '' }}
                                        class="form-checkbox text-indigo-600 rounded">
                                    <span class="text-sm text-gray-700">{{ $developer->name }}</span>
                                </label>
                            @endif
                        @endforeach
                    </div>
                </div>

                <div class="space-y-3">
                    <h1 class="font-semibold text-lg text-gray-800">Back-End Developer</h1>
                    <input type="text" id="backEndSearch" placeholder="Search Back-End Developers..." class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
                    <div class="space-y-2 w-full overflow-y-auto max-h-[300px] border rounded-md p-3" id="backEndList">
                        @foreach ($employee as $developer)
                            @if ($developer->roles->position != 'client')
                                <label class="flex items-center space-x-2 back-end-item">
                                    <input type="checkbox" name="developers[back_end][]" value="{{ $developer->id }}"
                                        {{ in_array($developer->id, old('developers.back_end', [])) ? 'checked' : '' }}
                                        class="form-checkbox text-indigo-600 rounded">
                                    <span class="text-sm text-gray-700">{{ $developer->name }}</span>
                                </label>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- <div class="w-full col-span-2 lg:col-span-1">
                <p class="text-sm text-gray-600">Client</p>
                <select name="client_id" class="w-full border text-sm border-gray-200 rounded-lg !px-2 !py-1" required>
                    <option value="">Select a client</option>

                    @foreach ($employee as $client)
                        <option value="{{ $client->id }}">{{ $client->name }}</option>
                    @endforeach
                </select>


                @error('role_id')
                    <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div> --}}

            <div class="col-span-2 lg:col-span-1 grid grid-cols-1 gap-2 w-full">
                <div>
                    <p class="text-sm text-gray-600" for="selected-client-name">Client</p>
                    <div class="flex relative w-full">
                        <input type="text" id="selected-client-name"
                            value="{{ old('client_id') ? $clients->firstWhere('id', old('client_id'))->name ?? 'Select a Client' : 'Select a Client' }}"
                            class="flex-grow border px-3 py-2 border-gray-200 rounded-lg cursor-pointer" readonly
                            onclick="openClientModal()" aria-label="Select a Client">
                        <input type="hidden" name="client_id" id="selected-client-id" value="{{ old('client') }}">
                    </div>
                    @error('client_id')
                        <p class="text-red-600 text-sm">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <section class="flex lg:flex-row flex-col gap-5 w-full">
                <div class="space-y-1 w-full">
                    <h1 class="font-bold text-xs">Date Started</h1>
                    <input type="date" name="date_started" id="date_started" value="{{ old('date_started') }}"
                        class="border px-2 py-1 rounded-sm w-full outline-none focus:ring-2 focus:ring-[#f56d11]">
                </div>
                <div class="space-y-1 w-full">
                    <h1 class="font-bold text-xs">Date Target</h1>
                    <input type="date" name="date_target" id="date_target" value="{{ old('date_target') }}"
                        class="border px-2 py-1 rounded-sm w-full outline-none focus:ring-2 focus:ring-[#f56d11]">
                </div>
            </section>
            <div class="space-y-1 w-full">
                <h1 class="font-bold text-xs">Instructions</h1>
                <textarea name="instructions" id="editor" class="w-full border-gray-200 rounded-lg" value="{{ old('instructions') }}">{{ old('instructions') }}</textarea>
            </div>
        </div>

    </form>

    <script>
        // Initialize CKEditor
        ClassicEditor
            .create(document.querySelector('#editor'))
            .then(editor => {
                console.log('CKEditor initialized');
            })
            .catch(error => {
                console.error(error);
            });
    </script>
    <script>
        function filterClientTable() {
            const input = document.getElementById("searchClientInput");
            const filter = input.value.toUpperCase();
            const table = document.getElementById("clientTableBody");
            const tr = table.getElementsByTagName("tr");

            for (let i = 0; i < tr.length; i++) {
                const tdName = tr[i].getElementsByTagName("td")[0];
                const tdRole = tr[i].getElementsByTagName("td")[1];
                if (tdName || tdRole) {
                    const txtValueName = tdName.textContent || tdName.innerText;
                    const txtValueRole = tdRole.textContent || tdRole.innerText;
                    if (txtValueName.toUpperCase().indexOf(filter) > -1 || txtValueRole.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }

        function closeClientModal() {
            document.getElementById('client-modal').classList.add('hidden');
        }

        function selectClient(id, name) {
            document.getElementById('selected-client-id').value = id; // Corrected ID
            document.getElementById('selected-client-name').value = name; // Corrected ID
            closeClientModal();
        }

        function openClientModal() { // Corrected Function name
            document.getElementById('client-modal').classList.remove('hidden');
        }
    </script>
    <script>
        function setupSearch(searchInputId, itemListClass, listContainerId) {
            const searchInput = document.getElementById(searchInputId);
            const items = document.querySelectorAll(`.${itemListClass}`);
            const listContainer = document.getElementById(listContainerId);

            searchInput.addEventListener('input', function () {
                const searchTerm = searchInput.value.toLowerCase();

                items.forEach(item => {
                    const name = item.querySelector('span').textContent.toLowerCase();
                    if (name.includes(searchTerm)) {
                        item.style.display = 'flex';
                    } else {
                        item.style.display = 'none';
                    }
                });

                // Check if all items are hidden, and display a message.
                let allHidden = true;
                items.forEach(item => {
                    if (item.style.display !== 'none') {
                        allHidden = false;
                    }
                });

                if (allHidden) {
                    if (!listContainer.querySelector('.no-results')) {
                        const noResultsMessage = document.createElement('p');
                        noResultsMessage.textContent = 'No results found.';
                        noResultsMessage.classList.add('no-results', 'text-sm', 'text-gray-500');
                        listContainer.appendChild(noResultsMessage);
                    }
                } else {
                    const noResultsMessage = listContainer.querySelector('.no-results');
                    if (noResultsMessage) {
                        noResultsMessage.remove();
                    }
                }
            });
        }

        setupSearch('webDesignerSearch', 'web-designer-item', 'webDesignerList');
        setupSearch('frontEndSearch', 'front-end-item', 'frontEndList');
        setupSearch('backEndSearch', 'back-end-item', 'backEndList');
    </script>
</x-main-layout>
{{-- Client Modal --}}
<div id="client-modal"
    class="fixed inset-0 bg-gray-900 px-4 md:px-20 z-50 bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white w-full max-w-sm md:max-w-lg lg:max-w-2xl px-5 pb-10 pt-5 rounded-lg">
        <div class="w-full flex md:flex-row justify-between items-center flex-col-reverse lg:flex-row gap-4 mb-4">
            <input type="text" id="searchClientInput"
                class="w-full md:w-80 px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500"
                placeholder="Search..." onkeyup="filterClientTable()">
            <div class="w-full flex justify-end md:w-auto">
                <button onclick="closeClientModal()"
                    class="bg-[#fa7011] text-white px-4 py-2 rounded w-fit">Close</button>
            </div>
        </div>

        <div class="overflow-x-auto w-full bg-white shadow-md rounded-lg max-h-[500px]">
            <table class="w-full text-left border-collapse min-w-[300px] md:min-w-[500px]">
                <thead class="sticky top-0 bg-[#fa7011] text-white">
                    <tr>
                        <th class="px-4 md:px-6 py-3 w-24 md:w-32">Name</th>
                        <th class="px-4 md:px-6 py-3 w-24 md:w-32">Role</th>
                        <th class="px-4 md:px-6 py-3 w-24 md:w-32 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody id="clientTableBody">
                    @forelse ($clients as $client)
                        <tr class="border-b">
                            <td class="px-4 md:px-6 py-3">{{ $client->name }}</td>
                            <td class="px-4 md:px-6 py-3">{{ ucfirst($client->roles->position) }}</td>
                            <td class="px-4 md:px-6 py-3 text-center">
                                <button onclick="selectClient('{{ $client->id }}', '{{ $client->name }}')"
                                    class="px-2 py-1 md:px-4 md:py-2 text-sm text-white bg-orange-500 rounded hover:bg-orange-600 w-full md:w-auto">
                                    Select
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr class="h-[400px]">
                            <td colspan="3" class="px-6 py-3">
                                <div class="flex h-full items-center justify-center">
                                    <i class="far fa-grin-beam-sweat"></i>
                                    No Data Found
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>