<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statement of Account</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        th, td {
            padding: 8px;
            text-align: left;
        }

        /* Remove borders for cleaner layout */
        .no-border, .no-border td {
            border: none;
        }

        /* Table for main statement section */
        .bordered th, .bordered td {
            border: 1px solid #000;
        }

        .center {
            text-align: center;
        }

        .bold {
            font-weight: bold;
        }

        .right {
            text-align: right;
        }

        .total {
            background-color: #a83232;
            color: white;
            font-weight: bold;
        }

        .small-text {
            font-size: 13px;
        }

        .soa-table {
            background-color: #f5c6a5;
        }
    </style>
</head>

<body>

    <!-- Header -->
    <table class="no-border">
        <tr>
            <td class="center"><img src="{{ public_path('/Assets/doc_header.png') }}" width="100%"></td>
        </tr>
    </table>

    <!-- Billing Details -->
    <table class="no-border">
        <tr>
            <td><span class="bold">Bill From:</span><br>
                R Web Solutions Corp.<br>
                {{ $soa->bill_from }}
            </td>
            <td><span class="bold">Bill To:</span><br>
                {{ $soa->client_name }}<br>
                {{ $soa->address }}
            </td>
        </tr>
    </table>

    <!-- Title -->
    <table class="no-border">
        <tr>
            <td class="center bold" colspan="4" style="font-size: 1.5rem;">Statement of Account</td>
        </tr>
    </table>

    <!-- Dates -->
    <table class="bordered">
        <tr>
            <td class="bold">Billing Date:</td>
            <td>{{ $soa->billing_date }}</td>
            <td class="bold">Due Date:</td>
            <td>{{ $soa->due_date }}</td>
        </tr>
    </table>

    <!-- Statement Table -->
    <table class="bordered">
        <tr class="soa-table">
            <th>Date</th>
            <th>Reference</th>
            <th>Qty</th>
            <th>Particulars</th>
            <th>Charges</th>
            <th>Credits</th>
        </tr>

        @foreach($soa->particulars as $particular)
        <tr>
            <td>{{ $particular->date }}</td>
            <td>{{ $particular->reference }}</td>
            <td>{{ $particular->quantity }}</td>
            <td>{{ $particular->particulars }}</td>
            <td>{{ number_format($particular->charges, 2) }}</td>
            <td></td>
        </tr>
        @endforeach

        <tr class="total">
            <td colspan="4" class="right">TOTAL AMOUNT DUE</td>
            <td>{{ number_format(collect($soa->particulars)->sum('charges'), 2) }}</td>
            <td></td>
        </tr>
    </table>

    <!-- Note -->
    <table class="no-border">
        <tr>
            <td class="small-text">
                This is your account as of the date shown. If your books do not agree with ours, please advise us immediately.<br><br>
                Any payments not made within the specified Due Date on the invoices shall incur a LATE PAYMENT FEE equivalent to
                one and a half percent (1.5%) on outstanding balances due each month or a fraction thereof until paid.
            </td>
        </tr>
    </table>

    <!-- Payment Methods -->
    <table class="no-border">
        <tr>
            <th colspan="2">Payment Methods</th>
        </tr>
        <tr>
            <td class="bold">Bank: BPI</td>
            <td class="bold">GCASH</td>
        </tr>
        <tr>
            <td>
                Account Name: R Web Solutions Corp.<br>
                Account Number: 8091-0082-63
            </td>
            <td>
                Account Name: Richard Dean Clemente<br>
                Account Number: 09176392247
            </td>
        </tr>
    </table>

    <!-- Signatures -->
    <table class="no-border">
        <tr>
            <th>Prepared by:</th>
            <th>Approved by:</th>
            <th>Received by:</th>
        </tr>
        <tr class="center">
            <td>@if (!$soa->preparedBy?->signature)
                <p>Not signed yet</p>
                @else
                <img src="{{ public_path($soa->preparedBy->signature) }}" width="120px">
                
            @endif
            </td>
            <td>
                @if ($soa->approvedBy?->signature)
                <img src="{{ public_path($soa->approvedBy->signature) }}" width="120px">
                @else
                <p>Not signed yet</p>
                @endif
            </td>
            <td>____________________</td>
        </tr>
        <tr class="center">
            <td>{{ $soa->preparedBy?->name }}<br><i>Accounting Staff</i></td>
            <td>{{ $soa->approvedBy?->name}}<br><i>General Manager</i></td>
            <td>Received by:</td>
        </tr>
    </table>

    <!-- Footer -->
    <table class="no-border">
        <tr>
            <td class="center"><img src="{{ public_path('/Assets/doc_footer.png') }}" width="100%"></td>
        </tr>
    </table>

</body>

</html>
