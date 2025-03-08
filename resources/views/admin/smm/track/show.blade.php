{{-- @extends('layouts.application') --}}

@section('title', 'Operation')
@section('header', 'Operation Job Order')

{{-- @section('content') --}}
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

<!-- CKEditor 5 Classic -->
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<style>

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

<x-main-layout breadcumb="SMM" page="Show Track">
    <div class="px-10 pt-10 space-y-10">
        <div class="">
            <div>
                <a href="{{ url('/admin/smm/track') }}">
                    <div class="w-fit px-4 py-1 bg-gray-400 rounded-md text-white custom-shadow custom-hover-shadow">
                        Back
                    </div>
                </a>
            </div>
            <div class="grid grid-cols-3">
                <div>
                    <p>Title</p>
                    <p>{{$job_order->title}}</p>
                </div>
                <div>
                    <p>Client</p>
                    <p>{{$job_order->latestJobDraft->client->name}}</p>
                </div>
                <div>
                    <p>Issuer</p>
                    {{$job_order->issuer->name}}
                </div>
            </div>
            <div>
                <p>Description</p>
                <div class="border border-gray-400 px-5 py-5">{!! $job_order->description !!}</div>
            </div>
        </div>

        <div>
            <p class="font-bold">Drafts</p>
            <table>
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Deadline</th>
                        <th>Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ( $job_order->jobDrafts as $job_draft )
                        <tr>
                            <td>{{$job_draft->type}}</td>
                            <td>{{$job_draft->date_target}}</td>
                            <td>{{$job_draft->status}}</td>
                            <td class="">
                                <div class="flex items-center justify-center space-x-2">
                                    <div onclick="window.location.href='{{ url('admin/smm/track/show/draft/' . $job_draft->id) }}'">
                                        <button class="px-4 py-2 text-sm text-white bg-green-500 rounded hover:bg-green-600">
                                            Show
                                        </button>
                                    </div>
                                    <div onclick="window.location.href='{{ url('admin/smm/track/edit/draft/' . $job_draft->id) }}'">
                                        <button {{$job_draft->status === 'completed' ? "disabled" : ""}} class="px-4 py-2 text-sm text-white {{$job_draft->status === 'completed' ? "bg-gray-500 rounded hover:bg-gray-600" : "bg-blue-500 rounded hover:bg-blue-600"}}">
                                            Edit
                                        </button>    
                                    </div>
                                    <div onclick="deleteJobOrder({{ $job_order->id }})" class="px-4 py-2 text-sm text-white bg-red-500 rounded hover:bg-red-600 cursor-pointer">
                                        Delete
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-main-layout>



<script>
    function deleteJobOrder(jobOrderId) {
        if (confirm('Are you sure you want to delete this item?')) {
            // Create a form element dynamically
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/admin/smm/track/' + jobOrderId;
            
            // CSRF token input
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = '{{ csrf_token() }}';
            form.appendChild(csrfInput);
            
            // Method override for DELETE
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            form.appendChild(methodInput);
            
            // Append the form to the document body and submit it
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>
    
{{-- @endsection --}}
