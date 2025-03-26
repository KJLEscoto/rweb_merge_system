<head>
    <title>{{ env('APP_NAME') }} | Web Development | Direct Job Order</title>
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.8.1/flowbite.min.js"></script>

<x-main-layout breadcumb="Web Development" page="Direct Job Order">
    @if (session('success'))
        <x-modal.flash-msg msg="success" />
    @elseif ($errors->has('invalid'))
        <x-modal.flash-msg msg="invalid" />
    @elseif (session('invalid'))
        <x-modal.flash-msg msg="invalid" />
    @endif

    <div class="space-y-5">
        @if ($web_projects)
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
                            <a href="{{ route('admin.web.direct-job-order.create') }}"
                                class="bg-[#f56d11] hover:scale-105 transition text-white px-3 py-2 text-sm rounded font-semibold shadow-md w-fit flex items-center gap-1">
                                <span class="ic--round-add w-5 h-5"></span>
                                Create Direct Job Order</a>
                        </section>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse border border-gray-300">
                        <thead>
                            <tr
                                class="*:px-6 *:py-3 *:text-left *:text-sm *:font-semibold *:bg-[#F57D11] *:text-white *:text-nowrap">
                                <th>Title</th>
                                <th>Client</th>
                                <th>Issuer</th>
                                <th class="!text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($web_projects as $direct)
                                                <tr class="border hover:bg-gray-100 *:px-6 *:py-4 *:text-nowrap *:text-sm">
                                                    <td class="flex items-center gap-2">
                                                        {{ $direct->title }}
                                                    </td>
                                                    <td>{{ $direct->client->name }}</td>
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
                                                            class="select-none rounded-full px-5 text-xs py-1 font-semibold w-fit {{ $statusClasses[$direct['status']] ?? 'text-gray-700 bg-gray-300' }}">
                                                            {{ $direct->client->name }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div class="flex items-center justify-center gap-2">
                                                            <a href="{{ route('admin.web.direct-job-order.showProjectChannels', $direct['id']) }}"
                                                                class="approve-btn px-2 py-1 font-medium bg-green-500 text-white rounded flex items-center justify-center gap-1">
                                                                <span class="basil--eye-solid !w-4 !h-4"></span>
                                                                <p>View</p>
                                                            </a>
                                                            <a href="{{ route('admin.web.direct-job-order.edit', $direct['id']) }}"
                                                                class="approve-btn px-2 py-1 font-medium bg-blue-500 text-white rounded flex items-center justify-center gap-1">
                                                                <span class="fluent--clipboard-text-edit-48-filled w-4 h-4"></span>
                                                                <p>Edit</p>
                                                            </a>
                                                            <button data-modal-target="delete-modal-{{ $direct['id'] }}"
                                                                data-modal-toggle="delete-modal-{{ $direct['id'] }}"
                                                                class="approve-btn px-2 py-1 font-medium bg-red-500 text-white rounded flex items-center justify-center gap-1"
                                                                type="button">
                                                                <span class="fluent--clipboard-text-edit-48-filled w-4 h-4"></span>
                                                                <p>Delete</p>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <div id="delete-modal-{{ $direct['id'] }}" tabindex="-1"
                                                    class="fixed top-0 left-0 right-0 z-50 w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] md:h-full justify-center items-center hidden">
                                                    <div class="relative w-full h-full max-w-md md:h-auto">
                                                        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                                            <button type="button"
                                                                class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-800 dark:hover:text-white"
                                                                data-modal-hide="delete-modal-{{ $direct['id'] }}">
                                                                <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                                                                    xmlns="http://www.w3.org/2000/svg">
                                                                    <path fill-rule="evenodd"
                                                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                                        clip-rule="evenodd"></path>
                                                                </svg>
                                                                <span class="sr-only">Close modal</span>
                                                            </button>
                                                            <div class="p-6 text-center">
                                                                <svg aria-hidden="true"
                                                                    class="mx-auto mb-4 text-gray-400 w-14 h-14 dark:text-gray-200" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24"
                                                                    xmlns="http://www.w3.org/2000/svg">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                        d="M12 10v9m-1-9h2c.552 0 1-.448 1-1v-3c0-.552-.448-1-1-1h-2c-.552 0-1 .448-1 1v3c0 .552.448 1 1 1z">
                                                                    </path>
                                                                </svg>
                                                                <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Are you
                                                                    sure you want to delete this project?</h3>
                                                                <form action="{{ route('admin.web.direct-job-order.delete', $direct['id']) }}"
                                                                    method="POST" class="inline">
                                                                    @csrf
                                                                    @method('POST')
                                                                    <button type="submit"
                                                                        class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2">
                                                                        Yes, I'm sure
                                                                    </button>
                                                                </form>
                                                                <button data-modal-hide="delete-modal-{{ $direct['id'] }}" type="button"
                                                                    class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">No,
                                                                    cancel</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
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
                        <a href="{{ route('admin.web.direct-job-order.create') }}"
                            class="bg-[#f56d11] hover:scale-105 transition text-white px-3 py-2 text-sm rounded font-semibold shadow-md w-fit flex items-center gap-1">
                            <span class="ic--round-add w-5 h-5"></span>
                            Create Direct Job Order</a>
                    </section>
                </div>
            </div>
        @endif
    </div>
</x-main-layout>