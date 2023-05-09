<!DOCTYPE html>
<html>
<head>
	<title>Receipt</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<style>
		.container {
			margin-top: 50px;
		}

		h1 {
			font-size: 36px;
			text-align: center;
			margin-bottom: 20px;
			color: #007bff;
		}

		h3 {
			font-size: 24px;
			margin-bottom: 10px;
			color: #007bff;
		}

		table {
			margin-bottom: 30px;
			width: 100%;
			max-width: 700px;
			border-collapse: collapse;
			border: 1px solid #ccc;
		}

		th {
			text-align: left;
			padding: 10px;
			background-color: #f5f5f5;
			border: 1px solid #ccc;
		}

		td {
			padding: 10px;
			border: 1px solid #ccc;
		}

		hr {
			border: 1px solid #ccc;
			margin: 30px 0;
		}

		p {
			font-size: 20px;
			text-align: center;
			margin-top: 20px;
			color: #007bff;
		}
	</style>
</head>
<body>
	<div class="container">
		<div class="row">
            <div class="col-sm-12 text-primary">
                <h3 style="text-align: center">ZANZIBAR ENVIRONMENTAL MANAGEMENT AUTHORITY(ZEMA)</h3>
			</div>
        </div>
        <div class="row">
			<div class="col-sm-12">
				<h3>RECEIPT</h3>
			</div>
		</div>
		<hr>
        <table>
            <tr>
                <th>Date :</th>
                <td>{{ $deposit->created_at->format('d/m/Y H:i:s') }}</td>
            </tr>
            <tr>
                <th>Receipt No:</th>
                <td>{{ $deposit->id }}</td>
            </tr>
            <tr>
                <th>Card Number:</th>
                <td>{{ $deposit->card_number }}</td>
            </tr>
            <tr>
                <th>Amount:</th>
                <td>{{ number_format($deposit->amount, 2) }} Tsh</td>
            </tr>

        </table>
		<hr>
		<p class="text-center">Thank you for your business!</p>
	</div>
</body>
</html>

