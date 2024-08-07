<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>MediCare | Inventory Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            text-align: center;
        }
        .img {
            max-width: 100%;
            height: auto;
        }
        .header {
            text-align: center;
            background-color: #2E8BC0;
            color: #fff;
            padding: 3px;
        }
        h1 {
            margin: 0;
            font-size: 15px;
        }
        .patient-info {
            padding: 20px;
        }
        .patient-info h2 {
            font-size: 20px;
            margin-top: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table,
        th,
        td {
            border: 1px solid #333;
        }
        th {
            padding: 10px;
            text-align: left;
            font-size: 15px;
        }
        td {
            padding: 10px;
            text-align: left;
            font-size: 13px;
        }
        .footer {
            text-align: center;
            background-color: #2E8BC0;
            color: #fff;
            padding: 10px;
            position: absolute;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="logo.jpg" style="height: 90px;">
        <p><b>Medical Mission Group Hospital and Health Services Cooperative of Camarines Sur</b>
            <br>
            Sta Elena Baras, Nabua, 4434 Camarines Sur, Philippines
            <br>
            Phone: +1 5589 55488 55
            <br>
            Email: medicare@example.com
            <br>
            Reference: {{ $reference }}
        </p>


        <div class="purchase-detail">
            <h3>Inventory Report</h3>
            <table>
                <tr>
                    <th><strong>NAME</strong></th>
                    <th><strong>CATEGORY</strong></th>
                    <th><strong>STOCK</strong></th>
                    <th><strong>BRAND</strong></th>
                    <th><strong>STATUS</strong></th>
                </tr>
                @foreach ($products as $product)
                    <tr>
                        <td>{{ $product->p_name }}</td>
                        @foreach ($categories as $category)
                            @if ($category->id === $product->category_id)
                                <td>{{ $category->category_name }}</td>
                            @endif
                        @endforeach
                        <td>{{ $product->stock }}</td>
                        <td>{{ $product->brand }}</td>
                        <td>{{ date('M j, Y', strtotime($product->expiration)) }}</td>
                        <!-- Add more table cells for other product attributes -->
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
            <div class="footer">
                Printing Date: {{ date('m/d/Y', strtotime($currentDate)) }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&copy; 2023 MediCare&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Printing Time: {{ $currentTime }}
            </div>

</body>

</html>
