<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="kdsTheme()" :class="isDark ? 'dark' : ''">
    <head>
        @include('partials.head')
        <style>
            html, body { height: 100%; overflow: hidden; }
        </style>
    </head>
    <body class="h-screen overflow-hidden antialiased"
        :class="isDark ? 'bg-gray-950 text-white' : 'bg-gray-100 text-gray-900'">

        {{ $slot }}

        @fluxScripts

        <script>
            function kdsTheme() {
                return {
                    isDark: localStorage.getItem('kds-theme') !== 'light',
                    toggle() {
                        this.isDark = !this.isDark;
                        localStorage.setItem('kds-theme', this.isDark ? 'dark' : 'light');
                    }
                }
            }
        </script>
    </body>
</html>
