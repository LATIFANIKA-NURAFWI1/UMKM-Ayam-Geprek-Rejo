<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="theme-color" content="#bc000a" />
        <title>{{ filled($title ?? null) ? $title.' — Geprek Rejo' : 'Kasir — Geprek Rejo' }}</title>

        <link rel="icon" href="/favicon.ico" sizes="any">

        {{-- Google Fonts: Plus Jakarta Sans + Inter --}}
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

        {{-- Material Symbols --}}
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            .font-jakarta { font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif; }
            .font-inter   { font-family: 'Inter', ui-sans-serif, system-ui, sans-serif; }
            .scrollbar-hide::-webkit-scrollbar { display: none; }
            .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
        </style>
    </head>
    <body class="font-inter min-h-screen bg-[#f7f9ff] text-[#181c20] antialiased flex flex-col md:pb-0 pb-20">

        {{ $slot }}

        @fluxScripts
    </body>
</html>
