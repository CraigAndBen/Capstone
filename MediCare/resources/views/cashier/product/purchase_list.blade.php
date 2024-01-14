@extends('layouts.inner_cashier')

@section('content')

    <!-- [ Main Content ] start -->
    <div class="pc-container pb-3">
        <div class="pc-content ">
            <!-- [ breadcrumb ] start -->
            <div class="page-header mt-4">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h5 class="m-b-10">Purchase Transaction</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('cashier.dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('cashier.dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item" aria-current="page">Purchase Transaction</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->


            <!-- [ Main Content ] start -->
            <div class="row">

                <!-- [ sample-page ] start -->
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h1>Purchase Transaction</h1>
                        </div>
                        <div class="card-body">
                            <div class="container">
                                <div class="d-flex justify-content-end">
                                    <div class="form-group d-flex">
                                        <a href="{{ route('cashier.purchase.report.view') }}" class="btn btn-success mr-2"
                                            target="_blank">View Report</a>
                                        <form action="{{ route('cashier.purchase.report.download') }}" method="GET">
                                            @csrf
                                            <button class="btn btn-success" style="margin-left: 10px;"
                                                target="_blank">Download Report</button>
                                        </form>
                                    </div>
                                </div>

                                <br>
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
                                    <div class="alert alert-info">
                                        <span class="fa fa-check-circle"></span> {{ session('info') }}
                                    </div>
                                @endif

                                @if ($purchases->isEmpty())
                                    <div class="alert alert-info">
                                        <span class="fa fa-check-circle"></span> No Notification Yet.
                                    </div>
                                @else
                                    <table id="purchaseListtable" class="table table-bordered">
                                        <thead class="bg-primary text-light text-center">
                                            <tr>
                                                <th class="text-center">Reference</th>
                                                <th class="text-center">Total Quantity</th>
                                                <th class="text-center">Total Price</th>
                                                <th class="text-center">Amount</th>
                                                <th class="text-center">Change</th>
                                                <th class="text-center"></th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-center">
                                            @foreach ($purchases as $purchase)
                                                <tr class="p-3">
                                                    <td>{{ $purchase->reference }}</td>
                                                    <td>{{ $purchase->total_quantity }}</td>
                                                    <td>₱{{ number_format($purchase->total_price, 2) }}</td>
                                                    <td>₱{{ number_format($purchase->amount, 2) }}</td>
                                                    <td>₱{{ number_format($purchase->change, 2) }}</td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-primary" data-toggle="modal"
                                                            data-target="#viewModal{{ $purchase->id }}">
                                                            View Receipt
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endif
                            </div>
                        </div>
                    </div>


                    {{-- View modal --}}
                    @foreach ($purchases as $purchase)
                        <div class="modal fade" id="viewModal{{ $purchase->id }}" tabindex="-1" role="dialog"
                            aria-labelledby="myModalLabel">
                            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable"
                                style="width: 4.36in; height: 8in;" role="document">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary">
                                        <h2 class="modal-title text-light" id="myModalLabel">Purchase Receipt</h2>
                                    </div>
                                    <div class="modal-body">
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

                                            <p style="font-size: 10px;">OFFICIAL RECEIPT &nbsp;&nbsp;&nbsp;&nbsp;<span
                                                    style="color: red;">NO.
                                                    &nbsp;&nbsp;{{ $purchase->reference }}</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span
                                                    style="font-size: 10px;">Date&nbsp;&nbsp; <u
                                                        style="border-bottom: 0.5px 
                                                width: 50%;
                                                display: inline-block;">{{ date('M j, Y', strtotime($purchase->created_at->toDateString())) }}</u></span>
                                                Received from:
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                                                with TIN:
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;




                                                and address at:
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                                                Engaged in the business style
                                                of:
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                OSCA/PWD ID No.:
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                                                In Partial/full payment for:
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                Cardholder's
                                                Signature:
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
                                                    @foreach ($purchaseDetails as $purchaseDetail)
                                                        @if ($purchaseDetail->reference === $purchase->reference)
                                                            <tr>
                                                                <td
                                                                    style="text-align:center; font-size: 10px; width: 60%; border: .5px solid black;">
                                                                    {{ $purchaseDetail->product->p_name }}</td>
                                                                <td
                                                                    style="text-align:center; font-size: 10px; width: 10%; border: .5px solid black;">
                                                                    {{ $purchaseDetail->quantity }}</td>
                                                                <td
                                                                    style="text-align:center; font-size: 10px; font-family: 'DejaVu Sans', sans-serif; width: 15%; border: .5px solid black;">
                                                                    ₱{{ $purchaseDetail->price }}
                                                                </td>
                                                                <td
                                                                    style="width: 15%; text-align:center; font-size: 10px; border: .5px solid black;">
                                                                </td>
                                                            </tr>
                                                        @endif
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
                                                            ₱{{ number_format($purchase->total_price, 2) }}</td>
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
                                                            ₱{{ number_format($purchase->amount, 2) }}</td>
                                                        <td style="border: .5px solid black;"></td>
                                                    </tr>
                                                    <tr>
                                                        <td
                                                            style="text-align:right; font-size: 10px; font-family: 'DejaVu Sans', sans-serif; border: .5px solid black;">
                                                            CHANGE - ₱</td>
                                                        <td style="border: .5px solid black;"></td>
                                                        <td
                                                            style="text-align:center; font-size: 10px; font-family: 'DejaVu Sans', sans-serif; border: .5px solid black;">
                                                            ₱{{ number_format($purchase->change, 2) }}</td>
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

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
                @endforeach
                {{-- End View Modal --}}


                <!-- [ sample-page ] end -->
            </div>
            <!-- [ Main Content ] end -->
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#purchaseListtable').DataTable();
        });
    </script>
@endsection
