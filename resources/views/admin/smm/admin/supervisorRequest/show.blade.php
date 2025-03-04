@extends('layouts.application')

@section('title', 'Admin')
@section('header', 'Show Job Order')

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
    .header, .footer {
        text-align: center;
    }
    .header img, .footer img {
        width: 100%;
        max-height: 150px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
    }
    td, th {
        border: 1px solid black;
        padding: 10px;
        text-align: left;
        vertical-align: top;
    }
    .highlight {
        background-color: #fa7011;
        height: 40px;
        text-align: center;
    }
    .gray-bar {
        background-color: #6b7280;
        height: 40px;
        text-align: center;
    }
    .section{
        border: 1px solid black;
    }
    .section-title {
        font-weight: bold;
        background-color: #6b7280;
        color: white;
        text-align: center;
    }
    .signature img {
        width: 100px;
        height: auto;
    }
    .section-remarks {
        padding: 10px;
    }
    #container-pdf{
        padding: 20px
    }
</style>

<!-- CKEditor 5 Classic -->
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>

<div class="container mx-auto p-6">
    <div class="w-full px-6 py-10 mx-auto rounded-lg custom-shadow bg-white">
        <div class="flex items-center justify-between">
            <a href="{{ url('/operation/requests') }}">
                <div class="w-fit px-4 py-1 bg-gray-400 rounded-md text-white custom-shadow custom-hover-shadow">
                    Back
                </div>
            </a>
        
            @if ($supervisor_request->status !== "Waiting for Operation Approval")
                <a href="{{ url('/operation/request/create/' . $supervisor_request->id) }}">
                    <div class="w-fit px-4 py-1 bg-[#fa7011] rounded-md text-white custom-shadow custom-hover-shadow">
                        Create Draft
                    </div>
                </a>
            @endif

        </div>
        <div id="container-pdf">
            <div class="header">
                <img src="{{ asset('/Assets/doc_header.png') }}" alt="Header">
                <h2>Supervisor Job Order Form</h2>
            </div>
        
            <div class="section">
                <div class="highlight"></div>

                <div class="gray-bar"></div>
                <table>
                    <tr>
                        <td><strong>Date Issued:</strong><br>
                            {{ $supervisor_request->created_at ? \Carbon\Carbon::parse($supervisor_request->created_at)->format('Y-m-d') : 'N/A' }}
                        </td>
                        
                        <td><strong>Target Finished Date:</strong><br>
                            {{ $supervisor_request->deadline }}
                        </td>
                                  
                    </tr>
                </table>
                <table>
                    <tr>
                        <td><strong>Issued by:</strong><br>{{ $supervisor_request->issuer->name }}</td>
                        <td>
                            <strong>Work Performed by:</strong><br>
                            {{$supervisor_request->assignee->name}}
                        </td>
                    </tr>
                </table>
                <div class="section-remarks">
                    <strong>Description:</strong>
                    <div class="text-sm text-gray-600 w-full max-h-[500px] overflow-y-auto bg-white border border-gray-300 p-2 rounded">
                        {!! $supervisor_request->description !!}
                    </div>
                </div>
                <div class="gray-bar"></div>
                <table>
                    <tr>
                        <td class="signature">
                            <strong>Assigned Personnel Signature:                     
                            {{$supervisor_request->assignee->name}}
                        </strong><br>
                        @if ($supervisor_request->status === "Waiting for Operation Approval")
                            Waiting for Approval
                        @else
                            <img src="{{ asset($supervisor_request->assignee->signature) }}" alt="Admin Signature">
                        @endif
                            
                        </td>
                        <td class="signature">
                            <strong>Supervisor Signature: {{$supervisor_request->issuer->name}}</strong><br>
                            <img src="{{ asset($supervisor_request->issuer->signature) }}" alt="Supervisor Signature">
                        </td>
                    </tr>
                </table>
            </div>
        
            <div class="footer">
                <img src="{{ asset('/Assets/doc_footer.png') }}" alt="Footer">
            </div>
        </div>
    </div>
</div>

<script>
    // Initialize CKEditor in Read-Only mode
    ClassicEditor
        .create(document.querySelector('#editor'), {
            toolbar: false, // Hide toolbar since it's read-only
            readOnly: true // Disable editing
        })
        .then(editor => {
            console.log('CKEditor (Read-Only) initialized');
        })
        .catch(error => {
            console.error(error);
        });
</script>

@endsection
