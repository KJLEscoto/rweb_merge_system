@extends('layouts.application')

@section('title', 'Job Order')
@section('header', 'List of Job Orders')

@section('content')
<script src="https://cdn.tailwindcss.com"></script>

<style>
    .custom-shadow {
        box-shadow: 0 4px 6px rgba(0, 0, 0, .3), 0 1px 3px rgba(0, 0, 0, .3);
    }
    .custom-hover-shadow:hover {
        box-shadow: 0 10px 15px rgba(0, 0, 0, 0), 0 4px 6px rgba(0, 0, 0, 0);
        transition: box-shadow 0.3s ease;
    }
    .custom-focus-ring:focus {
        outline: none;
        box-shadow: 0 0 0 1px #fa7011;
        transition: box-shadow 0.3s ease;
    }
</style>

<div class="container mx-auto p-6">
    <div class="overflow-x-auto overflow-y-auto bg-white shadow-md rounded-lg h-[500px]" style="max-height: 500px;">
        {{-- Success Message Component --}}
        @if(session('Status'))
            <x-success />
        @endif

        <table class="w-full table-fixed text-left border-collapse min-w-[700px]" id="projectTable">
            <thead class="sticky top-0 bg-[#fa7011] text-white">
                <tr>
                    <th class="w-[30%] px-4 py-3">Title</th>
                    <th class="w-[35%] px-4 py-3">Designated</th>
                    <th class="w-[20%] text-center px-4 py-3">Status</th>
                    <th class="w-[15%] px-4 py-3 text-center">Actions</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                @forelse ($job_drafts as $job_draft)
                    <tr class="project-row border-b">
                        <td class="w-[30%] px-4 py-3 truncate">{{ $job_draft->jobOrder->title }}</td>
                        <td class="w-[35%] px-4 py-3 truncate">
                            @if ($job_draft->type == "content_writer")
                                Content Writer - {{ $job_draft->contentWriter->name }}
                            @else
                                Graphic Designer - {{ $job_draft->graphicDesigner->name }}
                            @endif
                        </td>
                        <td class="w-[20%] text-center px-4 py-3 text-white">
                            <p class="w-full px-2 py-1 rounded-lg text-wrap
                                {{ $job_draft->status == 'completed' ? 'bg-green-400' : 
                                ($job_draft->status == 'Revision' ? 'bg-red-600' : 'bg-[#fa6e117e]') }} ">
                                {{ ucfirst($job_draft->status) }}
                            </p>
                        </td>
                        <td class="w-[15%] px-4 py-3 text-center">
                            <a href="{{ url('track/' . $job_draft->id) }}">
                                <button class="px-4 py-2 text-sm text-white bg-green-500 rounded hover:bg-green-600">
                                    Show
                                </button>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr class="h-[400px]">
                        <td colspan="4" class="px-6 py-3">
                            <div class="flex h-full items-center flex-col justify-center space-y-4">
                                <i class="far fa-grin-beam-sweat text-7xl" style="color: #fa7011;"></i>
                                <p class="text-[#fa7011]">No Data Found</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>



@endsection
