<head>
    <title>{{ env('APP_NAME') }} | Web Development | Create New Job Order</title>

    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>

    <style>
        /* Ensure CKEditor is scrollable with max height */
        .ck-editor__editable {
            max-height: 500px !important;
            overflow-y: auto !important;
        }
    </style>
</head>

<x-main-layout breadcumb="Web Development / Operation Job Order" page="Create New Job Order">
    <form action="{{ route('admin.web.operation-job-order.edit.post', $webRequest->id) }}" method="POST"
        class="bg-white p-6 rounded border-l-8 border-[#F57D11] shadow-md flex flex-col gap-5">
        @csrf
        @method('PUT')

        <div class="flex items-center gap-3">
            <a href="{{ route('admin.web.operation-job-order') }}"
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
                <input type="text" name="title" id="title"
                    value="{{ old('title') ? old('title') : $webRequest->title }}"
                    class="border px-2 py-1 rounded-sm w-full outline-none focus:ring-2 focus:ring-[#f56d11]">
            </div>
            <!-- Operator Selection Modal Trigger -->
            <div class="col-span-2 lg:col-span-1 grid grid-cols-2 gap-4 w-full">
                <div>
                    <p class="text-sm text-gray-600">Operator</p>
                    <div class="relative">
                        <input type="text" id="selected-operator-name"
                            value="{{ old('assigned_to') ? $operators->firstWhere('id', old('assigned_to'))->name ?? 'Select an Operator' : $webRequest->issued_to->name }}"
                            class="w-full border px-3 py-2  border-gray-200 rounded-lg cursor-pointer" readonly
                            onclick="openOperatorModal()">
                        <input type="hidden" name="assigned_to" id="selected-operator-id"
                            value="{{ old('assigned_to') ? old('assigned_to') : $webRequest->issued_to->id }}">
                    </div>
                    @error('assigned_to')
                        <p class="text-red-600 text-sm">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <p class="text-sm text-gray-600">Deadline</p>
                    <input type="date" name="deadline"
                        value="{{ old('deadline') ? old('deadline') : $webRequest->deadline }}"
                        class="w-full rounded-lg border px-3 py-2  border-gray-200 focus:ring-0">
                    @error('deadline')
                        <p class="text-red-600 text-sm">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            {{-- <div class="space-y-1 w-full">
                <h1 class="font-bold text-xs">Deadline</h1>
                <input type="date" name="deadline" id="deadline" value="{{ old('deadline') }}"
                    class="border px-2 py-1 rounded-sm w-full outline-none focus:ring-2 focus:ring-[#f56d11]">
            </div> --}}
            <div class="space-y-1 w-full">
                <h1 class="font-bold text-xs">Instructions</h1>
                <textarea name="instructions" id="editor"
                    class="w-full border-gray-200 rounded-lg">{{ old('instructions') ? old('instructions') : $webRequest->instructions }}</textarea>
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
        function openOperatorModal() {
            document.getElementById('operator-modal').classList.remove('hidden');
        }

        function closeOperatorModal() {
            document.getElementById('operator-modal').classList.add('hidden');
        }

        function selectOperator(operatorId, operatorName) {
            document.getElementById('selected-operator-name').value = operatorName;
            document.getElementById('selected-operator-id').value = operatorId;
            closeOperatorModal();
        }

        function filterOperatorTable() {
            let input = document.getElementById("searchOperatorInput").value.toLowerCase();
            let tableBody = document.getElementById("operatorTableBody");
            let rows = tableBody.getElementsByTagName("tr");

            for (let row of rows) {
                let name = row.getElementsByTagName("td")[0]?.textContent.toLowerCase();
                if (name.includes(input)) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            }
        }
    </script>
</x-main-layout>

<!-- Operator Selection Modal -->
<div id="operator-modal"
    class="fixed inset-0 bg-gray-900 px-4 md:px-20 z-50 bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white w-full max-w-sm md:max-w-lg lg:max-w-2xl px-5 pb-10 pt-5 rounded-lg">
        <!-- Search & Close button -->
        <div class="w-full flex md:flex-row justify-between items-center flex-col-reverse lg:flex-row gap-4 mb-4">
            <input type="text" id="searchOperatorInput"
                class="w-full md:w-80 px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500"
                placeholder="Search..." onkeyup="filterOperatorTable()">
            <div class="w-full flex justify-end md:w-auto">
                <button onclick="closeOperatorModal()"
                    class="bg-[#fa7011] text-white px-4 py-2 rounded w-fit">Close</button>
            </div>
        </div>


        <!-- Table Container -->
        <div class="overflow-x-auto w-full bg-white shadow-md rounded-lg max-h-[500px]">
            <table class="w-full text-left border-collapse min-w-[300px] md:min-w-[500px]">
                <thead class="sticky top-0 bg-[#fa7011] text-white">
                    <tr>
                        <th class="px-4 md:px-6 py-3 w-24 md:w-32">Name</th>
                        <th class="px-4 md:px-6 py-3 w-24 md:w-32">Role</th>
                        <th class="px-4 md:px-6 py-3 w-24 md:w-32 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody id="operatorTableBody">
                    @forelse ($operators as $operator)
                        <tr class="border-b">
                            <td class="px-4 md:px-6 py-3">{{ $operator->name }}</td>
                            <td class="px-4 md:px-6 py-3">{{ ucfirst($operator->roles->position) }}</td>
                            <td class="px-4 md:px-6 py-3 text-center">
                                <button onclick="selectOperator('{{ $operator->id }}', '{{ $operator->name }}')"
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