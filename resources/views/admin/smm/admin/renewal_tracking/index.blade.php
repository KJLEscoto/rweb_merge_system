<head>
    <title>{{ env('APP_NAME') }} | SMM | Renewal Tracking Show</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .table-container {
            overflow: auto;
            /* Enable both horizontal and vertical scrolling */
            max-height: 80vh;
            /* Adjust as needed */
            max-width: 100%;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 14px;
            min-width: 1200px;
            /* Ensure table doesn't shrink too much */
        }

        th,
        td {
            border: 1px solid #e0e0e0;
            padding: 12px 15px;
            text-align: left;
            white-space: nowrap;
            /* Prevent text wrapping */
        }

        th {
            background-color: #374151;
            /* Darker background for headers */
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        tr:nth-child(even) {
            background-color: #f9fafb;
            /* Light background for even rows */
        }

        tr:hover {
            background-color: #f3f4f6;
            /* Subtle hover effect */
        }

        .status-green {
            background-color: #a7f3d0;
            /* Light green */
            color: #166534;
            /* Darker green text */
            padding: 4px 8px;
            border-radius: 4px;
            font-weight: 500;
        }

        .status-red {
            background-color: #fecaca;
            /* Light red */
            color: #b91c1c;
            /* Darker red text */
            padding: 4px 8px;
            border-radius: 4px;
            font-weight: 500;
        }

        select {
            padding: 8px 10px;
            border: 1px solid #d1d5db;
            border-radius: 4px;
            background-color: white;
            font-size: 14px;
            min-width: 150px;
        }

        select:focus {
            outline: none;
            border-color: #6b7280;
            box-shadow: 0 0 0 2px rgba(107, 114, 128, 0.2);
        }

        /* Improved styling for specific columns */
        td:nth-child(2),
        td:nth-child(5),
        td:nth-child(8),
        td:nth-child(9),
        td:nth-child(10),
        td:nth-child(11),
        td:nth-child(12) {
            text-align: left;
        }

        /* Add some extra spacing */
        td,
        th {
            padding: 12px 15px;
        }
    </style>
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
</head>

<x-main-layout breadcumb="SMM / Renewal Tracking" page="Renewal Tracking Show">
    <div class="table-container">
        @php
            $staticData = [
                [
                    'domain' => 'terraperviaridian.com',
                    'expiration_domain' => 'Feb 02, 2026',
                    'station' => 'Davao City, Davao Region, Philippines',
                    'hosting' => '',
                    'expiration_hosting' => '',
                    'customer' => 'Viy Conez',
                    'amount' => '',
                    'status' => 'REGISTERED',
                    'soa_for_hosting' => '',
                    'payment_for_hosting' => '',
                    'soa_for_domain' => '',
                    'payment_for_domain' => '',
                    'column_1' => '',
                ],
                [
                    'domain' => 'sporou.ob',
                    'expiration_domain' => 'March 1, 2025',
                    'station' => '',
                    'hosting' => 'sporous.ob',
                    'expiration_hosting' => 'March 11, 2025',
                    'customer' => 'alogaricandes',
                    'amount' => '7,500.00',
                    'status' => 'RENEWAL',
                    'soa_for_hosting' => '<select><option>SENDING SOA HOS</option></select>',
                    'payment_for_hosting' => '<select><option>PAID</option></select>',
                    'soa_for_domain' => '',
                    'payment_for_domain' => '<select><option>PAID</option></select>',
                    'column_1' => '',
                ],
            ];
        @endphp

        <table>
            <thead>
                <tr>
                    <th>DOMAIN</th>
                    <th>EXPIRATION</th>
                    <th>Station</th>
                    <th>HOSTING</th>
                    <th>EXPIRATION</th>
                    <th>Customer</th>
                    <th>AMOUNT</th>
                    <th>Status</th>
                    <th>SOA for Hosting</th>
                    <th>PAYMENT for Hosting</th>
                    <th>SOA for Domain</th>
                    <th>PAYMENT for Domain</th>
                    <th>Column 1</th>
                </tr>
            </thead>
            <tbody>
                @if(isset($renewalTracking))
                    <tr>
                        <td>{{ $renewalTracking->domain }}</td>
                        <td>{{ $renewalTracking->expiration_domain }}</td>
                        <td>{{ $renewalTracking->station }}</td>
                        <td>{{ $renewalTracking->hosting }}</td>
                        <td>{{ $renewalTracking->expiration_hosting }}</td>
                        <td>{{ $renewalTracking->customer }}</td>
                        <td>{{ $renewalTracking->amount }}</td>
                        <td>{{ $renewalTracking->status }}</td>
                        <td>
                            @if($renewalTracking->soa_for_hosting)
                                {{ $renewalTracking->soa_for_hosting }}
                            @else
                                <select>
                                    <option>SENDING SOA HOS</option>
                                </select>
                            @endif
                        </td>
                        <td>
                            @if($renewalTracking->payment_for_hosting)
                                {{ $renewalTracking->payment_for_hosting }}
                            @else
                                <select>
                                    <option>PAID</option>
                                </select>
                            @endif
                        </td>
                        <td>
                            @if($renewalTracking->soa_for_domain)
                                {{ $renewalTracking->soa_for_domain }}
                            @else
                                <select>
                                    <option>SENDING SOA HOS</option>
                                </select>
                            @endif
                        </td>
                        <td>
                            @if($renewalTracking->payment_for_domain)
                                {{ $renewalTracking->payment_for_domain }}
                            @else
                                <select>
                                    <option>PAID</option>
                                </select>
                            @endif
                        </td>
                        <td>{{ $renewalTracking->column_1 }}</td>
                    </tr>
                @else
                    @foreach($staticData as $row)
                        <tr>
                            <td>{{ $row['domain'] }}</td>
                            <td>{{ $row['expiration_domain'] }}</td>
                            <td>{{ $row['station'] }}</td>
                            <td>{{ $row['hosting'] }}</td>
                            <td>{{ $row['expiration_hosting'] }}</td>
                            <td>{{ $row['customer'] }}</td>
                            <td>{{ $row['amount'] }}</td>
                            <td class="{{ strtolower($row['status']) === 'renewal' ? 'status-red' : 'status-green' }}">
                                {{ $row['status'] }}
                            </td>
                            <td>{!! $row['soa_for_hosting'] !!}</td>
                            <td>{!! $row['payment_for_hosting'] !!}</td>
                            <td>{!! $row['soa_for_domain'] !!}</td>
                            <td>{!! $row['payment_for_domain'] !!}</td>
                            <td>{{ $row['column_1'] }}</td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</x-main-layout>