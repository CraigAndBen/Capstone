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
        <p><b>Medical Mission Group Hospital and Health Services Cooperative of Camarines Sur</b>
            <br>
            C98V+GR4, Sta Elena Baras, Nabua, 4434 Camarines Sur, Philippines
            <br>
            Phone: +1 5589 55488 55
            <br>
            Email: medicare@gmail.com
            <br>
            Reference No: {{ $reference }}
        </p>
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
                    <td>{{ isset($patient->birthdate) ? date('F j, Y', strtotime($patient->birthdate)) : '' }}</td>

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
                <tr>
                    <th>Guardian First Name</th>
                    <td>{{ ucwords($patient->guardian_first_name) }}</td>
                </tr>
                <tr>
                    <th>Guardian Last Name</th>
                    <td>{{ ucwords($patient->guardian_last_name) }}</td>
                </tr>
                <tr>
                    <th>Guardian Birthdate</th>
                    <td>{{ isset($patient->guardian_birthdate) ? date('F j, Y', strtotime($patient->guardian_birthdate)) : '' }}
                    </td>
                </tr>
                <tr>
                    <th>Relationship</th>
                    <td>{{ ucwords($patient->relationship) }}</td>
                </tr>
                <tr>
                    <th>Guardian Phone</th>
                    <td>{{ $patient->guardian_phone }}</td>
                </tr>
                <tr>
                    <th>Guardian Email</th>
                    <td>{{ ucwords($patient->guardian_email) }}</td>
                </tr>
                <tr>
                    <th>Patient Type</th>
                    <td>{{ ucwords($patient->type) }}</td>
                </tr>
                <tr>
                    <th>Physician</th>
                    <td>Dr. {{ ucwords($doctor->first_name) }} {{ ucwords($doctor->last_name) }}</td>
                </tr>
                <tr>
                    <th>Medical Condition</th>
                    <td>{{ ucwords($patient->medical_condition) }}</td>
                </tr>
            </table>
            <br>
            @if ($patient->type == 'admitted_patient')
                <h2>Admission Details</h2>
                <table>
                    <tr>
                        <th>Admitted Date</th>
                        <td>{{ isset($patient->admitted_date) ? date('F j, Y', strtotime($patient->admitted_date)) : '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>Admitted Time</th>
                        <td>{{ date('h:i A', strtotime($patient->admitted_time)) }}</td>
                    </tr>
                    <tr>
                        <th>Discharged Date</th>
                        <td>{{ isset($patient->discharged_date) ? date('F j, Y', strtotime($patient->discharged_date)) : '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>Discharged Time</th>
                        <td>{{ date('h:i A', strtotime($patient->discharged_time)) }}</td>
                    </tr>
                    <tr>
                        <th>Room Number</th>
                        <td>{{ $patient->room_number }}</td>
                    </tr>
                    <tr>
                        <th>Bed Number</th>
                        <td>{{ $patient->bed_number }}</td>
                    </tr>
                </table>
            @else
                <h2>Appointment Details</h2>
                <table>
                    <tr>
                        <th>Appointment Date</th>
                        <td>{{ isset($patient->date) ? date('F j, Y', strtotime($patient->date)) : '' }}</td>
                    </tr>
                    <tr>
                        <th>Appointment Time</th>
                        <td>{{ date('h:i A', strtotime($patient->time)) }}</td>
                    </tr>
                </table>
                <br>
            @endif
            <br>
            <h2>Diagnose List</h2>
            <table>
                <tr>
                    <th>Diagnose Date</th>
                    <th>Diagnose Time</th>
                    <th>Diagnose Name</th>
                </tr>
                @foreach ($diagnoses as $diagnose)
                    <tr>
                        <td>{{ isset($diagnose->date) ? date('F j, Y', strtotime($diagnose->date)) : '' }}</td>
                        <td>{{ date('h:i A', strtotime($diagnose->time)) }}</td>
                        <td>{{ ucwords($diagnose->diagnose) }}</td>
                    </tr>
                @endforeach

            </table>
            <br>

            <h2>Medication List</h2>
            <table>
                <tr>
                    <th>Medication Date</th>
                    <th>Medication Time</th>
                    <th>Medication Name</th>
                    <th>Medication Dosage</th>
                    <th>Medication Duration</th>
                </tr>
                @foreach ($medications as $medication)
                    <tr>
                        <td>{{ isset($medication->date) ? date('F j, Y', strtotime($medication->date)) : '' }}</td>
                        <td>{{ date('h:i A', strtotime($medication->time)) }}</td>
                        <td>{{ ucwords($medication->medication_name) }}</td>
                        <td>{{ ucwords($medication->dosage) }}</td>
                        <td>{{ ucwords($medication->duration) }}</td>
                    </tr>
                @endforeach

            </table>
        </div>
    </div>
    {{-- <div class="footer">
        &copy; 2023 MediCare
    </div> --}}
</body>

</html>
