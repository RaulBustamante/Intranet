<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Ariel3') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('/css/app.css') }}">
    <link href="{{ asset('fontawesome/4.7.0/css/font-awesome.min.css') }}" rel="stylesheet"> 

    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #F5F5F5;
        }

        .navbar {
            background-color: #333;
            color: #fff;
        }

        /* Additional header-specific styles */
    </style>

    <!-- Header-specific JavaScript Files -->
    <!-- jQuery, Popper.js, and Bootstrap JS -->
    <script src="{{ asset('/js/jquery-3.7.0.min.js') }}"></script>
    <script src="{{ asset('/js/popper.js') }}"></script>
    <script src="{{ asset('/lib/bootstrap/js/bootstrap.min.js') }}"></script>
</head>
<body>

<nav class="navbar navbar-expand-md navbar-dark bg-dark mb-4">
    <a class="navbar-brand text-danger" href="{{ url('/home') }}">{{ config('app.name', 'Ariel3') }}</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarContent">
        <!-- Navbar links -->
    </div>
</nav>

<script>
    // JavaScript for interactive elements in the header, if necessary
</script>
</body>
</html>
