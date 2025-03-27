<head>
    <title>{{ env('APP_NAME') }} | SMM | Create SOA</title>

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

        /* Ensure CKEditor is scrollable with max height */
        .ck-editor__editable {
            max-height: 500px !important;
            overflow-y: auto !important;
        }
    </style>

    <!-- CKEditor 5 Classic CDN -->
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
</head>

<x-main-layout breadcumb="SMM / Direct Job Order" page="Create SOA">

    {{-- Success Message Component --}}
    @if (!Auth::user()->signature)
        <form action="{{ url('signature/store') }}" method="POST" id="modalSignatureForm">
            @csrf
            @method('PUT')
            <x-save-signature />
        </form>
    @endif

    <div class="w-full px-6 py-10 mx-auto rounded-lg custom-shadow bg-white">
        <div class="w-fit">
            <a href="{{ route('admin.smm.soa') }}">
                <div class="w-fit px-4 py-1 bg-gray-400 rounded-md text-white custom-shadow custom-hover-shadow">
                    Back
                </div>
            </a>
        </div>
        <form action="{{ route('admin.smm.soa.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <h1 class="text-xl font-bold mt-4">Create SOA</h1>
            <div class="grid grid-cols-4 space-y-4">
                <div class="col-span-4 grid grid-cols-2 gap-4 mt-4">
                    <!-- Job Orders -->
                    <div class="col-span-2 lg:col-span-1 w-full">
                        <p class="text-sm text-gray-600">Job Order ID</p>
                        <div class="relative">
                            <!-- Visible input for showing Job Order name -->
                            <input type="text" id="selected-job_draft-name"
                                value="{{ old('job_draft_id') ? optional($job_drafts->firstWhere('id', old('job_draft_id')))->name ?? 'Select a Job Order' : 'Select a Job Order' }}"
                                class="w-full border px-3 py-2 border-gray-200 rounded-lg cursor-pointer" readonly
                                onclick="openModal()">

                            <!-- Hidden input for passing Job Order ID in the form -->
                            <input type="hidden" name="job_draft_id" id="selected-job_draft_id"
                                value="{{ old('job_draft_id') }}">


                        </div>
                        @error('job_draft_id')
                            <p class="text-red-600 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Image Upload -->
                    <div class="col-span-2 lg:col-span-1 w-full">
                        <p class="text-sm text-gray-600">Company Name</p>
                        <input type="text" name="company" class="w-full border px-3 py-2 border-gray-200 rounded-lg">
                        @error('company')
                            <p class="text-red-600 text-sm">{{ $message }}</p>
                        @enderror

                    </div>

                    <!-- Image Upload -->
                    <div class="col-span-2 lg:col-span-1 w-full">
                        <p class="text-sm text-gray-600">Upload Image</p>
                        <input type="file" name="image_path" id="imageUpload" accept="image_path/*"
                            class="w-full border px-3 py-2 border-gray-200 rounded-lg">
                        @error('image_path')
                            <p class="text-red-600 text-sm">{{ $message }}</p>
                        @enderror
                        <!-- Image Preview -->
                        <div class="mt-4">
                            <img id="imagePreview" src="#" alt="Uploaded Image"
                                class="hidden w-32 h-32 object-cover rounded-md border">
                        </div>
                    </div>
                </div>

                <button type="submit"
                    class="col-span-1 text-center py-2 lg:py-4 w-full bg-[#fa7011] mt-10 rounded-lg custom-shadow custom-hover-shadow text-white font-bold">
                    Submit
                </button>
            </div>
        </form>
    </div>

    <!-- Client Selection Modal -->
    <div id="client-modal"
        class="fixed inset-0 bg-gray-900 px-4 md:px-20 z-50 bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white w-full max-w-sm md:max-w-lg lg:max-w-2xl px-5 pb-10 pt-5 rounded-lg">
            <!-- Search & Close button -->
            <div class="w-full flex md:flex-row justify-between items-center flex-col-reverse lg:flex-row gap-4 mb-4">
                <div class="flex items-center w-full md:w-auto relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-4 text-gray-500"></i>
                    <input type="text" id="searchInput"
                        class="w-full md:w-80 px-10 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500"
                        placeholder="Search..." onkeyup="filterTable()">
                    <button class="absolute right-2 px-4 py-2 bg-gray-200 rounded-md hover:bg-gray-300">
                        <i class="fa-solid fa-filter"></i>
                    </button>
                </div>
                <div class="w-full flex justify-end md:w-auto">
                    <button onclick="closeModal()"
                        class="bg-[#fa7011] text-white px-4 py-2 rounded w-fit">Close</button>
                </div>
            </div>

            <!-- Table Container -->
            <div class="overflow-x-auto w-full bg-white shadow-md rounded-lg max-h-[500px]">
                <table class="w-full text-left border-collapse min-w-[300px] md:min-w-[500px]">
                    <thead class="sticky top-0 bg-[#fa7011] text-white">
                        <tr>
                            <th class="px-4 md:px-6 py-3 w-24 md:w-32">Title</th>
                            <th class="px-4 md:px-6 py-3 w-24 md:w-32">Job Order ID</th>
                            <th class="px-4 md:px-6 py-3 w-24 md:w-32 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        @forelse ($job_drafts as $job_draft)
                            <tr class="border-b">
                                <td class="px-4 md:px-6 py-3">{{ $job_draft->jobOrder->title }}</td>
                                <td class="px-4 md:px-6 py-3">{{ ucfirst($job_draft->id) }}</td>
                                <td class="px-4 md:px-6 py-3 text-center">
                                    <button onclick="selectClient('{{ $job_draft->id }}', '{{ $job_draft->title }}')"
                                        class="px-2 py-1 md:px-4 md:py-2 text-sm text-white bg-orange-500 rounded hover:bg-orange-600 w-full md:w-auto">
                                        Select Job Order
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

</x-main-layout>

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
        document.getElementById('selected-job_draft_id').value = clientId; // Hidden input (stores ID)
        document.getElementById('selected-job_draft-name').value = clientId; // Text input (displays name)
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

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const contentCheckbox = document.getElementById("content-checkbox");
        const contentInput = document.getElementById("selected-content-writer-name");

        const graphicCheckbox = document.getElementById("graphic-checkbox");
        const graphicInput = document.getElementById("selected-graphic-designer-name");

        function toggleInput(checkbox, input, modalFunctionName) {
            if (checkbox.checked) {
                input.disabled = false;
                input.classList.remove("bg-gray-200", "cursor-not-allowed");
                input.onclick = window[modalFunctionName]; // Enable modal function
            } else {
                input.disabled = true;
                input.classList.add("bg-gray-200", "cursor-not-allowed");
                input.onclick = null; // Prevent clicking
            }
        }

        contentCheckbox.addEventListener("change", function () {
            toggleInput(contentCheckbox, contentInput, "openContentWriterModal");
        });

        graphicCheckbox.addEventListener("change", function () {
            toggleInput(graphicCheckbox, graphicInput, "openGraphicDesignerModal");
        });

        // Initial check in case old values exist
        toggleInput(contentCheckbox, contentInput, "openContentWriterModal");
        toggleInput(graphicCheckbox, graphicInput, "openGraphicDesignerModal");
    });
</script>

<script>
    document.getElementById('imageUpload').addEventListener('change', function (event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                const preview = document.getElementById('imagePreview');
                preview.src = e.target.result;
                preview.classList.remove('hidden');
            }
            reader.readAsDataURL(file);
        }
    });
</script>

{{-- @endsection --}}