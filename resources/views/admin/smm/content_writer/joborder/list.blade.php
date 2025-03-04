@extends('layouts.application')

@section('title', 'Admin')
@section('header', "Job Order")

@section('content')
<script src="https://cdn.tailwindcss.com"></script>

<div class="container mx-auto p-6">
    {{-- Success Message Component --}}
    @if(session('Status') === 'Job Order Accepted Successfully')
        <x-success />
    @endif

    @if(session('Status') === 'No Signature Found' && !Auth::user()->signature)
        <form action="{{ url('signature/store') }}" method="POST" id="modalSignatureForm">
            @csrf
            @method('PUT')
            <x-save-signature />
        </form>
    @endif

    {{-- Search Bar --}}
    {{-- <a href="{{ url('joborder/create') }}">
        <div class="bg-[#fa7011] w-fit block text-white px-4 py-2 rounded-lg shadow-md hover:bg-[#D95F0E] transition text-center lg:hidden">
            <i class="fa-solid fa-plus"></i>
        </div>
    </a> --}}
    <div class="w-full h-fit flex flex-col md:flex-row justify-between items-center gap-4 mb-4">
        {{-- <a href="{{ url('joborder/create') }}">
            <div class="bg-[#fa7011] hidden text-white px-4 py-2 rounded-lg shadow-md hover:bg-[#D95F0E] transition text-center w-full md:w-auto lg:block">
                Create New Job Order
            </div>
        </a> --}}


        <div class="flex items-center w-full md:w-auto relative">
            <i class="fa-solid fa-magnifying-glass absolute left-4 text-gray-500"></i>
            <input type="text" id="searchInput" class="w-full md:w-80 px-10 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500"
            placeholder="Search..." onkeyup="filterTable()" />

        </div>

        <div class="flex justify-between items-center gap-4 px-10">
            <a class="cursor-pointer" id="pendingBtn" onclick="filterByStatus('pending')">Pending</a>
            <a class="cursor-pointer" id="submittedBtn" onclick="filterByStatus('submitted to operations')">Submitted</a>
            <a class="cursor-pointer" id="allBtn" onclick="filterByStatus('all')">All</a>
        </div>
    </div>

    {{-- Table Wrapper --}}
    <div class="overflow-x-auto overflow-y-auto bg-white shadow-md rounded-lg h-[500px]" style="max-height: 500px;">
        <table class="w-full text-left border-collapse min-w-[500px]">
            <thead class="sticky top-0 bg-[#fa7011] text-white">
                <tr>
                    <th class="px-6 py-3 w-[35%]">Title</th>
                    <th class="px-6 py-3 w-[25]">Designated</th>
                    <th class="px-6 py-3 w-[20%] text-center">Status</th>
                    <th class="px-6 py-3 w-[20%] ">Actions</th>
                </tr>
            </thead>
            <tbody id="tableBody" class="overflow-y-auto">
                @forelse ($job_drafts as $job_draft)
                <tr class="border-b {{
                    (($job_draft->type == 'content_writer' && $job_draft->contentWriter->name != Auth::user()->name)
                    || ($job_draft->type == 'graphic_designer' && $job_draft->graphicDesigner->name != Auth::user()->name))
                    ? 'hidden'
                    : ''
                }}" data-status="{{ strtolower($job_draft->status) }}">
            
                        <td class="px-6 py-3">{{ $job_draft->jobOrder->title }}</td>
                        <td class="px-6 py-3">
                            @if ($job_draft->type == "content_writer")
                                Content Writer - {{ $job_draft->contentWriter->name }}
                            @else
                                Graphic Designer - {{ $job_draft->graphicDesigner->name }}
                            @endif
                        </td>
                        <td>
                                                    <p class="w-full text-center text-white px-2 py-1 rounded-lg text-wrap
                                {{ $job_draft->status == 'completed' ? 'bg-green-400' : 
                                ($job_draft->status == 'Revision' ? 'bg-red-600' : 'bg-[#fa6e117e]') }} ">
                                {{ ucfirst($job_draft->status) }}
                            </p>
                        </td>
                        <td class="px-6 py-3">
                            @if ($job_draft->status == 'pending' || $job_draft->status == 'Waiting for Content Writer Approval')
                                @if ($job_draft->status == 'pending')
                                    <a href="{{url('content/create/' . $job_draft->id)}}">
                                        <button class="px-2 py-1 mb-2 lg:mb-0 lg:px-4 lg:py-2 text-sm text-white bg-green-500 rounded hover:bg-green-600">
                                            Create
                                        </button>
                                    </a>
                                @elseif ($job_draft->status == 'Waiting for Content Writer Approval')
                                    <form action="{{ url('content/accept/' . $job_draft->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="px-2 py-1 mb-2 lg:mb-0 lg:px-4 lg:py-2 text-sm text-white bg-[#fa7011] rounded hover:bg-[#fa7011]">
                                            Accept
                                        </button>
                                    </form>                                
                                @endif
                                <a href="{{url('content/show/' . $job_draft->id)}}">
                                    <button class="px-2 py-1 lg:px-4 lg:py-2 text-sm text-white bg-gray-700 rounded hover:bg-gray-800">
                                        Show
                                    </button>
                                </a>

                            @elseif ($job_draft->status == "Submitted to Operations")
                                <a href="{{url('content/edit/' . $job_draft->id)}}">
                                    <button class="px-2 py-1 mb-2 lg:mb-0 lg:px-4 lg:py-2 text-sm text-white bg-blue-500 rounded hover:bg-blue-600">
                                        Edit
                                    </button>
                                </a>
                                <a href="{{url('content/show/' . $job_draft->id)}}">
                                    <button class="px-2 py-1 lg:px-4 lg:py-2 text-sm text-white bg-gray-700 rounded hover:bg-gray-800">
                                        Show
                                    </button>
                                </a>

                            @else
                                <a href="{{url('content/create/' . $job_draft->id)}}">
                                    <button disabled class="px-2 py-1 mb-2 cursor-not-allowed lg:mb-0 lg:px-4 lg:py-2 text-sm text-white bg-gray-500 rounded hover:bg-gray-600">
                                        Edit
                                    </button>
                                </a>
                                <a href="{{url('content/show/' . $job_draft->id)}}">
                                    <button class="px-2 py-1 lg:px-4 lg:py-2 text-sm text-white bg-gray-700 rounded hover:bg-gray-800">
                                        Show
                                    </button>
                                </a>
                            @endif
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

    {{-- Pagination Links --}}
    <div class="mt-4">
        {{-- {{ $list_of_projects->links('vendor.pagination.custom') }} --}}
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
</script>

<script>
    function filterByStatus(status) {
    let tableBody = document.getElementById("tableBody");
    let rows = tableBody.getElementsByTagName("tr");

    let buttons = document.querySelectorAll('.flex a'); // Select all buttons

    // Reset the active class for all buttons
    buttons.forEach(button => button.classList.remove('border-b', 'border-[#fa7011]'));

    // Loop through rows and filter
    for (let row of rows) {
        let rowStatus = row.getAttribute("data-status");

        if (status === 'all') {
            row.style.display = ""; // Show all rows
        } else if (status === 'pending') {
            row.style.display = (rowStatus === 'pending' || rowStatus === 'waiting for content writer approval') ? "" : "none";
        } else {
            row.style.display = (rowStatus === status) ? "" : "none";
        }
    }

    // Add active class to the clicked button
    if (status === 'pending') {
        document.getElementById('pendingBtn').classList.add('border-b', 'border-[#fa7011]');
    } else if (status === 'submitted to operations') {
        document.getElementById('submittedBtn').classList.add('border-b', 'border-[#fa7011]');
    } else if (status === 'all') {
        document.getElementById('allBtn').classList.add('border-b', 'border-[#fa7011]');
    }
}


    // ✅ Set default active tab to "Pending" when the page loads
    document.addEventListener('DOMContentLoaded', function() {
        filterByStatus('pending');
    });
</script>

@endsection