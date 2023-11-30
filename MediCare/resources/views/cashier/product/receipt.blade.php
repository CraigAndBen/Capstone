<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>MediCare | Inventory Report</title>
    <style>
        @page {
            size: A6 landscape;
            margin: 5mm; /* Adjust margin as needed */
        }

        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
        }

        .page-content {
            padding: 10px;
        }

        .container {
            max-width: 100%;
            margin: auto;
        }

        .img {
            text-align: center;
        }

        .img img {
            width: 80px;
            height: auto;
        }

        hr {
            border-color: #ddd;
        }

        .info {
            margin-bottom: 10px;
        }

        .info div {
            font-size: 10px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .table th,
        .table td {
            padding: 5px;
            text-align: center;
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
        }

        .table th {
            color: #000;
        }

        .left-align {
            text-align: left;
        }

        .total-section,
        .amount-section,
        .change-section {
            margin-top: 5px;
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
        }

        .total-row,
        .amount-row,
        .change-row {
            margin-bottom: 5px;
        }
    </style>
</head>

<body>

    <div class="page-content container">
        <div class="container">
            <div class="img">
                <img src="{{ public_path('logo.jpg') }}" alt="MediCare">
            </div>

            <div class="info">
                <div><span class="text-600 text-90">Report Type:</span> Receipt</div>
                <div><span class="text-600 text-90">Reference:</span> {{ $reference }}</div>
                <div><span class="text-600 text-90">Date:</span> {{ date('M j, Y', strtotime($currentDate)) }}</div>
                <div><span class="text-600 text-90">Time:</span> {{ $currentTime }}</div>
            </div>

            <hr>

            <table class="table">
                <thead>
                    <tr>
                        <th>Item Name</th>
                        <th>Unit Price</th>
                        <th>Quantity</th>
                        <th>Sub total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cart as $key => $item)
                        <tr>
                            <td>{{ $item['name'] }}</td>
                            <td>₱{{ number_format($item['price'], 2) }}</td>
                            <td>{{ $item['quantity'] }}</td>
                            <td>₱{{ number_format($item['price'] * $item['quantity'], 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <hr>

            <div class="left-align">
                <div class="total-section">
                    <div class="total-row">
                        <span>Total:</span>
                        <span> ₱{{ number_format(array_sum(array_map(function ($item) {return $item['price'] * $item['quantity'];}, $cart)), 2) }}</span>
                    </div>
                    <div class="amount-row">
                        <span>Amount:</span>
                        <span>₱{{ number_format($amount, 2) }}</span>
                    </div>
                    <div class="change-row">
                        <span>Change:</span>
                        <span>₱{{ number_format($change, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
