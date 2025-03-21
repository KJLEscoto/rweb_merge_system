
<head>

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

        .black-line {
            background-color: #000000;
            height: 2px;
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



    <div id="container-pdf">
        <div class="header">
            <img src="{{ public_path('/Assets/doc_header.png') }}" alt="Header">
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
            <div class="black-line"></div>
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
                        <strong>Prepared By: <br />{{ Auth::user()->name }}</strong><br>
                        <img src="{{ public_path(Auth::user()->signature) }}" alt="Supervisor Signature">
                    </td>
                    <td class="signature">

                        <strong>Noted By: <br />
                            @if ($endorsement->notedBy)
                                {{ $endorsement->notedBy->name }}
                            @endif
                        </strong><br>
                        @if (isset($endorsement->notedBy) && isset($endorsement->notedBy->signature))
                            <img src="{{ public_path($endorsement->notedBy->signature) }}" alt="Supervisor Signature">
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
                            <img src="{{ public_path($endorsement->approvedBy->signature) }}" alt="Top Manager Signature">
                        @endif

                    </td>

                    <td class="signature">

                    </td>
                </tr>
            </table>
        </div>

        <div class="footer">
            <img src="{{ public_path('/Assets/doc_footer.png') }}" alt="Footer">
        </div>
    </div>



