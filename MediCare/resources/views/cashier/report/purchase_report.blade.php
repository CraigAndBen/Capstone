<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title> MediCare | Purchase Transaction Report</title>
    <style>
        @page{
            size: a4;
        }
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;

        }

        header {
            width: 100%;
            position: fixed;
            top: -50px;
            left: 15px;
            align-content: center;
        }

        .img {
            float: left;
            padding-top: 10px;
            width: 110px;
            height: 70px;
        }
        .container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            padding: 5px;
            margin-top: 20px;
        }


        p,
        b {
            font-size: 13px;
        }

        h5 {
            font-family: Arial, sans-serif;
        }

        h1 {
            font-size: 20px;
            margin: 0;
        }

        .purchase-detail {
            padding: 10px;
        }

        .purchase-detail h3 {
            font-size: 20px;
            margin-top: 0;

        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            text-align: center;
        }

        table,
        th,
        td {
            border: 1px solid #333;
        }

        th,
        td {
            padding: 10px;
            text-align: center;
            /* Center-align content within table cells */
            font-size: 12px;
            font-family: 'Arial', 'DejaVu Sans', sans-serif;

        }

        footer {
            width: 100%;
            position: fixed;
            bottom: -100px;
            display: flex;
            left: 0px;
            right: 0px;
            justify-content: space-between;
            height: 100px;
            line-height: 35px;
            border-top: 1px solid #000;
            /* Align items in a row with space between them */

        }

        .footer-start,
        .footer-center,
        .footer-right {
            display: inline-block;
            margin-left: 90px;
            font-size: 11px;

        }
    </style>
</head>

<body>
    <header>
        <img class="img" src="data:image/jpeg;base64,{{ base64_encode(file_get_contents(public_path('logo.jpg'))) }}" alt="MediCare">
        <p><b>Medical Mission Group Hospital and Health Services Cooperative of Camarines Sur</b>
            <br>
            Sta Elena Baras, Nabua, 4434 Camarines Sur, Philippines
            <br>
            Phone: +1 5589 55488 55
            <br>
            Email: medicare@example.com
        </p>
    </header>
    <div class="container">
        <h5>Reference: {{ $reference }}</h5>
        <div class="purchase-detail">
            <h3>Purchase Details Report</h3>
            <table>
                <tr>
                    <th>REFERENCE</th>
                    <th>TOTAL QUANTITY</th>
                    <th>TOTAL PRICE</th>
                    <th>AMOUNT PAID</th>
                    <th>CHANGE</th>
                </tr>
                @foreach ($purchases as $purchase)
                    <tr>
                        <td>{{ ucwords($purchase->reference) }}</td>
                        <td>{{ ucwords($purchase->total_quantity) }}</td>
                        <td>₱{{ number_format($purchase->total_price, 2) }}</td>
                        <td>₱{{ number_format($purchase->amount, 2) }}</td>
                        <td>₱{{ number_format($purchase->change, 2) }}</td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
        <footer class="text-center">
            <div class="footer-start">
                Printing Date: {{ date('m/d/Y', strtotime($currentDate)) }}
            </div>
            <div class="footer-center">
                &copy; 2023 MediCare
                
            </div>
            <div class="footer-right">
                Printing Time: {{ $currentTime }}
            </div>
        </footer>
</body>

</html>
