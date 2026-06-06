<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover" />
        <meta name="theme-color" content="#f97316" />

        <title>{{ filled($title ?? null) ? $title.' — Geprek Rejo' : 'Geprek Rejo · Self Order' }}</title>

        <link rel="icon" href="/favicon.ico" sizes="any">
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">

        {{-- Fonts (Bunny / Instrument Sans) --}}
        @fonts

        {{-- Vite: Tailwind CSS + app.js --}}
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        {{-- NO @fluxAppearance here — customer pages are always light mode --}}
    </head>
    <body class="min-h-screen bg-orange-50 font-sans antialiased">

        {{ $slot }}

        {{-- @fluxScripts includes Livewire 4 + Alpine.js (needed for x-show/x-data/x-transition) --}}
        @fluxScripts
    </body>
</html>
