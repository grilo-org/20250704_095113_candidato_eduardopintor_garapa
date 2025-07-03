<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if (isset($title))
    <title>{{ $title }} &raquo; {{ config('app.name', 'Laravel') }} Admin</title>
    @else
    <title>{{ config('app.name', 'Laravel') }}</title>
    @endif
    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
</head>
<body class="welcome">
    <div class="flex-center position-ref full-height">
        <div class="content">
            <div class="title m-b-md">
                GarAppa
            </div>
        </div>
    </div>
</body>
</html>
