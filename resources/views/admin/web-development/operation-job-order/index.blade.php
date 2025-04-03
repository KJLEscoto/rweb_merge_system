<head>
    <title>{{ env('APP_NAME') }} | Web Development | Operation Job Order</title>
</head>

@php
    $direct_job_order = [
        [
            'id' => 1,
            'title' => 'Operation Job Order',
            'designated' => 'Admin',
            'status' => [
                'type' => 'pending',
                'message' => 'Waiting for Content Writer Approval',
            ],
        ],
        [
            'id' => 2,
            'title' => 'Operation Job Order',
            'designated' => 'Admin',
            'status' => [
                'type' => 'review',
                'message' => 'Pending Review',
            ],
        ],
        [
            'id' => 3,
            'title' => 'Operation Job Order',
            'designated' => 'Admin',
            'status' => [
                'type' => 'approved',
                'message' => 'Approved',
            ],
        ],
    ];

@endphp

<x-main-layout breadcumb="Web Development" page="Operation Job Order">
    <div class="space-y-5">
        @if ($web_requests)
            <div class="rounded bg-white border-l-8 border-[#f56d11] h-auto w-full flex flex-col gap-5 p-5">

                <div class="flex lg:flex-row flex-col-reverse items-end justify-between w-full gap-5">
                    {{-- Search Input --}}
                    <section class="lg:!w-1/2 w-full">
                        <div class="w-full relative flex items-center">
                            <span class="meteor-icons--search w-5 h-5 absolute left-3 text-gray-500"></span>
                            <input type="text" name="search" id="search"
                                class="pl-10 py-2 pr-4 rounded-lg border border-gray-300 w-full outline-none focus:ring-2 focus:ring-[#f56d11]"
                                placeholder="Search...">
                        </div>
                    </section>

                    <div class="w-auto">
                        <section class="w-fit hover:scale-105 transition">
                            <a href="{{ route('admin.web.operation-job-order.create') }}"
                                class="bg-[#f56d11] hover:scale-105 transition text-white px-3 py-2 text-sm rounded font-semibold shadow-md w-fit flex items-center gap-1">
                                <span class="ic--round-add w-5 h-5"></span>
                                Create New Job Order</a>
                        </section>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse border border-gray-300">
                        <thead>
                            <tr
                                class="*:px-6 *:py-3 *:text-left *:text-sm *:font-semibold *:bg-[#F57D11] *:text-white *:text-nowrap">
                                <th>Title</th>
                                <th>Issued To</th>
                                <th class="!text-center">Status</th>
                                <th class="!text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($web_requests as $web_request)
                                <tr class="text-center border hover:bg-gray-100 *:px-6 *:py-4 *:text-nowrap *:text-sm">
                                    <td class="">
                                        {{-- <div class="p-2 rounded bg-[#F57D11] text-white">
                                            <span class="mingcute--file-fill w-6 h-6"></span>
                                        </div> --}}
                                        {{ $web_request->title }}
                                    </td>
                                    <td>{{ $web_request->issued_to->name }}</td>
                                    <td class="">
                                        {{ $web_request->deadline }}
                                        {{-- @php
                                        $statusClasses = [
                                        'approved' => 'text-green-700 bg-green-300',
                                        'pending' => 'text-yellow-700 bg-yellow-300',
                                        'review' => 'text-blue-700 bg-blue-300',
                                        'delayed' => 'text-red-700 bg-red-300',
                                        ];
                                        @endphp

                                        <span
                                            class="select-none rounded-full px-5 text-xs py-1 font-semibold w-fit {{ $statusClasses[$direct['status']['type']] ?? 'text-gray-700 bg-gray-300' }}">
                                            {{ $direct['status']['message'] }}
                                        </span> --}}
                                    </td>

                                    <td>
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('admin.web.operation-job-order.show', $web_request->id) }}"
                                                class="approve-btn px-2 py-1 font-medium bg-green-500 text-white rounded flex items-center justify-center gap-1">
                                                <span class="basil--eye-solid !w-4 !h-4"></span>
                                                <p>View</p>
                                            </a>
                                            <a href="{{ route('admin.web.operation-job-order.edit', $web_request->id) }}"
                                                class="approve-btn px-2 py-1 font-medium bg-blue-500 text-white rounded flex items-center justify-center gap-1">
                                                <span class="fluent--clipboard-text-edit-48-filled w-4 h-4"></span>
                                                <p>Edit</p>
                                            </a>
                                            <form class="pt-3"
                                                action="{{ route('admin.web.operation-job-order.destroy', $web_request->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this job order?');">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                    class="delete-btn px-2 py-1 font-medium bg-red-500 text-white rounded flex items-center justify-center gap-1">
                                                    <span class="material-symbols-light--delete w-4 h-4"></span>
                                                    <p>Delete</p>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <p>pagination here.</p>
        @else
            <div class="w-full h-auto flex flex-col gap-10 items-center justify-center select-none">
                <h1 class="text-4xl font-semibold italic">No Updates Yet</h1>
                <img draggable="false" src="{{ asset('image/revisions_empty.png') }}" class="w-auto h-80">
                <div class="w-auto">
                    <section class="w-fit hover:scale-105 transition">
                        <a href="{{ route('admin.web.operation-job-order.create') }}"
                            class="bg-[#f56d11] hover:scale-105 transition text-white px-3 py-2 text-sm rounded font-semibold shadow-md w-fit flex items-center gap-1">
                            <span class="ic--round-add w-5 h-5"></span>
                            Create New Job Order</a>
                    </section>
                </div>
            </div>
        @endif
    </div>
</x-main-layout>