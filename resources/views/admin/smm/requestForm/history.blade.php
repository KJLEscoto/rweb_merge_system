@extends('layouts.application')

@section('title', 'Job Order')
@section('header', 'Request Form History')

@section('content')
<script src="https://cdn.tailwindcss.com"></script>

<style>
    .custom-shadow {
        box-shadow: 0 4px 6px rgba(0, 0, 0, .3), 0 1px 3px rgba(0, 0, 0, .3);
    }
    .active-tab {
        border-bottom: 2px solid #fa7011;
    }
    #approval-link {
        text-decoration: underline;
    }
    /* Add a minimum width to each column */
    th, td {
        min-width: 150px;
    }
</style>


<div class="container mx-auto p-4 sm:p-6">
    @if(session('Status'))
        <x-success />
    @endif

    <div class="w-full h-fit flex flex-col md:flex-row justify-between items-center gap-4 mb-4">
        <div class="flex items-center w-full md:w-auto relative">
            <i class="fa-solid fa-magnifying-glass absolute left-4 text-gray-500"></i>
            <input type="text" id="searchInput" class="w-full md:w-80 px-10 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500"
                   placeholder="Search..." onkeyup="filterTable()" />
        </div>

        {{-- <div class="flex justify-between items-center gap-4 px-10">
            <a class="cursor-pointer" id="pendingBtn" onclick="filterByStatus('pending')">Pending</a>
            <a class="cursor-pointer" id="submittedBtn" onclick="filterByStatus('submitted')">Approved</a>
            <a class="cursor-pointer" id="allBtn" onclick="filterByStatus('all')">All</a>
        </div> --}}
    </div>

    <div class="overflow-x-auto overflow-y-auto bg-white shadow-md rounded-lg h-auto">
        <table class="w-full text-left border-collapse" id="projectTable">
            <thead class="sticky top-0 bg-[#fa7011] text-white">
                <tr>
                    <th class="px-4 py-3"></th> <!-- Approve -->
                    <th class="px-4 py-3">Request No.</th>
                    <th class="px-4 py-3">Edit</th>
                    <th class="px-4 py-3">Delete</th>
                    <th class="px-4 py-3">Employee Name</th>
                    <th class="px-4 py-3">Particulars</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Descriptions</th>
                    <th class="px-4 py-3">Date Created</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                @forelse ($request_forms as $request_form)
                    <tr class="project-row border-b text-sm" data-status="{{ strtolower($request_form->status) }}">
                        <td class="px-4 py-3">
                            <div class="flex items-center space-x-4">
                                <a id="approval-link" href="{{ url('/requestForm/show/' . $request_form->id) }}" class="text-blue-500 hover:underline">
                                    View
                                </a>
                                <form action="{{ url('/requestForm/approve/' . $request_form->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button 
                                        {{ (Auth::user()->role_id === 6) || (Auth::user()->role_id === 5 && $request_form->status === "Approved by Top Manager") || (Auth::user()->role_id === 7 && $request_form->status === "Approved by Accounting" || Auth::user()->role_id === 7 && $request_form->status === "Approved by Operation") ? "disabled" : "" }}
                                        type="submit"
                                        class="px-3 py-1 rounded text-white {{ (Auth::user()->role_id === 6) || (Auth::user()->role_id === 5 && $request_form->status === "Approved by Top Manager") || (Auth::user()->role_id === 7 && $request_form->status === "Approved by Accounting" || Auth::user()->role_id === 7 && $request_form->status === "Approved by Operation") ? "bg-gray-300" : "bg-green-500" }}">
                                        Approve
                                    </button>
                                </form>
                            </div>
                        </td>
                        
                        
                        
                        <td class="px-4 py-3">{{$request_form->id}}</td>
                        <td class="px-4 py-3">
                            @if(Auth::user()->role_id == 7)
                                <span class="text-gray-400 cursor-not-allowed">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </span>
                            @else
                                <a href="{{ url('/requestForm/edit/' . $request_form->id) }}" class="text-blue-500 hover:text-blue-700">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                            @endif
                        </td>
                        
                        <td class="px-4 py-3">
                            @if(Auth::user()->role_id == 7)
                                <span class="text-gray-400">
                                    <i class="fa-solid fa-trash"></i>
                                </span>
                            @else
                                <form action="{{ url('/requestForm/delete/' . $request_form->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 focus:outline-none">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            @endif
                        </td>
                        
                        
                        <td class="px-4 py-3">{{$request_form->requestedBy->name}}</td>
                        <td class="px-4 py-3">
                            @foreach ($request_form->particulars as $particular)
                                {{$particular->particular}} <br>
                            @endforeach
                        </td>
                        <td class="px-4 py-3">{{$request_form->status}}</td>
                        <td class="px-4 py-3">{!! $request_form->description !!}</td>
                        <td class="px-4 py-3">{{$request_form->date}}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-6 py-3 text-center text-gray-500">
                            <div class="flex items-center flex-col justify-center space-y-4">
                                <i class="far fa-grin-beam-sweat text-7xl" style="color: #fa7011;"></i>
                                <p class="text-[#fa7011]">No Job Orders Found</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
    function filterTable() {
        let input = document.getElementById("searchInput").value.toLowerCase();
        let rows = document.querySelectorAll("#tableBody .project-row");
        rows.forEach(row => {
            row.style.display = row.textContent.toLowerCase().includes(input) ? "" : "none";
        });
    }

    function filterByStatus(status) {
        let rows = document.querySelectorAll("#tableBody .project-row");
        let buttons = document.querySelectorAll('.flex a');
        buttons.forEach(button => button.classList.remove('active-tab'));
        rows.forEach(row => {
            let rowStatus = row.getAttribute("data-status");
            if (status === 'pending') row.style.display = (rowStatus === 'submitted to operations') ? "" : "none";
            else if (status === 'submitted') row.style.display = (rowStatus !== 'revision' && rowStatus !== 'submitted to operations') ? "" : "none";
            else row.style.display = (rowStatus !== 'revision') ? "" : "none";
        });
        document.getElementById(status + 'Btn').classList.add('active-tab');
    }
</script>

@endsection