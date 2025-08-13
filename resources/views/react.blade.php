<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#A57C52">
    <meta property="og:locale" content="id_ID">
    <link rel="alternate" href="https://azharmaterial.com/" hreflang="id">
    <link rel="alternate" href="https://azharmaterial.com/" hreflang="x-default">

    <title>Azhar Material — Toko Bahan Bangunan Terpercaya</title>
    <meta name="description"
        content="Azhar Material - Toko Besi Terpercaya di Cilacap. Menyediakan material konstruksi berkualitas tinggi dengan pelayanan profesional.">
    <link rel="canonical" href="https://azharmaterial.com/">

    <!-- Favicon & PWA -->
    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" type="image/png" href="/img/logo.png">
    <link rel="apple-touch-icon" href="/img/logo.png">
    <link rel="manifest" href="/site.webmanifest">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <!-- Open Graph / Twitter -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="Azhar Material — Toko Bahan Bangunan Terpercaya">
    <meta property="og:description" content="Material konstruksi terpercaya. Pengiriman cepat sekitar Cilacap Barat.">
    <meta property="og:url" content="https://azharmaterial.com/">
    <meta property="og:image" content="https://azharmaterial.com/img/og/og-home.jpg">
    <meta property="og:site_name" content="Azhar Material">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Azhar Material — Toko Bahan Bangunan Terpercaya">
    <meta name="twitter:description" content="Material konstruksi terpercaya. Pengiriman cepat sekitar Cilacap Barat.">
    <meta name="twitter:image" content="https://azharmaterial.com/img/og/og-home.jpg">
    <meta name="robots" content="index, follow">

    <!-- Schema.org -->
    <script type="application/ld+json">
  {
    "@context":"https://schema.org",
    "@type":"Organization",
    "name":"Azhar Material",
    "url":"https://azharmaterial.com/",
    "logo":"https://azharmaterial.com/img/logo.png",
    "sameAs":[]
  }
  </script>
    <script type="application/ld+json">
  {
    "@context":"https://schema.org",
    "@type":"WebSite",
    "name":"Azhar Material",
    "url":"https://azharmaterial.com/",
    "potentialAction":{
      "@type":"SearchAction",
      "target":"https://azharmaterial.com/products?search={query}",
      "query-input":"required name=query"
    }
  }
  </script>

    @env('local')
    @viteReactRefresh
    @endenv

    {{-- Bootstrap auth utk FE --}}
    @php
        $isWeb = auth('web')->check();
        $isCustomer = auth('customer')->check();
        $authed = $isWeb || $isCustomer;
        $u = $isWeb ? auth('web')->user() : ($isCustomer ? auth('customer')->user() : null);
        if ($u && empty($u->role)) {
            $u->role = 'customer';
        }
        $bootUser = $u ? ['id' => $u->id, 'name' => $u->name, 'email' => $u->email, 'role' => $u->role] : null;
        $boot = ['isAuth' => (bool) $authed, 'user' => $bootUser];
    @endphp
    <script>
        window.__BOOT__ = {!! json_encode($boot, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!};
    </script>

    <!-- Vite assets -->
    @vite(['resources/css/app.css', 'resources/js/react/main.tsx'])

    <!-- Socket.IO (opsional) -->
    <script src="https://cdn.socket.io/4.7.2/socket.io.min.js" defer></script>
</head>

<body class="font-sans antialiased bg-gradient-to-br from-gray-50 via-white to-gray-100">
    <div id="app" class="min-h-screen">
        <div id="fallback-content" class="min-h-screen bg-gray-50 flex items-center justify-center">
            <div class="text-center">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary mx-auto mb-4"></div>
                <p class="text-gray-600">Loading...</p>
            </div>
        </div>
    </div>

    <script>
        (function() {
            var appRoot = document.getElementById('app'),
                fallback = document.getElementById('fallback-content');
            if (!appRoot || !fallback) return;
            try {
                var obs = new MutationObserver(function() {
                    fallback.style.display = 'none';
                    obs.disconnect();
                });
                obs.observe(appRoot, {
                    childList: true,
                    subtree: true
                });
            } catch (e) {}
            setTimeout(function() {
                fallback.style.display = 'none';
            }, 10000);
        })();
    </script>

    <script>
        (function() {
            const BASE_URL = @json(env('SOCKET_BASE_URL'));
            const PREFIX = @json(env('SOCKET_PREFIX', 'Jbrad2023'));
            if (!BASE_URL || typeof io === 'undefined') return;

            const uuid = () => 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, c => {
                const r = Math.random() * 16 | 0,
                    v = c === 'x' ? r : (r & 3 | 8);
                return v.toString(16);
            });

            const VISITOR_KEY = 'am_visitor_id',
                LAST_VISIT_KEY = 'am_last_visit_date';
            let visitorId = null,
                storeOK = true;
            try {
                visitorId = localStorage.getItem(VISITOR_KEY);
            } catch (_) {
                storeOK = false;
            }
            if (storeOK && !visitorId) {
                try {
                    visitorId = uuid();
                    localStorage.setItem(VISITOR_KEY, visitorId);
                } catch (_) {
                    storeOK = false;
                }
            }
            if (!visitorId) visitorId = uuid();

            const today = new Date().toISOString().slice(0, 10);
            let last = null;
            try {
                last = storeOK ? localStorage.getItem(LAST_VISIT_KEY) : null;
            } catch (_) {}
            const isNewToday = last !== today;

            const payload = () => ({
                room: PREFIX ? `${PREFIX}-analytics` : undefined,
                page_visited: location.pathname + location.search + location.hash,
                user_agent: navigator.userAgent,
                referrer: document.referrer || null,
                timestamp: new Date().toISOString(),
                visitor_id: visitorId,
                dedupe: !isNewToday,
            });

            try {
                const socket = io(BASE_URL, {
                    path: '/socket.io',
                    transports: ['websocket', 'polling'],
                    withCredentials: true
                });
                let t = null;
                const beat = () => {
                    if (t) return;
                    t = setInterval(() => {
                        if (socket.connected) socket.emit('public-online');
                    }, 25000);
                };
                socket.on('connect', () => {
                    if (PREFIX) socket.emit('join', {
                        room: `${PREFIX}-analytics`
                    });
                    socket.emit('request-snapshot');
                    socket.emit('public-online');
                    beat();
                    if (storeOK && isNewToday) {
                        try {
                            localStorage.setItem(LAST_VISIT_KEY, today);
                        } catch (_) {}
                    }
                    socket.emit('track-visitor', payload());
                });
                addEventListener('hashchange', () => {
                    if (socket.connected) socket.emit('track-visitor', payload());
                });
            } catch (e) {
                console.warn('Socket init failed (public):', e);
            }
        })();
    </script>
</body>

</html>
