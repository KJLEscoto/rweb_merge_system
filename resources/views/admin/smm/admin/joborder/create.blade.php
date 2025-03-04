@extends('layouts.application')

@section('title', 'Admin')
@section('header', "Create Job Order")

@section('content')
<script src="https://cdn.tailwindcss.com"></script>

<style>
    .custom-shadow {
        box-shadow: 0 2px 4px rgba(0, 0, 0, .3), 0 1px 3px rgba(0, 0, 0, .3);
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

    /* Ensure CKEditor is scrollable with max height */
    .ck-editor__editable {
        max-height: 500px !important;
        overflow-y: auto !important;
    }
</style>

<!-- CKEditor 5 Classic CDN -->
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>

<div class="container mx-auto p-6">
    {{-- Success Message Component --}}
    @if(session('Status'))
        <x-success />
    @endif
    <div class="w-full px-6 py-10 mx-auto rounded-lg custom-shadow bg-white">
        <div>
            <a href="{{ url('/joborder') }}">
                <div class="w-fit px-4 py-1 bg-gray-400 rounded-md text-white custom-shadow custom-hover-shadow">
                    Back
                </div>
            </a>
        </div>
        <form action="{{ url('/joborder/store') }}" method="POST">
            @csrf

            <h1 class="text-xl font-bold mt-4">Create Job Order</h1>
            <div class="grid grid-cols-4 space-y-4">
                <div class="col-span-4 grid grid-cols-2 gap-4 mt-4">
                    <div class="w-full">
                        <p class="text-sm text-gray-600">Title</p>
                        <input type="text" name="title" value="{{ old('title') }}" class="w-full border px-3 py-2  border-gray-200 rounded-lg">
                        @error('title')
                            <p class="text-red-600 text-sm">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="col-span-1 w-full">
                        <p class="text-sm text-gray-600">Client</p>
                        <div class="relative">
                            <input type="text" id="selected-client-name" value="{{ old('client_id') ? ($clients->firstWhere('id', old('client_id'))->name ?? 'Select a Client') : 'Select a Client' }}" class="w-full border px-3 py-2  border-gray-200 rounded-lg cursor-pointer" readonly onclick="openModal()">
                            <input type="hidden" name="client_id" id="selected-client-id" value="{{ old('client_id') }}">
                        </div>
                        @error('client_id')
                            <p class="text-red-600 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="w-full">
                        <p class="text-sm text-gray-600">Content Writer</p>
                        <select name="content_writer_id" class="w-full border-gray-200 rounded-lg text-sm ">
                            <option value="" disabled {{ old('content_writer_id') ? '' : 'selected' }}>Select A Content Writer</option>
                            @foreach ($content_writers as $content_writer)
                                <option value="{{ $content_writer->id }}" {{ old('content_writer_id') == $content_writer->id ? 'selected' : '' }} class="text-black text-sm">{{ $content_writer->name }}</option>
                            @endforeach
                        </select>
                        @error('content_writer_id')
                            <p class="text-red-600 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="w-full">
                        <p class="text-sm text-gray-600">Graphics Designer</p>
                        <select name="graphic_designer_id" class="w-full border-gray-200 rounded-lg text-sm ">
                            <option value="" disabled {{ old('graphic_designer_id') ? '' : 'selected' }}>Select A Graphic Designer</option>
                            @foreach ($graphic_designers as $graphic_designer)
                                <option value="{{ $graphic_designer->id }}" {{ old('graphic_designer_id') == $graphic_designer->id ? 'selected' : '' }}>{{ $graphic_designer->name }}</option>
                            @endforeach
                        </select>
                        @error('graphic_designer_id')
                            <p class="text-red-600 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-span-2 grid grid-cols-2 w-full gap-4 rounded-lg">
                        <div>
                            <p class="text-sm text-gray-600">Date Started</p>
                            <input type="date" name="date_started" value="{{ old('date_started') }}" class="w-full rounded-lg border px-3 py-2  border-gray-200 focus:ring-0">
                            @error('date_started')
                                <p class="text-red-600 text-sm">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Date Deadline</p>
                            <input type="date" name="date_target" value="{{ old('date_target') }}" class="w-full rounded-lg border px-3 py-2  border-gray-200 focus:ring-0">
                            @error('date_target')
                                <p class="text-red-600 text-sm">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="col-span-2 h-fit w-full">
                        <p class="text-sm text-gray-600">Instructions</p>

                        <!-- CKEditor Textarea -->
                        <textarea name="description" id="editor" class="w-full border-gray-200 rounded-lg">{{ old('description') }}</textarea>

                        @error('description')
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
    function openModal() {
        document.getElementById('client-modal').classList.remove('hidden');
    }
    function closeModal() {
        document.getElementById('client-modal').classList.add('hidden');
    }
    function selectClient(clientId, clientName) {
        document.getElementById('selected-client-name').value = clientName;
        document.getElementById('selected-client-id').value = clientId;
        closeModal();
    }

    // Initialize CKEditor with scrollable height
    ClassicEditor
        .create(document.querySelector('#editor'), {
            height: 500, // Set height to 500px
        })
        .then(editor => {
            editor.ui.view.editable.element.style.maxHeight = "500px"; // Limit max height
            editor.ui.view.editable.element.style.overflowY = "auto"; // Enable vertical scrolling
            console.log('CKEditor initialized with max height and scrolling');
        })
        .catch(error => {
            console.error(error);
        });
</script>

@endsection
