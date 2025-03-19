<head>
    <title>{{ env('APP_NAME') }} | SMM | Job Order</title>

    <script src="https://cdn.tailwindcss.com"></script>

</head>

<x-main-layout breadcumb="SMM" page="Job Order">

    {{-- Success Message Component --}}
    @if (session('Status'))
        <x-success />
    @endif

    {{-- Search Bar --}}
    <a href="{{ url('admin/smm/supervisor/joborder/create') }}">
        <div
            class="bg-[#fa7011] w-fit block text-white px-4 py-2 rounded-lg shadow-md hover:bg-[#D95F0E] transition text-center lg:hidden">
            <i class="fa-solid fa-plus"></i>
        </div>
    </a>
    <div class="w-full h-fit flex flex-col md:flex-row justify-between items-center gap-4 mb-4">
        <a href="{{ url('/admin/smm/supervisor/joborder/create') }}">
            <div
                class="bg-[#fa7011] hidden text-white px-4 py-2 rounded-lg shadow-md hover:bg-[#D95F0E] transition text-center w-full md:w-auto lg:block">
                Create New Job Order
            </div>
        </a>

        <div class="flex items-center w-full md:w-auto relative">
            <i class="fa-solid fa-magnifying-glass absolute left-4 text-gray-500"></i>
            <input type="text" id="searchInput"
                class="w-full md:w-80 px-10 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500"
                placeholder="Search..." onkeyup="filterTable()" />
        </div>
    </div>

    {{-- Table Wrapper --}}
    <div class="overflow-x-auto overflow-y-auto bg-white shadow-md rounded-lg h-[500px]" style="max-height: 500px;">
        <table class="w-full table-fixed text-left border-collapse min-w-[600px]">
            <thead class="sticky top-0 bg-[#fa7011] text-white">
                <tr>
                    <th class="w-[30%] px-4 py-3">Title</th>
                    <th class="w-[20%] px-4 py-3">Issued To</th>
                    <th class="w-[20%] px-4 py-3 text-center">Status</th>
                    <th class="w-[20%] px-4 py-3 text-center">Actions</th>
                </tr>
            </thead>
            <tbody id="tableBody" class="overflow-y-auto">
                @forelse ($supervisor_requests as $supervisor_request)
                    <tr class="border-b">
                        <td class="w-[30%] px-4 py-3 truncate">{{ $supervisor_request->title }}</td>
                        <td class="w-[20%] px-4 py-3 truncate">{{ $supervisor_request->assignee->name }}</td>
                        <td class="w-[20%] px-4 py-3 text-wrap text-center">
                            {{ $supervisor_request->status }}
                            @if ($supervisor_request->deadline < now())
                                <!-- Check if deadline has passed -->
                                <span class="text-sm font-bold text-red-500">{{ $supervisor_request->deadline }}
                                    LATE</span>
                            @else
                                <span class="text-sm font-bold text-green-500">{{ $supervisor_request->deadline }}
                                    ONGOING</span>
                            @endif
                        </td>
                        <td class="w-[20%] px-4 py-3 text-center">
                            <a href="{{ url('/admin/smm/supervisor/joborder/edit/' . $supervisor_request->id) }}">
                                @if ($supervisor_request->status == 'pending' || $supervisor_request->status == 'Waiting for Operation Approval')
                                    <button
                                        class="px-2 py-1 mb-2 lg:mb-0 lg:px-4 lg:py-2 text-sm text-white bg-green-500 rounded hover:bg-green-600">
                                        Edit
                                    </button>
                                @else
                                    <button disabled
                                        class="px-2 py-1 mb-2 lg:mb-0 lg:px-4 lg:py-2 text-sm text-gray-500 bg-gray-300 rounded cursor-not-allowed">
                                        Edit
                                    </button>
                                @endif
                            </a>

                            <a href="{{ url('/admin/smm/supervisor/joborder/show/' . $supervisor_request->id) }}">
                                <button
                                    class="px-2 py-1 lg:px-4 lg:py-2 text-sm text-white bg-gray-700 rounded hover:bg-gray-800">
                                    Show
                                </button>
                            </a>
                            <a href="{{ url('/admin/smm/supervisor/joborder/delete/' . $supervisor_request->id) }}">
                                <button
                                    class="px-2 py-1 lg:px-4 lg:py-2 text-sm text-white bg-red-700 rounded hover:bg-red-800">
                                    Delete
                                </button>
                            </a>
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
</script>

{{-- @endsection --}}
