@extends('layouts.application')

@section('title', 'Page Title')
@section('header', "Create Task")

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
        box-shadow: 0 0 0 1px #545454;
        transition: box-shadow 0.3s ease;
    }
</style>

<!-- CKEditor 5 CDN -->
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>

<div class="container mx-auto p-6">
    <div class="w-full px-6 py-10 mx-auto rounded-lg bg-white custom-shadow">
        <div>
            <a href="{{ url('/supervisor/task') }}">
                <div class="w-fit px-4 py-1 bg-gray-400 rounded-md text-white custom-shadow custom-hover-shadow">
                    Back
                </div>
            </a>
        </div>
        <form action="{{ url('supervisor/task/store/' . $job_draft->id) }}" method="POST">
            @csrf
            @method('PUT')
            <h1 class="text-xl font-bold mt-4">Create Draft</h1>
            <div class="grid grid-cols-4 space-y-4">
                <div class="col-span-4 grid grid-cols-2 gap-4 mt-4">
                    <div class="col-span-4 grid grid-cols-4">
                        <div class="col-span-4 lg:col-span-1 w-full">
                            <p class="text-sm text-gray-600 border-[#fa7011] border-b-2 w-fit">Title</p>
                            <p class="text-xl">{{ $job_draft->jobOrder->title }}</p>
                        </div>
                        <div class="col-span-4 lg:col-span-1 w-full">
                            <p class="text-sm text-gray-600 border-[#fa7011] border-b-2 w-fit">Client</p>
                            <p class="text-xl">{{ $job_draft->client->name }}</p>
                        </div>
                        <div class="col-span-4 lg:col-span-1 w-full">
                            <p class="text-sm text-gray-600 border-[#fa7011] border-b-2 w-fit">Date Started</p>
                            <p class="text-xl">{{ \Carbon\Carbon::parse($job_draft->date_started)->format('Y-m-d') }}</p>
                        </div>
                        <div class="col-span-4 lg:col-span-1 w-full">
                            <p class="text-sm text-gray-600 border-[#fa7011] border-b-2 w-fit">Date Target</p>
                            <p class="text-xl">{{ \Carbon\Carbon::parse($job_draft->date_target)->format('Y-m-d') }}</p>
                        </div>
                    </div>
                    <div class="col-span-4 h-fit w-full">
                        <p class="text-sm text-gray-600 max-h-96 overflow-y-auto">Instructions</p>
                        <div class="text-sm text-gray-600 w-full max-h-[500px] overflow-y-auto bg-white border border-gray-300 p-2 rounded">
                            {!! $job_draft->jobOrder->description !!}
                        </div>

                    </div>

                    @if ($job_draft->type == 'graphic_designer')
                        <div class="col-span-4 h-fit w-full">
                            <p class="text-sm text-gray-600 max-h-96 overflow-y-auto">Content Writer Draft</p>
                            <div class="text-sm text-gray-600 w-full max-h-[500px] overflow-y-auto bg-white border border-gray-300 p-2 rounded">
                                {!! $job_draft->parentDraft->draft !!}
                            </div>

                        </div>
                    @endif


                    <hr class="col-span-4 border border-gray-200" />
                    <div class="col-span-4 h-fit w-full">
                        <p class="text-sm text-gray-600">Draft</p>
                        <!-- CKEditor 5 textarea -->
                        <textarea name="draft" id="editor" class="w-full border-gray-200 rounded-lg custom-shadow custom-focus-ring min-h-[300px] max-h-[500px] overflow-y-auto"></textarea>
                        @error('draft')
                            <p class="text-red-600 text-sm">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <button type="submit" class="col-span-1 text-center py-4 w-full bg-[#fa7011] mt-10 rounded-lg custom-shadow custom-hover-shadow text-white font-bold">
                    Submit
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        ClassicEditor
            .create(document.querySelector('#editor'))
            .then(editor => {
                console.log('CKEditor 5 initialized!', editor);

                // Load existing draft content from old input if available, otherwise use the job draft's content
                editor.setData(`{!! addslashes(old('draft', $job_draft->draft ?? '')) !!}`);

                // Before form submission, update the textarea with the editor's data
                document.querySelector("form").addEventListener("submit", function () {
                    document.querySelector("#editor").value = editor.getData();
                });

            })
            .catch(error => {
                console.error('Error initializing CKEditor 5:', error);
            });
    });
</script>

@endsection

