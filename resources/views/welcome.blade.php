<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Data Profiling</title>

    <!-- Include the compiled Tailwind CSS file via Vite -->
    <!-- <link href="https://cdn.datatables.net/2.1.5/css/dataTables.tailwindcss.css" rel="stylesheet" type="text/css"> -->
    <link href="https://cdn.datatables.net/v/dt/dt-2.1.5/datatables.min.css" rel="stylesheet">
    

    <!-- jQuery should be loaded before DataTables -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/v/dt/dt-2.1.5/datatables.min.js"></script>

    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
</head>
<body class="antialiased">
    <div id="app">
        <app></app>
    </div>

    <!-- Include your compiled JavaScript files -->
    @vite(['resources/js/app.js'])
</body>
</html>
