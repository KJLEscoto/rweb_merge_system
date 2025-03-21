@props(['id' => '', 'yearlyTotals' => []])

{{-- confirmation --}}
<div id="{{ $id }}" class="pd-overlay hidden">
    <div
        class="w-full h-full fixed top-0 left-0 z-[100] flex items-center justify-center  overflow-x-hidden overflow-y-auto bg-black bg-opacity-70">
        <div
            class="w-full flex items-center justify-center p-10 transition-all ease-out opacity-0 sm:mx-auto modal-open:opacity-100 modal-open:duration-500">
            <div class="lg:!w-2/3 md:w-1/2 w-full flex flex-col lg:!p-10 p-5 gap-5 bg-white rounded-2xl">
                <div class="flex w-full items-center justify-between gap-3 text-nowrap">
                    <x-page-title title="DTR Summary" titleClass="lg:!text-xl text-sm" />

                    <div>
                        <x-button primary label="Close" button closeModal="{{ $id }}"
                            className="close-modal-button lg:!px-7 px-4" />
                    </div>
                </div>

                <div class=" bg-white shadow-md rounded-lg p-4 w-full border">
                    {{-- <div class="flex items-start space-x-6 border-b pb-4">
                        <img src="{{ $profile_image ?? 'https://via.placeholder.com/100' }}" alt="Profile Image"
                            class="w-24 h-24 rounded-full border">
                        <div class="flex-1 grid grid-cols-3 gap-x-4 gap-y-1 text-sm">
                            <span class="font-semibold">Full Name</span> <span class="col-span-2">:
                                {{ $user->firstname }}
                                {{ $user->lastname }}</span>
                            <span class="font-semibold">Email</span> <span class="col-span-2">:
                                {{ $user->email }}</span>
                            <span class="font-semibold">Phone</span> <span class="col-span-2">:
                                {{ $user->phone }}</span>
                            <span class="font-semibold">School ID</span> <span class="col-span-2">:
                                {{ $user->student_no }}</span>

                            <span class="font-semibold">Address</span> <span class="col-span-2">:
                                {{ $user->address }}</span>
                            <span class="font-semibold">Gender</span> <span class="col-span-2">:
                                {{ $user->gender }}</span>
                            <span class="font-semibold">Date Started</span> <span class="col-span-2">:
                                {{ $user->starting_date }}</span>

                            <span class="font-semibold">E-Name</span> <span class="col-span-2">:
                                {{ $user->emergency_contact_fullname }}</span>
                            <span class="font-semibold">E-Contact</span> <span class="col-span-2">:
                                {{ $user->emergency_contact_number }}</span>
                            <span class="font-semibold">E-Address</span> <span class="col-span-2">:
                                {{ $user->emergency_contact_address }}</span>
                        </div>
                    </div> --}}

                    <div>
                        <div class="overflow-x-auto">
                            @php $totalHoursOverall = 0; @endphp
                            @foreach ($yearlyTotals as $yearData)
                                @php $totalHoursOverall += $yearData['total_hours']; @endphp

                                <div class="mb-8">
                                    <h4
                                        class="md:text-base text-sm font-semibold bg-gray-100 p-3 rounded flex justify-between flex-wrap gap-2 items-center">
                                        <p><span class="!text-sm">Year</span> {{ $yearData['year'] }}</p>
                                        <p class="float-right">
                                            @if (floor((int) filter_var($yearData['total_hours'], FILTER_SANITIZE_NUMBER_INT) / 60) > 0)
                                                {{ floor((int) filter_var($yearData['total_hours'], FILTER_SANITIZE_NUMBER_INT) / 60) }}
                                                <span class="text-xs"> hrs </span>
                                            @endif

                                            {{ round((int) filter_var($yearData['total_hours'], FILTER_SANITIZE_NUMBER_INT) % 60) }}
                                            @if (round((int) filter_var($yearData['total_hours'], FILTER_SANITIZE_NUMBER_INT) % 60) <= 1)
                                                <span class="!text-xs">min</span>
                                            @else
                                                <span class="!text-xs">min</span>
                                            @endif
                                        </p>
                                    </h4>

                                    <table class="w-full text-left text-sm mt-2">
                                        <thead class="bg-gray-50">
                                            <tr>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200">
                                            @foreach ($yearData['months'] as $monthData)
                                                <tr class="hover:bg-gray-50">
                                                    <td
                                                        class="px-4 py-3 flex justify-between w-full gap-2 flex-wrap items-center">
                                                        <p class="font-medium lg:!text-sm text-xs flex self-end">
                                                            {{ $monthData['month_name'] }}
                                                        </p>
                                                        <p class="float-right">
                                                            @php
                                                                $totalMinutes = (int) filter_var(
                                                                    $monthData['total_hours'],
                                                                    FILTER_SANITIZE_NUMBER_INT,
                                                                );
                                                                $hours = floor($totalMinutes / 60);
                                                                $minutes = $totalMinutes % 60;
                                                            @endphp

                                                            @if ($hours > 0)
                                                                <span class="font-medium">
                                                                    {{ $hours }}</span> hrs
                                                            @elseif ($hours == 1)
                                                                <span class="font-medium">
                                                                    {{ $hours }}</span> hr
                                                            @endif

                                                            <span class="font-medium">
                                                                {{ $minutes }}
                                                            </span>
                                                            @if ($minutes <= 1)
                                                                <span class="!text-xs">min</span>
                                                            @else
                                                                <span class="!text-xs">mins</span>
                                                            @endif
                                                        </p>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>

                                    </table>
                                </div>
                            @endforeach

                            <div class="p-4 bg-orange-500 rounded">
                                <div
                                    class="md:!text-lg text-base font-semibold items-center flex flex-wrap gap-2 justify-between text-white">
                                    <span class="!text-sm">Overall Hours</span>
                                    <span>
                                        @if (floor((int) filter_var($totalHoursOverall, FILTER_SANITIZE_NUMBER_INT) / 60) > 0)
                                            {{ floor((int) filter_var($totalHoursOverall, FILTER_SANITIZE_NUMBER_INT) / 60) }}
                                            <span class="text-sm">hrs</span>
                                        @endif
                                        {{ round((int) filter_var($totalHoursOverall, FILTER_SANITIZE_NUMBER_INT) % 60) }}
                                        @if (round((int) filter_var($totalHoursOverall, FILTER_SANITIZE_NUMBER_INT) % 60) <= 1)
                                            <span class="text-sm">min</span>
                                        @else
                                            <span class="text-sm">mins</span>
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
