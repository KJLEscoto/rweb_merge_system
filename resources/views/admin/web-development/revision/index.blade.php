<head>
    <title>{{ env('APP_NAME') }} | Web Development | Revision</title>
</head>

@php
    $revisions = [
        [
            'title' => 'RWS024 REVISION CHECKLIST - COMMMODITIX',
            'designated' => 'Content Writer - Supervisor',
            'status' => 'Done',
        ],
        [
            'title' => 'RWS025 REVISION REPORT - PROJECT X',
            'designated' => 'Content Writer - Supervisor',
            'status' => 'Pending',
        ],
        [
            'title' => 'RWS026 REVISION DOCUMENT - SITE AUDIT',
            'designated' => 'Content Writer - Supervisor',
            'status' => 'Delayed',
        ],
    ];
@endphp

<x-main-layout breadcumb="Web Development" page="Revision">
    <main class="h-auto w-full flex flex-col gap-5">

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
                            <a href="{{ route('admin.front-end.revision-checklist.create') }}"
                                class="text-sm text-white bg-[#f56d11] px-5 py-2 rounded font-medium text-nowrap">Add
                                Revision Checklist</a>
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
                                <th class="!text-center">Status</th>
                                <th class="!text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($web_project_channels as $web_project_channel)
                                <tr class="border hover:bg-gray-100 *:px-6 *:py-4 *:text-nowrap *:text-sm">
                                    <td class="flex items-center gap-2">
                                        {{-- <div class="p-2 rounded bg-[#F57D11] text-white">
                                            <span class="mingcute--file-fill w-6 h-6"></span>
                                        </div> --}}
                                        {{ $web_project_channel->web_project->title }}
                                    </td>
                                    <td>{{ $web_project_channel->type }}</td>
                                    <td class="flex justify-center items-center">
                                        @php
                                            $statusClasses = [
                                                'Done' => 'text-green-700 bg-green-300',
                                                'Pending' => 'text-yellow-700 bg-yellow-300',
                                                'Delayed' => 'text-red-700 bg-red-300',
                                                'Revision' => 'text-red-700 bg-red-300',
                                            ];
                                        @endphp
                                        <p
                                            class="select-none rounded-full px-5 text-xs py-1 font-semibold w-fit {{ $statusClasses[$web_project_channel->status] }}">
                                            {{ $web_project_channel->status }}
                                        </p>
                                    </td>
                                    <td>
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('admin.web.revision.show', $web_project_channel->id) }}"
                                                class="approve-btn px-2 py-1 font-medium bg-green-500 text-white rounded flex items-center justify-center gap-1 hover:scale-105 transition">
                                                <span class="basil--eye-solid !w-4 !h-4"></span>
                                                <p>View</p>
                                            </a>
                                            {{-- <a href="#"
                                                class="approve-btn px-2 py-1 font-medium bg-blue-500 text-white rounded flex items-center justify-center gap-1">
                                                <span class="fluent--clipboard-text-edit-48-filled w-4 h-4"></span>
                                                <p>Edit</p>
                                            </a> --}}
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
                <h1 class="text-4xl font-semibold italic">No Revisions Yet</h1>
                <img draggable="false" src="{{ asset('image/revisions_empty.png') }}" class="w-auto h-80">
            </div>
        @endif
    </main>
</x-main-layout>
