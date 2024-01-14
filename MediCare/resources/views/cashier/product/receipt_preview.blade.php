@extends('layouts.inner_cashier')

@section('content')
    <div class="pc-container pb-3">
        <div class="pc-content">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card mx-auto my-auto" style="width: 4.36in; height: 9in;">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h1>Purchase</h1>
                                <div class="page-tools">
                                    <div class="action-buttons">
                                        <form method="POST" action="{{ route('cashier.product.purchase.receipt') }}"
                                            id="printForm" target="_blank">
                                            @csrf
                                            <div class="form-group">
                                                <input type="hidden" name="reference" value="{{ $reference }}">
                                                <input type="hidden" name="amount" value="{{ $amount }}">
                                                <input type="hidden" name="change" value="{{ $change }}">
                                            </div>
                                            <button type="submit"
                                                class="btn bg-outline-dark btn-outline-dark mx-1px text-95">
                                                <i class="mr-1 fa fa-print text-primary-m1 text-120 w-2"></i>
                                                Print
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="container">
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <strong>Whoops!</strong> There were some problems with your input. Please fix the
                                        following errors: <br>
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                @if (session('success'))
                                    <div class="alert alert-success">
                                        <span class="fa fa-check-circle"></span> {{ session('success') }}
                                    </div>
                                @endif
                                @if (session('info'))
                                    <div class="alert alert-warning">
                                        <span class="fa fa-xmark-circle"></span> {{ session('info') }}
                                    </div>
                                @endif
                                @if (session('error'))
                                    <div class="alert alert-danger">
                                        <span class="fa fa-xmark-circle"></span> {{ session('error') }}
                                    </div>
                                @endif

                                <div class="container px-0">
                                    <p
                                        style="font-size:13; text-align:center; margin-top: 2px; font-family: 'Times New Roman'">
                                        MEDICAL MISSION GROUP</p>

                                    <p
                                        style="font-size: 11px; justify-content: center; text-align: center; margin-top:0;">
                                        HOSPITAL & HEALTH SERVICES COOPERATIVE OF CAMARINES SUR
                                        <br>
                                        Sta. Elena Baras, Nabua Camarines Sur 4434 Philippines
                                        <br>
                                        VAT Reg. TIN: 005-659-320-00000 Tel. #:(054) 288-5555
                                    </p>

                                    <p style="font-size: 10px;">OFFICIAL RECEIPT &nbsp;&nbsp;<span
                                            style="color: red;">NO.
                                            &nbsp;&nbsp;{{ $reference }}</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span
                                            style="font-size: 10px;">Date&nbsp;&nbsp; <u
                                                style="border-bottom: 0.5px 
                                        width: 50%;
                                        display: inline-block;">{{ date('M j, Y', strtotime($currentDate)) }}</u></span>
                                        Received from
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                                        with TIN
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;


                                        and address at
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                                        Engaged in the business style
                                        of
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        OSCA/PWD ID No.
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                                        In Partial/full payment for
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        Cardholder's
                                        Signature
                                        Room:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        Date
                                        Admitted:

                                    <table border="1"
                                        style="width: 100%;
                                    border-collapse: collapse;
                                    margin-top: 20px; border: .5px solid black;">
                                        <thead>
                                            <tr>
                                                <th
                                                    style="text-align:center; font-size:13px; width: 60%; border: .5px solid black;">
                                                    PARTICULARS</th>
                                                <th
                                                    style="text-align:center; font-size:13px; width: 10%; border: .5px solid black;">
                                                    QTY</th>
                                                <th style="text-align:center; font-size:13px; width: 30%; border: .5px solid black;"
                                                    colspan="2">AMOUNT</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($cart as $key => $item)
                                                    <tr>
                                                        <td
                                                            style="text-align:center; font-size: 10px; width: 60%; border: .5px solid black;">
                                                            {{ $item['name'] }}</td>
                                                        <td
                                                            style="text-align:center; font-size: 10px; width: 10%; border: .5px solid black;">
                                                            {{ $item['quantity'] }}</td>
                                                        <td
                                                            style="text-align:center; font-size: 10px; font-family: 'DejaVu Sans', sans-serif; width: 15%; border: .5px solid black;">
                                                            ₱{{ number_format($item['price'] * $item['quantity'], 2) }}
                                                        </td>
                                                        <td
                                                            style="width: 15%; text-align:center; font-size: 10px; border: .5px solid black;">
                                                        </td>
                                                    </tr>
                                          
                                            @endforeach

                                            <!-- Additional rows for totals and other details -->
                                            <tr>
                                                <td style="font-size: 9px; border: .5px solid black;">
                                                    VATableSales&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Total
                                                    Sales VAT Inclusive
                                                    <br> VAT-Exempt
                                                    Sales&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Less
                                                    12% VAT
                                                    <br> Zero Rated
                                                    Sales&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Net
                                                    of VAT/Total
                                                    <br> VAT
                                                    Amount&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Less:
                                                    SC/PWD Discount
                                                    <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Total
                                                    Due
                                                    <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Less:
                                                    Withholding
                                                </td>
                                                <td style="border: .5px solid black;"></td>
                                                <td style="border: .5px solid black;"></td>

                                            </tr>
                                            <tr>
                                                <td
                                                    style="text-align:right; font-size: 10px; font-family: 'DejaVu Sans', sans-serif; border: .5px solid black;">
                                                    TOTAL AMOUNT - ₱</td>
                                                <td style="border: .5px solid black;"></td>
                                                <td
                                                    style="text-align:center; font-size: 10px; font-family: 'DejaVu Sans', sans-serif; border: .5px solid black;">
                                                    ₱{{ number_format(array_sum(array_map(function ($item) {return $item['price'] * $item['quantity'];}, $cart),), 2, ) }}</td>
                                                <td style="border: .5px solid black;"></td>

                                            </tr>
                                            <tr>
                                                <td style="font-size: 10px; border: .5px solid black;">Total in
                                                    Words</td>
                                                <td style="border: .5px solid black;"></td>
                                                <td style="border: .5px solid black;"></td>
                                                <td style="border: .5px solid black;"></td>
                                            </tr>
                                            <tr>
                                                <td
                                                    style="text-align:right; font-size: 10px; font-family: 'DejaVu Sans', sans-serif; border: .5px solid black;">
                                                    AMOUNT PAID - ₱</td>
                                                <td style="border: .5px solid black;"></td>
                                                <td
                                                    style="text-align:center; font-size: 10px; font-family: 'DejaVu Sans', sans-serif; border: .5px solid black;">
                                                    ₱{{ number_format($amount, 2) }}</td>
                                                <td style="border: .5px solid black;"></td>
                                            </tr>
                                            <tr>
                                                <td
                                                    style="text-align:right; font-size: 10px; font-family: 'DejaVu Sans', sans-serif; border: .5px solid black;">
                                                    CHANGE - ₱</td>
                                                <td style="border: .5px solid black;"></td>
                                                <td
                                                    style="text-align:center; font-size: 10px; font-family: 'DejaVu Sans', sans-serif; border: .5px solid black;">
                                                    ₱{{ number_format($change, 2) }}</td>
                                                <td style="border: .5px solid black;"></td>
                                            </tr>

                                        </tbody>
                                    </table>

                                    <br>

                                    <table border="1" style="width: 55%; float:left; margin-right: 10px;">
                                        <tr>
                                            <th style="font-size: 10px; border: .5px solid black;" colspan="2">
                                                Sr. Citizen TIN:
                                                <br>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th
                                                style="font-size: 10px; text-align: center; border: .5px solid black;">
                                                OSCA/PWD ID No.
                                                <br>
                                            </th>
                                            <th
                                                style="font-size: 10px; text-align: center; border: .5px solid black;">
                                                Signature
                                                <br>
                                            </th>
                                        </tr>
                                    </table>
                                    <br>
                                    <br>
                                    <span style="font-size: 10px;">
                                        <input type="checkbox" id="cash1" name="cash1"
                                            value="cash">Cash&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox"
                                            id="check1" name="check1" value="check">Check
                                    </span>
                                    <br>

                                    <span style="font-size: 8px; margin-top: 5; margin-bottom: 0;">
                                        500 Bklts (50x3) 155,001-180,000
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
                                </div>
                            </div>
                        </div>
                        <div class="row ml-6">
                            <div class="col-md-6 mb-2">
                                <form method="POST" action="{{ route('cashier.product.purchase.confirm') }}">
                                    @csrf
                                    <input type="hidden" name="reference" value="{{ $reference }}">
                                    <input type="hidden" name="amount" value="{{ $amount }}">
                                    <input type="hidden" name="change" value="{{ $change }}">
                                    <button type="submit" class="btn btn-info btn-bold px-4" style="font-size: 10px;">Pay Now</button>
                                    <a id="back" href="{{ route('cashier.product.purchase') }}" class="btn btn-danger" style="font-size: 10px;">Back</a>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function submitReceiptForm() {
            document.getElementById('printForm').submit();
        }
    </script>
@endsection
