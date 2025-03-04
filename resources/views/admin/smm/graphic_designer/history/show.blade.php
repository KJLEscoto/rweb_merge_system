<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Order</title>
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
</head>
<body>
    <div class="header">
        <img src="{{ public_path('/Assets/doc_header.png') }}" alt="Header">
        <h2>Operation Job Order Form</h2>
    </div>

    <div class="section">
        <div class="highlight"></div>
        <table>
            <tr>
                <td><strong>Client Name:</strong><br>{{ $job_draft->client->name }}</td>
                <td><strong>Client Address:</strong><br>{{ $job_draft->client->address }}</td>
            </tr>
        </table>
        <div class="gray-bar"></div>
        <table>
            <tr>
                <td><strong>Date Issued:</strong><br>
                    {{ $job_draft->date_started ? \Carbon\Carbon::parse($job_draft->date_started)->format('Y-m-d') : 'N/A' }}
                </td>
                
                <td><strong>Target Finished Date:</strong><br>
                    {{ $job_draft->date_started ? \Carbon\Carbon::parse($job_draft->date_started)->addDays($job_draft->days_to_add)->format('Y-m-d') : 'N/A' }}
                </td>
                          
            </tr>
        </table>
        <table>
            <tr>
                <td><strong>Issued by:</strong><br>{{ $job_draft->jobOrder->issuer->name }}</td>
                <td>
                    <strong>Work Performed by:</strong><br>
                    @if ($job_draft->type == "content_writer")
                        {{ $job_draft->contentWriter->name }}
                    @else
                        {{ $job_draft->graphicDesigner->name }}
                    @endif
                </td>
            </tr>
        </table>
        <div class="section-remarks">
            <strong>Description:</strong>
            <div class="text-sm text-gray-600 w-full max-h-[500px] overflow-y-auto bg-white border border-gray-300 p-2 rounded">
                {!! $job_draft->jobOrder->description !!}
            </div>
        </div>
        <div class="gray-bar"></div>
        <div class=""><strong> Complete Information </strong></div>
        <table>
            <tr>
                <td><strong>Date Completed:</strong><br>
                    {{ $job_draft->date_completed ? \Carbon\Carbon::parse($job_draft->date_completed)->format('Y-m-d') : 'N/A' }}
                </td>
                
                <td><strong>Time Required:</strong><br>
                    @if($job_draft->date_started && $job_draft->date_completed)
                        {{ \Carbon\Carbon::parse($job_draft->date_started)->diffInDays(\Carbon\Carbon::parse($job_draft->date_completed)) }} days
                    @else
                        N/A
                    @endif
                </td>                             
            </tr>
        </table>
        <div class="section-remarks">
            <strong>Remarks:</strong>
            <div class="text-sm text-gray-600 w-full max-h-[500px] overflow-y-auto bg-white border border-gray-300 p-2 rounded">
                {!! $job_draft->feedback !!}
            </div>
        </div>
        <table>
            <tr>
                <td class="signature">
                    <strong>Assigned Personnel Signature:</strong><br>
                    @if(file_exists(public_path($job_draft->signature_worker)))
                        <img src="{{ public_path($job_draft->signature_worker) }}" alt="Worker Signature">
                    @else
                        <p>Signature not found in directory</p>
                    @endif
                </td>
                <td class="signature">
                    <strong>Supervisor Signature:</strong><br>
                    @if(file_exists(public_path($job_draft->signature_supervisor)))
                        <img src="{{ public_path($job_draft->signature_supervisor) }}" alt="Supervisor Signature">
                    @else
                        <p>Signature not found in directory</p>
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <img src="{{ public_path('/Assets/doc_footer.png') }}" alt="Footer">
    </div>
</body>
</html>
