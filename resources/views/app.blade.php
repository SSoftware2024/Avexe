<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">  
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" href="{{ asset('images/logo_no_background.png') }}" type="image/x-icon">
        @vite(['resources/scss/app.scss','resources/js/app.js'])
        <title>{{ config('app.name', 'Laravel') }}</title>   
        <x-inertia::head />
        @routes
    </head>
    <body>
        <x-inertia::app />
    </body>
</html>