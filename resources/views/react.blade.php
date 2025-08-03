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
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/react/main.tsx'])
</head>
<body class="font-sans antialiased">
    <!-- React App Root -->
    <div id="react-root" class="min-h-screen bg-gray-50">
        <!-- Immediate Company Profile Fallback -->
        <div id="fallback-content" class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100">
            <!-- Hero Section -->
            <section class="relative h-screen flex items-center justify-center overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-r from-blue-600 to-indigo-600 opacity-90"></div>
                
                <div class="relative z-10 text-center text-white px-4 max-w-4xl mx-auto">
                    <div class="flex justify-center mb-6">
                        <svg class="h-16 w-16" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                        </svg>
                    </div>
                    <h1 class="text-4xl md:text-6xl font-bold mb-6">
                        Azhar Material
                    </h1>
                    <p class="text-xl md:text-2xl mb-4 opacity-90">
                        Your Trusted Construction Partner
                    </p>
                    <p class="text-lg mb-8 opacity-80 max-w-2xl mx-auto">
                        Menyediakan material konstruksi berkualitas tinggi untuk berbagai kebutuhan proyek Anda
                    </p>
                    <button class="bg-white text-blue-600 px-8 py-3 rounded-full font-semibold hover:bg-gray-100 transition-colors">
                        Lihat Produk Kami
                    </button>
                </div>
            </section>

            <!-- Loading indicator -->
            <div class="fixed bottom-4 right-4 bg-white p-3 rounded-full shadow-lg">
                <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600"></div>
            </div>
        </div>
    </div>

    <!-- Hide fallback content when React loads -->
    <script>
        // Observer to detect when React content is loaded
        const observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                if (mutation.type === 'childList') {
                    const reactRoot = document.getElementById('react-root');
                    const fallbackContent = document.getElementById('fallback-content');
                    
                    // If React has rendered content (more than just the fallback)
                    if (reactRoot && reactRoot.children.length > 1 || 
                        (reactRoot.children.length === 1 && !reactRoot.children[0].id)) {
                        if (fallbackContent) {
                            fallbackContent.style.display = 'none';
                        }
                        observer.disconnect();
                    }
                }
            });
        });

        observer.observe(document.getElementById('react-root'), {
            childList: true,
            subtree: true
        });

        // Fallback timeout in case observer doesn't work
        setTimeout(() => {
            const fallbackContent = document.getElementById('fallback-content');
            if (fallbackContent) {
                fallbackContent.style.display = 'none';
            }
        }, 10000); // 10 seconds
    </script>
</body>
</html>