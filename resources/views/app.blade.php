<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">  
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        {{-- @vite(['resources/css/app.css','resources/js/app.js']) --}}
        @vite(['resources/scss/app.scss','resources/js/app.js'])
        {{-- @vite(['resources/js/app.js']) --}}
        <title>{{ config('app.name', 'Laravel') }}</title>   
        <x-inertia::head />
        @routes
    </head>
    <body>
        <x-inertia::app />
    </body>
</html>