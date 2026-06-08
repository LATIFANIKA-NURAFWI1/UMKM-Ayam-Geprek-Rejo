<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="kdsTheme()" :class="isDark ? 'dark' : ''">
    <head>
        @include('partials.head')
        <style>
            html, body { height: 100%; margin: 0; padding: 0; }
        </style>
    </head>
    <body class="h-screen overflow-hidden antialiased"
        :class="isDark ? 'bg-gray-950' : 'bg-gray-100'">

        {{ $slot }}

        @fluxScripts
        @stack('scripts')

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
