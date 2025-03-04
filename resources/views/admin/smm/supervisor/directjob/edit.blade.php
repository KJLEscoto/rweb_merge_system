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
            <a href="{{ url('/supervisor/directjob') }}">
                <div class="w-fit px-4 py-1 bg-gray-400 rounded-md text-white custom-shadow custom-hover-shadow">
                    Back
                </div>
            </a>
        </div>
        <form action="{{ url('/supervisor/directjob/update/' . $job_draft->id) }}" method="POST">
            @csrf
            @method('PUT')
            <h1 class="text-xl font-bold mt-4">Edit Form</h1>
            <div class="grid grid-cols-4 space-y-4">
                <div class="col-span-4 grid grid-cols-2 gap-4 mt-4">
                    <div class="w-full col-span-2 lg:col-span-1">
                        <p class="text-sm text-gray-600">Title</p>
                        <input type="text" name="title" class="w-full border px-3 py-2  border-gray-200 rounded-lg" value="{{ old('title', $job_draft->jobOrder->title) }}">
                        @error('title')
                            <p class="text-red-600 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    @if ($job_draft->type === 'graphic_designer')
                    <!-- Graphics Designer -->
                    <div class="w-full col-span-2 lg:col-span-1">
                        <p class="text-sm text-gray-600">Graphics Designer</p>
                        <div class="relative">
                            <input type="text" id="selected-graphic-designer-name"
                                value="{{ old('graphic_designer_id') ? ($graphicworkers->firstWhere('id', old('graphic_designer_id'))->name ?? 'Select a Graphics Designer') : ($job_draft->graphicDesigner->name ?? 'Select a Graphic Designer') }}"
                                class="w-full border px-3 py-2  border-gray-200 rounded-lg cursor-pointer" readonly
                                onclick="openGraphicDesignerModal()">
                            <input type="hidden" name="graphic_designer_id" id="selected-graphic-designer-id"
                                value="{{ old('graphic_designer_id', $job_draft->graphicDesigner->id ?? '') }}">
                        </div>
                        @error('graphic_designer_id')
                            <p class="text-red-600 text-sm">{{ $message }}</p>
                        @enderror
                    </div>
                    @elseif ($job_draft->type === 'content_writer')
                    <div class="w-full col-span-2 lg:col-span-1">
                            <p class="text-sm text-gray-600">Content Writer</p>
                            <div class="relative">
                                <input type="text" id="selected-content-writer-name"
                                    value="{{ old('content_writer_id') ? ($contentworkers->firstWhere('id', old('content_writer_id'))->name ?? 'Select a Content Writer') : ($job_draft->contentWriter->name ?? 'Select a Content Writer') }}"
                                    class="w-full border px-3 py-2  border-gray-200 rounded-lg cursor-pointer" readonly
                                    onclick="openContentWriterModal()">
                                <input type="hidden" name="content_writer_id" id="selected-content-writer-id"
                                    value="{{ old('content_writer_id', $job_draft->contentWriter->id ?? '') }}">
                            </div>
                            @error('content_writer_id')
                                <p class="text-red-600 text-sm">{{ $message }}</p>
                            @enderror
                        </div>

                    @endif

                    <!-- Client -->
                    <div class="w-full col-span-2 lg:col-span-1">
                        <p class="text-sm text-gray-600">Client</p>
                        <div class="relative">
                            <input type="text" id="selected-client-name"
                            value="{{ old('client_id') ? ($clients->firstWhere('id', old('client_id'))->name ?? 'Select a Client') : ($job_draft->client->name ?? 'Select a Client') }}"
                            class="w-full border px-3 py-2  border-gray-200 rounded-lg cursor-pointer" readonly onclick="openModal()">
                            <input type="hidden" name="client_id" id="selected-client-id" value="{{ old('client_id', $job_draft->client->id ?? '') }}">
                        </div>
                        @error('client_id')
                            <p class="text-red-600 text-sm">{{ $message }}</p>
                        @enderror
                    </div>



                    <div class="col-span-2 lg:col-span-1 w-full gap-4 rounded-lg">
                        <p class="text-sm text-gray-600">Days to Add</p>
                        <input type="number" name="days_to_add" class="w-full rounded-lg border px-3 py-2  border-gray-200" value="{{$job_draft->days_to_add }}">
                        @error('days_to_add')
                            <p class="text-red-600 text-sm">{{ $message }}</p>
                        @enderror
                        
                    </div>

                    <div class="col-span-2 h-fit w-full">
                        <p class="text-sm text-gray-600">Instructions</p>

                        <!-- CKEditor Textarea -->
                        <textarea name="description" id="editor" class="w-full border-gray-200 rounded-lg">{{ old('description', $job_draft->jobOrder->description) }}</textarea>

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

<!-- Client Selection Modal -->
<div id="client-modal" class="fixed inset-0 bg-gray-900 px-4 md:px-20 z-50 bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white w-full max-w-sm md:max-w-lg lg:max-w-2xl px-5 pb-10 pt-5 rounded-lg">
        <!-- Search & Close button -->
        <div class="w-full flex md:flex-row justify-between items-center flex-col-reverse lg:flex-row gap-4 mb-4">
            <div class="flex items-center w-full md:w-auto relative">
                <i class="fa-solid fa-magnifying-glass absolute left-4 text-gray-500"></i>
                <input type="text" id="searchInput" class="w-full md:w-80 px-10 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500" placeholder="Search..." onkeyup="filterTable()">
                <button class="absolute right-2 px-4 py-2 bg-gray-200 rounded-md hover:bg-gray-300">
                    <i class="fa-solid fa-filter"></i>
                </button>
            </div>
            <div class="w-full flex justify-end md:w-auto">
                <button onclick="closeModal()" class="bg-[#fa7011] text-white px-4 py-2 rounded w-fit">Close</button>
            </div>
        </div>

        <!-- Table Container -->
        <div class="overflow-x-auto w-full bg-white shadow-md rounded-lg max-h-[500px]">
            <table class="w-full text-left border-collapse min-w-[300px] md:min-w-[500px]">
                <thead class="sticky top-0 bg-[#fa7011] text-white">
                    <tr>
                        <th class="px-4 md:px-6 py-3 w-24 md:w-32">Title</th>
                        <th class="px-4 md:px-6 py-3 w-24 md:w-32">Role</th>
                        <th class="px-4 md:px-6 py-3 w-24 md:w-32 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    @forelse ($clients as $client)
                        <tr class="border-b">
                            <td class="px-4 md:px-6 py-3">{{ $client->name }}</td>
                            <td class="px-4 md:px-6 py-3">{{ ucfirst($client->role->position) }}</td>
                            <td class="px-4 md:px-6 py-3 text-center">
                                <button onclick="selectClient('{{ $client->id }}', '{{ $client->name }}')" class="px-2 py-1 md:px-4 md:py-2 text-sm text-white bg-orange-500 rounded hover:bg-orange-600 w-full md:w-auto">
                                    Select Client
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


<!-- Content Writer Modal -->
<div id="content-writer-modal" class="fixed inset-0 bg-gray-900 px-4 md:px-20 z-50 bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white w-full max-w-sm md:max-w-lg lg:max-w-2xl px-5 pb-10 pt-5 rounded-lg">
        <!-- Search & Close button -->
        <div class="w-full flex md:flex-row justify-between items-center flex-col-reverse lg:flex-row gap-4 mb-4">
            <div class="flex items-center w-full md:w-auto relative">
                <i class="fa-solid fa-magnifying-glass absolute left-4 text-gray-500"></i>
                <input type="text" id="searchContentWriterInput" class="w-full md:w-80 px-10 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500" placeholder="Search..." onkeyup="filterContentWriterTable()">
                <button class="absolute right-2 px-4 py-2 bg-gray-200 rounded-md hover:bg-gray-300">
                    <i class="fa-solid fa-filter"></i>
                </button>
            </div>
            <div class="w-full flex justify-end md:w-auto">
                <button onclick="closeContentWriterModal()" class="bg-[#fa7011] text-white px-4 py-2 rounded w-fit">Close</button>
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
                <tbody id="contentWriterTableBody">
                    @forelse ($contentworkers as $content_writer)
                        <tr class="border-b">
                            <td class="px-4 md:px-6 py-3">{{ $content_writer->name }}</td>
                            <td class="px-4 md:px-6 py-3">{{ Str::title(str_replace('_', ' ', $content_writer->role->position)) }}</td>
                            <td class="px-4 md:px-6 py-3 text-center">
                                <button onclick="selectContentWriter('{{ $content_writer->id }}', '{{ $content_writer->name }}')" class="px-2 py-1 md:px-4 md:py-2 text-sm text-white bg-orange-500 rounded hover:bg-orange-600 w-full md:w-auto">
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


<!-- Graphics Designer Modal -->
<div id="graphic-designer-modal" class="fixed inset-0 bg-gray-900 px-4 md:px-20 z-50 bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white w-full max-w-sm md:max-w-lg lg:max-w-2xl px-5 pb-10 pt-5 rounded-lg">
        <!-- Search & Close button -->
        <div class="w-full flex md:flex-row justify-between items-center flex-col-reverse lg:flex-row gap-4 mb-4">
            <div class="flex items-center w-full md:w-auto relative">
                <i class="fa-solid fa-magnifying-glass absolute left-4 text-gray-500"></i>
                <input type="text" id="searchGraphicDesignerInput" class="w-full md:w-80 px-10 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500" placeholder="Search..." onkeyup="filterGraphicDesignerTable()">
                <button class="absolute right-2 px-4 py-2 bg-gray-200 rounded-md hover:bg-gray-300">
                    <i class="fa-solid fa-filter"></i>
                </button>
            </div>
            <div class="w-full flex justify-end md:w-auto">
                <button onclick="closeGraphicDesignerModal()" class="bg-[#fa7011] text-white px-4 py-2 rounded w-fit">Close</button>
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
                <tbody id="graphicDesignerTableBody">
                    @forelse ($graphicworkers as $graphic_designer)
                        <tr class="border-b">
                            <td class="px-4 md:px-6 py-3">{{ $graphic_designer->name }}</td>
                            <td class="px-4 md:px-6 py-3">{{ Str::title(str_replace('_', ' ', $graphic_designer->role->position)) }}</td>

                            <td class="px-4 md:px-6 py-3 text-center">
                                <button onclick="selectGraphicDesigner('{{ $graphic_designer->id }}', '{{ $graphic_designer->name }}')" class="px-2 py-1 md:px-4 md:py-2 text-sm text-white bg-orange-500 rounded hover:bg-orange-600 w-full md:w-auto">
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
    function filterTable() {
        let input = document.getElementById("searchInput").value.toLowerCase();
        let tableBody = document.getElementById("tableBody");
        let rows = tableBody.getElementsByTagName("tr");

        for (let row of rows) {
            let title = row.getElementsByTagName("td")[0]?.textContent.toLowerCase();
            let assignedBy = row.getElementsByTagName("td")[1]?.textContent.toLowerCase();

            if (title.includes(input) || assignedBy.includes(input)) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        }
    }

    // Open Content Writer Modal
    function openContentWriterModal() {
        document.getElementById('content-writer-modal').classList.remove('hidden');
    }

    // Close Content Writer Modal
    function closeContentWriterModal() {
        document.getElementById('content-writer-modal').classList.add('hidden');
    }

    // Select a Content Writer
    function selectContentWriter(contentWriterId, contentWriterName) {
        document.getElementById('selected-content-writer-name').value = contentWriterName;
        document.getElementById('selected-content-writer-id').value = contentWriterId;
        closeContentWriterModal();
    }

    // Filter Content Writer Table
    function filterContentWriterTable() {
        let input = document.getElementById("searchContentWriterInput").value.toLowerCase();
        let tableBody = document.getElementById("contentWriterTableBody");
        let rows = tableBody.getElementsByTagName("tr");

        for (let row of rows) {
            let name = row.getElementsByTagName("td")[0]?.textContent.toLowerCase();
            let role = row.getElementsByTagName("td")[1]?.textContent.toLowerCase();

            if (name.includes(input) || role.includes(input)) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        }
    }

    function openModal() {
        document.getElementById('client-modal').classList.remove('hidden');
    }
    function closeModal() {
        document.getElementById('client-modal').classList.add('hidden');
    }
    function selectClient(clientId, clientName) {
        document.getElementById('selected-client-name').value = clientName;
        document.getElementById('selected-client-id').value = clientId;
        closeModal();
    }

    // Open Graphics Designer Modal
    function openGraphicDesignerModal() {
        document.getElementById('graphic-designer-modal').classList.remove('hidden');
    }

    // Close Graphics Designer Modal
    function closeGraphicDesignerModal() {
        document.getElementById('graphic-designer-modal').classList.add('hidden');
    }

    // Select a Graphics Designer
    function selectGraphicDesigner(graphicDesignerId, graphicDesignerName) {
        document.getElementById('selected-graphic-designer-name').value = graphicDesignerName;
        document.getElementById('selected-graphic-designer-id').value = graphicDesignerId;
        closeGraphicDesignerModal();
    }

    // Filter Graphics Designer Table
    function filterGraphicDesignerTable() {
        let input = document.getElementById("searchGraphicDesignerInput").value.toLowerCase();
        let tableBody = document.getElementById("graphicDesignerTableBody");
        let rows = tableBody.getElementsByTagName("tr");

        for (let row of rows) {
            let name = row.getElementsByTagName("td")[0]?.textContent.toLowerCase();
            let role = row.getElementsByTagName("td")[1]?.textContent.toLowerCase();

            if (name.includes(input) || role.includes(input)) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        }
    }


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

@endsection
