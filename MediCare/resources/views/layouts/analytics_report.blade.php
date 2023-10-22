<!DOCTYPE html>
<html lang="en">

<head>
    <style>
        body {
            margin: 0;
            padding: 20px;
            font-size: 12px; /* Adjust the font size for printing */
            color: #000; /* Change text color for better printing */
        }
    
        .page-title {
            font-size: 20px; /* Increase the font size for the title */
            font-weight: bold;
            margin: 0;
            padding: 0;
        }
    
        .mb-4,
        .my-4 {
            margin: 1rem 0; /* Add spacing between elements for better readability */
        }
    
        hr {
            margin: 1rem 0; /* Add spacing before and after the horizontal rule */
            border: 0;
            border-top: 1px solid #000; /* Use a solid black line for the HR */
        }
    
        .text-110 {
            font-size: 110%;
        }
    
        .text-120 {
            font-size: 120%;
        }
    
        .text-150 {
            font-size: 150%;
        }
    
        
    </style>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- [Favicon] icon -->
    <link href="{{ asset('logo.jpg') }}" rel="icon">
    <title>Analytics Report</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />
    @yield('style')
</head>

<body>
    @yield('content')
</body>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
@yield('scripts')

</html>
