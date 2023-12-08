<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>MediCare | Item Price List Report</title>
    <style>
        @page {
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

        .container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            padding: 5px;
        }

        .img {
            float: left;
            padding-top: 10px;
            width: 110px;
            height: 70px;
        }

        p,
        b {
            font-size: 13px;
        }

        h5 {
            font-family: Arial, sans-serif;
            margin-top: 40px;
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
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table,
        th,
        td {
            border: 1px solid #333;
            text-align: center;
        }

        th,
        td {
            padding: 10px;
            font-size: 12px;
            font-family: 'DejaVu Sans', sans-serif;
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
            <h3>Item Price List Report</h3>
            <table>
                <thead>
                    <tr>
                        <th>ITEM NAME</th>
                        <th>CATEGORY</th>
                        <th>PRICE</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @foreach ($products as $product)
                        @php
                            $productHasPrice = false;
                        @endphp

                        @foreach ($categories as $category)
                            @if ($product->category_id == $category->id)
                                @foreach ($products_price as $price)
                                    @if ($price->product_id == $product->id)
                                        @php
                                            $productHasPrice = true;
                                        @endphp
                                        <tr>
                                            <td>{{ ucwords($product->p_name) }}</td>
                                            <td>{{ ucwords($category->category_name) }}</td>
                                            <td>₱{{ number_format($price->price, 2) }}</td>

                                        </tr>
                                    @endif
                                @endforeach
                            @endif
                        @endforeach

                        {{-- Only display the row if the product has a price --}}
                        @if (!$productHasPrice)
                            {{-- Handle the case where the product does not have a price --}}
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
        <footer>
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
