<head>
    <title>{{ env('APP_NAME') }} | Web Development | Instructions Manual</title>
</head>

@php
    $manuals = [
        [
            'file_name' => 'RWS.031 - INSTRUCTION MANUAL - SILANGAN',
            'date' => 'Feb 26, 2025',
        ],
    ];
@endphp

<x-main-layout breadcumb="Web Development" page="Instructions Manual">
    <main class="h-auto w-full flex flex-col gap-5">

        @if ($manuals)
            <div class="rounded bg-white border-l-8 border-[#f56d11] h-auto w-full flex flex-col gap-5 p-5">
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse border border-gray-300">
                        <thead>
                            <tr
                                class="*:px-6 *:py-3 *:text-left *:text-sm *:font-semibold *:bg-[#F57D11] *:text-white *:text-nowrap">
                                <th>File Name</th>
                                <th>Date</th>
                                <th class="!text-center"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($manuals as $manual)
                                <tr class="border hover:bg-gray-100 *:px-6 *:py-4 *:text-nowrap *:text-sm">
                                    <td class="flex items-center gap-2">
                                        <div class="p-2 rounded bg-[#F57D11] text-white">
                                            <span class="mingcute--file-fill w-6 h-6"></span>
                                        </div>
                                        {{ $manual['file_name'] }}
                                    </td>
                                    <td class="!text-center">{{ $manual['date'] }}</td>
                                    {{-- <td class="flex justify-center items-center">
                                        @php
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
                                        </span>
                                    </td> --}}
                                    <td>
                                        <button
                                            class="px-3 py-2 bg-[#1f2835] text-white rounded hover:scale-105 transition-all duration-150 ease-in flex items-center gap-1">
                                            <span class="tdesign--file-download-filled w-4 h-4"></span>
                                            <p class="text-xs font-medium">Download</p>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <p>pagination here.</p>
        @else
            <div class="w-full h-full flex flex-col gap-10 items-center justify-center select-none">
                <h1 class="text-4xl font-semibold italic">No Updates Yet</h1>
                <img draggable="false" src="{{ asset('image/manuals_empty.png') }}" class="w-auto h-80">
            </div>
        @endif
    </main>
</x-main-layout>
