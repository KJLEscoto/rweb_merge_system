@extends('layouts.application')

@section('title', 'Job Order')
@section('header', 'Job Order')

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

    canvas {
        border: 2px solid #000;
        background-color: #fff;
    }
</style>

<!-- Include CKEditor 5 CDN -->
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>

<div class="mx-auto max-w-screen-2xl">
    <div class="h-full mx-auto max-w-screen-xl">
        {{-- Middle Part --}}
        <div class="">
            <div class="">
                <div class="h-auto gap-8 m-10 p-10 relative bg-white"
                     style="box-shadow: 0 20px 30px -5px rgba(0, 0, 0, 0.3); border-radius: 8px;">
                     
                    <!-- Back Button -->
                    <div class="rounded-md text-white flex justify-end mb-10">
                        <a href="{{ url('/operation/show/' . $job_draft->id) }}"
                           class="w-fit px-4 py-1 bg-[#fa7011] rounded hover:bg-[#d95f0a] transition duration-200">
                            Back
                        </a>
                    </div>
                    
                    <!-- Responsive Grid for Details -->
                    <div class="grid grid-cols-1 lg:grid-cols-5 gap-4">
                        <!-- Project Name -->
                        <div class="lg:col-span-1 font-semibold">Project Name:</div>
                        <div class="lg:col-span-4">{{ $job_draft->jobOrder->title }}</div>

                        <!-- Designation -->
                        <div class="lg:col-span-1 font-semibold">Designation:</div>
                        <div class="lg:col-span-4">{{ Str::title(str_replace('_', ' ', $job_draft->type)) }}</div>

                        <!-- Google Drive Link -->
                        <div class="lg:col-span-1 font-semibold">Google Drive Link:</div>
                        <div class="lg:col-span-4">
                            <div id="draftContent" class="max-h-[300px] rounded-lg overflow-y-auto break-all">
                                {!! $job_draft->draft !!}
                            </div>
                        </div>
                    </div>

                    <!-- Decline Form Section -->
                    <div class="mt-10">
                        <form action="{{ url('/operation/decline/' . $job_draft->id) }}" method="POST">
                            @csrf
                            
                            <!-- CKEditor 5 for Decline Reason -->
                            <label for="summary" class="block font-semibold">Decline Reason:</label>
                            <textarea class="w-full border p-2 rounded-md" name="summary" id="summaryEditor">{{ old('summary') }}</textarea>
                            
                            @error('summary')
                                <p class="text-red-600 text-sm">{{ $message }}</p>
                            @enderror

                            <button type="submit"
                                    class="px-4 py-2 mt-4 text-sm text-white bg-red-500 rounded hover:bg-red-600 disabled:opacity-50 disabled:cursor-not-allowed"
                                    id="declineBtn">
                                Decline
                            </button>
                        </form>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Initialize CKEditor 5 -->
<script>
    ClassicEditor
        .create(document.querySelector('#summaryEditor'))
        .catch(error => {
            console.error(error);
        });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const element = document.getElementById('draftContent');
        if (element.scrollHeight > element.clientHeight || element.scrollWidth > element.clientWidth) {
            element.classList.add('border', 'border-gray-200', 'p-4');
        }
    });
</script>
@endsection
