@extends('layouts.application')

@section('title', 'Job Order')
@section('header', 'Request Form')

@section('content')
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>



<style>
    .custom-shadow {
        box-shadow: 0 4px 6px rgba(0, 0, 0, .3), 0 1px 3px rgba(0, 0, 0, .3);
    }
    .custom-hover-shadow:hover {
        box-shadow: 0 10px 15px rgba(0, 0, 0, 0), 0 4px 6px rgba(0, 0, 0, 0);
        transition: box-shadow 0.3s ease;
    }
    .custom-focus-ring:focus {
        outline: none;
        box-shadow: 0 0 0 1px #fa7011;
        transition: box-shadow 0.3s ease;
    }
    .active-tab {
        border-bottom: 2px solid #fa7011;
    }
    body {
            font-family: Arial, sans-serif;

            margin: 0;
        }
        .header, .footer {
            text-align: center;
        }
        .header img, .footer img {
            width: 100%;
            max-height: 150px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
        td, th {
            border: 1px solid black;
            padding: 10px;
            text-align: left;
            vertical-align: top;
        }
        .highlight {
            background-color: #fa7011;
            height: 40px;
            text-align: center;
        }
        .gray-bar {
            background-color: #6b7280;
            height: 40px;
            text-align: center;
        }
        .long-bar{
            border: 1px solid black;
            padding: 10px;
            text-align: left;
            vertical-align: top;
        }
        .section{
            border: 1px solid black;
        }
        .section-title {
            font-weight: bold;
            background-color: #6b7280;
            color: white;
            text-align: center;
        }
        .signature img {
            width: 100px;
            height: auto;
        }
        .section-remarks {
            padding: 10px;
        }

        #printable-area {
            page-break-after: always; /* Ensures footer appears at the bottom */
        }
        #signature-2 {
            display: flex;
            justify-content: space-around;
            align-items: center;
        }
        #graphicDesignerTableBody tr td, #graphicDesignerTableHead tr th, #contentWriterTableBody tr td, #contentWriterTableHead tr th {
            border: none;
        }
        #btn-container{
            display: flex;
            gap: 2rem;
            padding: 1rem;
            justify-content: end;
            align-items: center;
        }
        #history-btn {
            padding-left: 1.75rem;
            padding-right: 1.75rem;
            padding-top: .75rem;
            padding-bottom: .75rem;
            border: 1px solid none;
            background-color: #4CAF50;
            border-radius: 5px;
        }
        #print-btn {
            padding-left: 1.75rem;
            padding-right: 1.75rem;
            padding-top: .75rem;
            padding-bottom: .75rem;
            border: 1px solid none;
            background-color: #c1c1c1;
            border-radius: 5px;
        }
        #signatures img {
            width: 10rem;
        }
        
</style>

<div class="container mx-auto p-4 sm:p-6">
    <body>
        <div id="btn-container">
            <div>
                <button id="history-btn">
                    <a href="{{url('/requestForm/history')}}"><i class="fa-solid fa-clock-rotate-left"></i> History</a>
                </button>
            </div>
            <div>
                <button id="print-btn" onclick="downloadRequestForm()">
                    <i class="fa-solid fa-download"></i> Download
                </button>
            </div>
            
            
        </div>
        <div id="printable-area">
            <div class="header">
                <img src="{{ asset('/Assets/doc_header.png') }}" alt="Header">
                <h2>Request Form</h2>
            </div>
        
            <div class="section">
                <div class="highlight"></div>
                <table>
                    <tr>
                        <td><strong>Department:</strong><br>
                            <p>{{$request_form?->requestedBy->role->position}}</p>
                        </td>
                        <td><strong>Date:</strong><br>
                            <p>{{$request_form?->date}}</p>
                        </td>
                    </tr>
                </table>
                <div class="long-bar">
                    <p><strong>Requested By: </strong></p>
                    <p>{{$request_form?->requestedBy->name}}</p>
                </div>
                <div class="gray-bar"></div>
                <table>
                    <tr>
                        <td><strong>Particulars:</strong><br>
                            @php
                                $selectedParticulars = collect($request_form?->particulars)->pluck('particular')->toArray();
                            @endphp
                        
                            <label><input type="checkbox" name="particulars[]" value="Domain" {{ in_array('Domain', $selectedParticulars) ? 'checked' : '' }}> Domain</label><br>
                            <label><input type="checkbox" name="particulars[]" value="Hosting and Servers" {{ in_array('Hosting and Servers', $selectedParticulars) ? 'checked' : '' }}> Hosting and Servers</label><br>
                            <label><input type="checkbox" name="particulars[]" value="Office Supplies" {{ in_array('Office Supplies', $selectedParticulars) ? 'checked' : '' }}> Office Supplies</label><br>
                            <label><input type="checkbox" name="particulars[]" value="Fare" {{ in_array('Fare', $selectedParticulars) ? 'checked' : '' }}> Fare</label><br>
                            <label><input type="checkbox" name="particulars[]" value="Petty Cash" {{ in_array('Petty Cash', $selectedParticulars) ? 'checked' : '' }}> Petty Cash</label><br>
                            <label><input type="checkbox" name="particulars[]" value="Credit Card" {{ in_array('Credit Card', $selectedParticulars) ? 'checked' : '' }}> Credit Card</label><br>
                            <label><input type="checkbox" name="particulars[]" value="Office Asset / Cash Advance" {{ in_array('Office Asset / Cash Advance', $selectedParticulars) ? 'checked' : '' }}> Office Asset / Cash Advance</label><br>
                            <label><input type="checkbox" name="particulars[]" value="Car" {{ in_array('Car', $selectedParticulars) ? 'checked' : '' }}> Car</label><br>
                            <label><input type="checkbox" name="particulars[]" value="Flyers" {{ in_array('Flyers', $selectedParticulars) ? 'checked' : '' }}> Flyers</label><br>
                        </td>
                        <td>
                            <label><input type="checkbox" name="particulars[]" value="for Multimedia Use" {{ in_array('for Multimedia Use', $selectedParticulars) ? 'checked' : '' }}> for Multimedia Use</label><br>
                            <label><input type="checkbox" name="particulars[]" value="Cash Advance" {{ in_array('Cash Advance', $selectedParticulars) ? 'checked' : '' }}> Cash Advance</label><br>
                            <label><input type="checkbox" name="particulars[]" value="Signage" {{ in_array('Signage', $selectedParticulars) ? 'checked' : '' }}> Signage</label><br>
                            <label><input type="checkbox" name="particulars[]" value="Reimbursement" {{ in_array('Reimbursement', $selectedParticulars) ? 'checked' : '' }}> Reimbursement</label><br>
                            <label><input type="checkbox" name="particulars[]" value="For Marketing Use" {{ in_array('For Marketing Use', $selectedParticulars) ? 'checked' : '' }}> For Marketing Use</label><br>
                            <label><input type="checkbox" name="particulars[]" value="Ads - AUB" {{ in_array('Ads - AUB', $selectedParticulars) ? 'checked' : '' }}> Ads - AUB</label><br>
                            <label><input type="checkbox" name="particulars[]" value="Repair and Maintenance" {{ in_array('Repair and Maintenance', $selectedParticulars) ? 'checked' : '' }}> Repair and Maintenance</label><br>
                            <label><input type="checkbox" name="particulars[]" value="Refund" {{ in_array('Refund', $selectedParticulars) ? 'checked' : '' }}> Refund</label><br>
                            <label>
                                <input type="checkbox" name="particulars[]" value="Others" {{ in_array('Others', $selectedParticulars) ? 'checked' : '' }}>
                                Others: <input type="text" name="other_particulars" class="border p-1" value="{{ in_array('Others', $selectedParticulars) ? $request_form?->other_particulars : '' }}">
                            </label>
                        </td>
                        
                    </tr>
                </table>
                
                <div class="long-bar" id="open-modal">
                    <strong>Description:</strong>
                    <div class="text-sm text-gray-600 w-full max-h-[500px] overflow-y-auto bg-white border border-gray-300 p-2 rounded">
                        {!! $request_form?->description !!}
                    </div>
                </div>
    
                <div id="signatures" class="p-5">
                    <div id="signature-2">
                        
                        <div>
                            <strong>Requested By:</strong>
                            <p>{{ $request_form?->requestedBy->name ?? '' }}</p> <br>
                            @if(!empty($request_form?->requestedBy->signature))
                                <img src="{{ asset($request_form?->requestedBy->signature) }}" alt="Supervisor Signature">
                            @endif
                        </div>
                        <div>
                            <strong>Received By:</strong>
                            <p>{{ $request_form?->receiver->name ?? '' }}</p> <br>
                            @if ($request_form?->status == "Approved by Accounting")
                                @if(!empty($request_form?->receiver->signature))
                                    <img src="{{ asset($request_form?->receiver->signature) }}" alt="Supervisor Signature">
                                @endif
                            @endif
                        </div>
                        
                    </div>
    
                    <div id="signature-2">
    
                        <div>
                            <strong>Manager:</strong>
                            <p>{{ $request_form?->manager->name ?? '' }}</p> <br>
                            @if ($request_form?->status != "Approved by Operation")
                                @if(!empty($request_form?->receiver->signature))
                                    <img src="{{ asset($request_form?->receiver->signature) }}" alt="Supervisor Signature">
                                @endif
                            @endif
                        
    
                        </div>
                    </div>
                </div>
                
            </div>
        
            <div class="footer" style="page-break-before: always;">
                <img src="{{ asset('/Assets/doc_footer.png') }}" alt="Footer">
            </div>            
        </div>
    </body>
</div>


@php
    // Collect the selected particulars from the request form
    $selectedParticulars = collect($request_form?->particulars)->pluck('particular')->toArray();
@endphp

<!-- Modal -->
<div id="description-modal" class="fixed inset-0 z-50 bg-black bg-opacity-50 hidden flex items-center justify-center">
    <div class="bg-white p-6 rounded-lg shadow-lg w-1/2">
        <form id="description-form" action="{{ url('requestForm/update/' . $request_form?->id) }}" method="POST">
            @csrf
            <div class="h-40 overflow-y-auto">
                <label>
                    <input type="checkbox" name="particulars[]" value="Domain" {{ in_array('Domain', $selectedParticulars) ? 'checked' : '' }}>
                    Domain
                </label><br>
                <label>
                    <input type="checkbox" name="particulars[]" value="Hosting and Servers" {{ in_array('Hosting and Servers', $selectedParticulars) ? 'checked' : '' }}>
                    Hosting and Servers
                </label><br>
                <label>
                    <input type="checkbox" name="particulars[]" value="Office Supplies" {{ in_array('Office Supplies', $selectedParticulars) ? 'checked' : '' }}>
                    Office Supplies
                </label><br>
                <label>
                    <input type="checkbox" name="particulars[]" value="Fare" {{ in_array('Fare', $selectedParticulars) ? 'checked' : '' }}>
                    Fare
                </label><br>
                <label>
                    <input type="checkbox" name="particulars[]" value="Petty Cash" {{ in_array('Petty Cash', $selectedParticulars) ? 'checked' : '' }}>
                    Petty Cash
                </label><br>
                <label>
                    <input type="checkbox" name="particulars[]" value="Credit Card" {{ in_array('Credit Card', $selectedParticulars) ? 'checked' : '' }}>
                    Credit Card
                </label><br>
                <label>
                    <input type="checkbox" name="particulars[]" value="Office Asset / Cash Advance" {{ in_array('Office Asset / Cash Advance', $selectedParticulars) ? 'checked' : '' }}>
                    Office Asset / Cash Advance
                </label><br>
                <label>
                    <input type="checkbox" name="particulars[]" value="Car" {{ in_array('Car', $selectedParticulars) ? 'checked' : '' }}>
                    Car
                </label><br>
                <label>
                    <input type="checkbox" name="particulars[]" value="Flyers" {{ in_array('Flyers', $selectedParticulars) ? 'checked' : '' }}>
                    Flyers
                </label><br>
                <label>
                    <input type="checkbox" name="particulars[]" value="for Multimedia Use" {{ in_array('for Multimedia Use', $selectedParticulars) ? 'checked' : '' }}>
                    for Multimedia Use
                </label><br>
                <label>
                    <input type="checkbox" name="particulars[]" value="Cash Advance" {{ in_array('Cash Advance', $selectedParticulars) ? 'checked' : '' }}>
                    Cash Advance
                </label><br>
                <label>
                    <input type="checkbox" name="particulars[]" value="Signage" {{ in_array('Signage', $selectedParticulars) ? 'checked' : '' }}>
                    Signage
                </label><br>
                <label>
                    <input type="checkbox" name="particulars[]" value="Reimbursement" {{ in_array('Reimbursement', $selectedParticulars) ? 'checked' : '' }}>
                    Reimbursement
                </label><br>
                <label>
                    <input type="checkbox" name="particulars[]" value="For Marketing Use" {{ in_array('For Marketing Use', $selectedParticulars) ? 'checked' : '' }}>
                    For Marketing Use
                </label><br>
                <label>
                    <input type="checkbox" name="particulars[]" value="Ads - AUB" {{ in_array('Ads - AUB', $selectedParticulars) ? 'checked' : '' }}>
                    Ads - AUB
                </label><br>
                <label>
                    <input type="checkbox" name="particulars[]" value="Repair and Maintenance" {{ in_array('Repair and Maintenance', $selectedParticulars) ? 'checked' : '' }}>
                    Repair and Maintenance
                </label><br>
                <label>
                    <input type="checkbox" name="particulars[]" value="Refund" {{ in_array('Refund', $selectedParticulars) ? 'checked' : '' }}>
                    Refund
                </label><br>
                <label>
                    <input type="checkbox" name="particulars[]" value="Others" {{ in_array('Others', $selectedParticulars) ? 'checked' : '' }}>
                    Others: <input type="text" name="other_particulars" class="border p-1" value="{{ in_array('Others', $selectedParticulars) ? $request_form?->other_particulars : '' }}">
                </label>
            </div>
            <div class="mt-4">
                <h1 class="font-bold">Date:</h1>
                <input type="date" name="date" class="border p-2 w-full rounded" value="{{ $request_form?->date }}">
            </div>
            <!-- Graphics Designer BUT CHANGED NAME TO MANAGER -->
            <div class="w-full col-span-2 lg:col-span-1">
                <h1 class="font-bold">Manager:</h1>
                <div class="relative">
                    <input type="text" id="selected-graphic-designer-name"
                        value="{{ $request_form?->manager->name ?? 'Select a Manager' }}"
                        class="w-full border px-3 py-2 border-gray-200 rounded-lg cursor-pointer" readonly
                        onclick="openGraphicDesignerModal()">
                    <input type="hidden" name="manager_id" id="selected-graphic-designer-id"
                        value="{{ $request_form?->manager->id ?? '' }}">
                </div>
                @error('manager_id')
                    <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="w-full col-span-2 lg:col-span-1">
                <h1 class="font-bold">Auditor:</h1>
                <div class="relative">
                    <input type="text" id="selected-content-writer-name"
                        value="{{ $request_form?->receiver->name ?? 'Select an Auditor' }}"
                        class="w-full border px-3 py-2 border-gray-200 rounded-lg cursor-pointer" readonly
                        onclick="openContentWriterModal()">
                    <input type="hidden" name="receiver_id" id="selected-content-writer-id"
                        value="{{ $request_form?->receiver->id ?? '' }}">
                </div>
                @error('receiver_id')
                    <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mt-4">
                <h1 class="font-bold">Description:</h1>
                <textarea id="description-editor" name="description">{{ $request_form?->description }}</textarea>
            </div>
            <div class="mt-4 flex justify-end">
                <button type="button" id="close-modal" class="px-4 py-2 bg-gray-500 text-white rounded">Cancel</button>
                <button type="submit" id="save-description" class="ml-2 px-4 py-2 bg-orange-500 text-white rounded">Save</button>
            </div>
        </form>
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
                <thead id="contentWriterTableHead" class="sticky top-0 bg-[#fa7011] text-white">
                    <tr>
                        <th class="px-4 md:px-6 py-3 w-24 md:w-32">Name</th>
                        <th class="px-4 md:px-6 py-3 w-24 md:w-32">Role</th>
                        <th class="px-4 md:px-6 py-3 w-24 md:w-32 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody id="contentWriterTableBody">
                    @forelse ($accounting as $accountant)
                        <tr class="border-b">
                            <td class="px-4 md:px-6 py-3">{{ $accountant->name }}</td>
                            <td class="px-4 md:px-6 py-3">{{ Str::title(str_replace('_', ' ', $accountant->role->position)) }}</td>
                            <td class="px-4 md:px-6 py-3 text-center">
                                <button onclick="selectContentWriter('{{ $accountant->id }}', '{{ $accountant->name }}')" class="px-2 py-1 md:px-4 md:py-2 text-sm text-white bg-orange-500 rounded hover:bg-orange-600 w-full md:w-auto">
                                    Select
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr class="h-[400px]">
                            <td colspan="3" class="px-6 py-3">
                                <div class="flex h-full items-center flex-col justify-center space-y-4">
                                    <i class="far fa-grin-beam-sweat text-7xl" style="color: #fa7011;"></i>
                                    <p class="text-[#fa7011]">No Data Found</p>
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
                <thead id="graphicDesignerTableHead" class="sticky top-0 bg-[#fa7011] text-white">
                    <tr>
                        <th class="px-4 md:px-6 py-3 w-24 md:w-32">Name</th>
                        <th class="px-4 md:px-6 py-3 w-24 md:w-32">Role</th>
                        <th class="px-4 md:px-6 py-3 w-24 md:w-32 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody id="graphicDesignerTableBody">
                    @foreach ($managers as $manager)
                        <tr class="border-b">
                            <td class="px-4 md:px-6 py-3">{{ $manager->name }}</td>
                            <td class="px-4 md:px-6 py-3">{{ Str::title(str_replace('_', ' ', $manager->role->position)) }}</td>
                            <td class="px-4 md:px-6 py-3 text-center">
                                <button onclick="selectGraphicDesigner('{{ $manager->id }}', '{{ $manager->name }}')" class="px-2 py-1 md:px-4 md:py-2 text-sm text-white bg-orange-500 rounded hover:bg-orange-600 w-full md:w-auto">
                                    Select
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- CKEditor Script -->
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
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

    function openGraphicDesignerModal() {
        document.getElementById('graphic-designer-modal').classList.remove('hidden');
    }

    function closeGraphicDesignerModal() {
        document.getElementById('graphic-designer-modal').classList.add('hidden');
    }

    function selectGraphicDesigner(graphicDesignerId, graphicDesignerName) {
        document.getElementById('selected-graphic-designer-name').value = graphicDesignerName;
        document.getElementById('selected-graphic-designer-id').value = graphicDesignerId;
        closeGraphicDesignerModal();
    }

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

    document.addEventListener("DOMContentLoaded", function () {
        ClassicEditor
            .create(document.querySelector("#description-editor"))
            .then(editor => {
                window.editor = editor;
            })
            .catch(error => {
                console.error("There was a problem initializing CKEditor:", error);
            });

        // Open and Close Modal
        const openModal = document.getElementById("open-modal");
        const modal = document.getElementById("description-modal");
        const closeModal = document.getElementById("close-modal");
        const saveButton = document.getElementById("save-description");
        const form = document.getElementById("description-form");

        openModal.addEventListener("click", function () {
            modal.classList.remove("hidden");
        });

        closeModal.addEventListener("click", function () {
            modal.classList.add("hidden");
        });

        // Ensure CKEditor content is included in form submission
        form.addEventListener("submit", function () {
            document.querySelector("textarea[name='description']").value = editor.getData();
        });
    });
</script>

<script>
    function downloadRequestForm() {
        var element = document.getElementById('printable-area'); // Select the printable content

        html2pdf(element, {
            margin: [10, 10, 10, 10], // Top, right, bottom, left margins
            filename: 'Job_Order.pdf',
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { 
                scale: 2, 
                logging: false,
                scrollX: 0, 
                scrollY: 0, 
                windowWidth: document.documentElement.offsetWidth,
                windowHeight: document.documentElement.offsetHeight
            },
            jsPDF: { 
                unit: 'mm', 
                format: 'a4', 
                orientation: 'portrait'
            }
        }).then(() => {
            console.log("PDF Downloaded Successfully");
        }).catch(error => {
            console.error("Error generating PDF:", error);
        });
    }
</script>



@endsection
