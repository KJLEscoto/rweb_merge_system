<head>
    <title>{{ env('APP_NAME') }} | Front-end | Revision Checklist</title>
</head>

@php
    $revisions = [
        [
            'file_name' => 'RWS024 REVISION CHECKLIST - COMMMODITIX',
            'date' => 'Feb 26, 2025',
            'status' => 'Done',
        ],
        [
            'file_name' => 'RWS025 REVISION REPORT - PROJECT X',
            'date' => 'Feb 27, 2025',
            'status' => 'Pending',
        ],
        [
            'file_name' => 'RWS026 REVISION DOCUMENT - SITE AUDIT',
            'date' => 'Feb 28, 2025',
            'status' => 'Delayed',
        ],
    ];
@endphp

<x-main-layout breadcumb="Front-end" page="Revision Checklist">
    <main class="h-auto w-full flex flex-col gap-5 px-10 py-10">

        @if ($revisions)
            <div class="rounded bg-white border-l-8 border-[#f56d11] h-auto w-full flex flex-col gap-5 p-5">

                <div class="flex items-end justify-between w-full gap-5">
                    {{-- Search Input --}}
                    <section class="w-full">
                        <div class="w-1/2 relative flex items-center">
                            <span class="meteor-icons--search w-5 h-5 absolute left-3 text-gray-500"></span>
                            <input type="text" name="search" id="search"
                                class="pl-10 py-2 pr-4 rounded-lg border border-gray-300 w-full outline-none focus:ring-2 focus:ring-[#f56d11]"
                                placeholder="Search...">
                        </div>
                    </section>

                    <div class="w-auto">
                        <section class="w-fit hover:scale-105 transition">
                            <a href="{{ route('admin.front-end.revision-checklist.create') }}"
                                class="text-sm text-white bg-[#f56d11] px-5 py-2 rounded font-medium text-nowrap">Add
                                Revision Checklist</a>
                        </section>
                    </div>
                </div>

                <table class="w-full border-collapse border border-gray-300">
                    <thead>
                        <tr
                            class="*:px-6 *:py-3 *:text-left *:text-sm *:font-semibold *:bg-[#F57D11] *:text-white *:text-nowrap">
                            <th>File Name</th>
                            <th>Date</th>
                            <th class="!text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($revisions as $revision)
                            <tr class="border hover:bg-gray-100 *:px-6 *:py-4 *:text-nowrap *:text-sm">
                                <td class="flex items-center gap-2">
                                    <div class="p-2 rounded bg-[#F57D11] text-white">
                                        <span class="mingcute--file-fill w-6 h-6"></span>
                                    </div>
                                    {{ $revision['file_name'] }}
                                </td>
                                <td>{{ $revision['date'] }}</td>
                                <td class="flex justify-center items-center">
                                    @php
                                        $statusClasses = [
                                            'Done' => 'text-green-700 bg-green-300',
                                            'Pending' => 'text-yellow-700 bg-yellow-300',
                                            'Delayed' => 'text-red-700 bg-red-300',
                                        ];
                                    @endphp
                                    <p
                                        class="select-none rounded-full px-5 text-xs py-1 font-semibold w-fit {{ $statusClasses[$revision['status']] }}">
                                        {{ $revision['status'] }}
                                    </p>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <p>pagination here.</p>
        @else
            <div class="w-full h-full flex flex-col gap-10 items-center justify-center select-none">
                <h1 class="text-4xl font-semibold italic">No Updates Yet</h1>
                <img draggable="false" src="{{ asset('image/revisions_empty.png') }}" class="w-auto h-80">
            </div>
        @endif
    </main>
</x-main-layout>
