<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Order</title>
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

        .letter {
            padding: 2rem;
            height: 10rem
        }
    </style>
</head>

<body>
    <div class="header">
        <img src="{{ public_path('/Assets/doc_header.png') }}" alt="Header">
        <h2>Acknowledgement & Acceptance</h2>
    </div>

    <div class="section">
        <div class="highlight"></div>
        <div class="letter">I, <strong>{{ $job_draft->client->name }}</strong>, hereby acknowledge and accept the
            project, namely, <strong>{{ $job_draft->jobOrder->title }}</strong>.</div>
        <div class="gray-bar"></div>
        <table>
            <tr>
                <td><strong>Date:</strong><br>
                    {{ $job_draft->date_started }}
                </td>

                <td class="signature">
                    <strong>Signature:</strong><br>
                    @if (file_exists(public_path($job_draft->client_signature)))
                        <img src="{{ public_path($job_draft->client_signature) }}" alt="Client Signature">
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
