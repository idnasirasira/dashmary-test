<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-base-200/50 font-sans antialiased dark:bg-base-200">
    <x-main full-width>

        <x-slot:content>
            <x-theme-toggle />

            {{ $slot }}
        </x-slot>
    </x-main>
</body>

</html>
