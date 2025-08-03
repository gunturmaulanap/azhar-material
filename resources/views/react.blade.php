<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Azhar Material') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/react/main.tsx'])
</head>
<body class="font-sans antialiased">
    <!-- React App Root -->
    <div id="react-root" class="min-h-screen bg-gray-50"></div>

    <!-- Loading Spinner -->
    <script>
        // Show loading spinner until React is ready
        if (!document.getElementById('react-root').children.length) {
            document.getElementById('react-root').innerHTML = `
                <div class="min-h-screen flex items-center justify-center">
                    <div class="animate-spin rounded-full h-32 w-32 border-b-2 border-blue-600"></div>
                </div>
            `;
        }
    </script>
</body>
</html>