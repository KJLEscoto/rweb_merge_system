@extends('layouts.application')

@section('title', 'Revision')
@section('header', 'Revision')

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
        <table class="w-full text-left border-collapse" id="projectTable">
            <thead class="sticky top-0 bg-[#fa7011] text-white">
                <tr>
                    <th class="w-[25%] px-6 py-3">Title</th>
                    <th class="w-[35%] px-6 py-3">Designated</th>
                    <th class="w-[20%] px-6 py-3 text-center">Status</th>
                    <th class="w-[20%] px-6 py-3">Actions</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                @forelse ($job_drafts as $job_draft)
                    <tr class="project-row border-b">
                        <td class="w-[25%] px-6 py-3 truncate">{{ $job_draft->jobOrder->title }}</td>
                        <td class="w-[35%] px-4 py-3 truncate">
                            @if ($job_draft->type == "content_writer")
                                Content Writer - {{ $job_draft->contentWriter->name }}
                            @else
                                Graphic Designer - {{ $job_draft->graphicDesigner->name }}
                            @endif
                        </td>
                        <td class="w-[20%] px-6 py-3 text-white text-center">
                            <p class="w-full px-2 py-1 rounded-lg text-nowrap
                                {{ $job_draft->status == 'completed' ? 'bg-green-400' : 
                                ($job_draft->status == 'Revision' ? 'bg-red-600' : 'bg-[#fa6e117e]') }} ">
                                {{ ucfirst($job_draft->status) }}
                            </p>
                        </td>


                        <td class="w-[20%] px-4 py-3">
                                <a href="{{ $job_draft->status === 'Revision' ? url('revision/edit/' . $job_draft->id) : '#' }}">
                                    <button class="px-4 py-2 text-sm text-white rounded {{ $job_draft->status === 'Revision' ? 'bg-orange-500 hover:bg-orange-600' : 'bg-gray-400 cursor-not-allowed pointer-events-none' }}">
                                        Revise
                                    </button>
                                </a>
                                                            

                
                                <a href="{{ url('revision/show/' . $job_draft->id) }}">
                                    <button class="px-4 py-2 text-sm text-white bg-gray-600 rounded hover:bg-gray-600 inline-block text-center">
                                        Show
                                    </button>
                                </a>
                                        
                        </td>
                    </tr>
                @empty
                    <tr class="h-[400px]">
                        <td colspan="5" class="px-6 py-3">
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
