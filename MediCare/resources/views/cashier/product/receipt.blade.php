<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>MediCare | Receipt</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0px;
            padding: 0;
            color: black;
            box-sizing: border-box;
        }

        u {
            border-bottom: 0.5px solid black;
            width: 50%;
            display: inline-block;
        }

        table {
            width: 150%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table,
        th,
        td {
            border: .5px solid black;
            font-size: 8px;
        }

        .a {
            display: inline-block;
        }
    </style>
</head>

<body>
    <p style="font-size:13; text-align:center; margin-top: 2px; font-family: 'Times New Roman'">
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;MEDICAL MISSION GROUP</p>
    <p style="font-size: 7; justify-content: center; text-align: center; margin-top:0;">
        &nbsp;&nbsp;&nbsp;HOSPITAL & HEALTH SERVICES COOPERATIVE OF CAMARINES SUR
        <br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        Sta. Elena Baras, Nabua Camarines Sur 4434 Philippines
        <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        VAT Reg. TIN: 005-659-320-00000 Tel. #:(054) 288-5555
    </p>
    <!-- Underline the date using the <u> tag -->
    <p style="font-size: 8px;">OFFICIAL RECEIPT <span style="color: red;">NO.
            &nbsp;&nbsp;{{ $reference }}</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span
            style="font-size: 8px;">Date&nbsp;&nbsp; <u>{{ date('M j, Y', strtotime($currentDate)) }}</u></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        Received from :
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

        with TIN :
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        
        and address at :
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

        Engaged in the business style
        of :
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

        OSCA/PWD ID No. :
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        
        In Partial/full payment for :
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

        Cardholder's
        Signature :

        Room:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Date
        Admitted:
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

       
        <table border="1" style="width: 100%;">
            <thead>
                <tr>
                    <th style="text-align:center; width: 60%;">PARTICULARS</th>
                    <th style="text-align:center; width: 10%;">QTY</th>
                    <th style="text-align:center; width: 30%;" colspan="2">AMOUNT</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($cart as $key => $item)
                <tr>
                    <td style="text-align:center; font-size: 7px; width: 60%;">{{ $item['name'] }}</td>
                    <td style="text-align:center; font-size: 7px; width: 10%;">{{ $item['quantity'] }}</td>
                    <td style="text-align:center; font-size: 7px; font-family: 'DejaVu Sans', sans-serif; width: 15%;">
                        ₱{{ number_format($item['price'], 2) }}
                    </td>
                    <td style="text-align:center; font-size: 7px; font-family: 'DejaVu Sans', sans-serif; width: 15%;">₱{{ number_format($item['price'] * $item['quantity'], 2) }}</td>
                </tr>
                @endforeach
                <tr>
                    <td style="font-size: 7px;"> VATableSales&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Total Sales VAT Inclusive
                    <br> VAT-Exempt Sales&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Less 12% VAT
                    <br> Zero Rated Sales&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Net of VAT/Total
                    <br> VAT Amount&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Less: SC/PWD Discount
                    <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Total Due
                    <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Less: Withholding</td>
                    <td></td>
                    <td></td>
                    
                </tr>
                <tr>
                    <td style="text-align:right; font-size: 7px; font-family: 'DejaVu Sans', sans-serif;">TOTAL AMOUNT - ₱</td>
                    <td></td>
                    <td style="text-align:center; font-size: 7px; font-family: 'DejaVu Sans', sans-serif;"></td>
                    <td style="text-align:center; font-size: 7px; font-family: 'DejaVu Sans', sans-serif;">₱{{ number_format(array_sum(array_map(function ($item) {return $item['price'] * $item['quantity'];}, $cart)),2) }}</td>
                    
                </tr>
                <tr>
                    <td style="font-size: 7px;">Total in Words</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="text-align:right; font-size: 7px; font-family: 'DejaVu Sans', sans-serif;">AMOUNT PAID - ₱</td>
                    <td></td>
                    <td style="text-align:center; font-size: 7px; font-family: 'DejaVu Sans', sans-serif;"></td>
                    <td style="text-align:center; font-size: 7px; font-family: 'DejaVu Sans', sans-serif;">₱{{ number_format($amount, 2) }}</td>
                </tr>
                <tr>
                    <td style="text-align:right; font-size: 7px; font-family: 'DejaVu Sans', sans-serif;">CHANGE - ₱</td>
                    <td></td>
                    <td style="text-align:center; font-size: 7px; font-family: 'DejaVu Sans', sans-serif;"></td>
                    <td style="text-align:center; font-size: 7px; font-family: 'DejaVu Sans', sans-serif;">₱{{ number_format($change, 2) }}</td>
                </tr>
                
            </tbody>
        </table>

           
       <br>     
        
       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <table style="width: 55%; float:left; margin: right 10px;">
                <tr>
                    <th style="font-size: 7px;" colspan="2">Sr. Citizen TIN:
                        <br>
                    </th>
                </tr>
                <tr>
                    <th style="font-size: 7px; text-align: center;">OSCA/PWD ID No.
                        <br>
                    </th>
                    <th style="font-size: 7px; text-align: center;">Signature
                        <br>
                    </th>
                </tr>
            </table>
<span style="font-size: 7px;">

    <input type="checkbox" id="cash1" name="cash1" value="cash">Cash&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" id="check1" name="check1" value="check">Check
</span>
<br>
          
        <span style="font-size: 5px; margin-top: 5; margin-bottom: 0;">500 Bklts (50x3) 155,001-180,000
            <br>
            BIR Authority to Print No. 066AU20230000000192
            <br>
            Date Issued: January 28, 2023
            <br>
            MODCOR, PrintingPress Iriga City
            <br>
            TIN: 136-740-603-000 NV
            <br>
        </span>
   
    </body>

    </html>
