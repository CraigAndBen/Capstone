<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>MediCare | Request List Report</title>
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
            <h3>Request List Report</h3>
            <table style="width: 100%; text-align: center; border-collapse: collapse;">
                <tr>
                    <th><strong>Requester Name</strong></th>
                    <th><strong>Department</strong></th>
                    <th><strong>Date</strong></th>
                    <th><strong>Time</strong></th>
                    <th><strong>Item</strong></th>
                    <th><strong>Brand</strong></th>
                    <th><strong>Quantity</strong></th>
                </tr>
                @php
                    $previousIdentifier = null;
                @endphp
                @foreach ($requests as $request)
                    @php
                        // Generate a unique identifier for the row
                        $rowIdentifier = $request->name_requester . $request->department . $request->date . $request->created_at;
                    @endphp
            
                    @if ($rowIdentifier !== $previousIdentifier)
                        <tr>
                            <td rowspan="{{ $requests->where('name_requester', $request->name_requester)->where('department', $request->department)->where('date', $request->date)->count() }}">{{ $request->name_requester }}</td>
                            <td rowspan="{{ $requests->where('name_requester', $request->name_requester)->where('department', $request->department)->where('date', $request->date)->count() }}">{{ $request->department }}</td>
                            <td rowspan="{{ $requests->where('name_requester', $request->name_requester)->where('department', $request->department)->where('date', $request->date)->count() }}">{{ date('M j, Y', strtotime($request->date)) }}</td>
                            <td rowspan="{{ $requests->where('name_requester', $request->name_requester)->where('department', $request->department)->where('date', $request->date)->count() }}">{{ date('g:i A', strtotime($request->created_at)) }}</td>
                            {{-- Display the time --}}
                            @foreach ($products as $product)
                                @if ($product->id === $request->product_id)
                                    <td>{{ $product->p_name }}</td>
                                    <td>{{ $request->brand }}</td>
                                    <td>{{ $request->quantity }}</td>
                                @endif
                            @endforeach
                        </tr>
                    @else
                        <tr>
                             {{-- Leave empty cells for name, department, date, and time --}}
                            @foreach ($products as $product)
                                @if ($product->id === $request->product_id)
                                    <td>{{ $product->p_name }}</td>
                                    <td>{{ $request->brand }}</td>
                                    <td>{{ $request->quantity }}</td>
                                @endif
                            @endforeach
                        </tr>
                    @endif
            
                    @php
                        $previousIdentifier = $rowIdentifier;
                    @endphp
                @endforeach
            </table>
            
        </div>
    </div>
    <div class="footer">
        Printing Date: {{ date('m/d/Y', strtotime($currentDate)) }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&copy; 2023 MediCare&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Printing Time: {{ $currentTime }}
    </div>
</body>

</html>
