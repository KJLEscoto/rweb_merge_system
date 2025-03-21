<head>
    <title>{{ env('APP_NAME') }} | Web Development | Approvals</title>
</head>

@php
    $direct_job_order = [
        [
            'id' => 1,
            'title' => 'Direct Job Order',
            'designated' => 'Content Writer - Supervisor',
            'status' => [
                'type' => 'pending',
                'message' => 'Waiting for Content Writer Approval',
            ],
        ],
        [
            'id' => 2,
            'title' => 'Direct Job Order',
            'designated' => 'Graphic Designer - Team Lead',
            'status' => [
                'type' => 'review',
                'message' => 'Pending Review',
            ],
        ],
        [
            'id' => 3,
            'title' => 'Direct Job Order',
            'designated' => 'Web Developer - Junior',
            'status' => [
                'type' => 'approved',
                'message' => 'Approved',
            ],
        ],
    ];

@endphp

<x-main-layout breadcumb="Web Development" page="Approvals">
    <div class="space-y-5">
        @if ($web_project_channels)
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

                    {{-- <div class="w-auto">
                        <section class="w-fit hover:scale-105 transition">
                            <a href="{{ route('admin.web.direct-job-order.create') }}"
                                class="bg-[#f56d11] hover:scale-105 transition text-white px-3 py-2 text-sm rounded font-semibold shadow-md w-fit flex items-center gap-1">
                                <span class="ic--round-add w-5 h-5"></span>
                                Create Direct Job Order</a>
                        </section>
                    </div> --}}
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse border border-gray-300">
                        <thead>
                            <tr
                                class="*:px-6 *:py-3 *:text-left *:text-sm *:font-semibold *:bg-[#F57D11] *:text-white *:text-nowrap">
                                <th>Title</th>
                                <th>Designated</th>
                                <th>Deadline</th>
                                <th class="!text-center">Status</th>
                                <th class="!text-center">Actions</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($web_project_channels as $direct)
                                <tr class="border hover:bg-gray-100 *:px-6 *:py-4 *:text-nowrap *:text-sm">
                                    <td class="flex items-center gap-2">
                                        {{-- <div class="p-2 rounded bg-[#F57D11] text-white">
                                            <span class="mingcute--file-fill w-6 h-6"></span>
                                        </div> --}}
                                        {{ $direct->web_project->title }}
                                    </td>
                                    <td>{{ $direct->users->name . ' - ' . $direct->type }}</td>
                                    <td>{{ $direct->date_targeted }}</td>
                                    <td class="flex justify-center items-center">
                                        @php
                                            $statusClasses = [
                                                'approved' => 'text-green-700 bg-green-300',
                                                'pending' => 'text-yellow-700 bg-yellow-300',
                                                'review' => 'text-blue-700 bg-blue-300',
                                                'delayed' => 'text-red-700 bg-red-300',
                                            ];
                                        @endphp

                                        <span
                                            class="select-none rounded-full px-5 text-xs py-1 font-semibold w-fit {{ $statusClasses[$direct->status] ?? 'text-gray-700 bg-gray-300' }}">
                                            {{ $direct->status }}
                                        </span>
                                    </td>

                                    <td>
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('admin.web.approvals.show', $direct->id) }}"
                                                class="approve-btn px-2 py-1 font-medium bg-green-500 text-white rounded flex items-center justify-center gap-1">
                                                <span class="basil--eye-solid !w-4 !h-4"></span>
                                                <p>View</p>
                                            </a>
                                            <a href="#"
                                                class="approve-btn px-2 py-1 font-medium bg-blue-500 text-white rounded flex items-center justify-center gap-1">
                                                <span class="fluent--clipboard-text-edit-48-filled w-4 h-4"></span>
                                                <p>Edit</p>
                                            </a>
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
            </div>
        @endif
    </div>
</x-main-layout>
