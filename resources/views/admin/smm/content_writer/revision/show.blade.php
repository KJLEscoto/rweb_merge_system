@extends('layouts.application')

@section('title', 'Content Revision')
@section('header', 'Show Content Revision')

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

<!-- Quill CSS -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

<!-- Quill JS -->
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>

<div class="container mx-auto p-6">
    <div class="w-full px-6 py-10 mx-auto rounded-lg custom-shadow bg-white">
        <div>
            <a href="{{ url('/content') }}">
                <div class="w-fit px-4 py-1 bg-gray-400 rounded-md text-white custom-shadow custom-hover-shadow">
                    Back
                </div>
            </a>
        </div>
        <h1 class="text-xl font-bold mt-4">Show</h1>
        <div class="mt-4 grid grid-cols-4">
            <div class="col-span-4 flex  mb-10">
                <div class="w-full">
                    <p class="text-sm font-bold text-gray-600">Title</p>
                    <p class="border-b-2 border-[#fa7011] w-fit">{{ $job_draft->jobOrder->title }}</p>
                </div>
                <div class="w-full">
                    <p class="text-sm font-bold text-gray-600">Client</p>
                    <p class="border-b-2 border-[#fa7011] w-fit">{{$job_draft->client->name}}</p>
                </div>
                <div class="w-full">
                    <p class="text-sm font-bold text-gray-600">Date Started</p>
                    <p class="border-b-2 border-[#fa7011] w-fit">{{$job_draft->date_started}}</p>
                </div>
                <div class="w-full ">
                    <p class="text-sm font-bold text-gray-600">Deadline</p>
                    <p class="border-b-2 border-[#fa7011] w-fit">{{$job_draft->date_target}}</p>
                </div>
            </div>
            <div class="col-span-4 h-fit">
                <p class="text-sm font-bold text-gray-600">Instructions</p>
                <!-- Quill Editor -->
                <div id="quill-editor" class="w-full border-gray-200 rounded-lg custom-shadow custom-focus-ring min-h-fit max-h-[500px] overflow-y-auto">{!! json_encode($job_draft->jobOrder->description) !!}</div>

                <!-- Hidden textarea to store Quill content -->
                <textarea name="draft" id="description" class="hidden min-h-[300px] max-h-[500px] overflow-y-auto"></textarea>
            </div>
            <div class="mt-10 flex gap-8 ">
                <div class="flex items-center justify-center flex-col">
                    <img class="size-14" src="{{asset('/Assets/user-profile-profilepage.png')}}" alt="">
                    {{-- <img src="{{ asset('/Assets/' . $job_draft->content_writer->image) }}" alt="Content Writer Image"> --}}
                    <h1 class="text-sm">{{$job_draft->graphicDesigner->name}}</h1>
                    <h1 class="text-[10px] border-b-2 border-[#fa7011] w-fit">Graphic Designer</h1>
                </div>
                <div class="flex items-center justify-center flex-col">
                    <img class="size-14" src="{{asset('/Assets/user-profile-profilepage.png')}}" alt="">
                    {{-- <img src="{{ asset('/Assets/' . $job_draft->content_writer->image) }}" alt="Content Writer Image"> --}}
                    <h1 class="text-sm">{{$job_draft->contentWriter->name}}</h1>
                    <h1 class="text-[10px] border-b-2 border-[#fa7011] w-fit">Content Writer</h1>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    var quill = new Quill('#quill-editor', {
        theme: 'snow', // Snow theme with toolbar
        placeholder: 'Write something...',
        readOnly: true, // Set to read-only
        modules: {
            toolbar: false,
        },
        scrollingContainer: '#quill-editor' // Add scrolling behavior
    });

    // Set existing content in the Quill editor
    quill.root.innerHTML = {!! json_encode($job_draft->jobOrder->description) !!};

    // Sync Quill content with the hidden textarea on form submission
    document.querySelector('form').onsubmit = function () {
        document.querySelector('#description').value = quill.root.innerHTML;
    };

    </script>
@endsection
