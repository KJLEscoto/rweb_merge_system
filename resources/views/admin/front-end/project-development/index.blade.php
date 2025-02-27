<head>
    <title>{{ env('APP_NAME') }} | Front-end | Project Development</title>
</head>

<x-main-layout breadcumb="Front-end" page="Project Development">
    <main class="h-auto w-full flex flex-col gap-5 px-10 py-10">
        <div class="rounded bg-white border-l-8 border-[#f56d11] h-auto w-full flex flex-col gap-5 p-5">

            {{-- Search Input --}}
            <section class="w-full">
                <div class="w-1/2 relative flex items-center">
                    <span class="meteor-icons--search w-5 h-5 absolute left-3 text-gray-500"></span>
                    <input type="text" name="search" id="search"
                        class="pl-10 py-2 pr-4 rounded-lg border border-gray-300 w-full outline-none focus:ring-2 focus:ring-[#f56d11]"
                        placeholder="Search...">
                </div>
            </section>

            {{-- Table --}}
            <table class="w-full border-collapse border border-gray-300">
                <thead>
                    <tr
                        class="*:px-6 *:py-3 *:text-left *:text-sm *:font-semibold *:bg-[#F57D11] *:text-white *:text-nowrap">
                        <th>File Name</th>
                        <th>Submission Date</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Dummy Data --}}
                    <tr class="border hover:bg-gray-100 *:px-6 *:py-4 *:text-nowrap *:text-sm">
                        <td>Front-End Approval</td>
                        <td>Feb 26, 2025</td>
                        <td>
                            <p
                                class="select-none text-green-700 rounded-full px-5 text-xs py-1 bg-green-300 font-semibold w-fit">
                                Signed
                            </p>
                        </td>
                        <td class="flex items-center gap-2">
                            <a href="{{ route('admin.front-end.project-development.show') }}"
                                class="px-3 py-2 bg-[#F57D11] text-white rounded hover:scale-105 transition-all duration-150 ease-in flex items-center gap-1">
                                <span class="mdi--file-check w-4 h-4"></span>
                                <p class="text-xs font-medium">Approval Form</p>
                            </a>
                            <button
                                class="px-3 py-2 bg-[#1f2835] text-white rounded hover:scale-105 transition-all duration-150 ease-in flex items-center gap-1">
                                <span class="tdesign--file-download-filled w-4 h-4"></span>
                                <p class="text-xs font-medium">Download</p>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <p>pagination here.</p>
    </main>
</x-main-layout>
