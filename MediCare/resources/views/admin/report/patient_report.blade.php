<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Patient Information</title>
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

        table,
        th,
        td {
            border: 1px solid #333;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            background-color: #007BFF;
            color: #fff;
            padding: 10px;
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


        <div class="header">
            <h2>Patient Information</h2>
        </div>
        <div class="patient-info">
            <h2>Patient Details</h2>
            <table>
                <tr>
                    <th>First Name</th>
                    <td>{{ ucwords($patient->first_name) }}</td>
                </tr>
                <tr>
                    <th>Middle Name</th>
                    <td>{{ ucwords($patient->middle_name) }}</td>
                </tr>
                <tr>
                    <th>Last Name</th>
                    <td>{{ ucwords($patient->last_name) }}</td>
                </tr>
                <tr>
                    <th>Date of Birth</th>
                    <td>{{ date('F j, Y', strtotime($patient->birthdate)) }}</td>
                </tr>
                <tr>
                    <th>Gender</th>
                    <td>{{ ucwords($patient->gender) }}</td>
                </tr>
                <tr>
                    <th>Street</th>
                    <td>{{ ucwords($patient->street) }}</td>
                </tr>
                <tr>
                    <th>Brgy</th>
                    <td>{{ ucwords($patient->brgy) }}</td>
                </tr>
                <tr>
                    <th>City</th>
                    <td>{{ ucwords($patient->city) }}</td>
                </tr>
                <tr>
                    <th>Province</th>
                    <td>{{ ucwords($patient->province) }}</td>
                </tr>
                <tr>
                    <th>Phone</th>
                    <td>{{ $patient->phone }}</td>
                </tr>
                @if ($patient->type == 'admitted_patient')
                    <tr>
                        <th>Patient Type</th>
                        <td>Admitted Patient</td>
                    </tr>
                    <tr>
                        <th>Admitted Date</th>
                        <td>{{$patient->admitted_date}}</td>
                    </tr>
                @else
                    <tr>
                        <th>Patient Type</th>
                        <td>Outpatient</td>
                    </tr>
                    <tr>
                        <th>Appointment Date</th>
                        <td>{{date('F j, Y', strtotime($patient->date))}}</td>
                    </tr>
                    <tr>
                        <th>Appointment Time</th>
                        <td>{{ date('h:i A', strtotime($patient->time)) }}</td>
                    </tr>
                @endif
            </table>
            <h2>Admission Details</h2>
            <table>
                <tr>
                    <th>First Name</th>
                    <td>{{ ucwords($patient->first_name) }}</td>
                </tr>
                <tr>
                    <th>Middle Name</th>
                    <td>{{ ucwords($patient->middle_name) }}</td>
                </tr>
            </table>
            <br>
            <h2>Diagnosis List</h2>
            <ul>
                <li>Diagnosis 1: Fever</li>
                <li>Diagnosis 2: Hypertension</li>
            </ul>

            <h2>Medication List</h2>
            <ul>
                <li>Medication 1: Paracetamol</li>
                <li>Medication 2: Lisinopril</li>
            </ul>
        </div>
        <div class="footer">
            &copy; 2023 MediCare
        </div>
    </div>
</body>

</html>
