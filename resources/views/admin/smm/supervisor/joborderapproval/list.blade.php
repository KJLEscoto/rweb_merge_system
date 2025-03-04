@extends('layouts.application')

@section('title', 'Job Order')
@section('header', 'List of Job Orders')

@section('content')
<script src="https://cdn.tailwindcss.com"></script>

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
</style>

<div class="container mx-auto p-4 sm:p-6">
    {{-- Success Message Component --}}
    @if(session('Status'))
        <x-success />
    @endif

    {{-- Search and Filter --}}
    <div class="w-full h-fit flex flex-col md:flex-row justify-between items-center gap-4 mb-4">
        <div class="flex items-center w-full md:w-auto relative">
            <i class="fa-solid fa-magnifying-glass absolute left-4 text-gray-500"></i>
            <input type="text" id="searchInput" class="w-full md:w-80 px-10 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500"
                   placeholder="Search..." onkeyup="filterTable()" />
        </div>

        <div class="flex justify-between items-center gap-4 px-10">
            <a class="cursor-pointer" id="pendingBtn" onclick="filterByStatus('pending')">Pending</a>
            <a class="cursor-pointer" id="submittedBtn" onclick="filterByStatus('submitted')">Approved</a>
            <a class="cursor-pointer" id="allBtn" onclick="filterByStatus('all')">All</a>
        </div>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto overflow-y-auto bg-white shadow-md rounded-lg h-[500px] max-h-[500px]">
        <table class="w-full table-fixed text-left border-collapse min-w-[600px] sm:min-w-max" id="projectTable">
            <thead class="sticky top-0 bg-[#fa7011] text-white">
                <tr>
                    <th class="w-[25%] px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-base">Title</th>
                    <th class="w-[25%] px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-base">Designated</th>
                    <th class="w-[20%] px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-base">Deadline</th>
                    <th class="w-[15%] px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-base text-center">Status</th>
                    <th class="w-[15%] px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-base text-center">Actions</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                @forelse ($job_drafts as $job_draft)
                    <tr class="project-row border-b text-xs sm:text-sm"
                        data-status="{{ strtolower($job_draft->status) }}"
                        data-title="{{ strtolower($job_draft->jobOrder->title) }}"
                        data-designated="{{ strtolower($job_draft->type == 'content_writer' ? 'content writer - ' . $job_draft->contentWriter->name : 'graphic designer - ' . $job_draft->graphicDesigner->name) }}">
                        <td class="w-[25%] px-2 sm:px-4 py-2 sm:py-3 truncate">{{ $job_draft->jobOrder->title }}</td>
                        <td class="w-[25%] px-2 sm:px-4 py-2 sm:py-3 truncate">
                            @if ($job_draft->type == "content_writer")
                                Content Writer - {{ $job_draft->contentWriter->name }}
                            @else
                                Graphic Designer - {{ $job_draft->graphicDesigner->name }}
                            @endif
                        </td>
                        <td class="w-[20%] px-2 sm:px-4 py-2 sm:py-3 whitespace-nowrap">{{ $job_draft->date_target }}</td>
                        <td class="w-[15%] px-2 sm:px-4 py-2 sm:py-3 text-center text-white">
                            <p class="w-full px-2 py-1 rounded-lg text-wrap
                                {{ $job_draft->status == 'completed' ? 'bg-green-400' : 
                                ($job_draft->status == 'Revision' ? 'bg-red-600' : 'bg-[#fa6e117e]') }} ">
                                {{ ucfirst($job_draft->status) }}
                            </p>
                        </td>
                        <td class="w-[15%] px-2 sm:px-4 py-2 sm:py-3 text-center border-b">
                            <a href="{{ url('supervisor/approve/show/' . $job_draft->id) }}">
                                <button class="px-2 sm:px-3 py-1 sm:py-2 text-xs sm:text-sm text-white  rounded whitespace-nowrap {{$job_draft->status !== 'Submitted to Supervisor' ? "cursor-not-allowed bg-gray-400" : "bg-green-500 hover:bg-green-600"}}" {{$job_draft->status !== 'Submitted to Supervisor' ? "disabled" : ""}}>
                                    View Form
                                </button>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr class="h-[400px]">
                        <td colspan="5" class="px-6 py-3">
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

{{-- JavaScript --}}
<script>
    // ✅ Filter by Search
    function filterTable() {
        let input = document.getElementById("searchInput").value.toLowerCase();
        let rows = document.querySelectorAll("#tableBody .project-row");

        rows.forEach(row => {
            let title = row.getAttribute("data-title");
            let designated = row.getAttribute("data-designated");

            if (title.includes(input) || designated.includes(input)) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    }

    // ✅ Filter by Status Tabs
    function filterByStatus(status) {
        let rows = document.querySelectorAll("#tableBody .project-row");
        let buttons = document.querySelectorAll('.flex a');

        // Remove active class from all buttons
        buttons.forEach(button => button.classList.remove('active-tab'));

        // Logic for filtering
        rows.forEach(row => {
            let rowStatus = row.getAttribute("data-status");

            // Pending: Only "Submitted to Operations"
            if (status === 'pending') {
                row.style.display = (rowStatus === 'submitted to supervisor') ? "" : "none";
            }
            // Submitted: All except "Revision" and "Submitted to Operations"
            else if (status === 'submitted') {
                row.style.display = (rowStatus !== 'revision' && rowStatus !== 'submitted to supervisor') ? "" : "none";
            }
            // All: All except "Revision"
            else if (status === 'all') {
                row.style.display = (rowStatus !== 'revision') ? "" : "none";
            }
        });

        // Add active class to clicked button
        if (status === 'pending') document.getElementById('pendingBtn').classList.add('active-tab');
        else if (status === 'submitted') document.getElementById('submittedBtn').classList.add('active-tab');
        else if (status === 'all') document.getElementById('allBtn').classList.add('active-tab');
    }

    // ✅ Default: Pending Tab Active
    document.addEventListener('DOMContentLoaded', () => {
        filterByStatus('pending');
    });
</script>

@endsection
