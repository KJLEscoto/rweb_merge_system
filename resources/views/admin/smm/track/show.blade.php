<head>
    <title>{{ env('APP_NAME') }} | SMM | Create Direct Job Order</title>

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

    <!-- CKEditor 5 Classic -->
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
</head>

<x-main-layout breadcumb="SMM" page="Show Track">
    <div class="space-y-10">
        <div class="bg-white p-10 rounded-lg shadow-lg">
            <div class="">
                <div>
                    <a href="{{ url('/admin/smm/track') }}">
                        <div class="w-fit px-4 py-1 bg-gray-400 rounded-md text-white custom-shadow custom-hover-shadow">
                            Back
                        </div>
                    </a>
                </div>
                <div class="grid grid-cols-3 mt-10">
                    <div>
                        <p class="font-bold">Title</p>
                        <p class="border-b border-[#fa7011] w-fit">{{ $job_order->title }}</p>
                    </div>
                    <div>
                        <p class="font-bold">Client</p>
                        <p class="border-b border-[#fa7011] w-fit">{{ $job_order->latestJobDraft->client->name }}</p>
                    </div>
                    <div>
                        <p class="font-bold">Issuer</p>
                        <p class="border-b border-[#fa7011] w-fit">{{ $job_order->issuer->name }}</p>
                    </div>
                </div>
                <div class="mt-5">
                    <p class="font-bold">Description</p>
                    <div class="border border-gray-400 p-3 rounded-lg max-h-[500px] overflow-y-auto">
                        {!! $job_order->description !!}</div>
                </div>
            </div>

            <div>

                <p class="font-bold mt-10">Drafts</p>
                <div class="overflow-x-auto overflow-y-auto bg-white shadow-md rounded-lg">
                    <table class="w-full text-left border-collapse min-w-full sm:min-w-max" id="projectTable">
                        <thead class="sticky top-0 bg-[#fa7011] text-white">
                            <tr>
                                <th
                                    class="px-2 sm:px-4 py-2 text-center md:text-left text-nowrap sm:py-3 text-xs sm:text-base">
                                    Type</th>
                                <th
                                    class="px-2 sm:px-4 py-2 text-center md:text-left text-nowrap sm:py-3 text-xs sm:text-base">
                                    Deadline</th>
                                <th
                                    class="px-2 sm:px-4 py-2 text-center md:text-left text-nowrap sm:py-3 text-xs sm:text-base">
                                    Status</th>
                                <th class="px-2 sm:px-4 py-2 text-center text-nowrap sm:py-3 text-xs sm:text-base">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($job_order->jobDrafts as $job_draft)
                                <tr class="project-row border-b text-xs sm:text-sm">
                                    <td class="px-2 sm:px-4 py-2 sm:py-3">{{ $job_draft->type }}</td>
                                    <td class="px-2 sm:px-4 py-2 sm:py-3">{{ $job_draft->date_target }}</td>
                                    <td class="px-2 sm:px-4 py-2 sm:py-3">{{ $job_draft->status }}</td>
                                    <td class="px-2 sm:px-4 py-2 sm:py-3">
                                        <div class="flex items-center justify-center space-x-2">
                                            <div
                                                onclick="window.location.href='{{ url('admin/smm/track/show/draft/' . $job_draft->id) }}'">
                                                <button
                                                    class="px-4 py-2 text-sm text-white bg-green-500 rounded hover:bg-green-600">
                                                    Show
                                                </button>
                                            </div>
                                            <div
                                                onclick="window.location.href='{{ url('admin/smm/track/edit/draft/' . $job_draft->id) }}'">
                                                <button {{ $job_draft->status === 'completed' ? 'disabled' : '' }}
                                                    class="px-4 py-2 text-sm text-white {{ $job_draft->status === 'completed' ? 'bg-gray-500 rounded hover:bg-gray-600' : 'bg-blue-500 rounded hover:bg-blue-600' }}">
                                                    Edit
                                                </button>
                                            </div>
                                            <div onclick="deleteJobOrder({{ $job_order->id }})"
                                                class="px-4 py-2 text-sm text-white bg-red-500 rounded hover:bg-red-600 cursor-pointer">
                                                Delete
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
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
