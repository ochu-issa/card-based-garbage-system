<!DOCTYPE html>
<html>
<head>
    <title>Payment Report</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        h1 {
            font-size: 24px;
            text-align: center;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            text-align: left;
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .badge-success {
            background-color: #28a745;
            color: #fff;
            padding: 5px 10px;
            border-radius: 20px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <h1>Card Based Garbage Collection System <br> Card Payments Report</h1>
        <h3>Date Range: {{$DateRange}}</h3>
        <br>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>S/no</th>
                    <th>Card Number</th>
                    <th>Amount</th>
                    <th>Date & time</th>
                    <th>Status</th>
            </thead>
            <tbody>
                @foreach($depositFunds as $index => $deposit)
                    <tr>
                        <td>{{$index + 1}}</td>
                        <td>{{$deposit->card_number}}</td>
                        <td>{{number_format($deposit->amount) }}</td>
                        <td>{{$deposit->created_at}}</td>
                        <td><span class="badge-success">SUCCESS</span> </td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="2"><b>TOTAL AMOUNT (Tsh)</b></td>
                    <td><b>{{number_format($total)}} /=</b></td>
                    <td><b></b></td>
                </tr>
            </tbody>
        </table>
        <hr>
        <p><i>This report was generated by : {{Auth::user()->f_name}}  {{Auth::user()->l_name}}</i></p>
    </div>
</body>
</html>
