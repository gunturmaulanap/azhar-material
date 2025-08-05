<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', $title ?? config('app.name'))</title>
    <link rel="icon" type="image/png" href="{{ asset('img/azhar.png') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @viteReactRefresh
    @vite(['resources/css/app.css', 'resources/js/react/main.tsx'])
</head>

<body class="font-sans antialiased">
    <div id="app" class="min-h-screen bg-gray-50">
        <div id="fallback-content" class="flex items-center justify-center min-h-screen">
            <div>
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto mb-4"></div>
                <p class="text-center text-gray-500">Loading...</p>
            </div>
        </div>
    </div>

    <script>
        const observer = new MutationObserver(() => {
            const fallback = document.getElementById('fallback-content');
            if (fallback) fallback.style.display = 'none';
            observer.disconnect();
        });
        observer.observe(document.getElementById('app'), {
            childList: true,
            subtree: true
        });
        setTimeout(() => {
            const fallback = document.getElementById('fallback-content');
            if (fallback) fallback.style.display = 'none';
        }, 10000);
    </script>
</body>

</html>
