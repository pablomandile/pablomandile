<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" style="color-scheme: dark;">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        {{-- La cara pública del sitio es siempre oscura. Lo declaramos de forma
             inequívoca ("dark", no "dark light") —acá, en el atributo style de
             <html> y en :root del CSS— para que el "modo oscuro automático" de
             Chrome/Samsung Internet no re-oscurezca la página invirtiendo los
             gradientes, el glow y el texto con degradado. --}}
        <meta name="color-scheme" content="dark">

        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <meta name="description" content="Pablo Mandile — Programador Full-Stack PHP · Laravel · Vue. Portfolio personal.">

        <link rel="icon" href="/favicon-v2.ico" sizes="any">
        <link rel="icon" type="image/png" href="/img/logo-v2.png">
        <link rel="apple-touch-icon" href="/img/logo-v2.png">

        @routes
        @vite(['resources/js/app.ts'])
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        @inertia
    </body>
</html>
