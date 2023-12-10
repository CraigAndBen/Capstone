<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>MediCare | Inventory Report</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 0 auto;
            padding: 0;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table,
        th,
        td {
            border: 0px solid #333;
        }
    </style>
</head>
<body>
    <p style="font-size:10px;">&nbsp;&nbsp;Report Type:&nbsp;&nbsp;Receipt
        <br>
        Reference:&nbsp;&nbsp;{{ $reference }}
        <br>
        Date:&nbsp;&nbsp;{{ date('M j, Y', strtotime($currentDate)) }}
        <br>
        Time:&nbsp;&nbsp;{{ $currentTime }}
    </p>
    <table style="font-size:10px;">
        <thead>
            <tr>
                <th style="text-align: center;">Item Name</th>
                <th style="text-align: center;">Unit Price</th>
                <th style="text-align: center;">Quantity</th>
                <th style="text-align: center;">Sub total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($cart as $key => $item)
                <tr>
                    <td style="text-align: center;">{{ $item['name'] }}</td>
                    <td style="text-align: center;">₱{{ number_format($item['price'], 2) }}</td>
                    <td style="text-align: center;">{{ $item['quantity'] }}</td>
                    <td style="text-align: center;">₱{{ number_format($item['price'] * $item['quantity'], 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

 
    <p style=" font-size: 10px;">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Total:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;₱{{ number_format(array_sum(array_map(function ($item) {return $item['price'] * $item['quantity'];}, $cart)),2) }}
        <br>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Amount:&nbsp;&nbsp;₱{{ number_format($amount, 2) }}
        <br>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Change:&nbsp;&nbsp;₱{{ number_format($change, 2) }}
    </p>
</body>

</html>
