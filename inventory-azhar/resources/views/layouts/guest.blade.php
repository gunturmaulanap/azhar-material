<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name') }}</title>

    <!-- Fonts -->
    <link rel="icon" type="image/png" href="{{ asset('img/azhar.png') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* For large screens (desktop) */
        @media (min-width: 1024px) {
            #page {
                background-image: url('{{ asset('img/logo-azhar.png') }}');
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;
                padding-top: 10vh;
                padding-bottom: 10vh;
            }

            .logo-mobile {
                display: none;
            }
        }

        /* For small screens (mobile) */
        @media (max-width: 1023px) {
            #page {
                background-image: none;
            }

            .logo-mobile {
                display: block;
                margin-bottom: 20px;
                height: 80px;
                /* Adjust the logo size as needed */
            }

            .text-xl {
                margin-top: 10px;
                /* Adjust margin to align with logo */
            }
        }
    </style>
</head>

<body class="font-sans text-gray-900 antialiased">
    <div id="page" class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
        <!-- Logo for mobile only -->
        <div class="mb-4 logo-mobile">
            <img src="{{ asset('img/azhar.png') }}" alt="Company Logo" class="h-16 w-auto">
        </div>

        <h2 class="text-xl z-10">Masuk Akun</h2>
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 overflow-hidden sm:rounded-lg z-10">
            {{ $slot }}
        </div>
    </div>
</body>

</html>
