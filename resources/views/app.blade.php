<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        {{-- Le indica al navegador que el sitio maneja su propio modo oscuro.
             Evita que el "modo oscuro automático" de Chrome/Samsung Internet
             re-oscurezca una página que ya es oscura y apague los gradientes/neón. --}}
        <meta name="color-scheme" content="dark light">

        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <meta name="description" content="Pablo Mandile — Programador Full-Stack PHP · Laravel · Vue. Portfolio personal.">

        <link rel="icon" href="/favicon.ico" sizes="any">
        <link rel="icon" type="image/png" href="/img/logo.png">
        <link rel="apple-touch-icon" href="/img/logo.png">

        @routes
        @vite(['resources/js/app.ts'])
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        @inertia
    </body>
</html>
