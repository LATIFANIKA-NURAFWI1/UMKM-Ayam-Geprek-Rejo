<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
        <style>
            html, body { height: 100%; overflow: hidden; }
        </style>
    </head>
    <body class="h-screen overflow-hidden bg-gray-950 text-white antialiased">

        {{ $slot }}

        @fluxScripts
    </body>
</html>
