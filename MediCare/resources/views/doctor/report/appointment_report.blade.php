<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Appointment Information</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
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

        table, th, td {
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
        <p><b>Medical Mission Group Hospital and Health Services Cooperative of Camarines Sur</b>
            <br>
            C98V+GR4, Sta Elena Baras, Nabua, 4434 Camarines Sur, Philippines
            <br>
            Phone: +1 5589 55488 55
            <br>
            Email: medicare@example.com
            <br>
            Reference No: {{ $reference }}
        </p>
        <hr>

        <div class="header">
            <h2>Appointment Information</h2>
        </div>
        <div class="patient-info">
            <h2>Patient Details</h2>
            <table>
                <tr>
                    <th>First Name</th>
                    <td>{{ ucwords($appointment->first_name) }}</td>
                </tr>
                <tr>
                    <th>Middle Name</th>
                    <td>{{ ucwords($appointment->middle_name) }}</td>
                </tr>
                <tr>
                    <th>Last Name</th>
                    <td>{{ ucwords($appointment->last_name) }}</td>
                </tr>
                <tr>
                    <th>Date of Birth</th>
                    <td>{{ isset($appointment->birthdate) ? date('F j, Y', strtotime($appointment->birthdate)) : "" }}</td>

                </tr>
                <tr>
                    <th>Gender</th>
                    <td>{{ ucwords($appointment->gender) }}</td>
                </tr>
                <tr>
                    <th>Street</th>
                    <td>{{ ucwords($appointment->street) }}</td>
                </tr>
                <tr>
                    <th>Brgy</th>
                    <td>{{ ucwords($appointment->brgy) }}</td>
                </tr>
                <tr>
                    <th>City</th>
                    <td>{{ ucwords($appointment->city) }}</td>
                </tr>
                <tr>
                    <th>Province</th>
                    <td>{{ ucwords($appointment->province) }}</td>
                </tr>
                <tr>
                    <th>Phone</th>
                    <td>{{ $appointment->phone }}</td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td>{{ $appointment->email }}</td>
                </tr>
                <tr>
                    <th>Physician</th>
                    <td>Dr. {{ ucwords($doctor->first_name) }} {{ucwords($doctor->last_name)}}</td>
                </tr>
            </table>
            <br>
            <h2>Appointment Details</h2>
            <table>
                <tr>
                    <th>Appointment Date</th>
                    <td>{{ isset($appointment->appointment_date) ? date('F j, Y', strtotime($appointment->appointment_date)) : "" }}</td>

                </tr>
                <tr>
                    <th>Appointment Time</th>
                    <td>{{ ucwords($appointment->appointment_time) }}</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>{{ ucwords($appointment->status) }}</td>
                </tr>
            </table>
            <br>
        </div>
    </div>
    <div class="footer">
        &copy; 2023 MediCare
    </div>
</body>

</html>
