<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>MediCare | Medicine List Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;

        }

        .container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            padding: 5px;
        }
        .img  {
            float:left;
            padding-top: 10px;
            width: 110px;
            height: 70px;
        }

        p,
        b {
            font-size: 13px;
        }

        .header h1 {
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
            font-family: 'DejaVu Sans', sans-serif;

        }

        .footer {
            position: absolute;
            bottom: 10px;


            display: flex;
            justify-content: space-between;
            /* Align items in a row with space between them */

        }

        .footer-start,
        .footer-center,
        .footer-right {
            display: inline-flex;
            margin-left: 70px;
            font-size: 13px;

        }
    </style>
</head>

<body>
    <div class="container">
        <img class="img" src="{{ public_path('logo.jpg') }}" alt="MediCare" >
            <p><b>Medical Mission Group Hospital and Health Services Cooperative of Camarines Sur</b>
                <br>
                Sta Elena Baras, Nabua, 4434 Camarines Sur, Philippines
                <br>
                Phone: +1 5589 55488 55
                <br>
                Email: medicare@example.com
            </p>
    </div>
        <div class="purchase-detail">
            <h3>Medicine List Report</h3>
            <table>
                <tr>
                    <th>ITEM NAME</th>
                    <th>CATEGORY</th>
                    <th>STOCK</th>
                    <th>BRAND</th>
                    <th>EXPIRATION</th>
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
        <div class="footer">
            <div class="footer-start">
                Printing Date: {{ date('m/d/Y', strtotime($currentDate)) }}
            </div>
            <div class="footer-center">
                Printing Time: {{ $currentTime }}
            </div>
            <div class="footer-right">
                &copy; 2023 MediCare
            </div>
        </div>


    </div>
</body>

</html>
