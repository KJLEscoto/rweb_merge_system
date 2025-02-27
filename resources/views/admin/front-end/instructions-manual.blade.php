<head>
    <title>{{ env('APP_NAME') }} | Front-end | Instructions Manual</title>
</head>

@php
    $manuals = [
        [
            'file_name' => 'RWS.031 - INSTRUCTION MANUAL - SILANGAN',
            'date' => 'Feb 26, 2025',
        ],
    ];
@endphp

<x-main-layout breadcumb="Front-end" page="Instructions Manual">
    <main class="h-auto w-full flex flex-col gap-5 px-10 py-10">

        @if ($manuals)
            <div class="rounded bg-white border-l-8 border-[#f56d11] h-auto w-full flex flex-col gap-5 p-5">
                <table class="w-full border-collapse border border-gray-300">
                    <thead>
                        <tr
                            class="*:px-6 *:py-3 *:text-left *:text-sm *:font-semibold *:bg-[#F57D11] *:text-white *:text-nowrap">
                            <th>File Name</th>
                            <th class="!text-center">Date</th>
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
                            </tr>
                        @endforeach
                    </tbody>
                </table>
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
