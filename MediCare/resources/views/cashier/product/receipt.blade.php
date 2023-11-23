<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>MediCare | Inventory Report</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            
        }

        .page-content {
            padding: 20px;
            margin: 20px auto;
        }

        .container {
            max-width: 600px; /* Adjust the max-width as needed */
            margin: auto;
        }
        
        .img {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh; /* You can adjust this height as needed */
        margin-left: 225px;
    }

    .img img {
        width: 140px;
        height: 100px;
    }


        hr {
            border-color: #ddd;
        }

        .info {
            margin-bottom: 20px;
        }

        .info div {
            margin-bottom: 10px;
            font-size: 13px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .table th,
        .table td {
            padding: 10px;
            text-align: center;
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 13px;
        }

        .table th {
            color: #000;
        
        }

        .left-align {
            text-align: left;
            margin-left: 480px;
        }

        .total-section,
        .amount-section,
        .change-section {
            margin-top: 20px;
            text-align: left !important;
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 13px;
        }

        .total-row,
        .amount-row,
        .change-row {
            margin-bottom: 10px;
        }
    </style>
</head>

<body>

    <div class="page-content container">
        <div class="container px-0">
            <div class="row mt-4">
                <div class="col-12 col-lg-12">
                    <hr class="row brc-default-l1 mx-n1 mb-4" />
                    <div class="img">
                        <img src="{{ public_path('logo.jpg') }}" alt="MediCare" >
                    </div>
                    
                    <div class="info text-95 col-sm-6 align-self-start d-sm-flex justify-content-start">
                        <hr class="d-sm-none" />
                        <div class="text-grey-m2">
                            <div class="my-2"><span class="text-600 text-90">Report Type:</span> Receipt
                            </div>
                            <div class="my-2"><span class="text-600 text-90">Reference:</span> {{ $reference }}
                            </div>
                            <div class="my-2"><span class="text-600 text-90">Date:</span> {{ date('M j, Y', strtotime($currentDate)) }}</div>
                            <div class="my-2"><span class="text-600 text-90">Time:</span> {{ $currentTime }}</div>
                        </div>
                    </div>

                    <hr class="row brc-default-l1 mx-n1 mb-4" />

                    <table class="table">
                        <thead>
                            <tr>
                                <th>Item Id</th>
                                <th>Item Name</th>
                                <th>Unit Price</th>
                                <th>Quantity</th>
                                <th>Sub total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cart as $key => $item)
                                <tr>
                                    <td>{{ $item['product_id'] }}</td>
                                    <td>{{ $item['name'] }}</td>
                                    <td>₱{{ number_format($item['price'], 2) }}</td>
                                    <td>{{ $item['quantity'] }}</td>
                                    <td>₱{{ number_format($item['price'] * $item['quantity'], 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <hr class="row brc-default-l1 mx-n1 mb-4" />
                    <div class="left-align">
                        <div class="total-section">
                            <div class="total-row">
                                <span>Total:</span>
                                <span> ₱{{ number_format(array_sum(array_map(function ($item) 
                                    {return $item['price'] * $item['quantity'];}, $cart)), 2) }}</span>
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
        </div>
    </div>

</body>

</html>
