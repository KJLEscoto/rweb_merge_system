@extends('layouts.application')

@section('title', 'Content Revision')
@section('header', 'Content List Revision')

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
                    <th class="px-6 py-3">Title</th>
                    <th class="px-6 py-3">Designated</th>
                    <th class="px-6 py-3">Deadline</th>
                    <th class="px-6 py-3">Actions</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                @forelse ($job_drafts as $job_draft)
                    <tr class="project-row border-b">
                        <td class="px-6 py-3">{{ $job_draft->jobOrder->title }}</td>
                        @if ($job_draft->type == "content_writer")
                            <td class="px-6 py-3">Content Writer - {{ $job_draft->contentWriter->name }}</td>
                        @else
                            <td class="px-6 py-3">Graphic Designer - {{ $job_draft->graphicDesigner->name }}</td>
                        @endif
                        <td class="px-6 py-3">
                            {{$job_draft->date_target}}
                        </td>


                        <td class="px-6 py-3 border-b flex space-x-3">

                            <div>
                                <a href="{{url('content/revisions/edit/' . $job_draft->id)}}">
                                    <button class="px-4 py-2 text-sm text-white bg-green-500 rounded hover:bg-green-600">
                                        Edit Draft
                                    </button>
                                </a>
                            </div>

                            <div>
                                <a href="{{url('content/revisions/show/' . $job_draft->id)}}">
                                    <button class="px-4 py-2 text-sm text-white bg-blue-500 rounded hover:bg-blue-600">
                                        Show
                                    </button>
                                </a>
                            </div>

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
