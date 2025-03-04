@extends('layouts.application')

@section('title', 'Admin')
@section('header', "Edit Job Order")

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
</style>

<!-- CKEditor 5 Classic -->
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>

<div class="container mx-auto p-6">
    <div class="w-full px-6 py-10 mx-auto rounded-lg custom-shadow bg-white">
        <div>
            <a href="{{ url('/supervisor/joborder') }}">
                <div class="w-fit px-4 py-1 bg-gray-400 rounded-md text-white custom-shadow custom-hover-shadow">
                    Back
                </div>
            </a>
        </div>
        <form action="{{ url('/supervisor/joborder/update/' . $supervisor_request->id) }}" method="POST">
            @csrf
            @method('PUT')
            <h1 class="text-xl font-bold mt-4">Edit Form</h1>
            <div class="grid grid-cols-4 space-y-4">
                <div class="col-span-4 grid grid-cols-2 gap-4 mt-4">
                    <div class="col-span-2 lg:col-span-1 w-full">
                        <p class="text-sm text-gray-600">Title</p>
                        <input type="text" name="title" class="w-full border px-3 py-2  border-gray-200 rounded-lg" value="{{ old('title', $supervisor_request->title) }}">
                        @error('title')
                            <p class="text-red-600 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Operator Selection Modal Trigger -->
                    <div class="col-span-2 lg:col-span-1 grid grid-cols-2 gap-4 w-full">
                        <div>
                            <p class="text-sm text-gray-600">Operator</p>
                            <div class="relative">
                                <input type="text" id="selected-operator-name"
                                    value="{{ old('assigned_to') ? ($operators->firstWhere('id', old('assigned_to'))->name ?? 'Select an Operator') : ($supervisor_request->assignee->name ?? 'Select an Operator') }}"
                                    class="w-full border px-3 py-2  border-gray-200 rounded-lg cursor-pointer" readonly
                                    onclick="openOperatorModal()">
    
                                <input type="hidden" name="assigned_to" id="selected-operator-id"
                                    value="{{ old('assigned_to', $supervisor_request->assignee->id) }}">
                            </div>
                            @error('assigned_to')
                                <p class="text-red-600 text-sm">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Deadline</p>
                            <input type="date" name="deadline" value={{$supervisor_request->deadline}} class="w-full rounded-lg border px-3 py-2  border-gray-200 focus:ring-0">
                            @error('deadline')
                                <p class="text-red-600 text-sm">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>



                    <div class="col-span-2 h-fit w-full">
                        <p class="text-sm text-gray-600">Instructions</p>

                        <!-- CKEditor Textarea -->
                        <textarea name="description" id="editor" class="w-full border-gray-200 rounded-lg">{{ old('description', $supervisor_request->description) }}</textarea>

                        @error('description')
                            <p class="text-red-600 text-sm">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <button type="submit" class="col-span-1 text-center py-4 w-full bg-[#fa7011] mt-10 rounded-lg text-white font-bold">
                    Submit
                </button>
            </div>
        </form>
    </div>

    <!-- Modal -->
    <!-- Operator Selection Modal -->
<div id="operator-modal" class="fixed inset-0 bg-gray-900 px-20 z-50 bg-opacity-50 flex flex-col items-center justify-center hidden">
    <div class="bg-white w-full px-5 pb-10 pt-5 rounded-lg">
        <div class="w-full h-fit flex flex-col md:flex-row justify-between items-center gap-4 mb-4">
            <input type="text" id="searchOperatorInput" class="w-full md:w-80 px-10 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500" placeholder="Search..." onkeyup="filterOperatorTable()">
            <button onclick="closeOperatorModal()" class=" bg-[#fa7011] text-white px-4 py-2 rounded">Close</button>
        </div>
        <div class="overflow-x-auto overflow-y-auto w-full bg-white shadow-md rounded-lg h-[500px]" style="max-height: 500px;">
            <table class="w-full text-left border-collapse min-w-[500px]">
                <thead class="sticky top-0 bg-[#fa7011] text-white">
                    <tr>
                        <th class="px-6 py-3 w-32">Name</th>
                        <th class="px-6 py-3 w-32">Role</th>
                        <th class="px-6 py-3 w-32 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody id="operatorTableBody">
                    @forelse ($operators as $operator)
                        <tr class="border-b">
                            <td class="px-6 py-3">{{ $operator->name }}</td>
                            <td class="px-6 py-3">{{ ucfirst($operator->role->position) }}</td>
                            <td class="px-6 py-3 text-center">
                                <button onclick="selectOperator('{{ $operator->id }}', '{{ $operator->name }}')" class="px-2 py-1 mb-2 lg:mb-0 lg:px-4 lg:py-2 text-sm text-white bg-orange-500 rounded hover:bg-orange-600">
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
</div>

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

    ClassicEditor.create(document.querySelector('#editor'))
        .then(editor => console.log('CKEditor initialized'))
        .catch(error => console.error(error));
</script>

@endsection
