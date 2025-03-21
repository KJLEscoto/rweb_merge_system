{{-- @extends('layouts.application') --}}

@section('title', 'Endorsement')
@section('header', 'Endorsement Letter')

{{-- @section('content') --}}
<script src="https://cdn.tailwindcss.com"></script>

<head>
    <title>{{ env('APP_NAME') }} | SMM | Endorsement Letter</title>

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
        .header,
        .footer {
            text-align: center;
        }

        .header img,
        .footer img {
            width: 100%;
            max-height: 150px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        td,
        th {
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

        .section {
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

        #container-pdf {
            padding: 20px
        }
    </style>
</head>

<x-main-layout breadcumb="SMM / Endorsement" page="Endorsement Form">


    <div id="container-pdf">

        <div class="w-full flex justify-between">
            <div class="bg-[#fa7011] text-white rounded-md px-3 py-1 w-fit mb-4">
                <a href="{{ route('admin.smm.endorsement') }}">Back</a>
            </div>
            <div class="flex gap-4">
                <div class="bg-green-600 text-white rounded-md px-3 py-1 w-fit mb-4">
                    <a href="{{ route('endorsement.download', $endorsement->id) }}"><span><i
                                class="fas fa-download"></i></span> Download</a>
                </div>
                @if ($endorsement->status == 'Approved by Supervisor')
                    <div class="bg-[#fa7011] text-white rounded-md px-3 py-1 w-fit mb-4">
                        <a href="{{ route('admin.smm.supervisor.joborder') }}"><span><i
                                    class="fas fa-download"></i></span> Create Draft</a>
                    </div>
                @endif
            </div>
        </div>
        <div class="header">
            <img src="{{ asset('/Assets/doc_header.png') }}" alt="Header">
            <h2>Endorsement Form</h2>
        </div>

        <div class="section">
            <div class="highlight"></div>
            <table>
                <tr>
                    <td><strong>Client Name:</strong><br>{{ $endorsement->client->name }}</td>
                    <td><strong>Client Address:</strong><br>{{ $endorsement->client->address }}</td>
                </tr>
            </table>
            <div class="gray-bar"></div>
            <table>
                <tr>
                    <td><strong>Date Issued:</strong><br>
                        {{ $endorsement->date_issued }}
                    </td>

                    <td><strong>Person In Charge:</strong><br>
                        {{ $endorsement->personInCharge->name }}
                    </td>

                </tr>
            </table>
            <div class="gray-bar"></div>
            <div class="section-remarks">
                <strong>Project Scope:</strong>
                <div
                    class="text-sm text-gray-600 w-full max-h-[500px] overflow-y-auto bg-white border border-gray-300 p-2 rounded">
                    {!! $endorsement->project_scope !!}
                </div>
            </div>
            <div class="gray-bar"></div>
            <div class="section-remarks">
                <strong>Timeline:</strong>
                <div
                    class="text-sm text-gray-600 w-full max-h-[500px] overflow-y-auto bg-white border border-gray-300 p-2 rounded">
                    {!! $endorsement->timeline !!}
                </div>
            </div>
            <div class="section-remarks">
                <strong>Deliverables:</strong>
                <div
                    class="text-sm text-gray-600 w-full max-h-[500px] overflow-y-auto bg-white border border-gray-300 p-2 rounded">
                    {!! $endorsement->deliverables !!}
                </div>
            </div>
            <table>
                <tr>
                    <td class="signature">
                        <strong>Prepared By: <br />{{ $endorsement->preparedBy->name }}</strong><br>
                        <img src="{{ asset($endorsement->preparedBy->signature) }}" alt="Supervisor Signature">
                    </td>
                    <td class="signature">

                        <strong>Noted By: <br />
                            @if ($endorsement->notedBy)
                                {{ $endorsement->notedBy->name }}
                            @endif
                        </strong><br>
                        @if (isset($endorsement->notedBy) && isset($endorsement->notedBy->signature))
                            <img src="{{ asset($endorsement->notedBy->signature) }}" alt="Supervisor Signature">
                        @endif

                    </td>

                </tr>
            </table>
            <table>
                <tr>
                    <td class="signature">

                        <strong>Approved By: <br />
                            @if (isset($endorsement->approvedBy))
                                {{ $endorsement->approvedBy->name }}
                            @endif
                        </strong><br>
                        @if (isset($endorsement->approvedBy->signature))
                            <img src="{{ asset($endorsement->approvedBy->signature) }}" alt="Top Management Signature">
                        @endif

                    </td>

                    <td class="signature">

                    </td>
                </tr>
            </table>
        </div>

        <div class="footer">
            <img src="{{ asset('/Assets/doc_footer.png') }}" alt="Footer">
        </div>
    </div>


</x-main-layout>
{{-- @endsection --}}
