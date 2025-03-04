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
            <a href="{{ url('/revision') }}">
                <div class="w-fit px-4 py-1 bg-gray-400 rounded-md text-white custom-shadow custom-hover-shadow">
                    Back
                </div>
            </a>
        </div>

        <div class="mt-10">
            <h1 class="font-bold text-2xl">
                Past Revisions
            </h1>
        </div>

        <div class="mt-10 grid grid-cols-3 gap-8">
            @foreach ($revisions as $revision)
            {{-- {{$revision}} --}}
                <div class="rounded-lg shadow-lg {{$revision->status == 'complete' ? 'bg-green-500' : 'bg-gray-400'}} col-span-1 text-white p-10 mb-10">
                    <div class="mb-10">
                        <p>Revision By:</p>
                        <p>{{$revision->declinedBy->name}}</p>
                        <p>{{$revision->revision_date}}</p>
                    </div>

                    <div class="flex items-center justify-center cursor-pointer">
                        <div class="bg-[#fa7011] w-fit px-2 py-1 rounded-md" onclick="showDetails('{{ $revision->declinedBy->name }}', '{{ $revision->revision_date }}', `{!! $revision->summary !!}`, `{!! $revision->last_draft !!}`)">
                            Show Details
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Modal -->
        <div id="modal" class="fixed inset-0 hidden bg-black bg-opacity-50 z-50 flex justify-center items-center">
            <div class="bg-white rounded-lg w-[600px] p-8">
                <div class="flex justify-between items-center">
                    <h2 class="text-xl font-bold">Revision Details</h2>
                    <button onclick="hideDetails()" class="text-gray-500 hover:text-gray-700">&times;</button>
                </div>
                <div class="mt-4">
                    <p><strong>Revision By:</strong> <span id="modalRevisionBy"></span></p>
                    <p><strong>Revision Date:</strong> <span id="modalRevisionDate"></span></p>
                    
                    <div class="max-h-[200px] overflow-y-auto border rounded p-2 mt-4">
                        <p><strong>Summary:</strong></p>
                        <div id="modalSummary"></div>
                    </div>
                    <div class="max-h-[200px] overflow-y-auto border rounded p-2 mt-4">
                        <p><strong>Last Draft:</strong></p>
                        <div id="modalLastDraft"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function showDetails(revisionBy, revisionDate, summary, last_draft) {
        document.getElementById('modalRevisionBy').textContent = revisionBy;
        document.getElementById('modalRevisionDate').textContent = revisionDate;
        document.getElementById('modalSummary').innerHTML = summary;
        document.getElementById('modalLastDraft').innerHTML = last_draft;

        document.getElementById('modal').classList.remove('hidden');
    }

    function hideDetails() {
        document.getElementById('modal').classList.add('hidden');
    }
</script>

@endsection
