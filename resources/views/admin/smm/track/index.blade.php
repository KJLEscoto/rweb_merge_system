<head>
    <title>{{ env('APP_NAME') }} | SMM | Approvals</title>

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
    </style>
</head>

<x-main-layout breadcumb="SMM" page="Track">

    <div class="overflow-x-auto overflow-y-auto bg-white shadow-md rounded-lg h-[500px]" style="max-height: 500px;">
        {{-- Success Message Component --}}
        @if (session('Status'))
            <x-success />
        @endif

        <table class="w-full table-fixed text-left border-collapse min-w-[700px]" id="projectTable">
            <thead class="sticky top-0 bg-[#fa7011] text-white">
                <tr>
                    <th class="w-[30%] px-4 py-3">Title</th>
                    <th class="w-[20%] px-4 py-3">Client</th>
                    <th class="w-[25%] px-4 py-3 text-center">Actions</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                @forelse ($job_orders as $job_order)
                    <tr class="project-row border-b">
                        <td class="w-[30%] px-4 py-3 truncate">{{ $job_order->title }}</td>
                        <td class="w-[20%] px-4 py-3 truncate">
                            {{ $job_order->latestJobDraft->client->name }}
                        </td>



                        <td class="w-[25%] px-4 py-3">
                            <div class="flex items-center justify-center space-x-2">
                                <div>
                                    <a href="{{ url('admin/smm/track/' . $job_order->id) }}">
                                        <button
                                            class="px-4 py-2 text-sm text-white bg-green-500 rounded hover:bg-green-600">
                                            Show
                                        </button>
                                    </a>
                                </div>
                                {{-- <div>
                                        <a href="{{ url('admin/smm/track/' . $job_order->id) . '/edit' }}">
                                            <button class="px-4 py-2 text-sm text-white bg-blue-500 rounded hover:bg-blue-600">
                                                Edit
                                            </button>
                                        </a>
                                    </div> --}}
                                <div onclick="deleteJobOrder({{ $job_order->id }})"
                                    class="px-4 py-2 text-sm text-white bg-red-500 rounded hover:bg-red-600 cursor-pointer">
                                    Delete
                                </div>
                            </div>
                        </td>


                    </tr>
                @empty
                    <tr class="h-[400px]">
                        <td colspan="4" class="px-6 py-3">
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

</x-main-layout>

<script>
    function deleteJobOrder(jobOrderId) {
        if (confirm('Are you sure you want to delete this item?')) {
            // Create a form element dynamically
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/admin/smm/track/' + jobOrderId;

            // CSRF token input
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = '{{ csrf_token() }}';
            form.appendChild(csrfInput);

            // Method override for DELETE
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            form.appendChild(methodInput);

            // Append the form to the document body and submit it
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>


{{-- @endsection --}}
