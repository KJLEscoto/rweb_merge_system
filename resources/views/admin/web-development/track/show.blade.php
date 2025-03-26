<head>
    <title>{{ env('APP_NAME') }} | Web Development | View Track</title>

    {{--
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script> --}}
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

<x-main-layout breadcumb="Web Development / Track" page="View Track">
    <div class="bg-white p-6 rounded border-l-8 border-[#F57D11] shadow-md flex flex-col gap-5">

        <div class="flex items-center gap-3">
            <a href="{{ route('admin.web.track') }}"
                class="border hover:border-[#f56d11] text-[#f56d11] transition flex items-center gap-1 px-3 py-2 text-sm rounded font-semibold w-fit">
                <span class="eva--arrow-back-fill w-4 h-4"></span>
                Back
            </a>
            {{-- <button type="submit"
                class="bg-[#f56d11] hover:scale-105 transition text-white px-3 py-2 text-sm rounded font-semibold shadow-md w-fit">
                Submit
            </button> --}}
        </div>

        <div class="space-y-5 lg:p-10 p-7 border rounded">
            <section class="grid lg:grid-cols-3 grid-cols-1 lg:gap-2 gap-5">
                <div class="space-y-1 w-full">
                    <h1 class="font-bold text-xs">Title</h1>
                    <p>{{ $web_project->title }}</p>
                </div>
                <div class="space-y-1 w-full">
                    <h1 class="font-bold text-xs">Client</h1>
                    <p>{{ $web_project->client->name }}</p>
                </div>
                <div class="space-y-1 w-full">
                    <h1 class="font-bold text-xs">Issuer</h1>
                    <p>{{ $web_project->issuer->name }}</p>
                </div>
            </section>
            <div class="space-y-1 w-full">
                <h1 class="font-bold text-xs">Description</h1>
                <p class="p-3 border rounded">{{ $web_project->instructions }}</p>
            </div>
            <hr class="border border-[#f56d11]">
            <div class="space-y-1 w-full">
                <h1 class="font-bold text-xs">Drafts</h1>
                @if ($web_project->web_project_channels)
                            <div class="overflow-x-auto">
                                <table class="w-full border-collapse border border-gray-300">
                                    <thead>
                                        <tr
                                            class="*:px-6 *:py-3 *:text-left *:text-sm *:font-semibold *:bg-[#F57D11] *:text-white *:text-nowrap">
                                            <th>Type</th>
                                            <th>Deadline</th>
                                            <th class="!text-center">Status</th>
                                            <th class="!text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($web_project->web_project_channels as $web_project_channel)
                                                                <tr class="border hover:bg-gray-100 *:px-6 *:py-4 *:text-nowrap *:text-sm">
                                                                    <td class="flex items-center gap-2">
                                                                        {{-- <div class="p-2 rounded bg-[#F57D11] text-white">
                                                                            <span class="mingcute--file-fill w-6 h-6"></span>
                                                                        </div> --}}
                                                                        {{ $web_project_channel->type }}
                                                                    </td>
                                                                    <td>{{ $web_project_channel->date_targeted }}</td>
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
                                                                            class="select-none rounded-full px-5 text-xs py-1 font-semibold w-fit {{ $statusClasses[$web_project_channel->status] ?? 'text-gray-700 bg-gray-300' }}">
                                                                            {{ $web_project_channel->status }}
                                                                        </span>
                                                                    </td>
                                                                    <td>
                                                                        <div class="flex items-center justify-center gap-2">
                                                                            <a href="{{ route('admin.web.track.draft.show', [1, $web_project_channel->id]) }}"
                                                                                class="hover:scale-105 transition px-2 py-1 font-medium bg-green-500 text-white rounded flex items-center justify-center gap-1">
                                                                                <span class="basil--eye-solid !w-4 !h-4"></span>
                                                                                <p>View</p>
                                                                            </a>
                                                                            <a href="{{ route('admin.web.track.draft.edit', [$web_project_channel->id, $web_project_channel->id, $web_project_channel->user_id]) }}"
                                                                                class="hover:scale-105 transition px-2 py-1 font-medium bg-blue-500 text-white rounded flex items-center justify-center gap-1">
                                                                                <span class="fluent--clipboard-text-edit-48-filled w-4 h-4"></span>
                                                                                <p>Edit</p>
                                                                            </a>
                                                                            <a href="#"
                                                                                class="hover:scale-105 transition px-2 py-1 font-medium bg-red-500 text-white rounded flex items-center justify-center gap-1">
                                                                                <span class="material-symbols-light--delete w-4 h-4"></span>
                                                                                <p>Delete</p>
                                                                            </a>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                @else
                    <div class="w-full h-auto flex flex-col gap-10 items-center justify-center select-none">
                        <h1 class="text-4xl font-semibold italic">No Updates Yet</h1>
                        <img draggable="false" src="{{ asset('image/revisions_empty.png') }}" class="w-auto h-80">
                    </div>
                @endif
            </div>
        </div>

    </div>

    <script>
        // Initialize CKEditor
        ClassicEditor
            .create(document.querySelector('#editor'))
            .then(editor => {
                console.log('CKEditor initialized');
            })
            .catch(error => {
                console.error(error);
            });
    </script>
</x-main-layout>