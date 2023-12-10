<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>MediCare | Item Price List Report</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
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

        h1 {
            margin: 0;
            font-size: 15px;
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
            font-family: 'DejaVu Sans', sans-serif;
        }
        th {
            padding: 10px;
            text-align: left;
            font-size: 10px;
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
        <p><b>Medical Mission Group Hospital and Health Services Cooperative of Camarines Sur</b>
            <br>
            Sta Elena Baras, Nabua, 4434 Camarines Sur, Philippines
            <br>
            Phone: +1 5589 55488 55
            <br>
            Email: medicare@example.com
            <br>
            Reference: {{ $reference }}
        <div class="purchase-detail">
            <h3>Item Price List Report</h3>
            <table>
                <thead>
                    <tr>
                        <th style="text-align:center;"><strong>ITEM NAME</strong></th>
                        <th style="text-align:center"><strong>CATEGORY</strong></th>
                        <th style="text-align:center"><strong>PRICE</strong></th>
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
    <div class="footer">
        Printing Date: {{ date('m/d/Y', strtotime($currentDate)) }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&copy; 2023 MediCare&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Printing Time: {{ $currentTime }}
    </div>
</body>

</html>
