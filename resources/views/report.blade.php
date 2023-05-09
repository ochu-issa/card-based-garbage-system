<!DOCTYPE html>
<html>

<head>
    <title>Scanned Card Report</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 16px;
            line-height: 1.5;
        }

        .container-fluid {
            margin: 0 auto;
            max-width: 960px;
            padding: 20px;
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        @media only screen and (max-width: 600px) {

            table,
            th,
            td {
                font-size: 14px;
            }
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <h1>Scanned Card Report</h1>
        <h3>Date Range: {{$DateRange}}</h3>
        <table>
            <thead>
                <tr>
                    <th>S/no</th>
                    <th>Card Number</th>
                    <th>Resident Name</th>
                    <th>Address</th>
                    <th>Phone Number</th>
                    <th>Scanned times</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($scannedCards as $index => $card)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $card->card_number }}</td>
                        <td>{{ $card->resident_full_name }}</td>
                        <td>{{ $card->address }}</td>
                        <td>{{ $card->phone_number }}</td>
                        <td>{{ $card->scanned_times }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>
