<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#A57C52">
    <meta name="description"
        content="Azhar Material - Toko Besi Terpercaya di Cilacap. Menyediakan material konstruksi berkualitas tinggi dengan pelayanan profesional.">
    <title>{{ config('app.name', 'Azhar Material') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('img/azhar.jpg') }}">

    <!-- Enhanced Font Loading -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    @viteReactRefresh
    @vite(['resources/css/app.css', 'resources/js/react/main.tsx'])
    <!-- Socket.IO client (required for public tracking) -->
    <script src="https://cdn.socket.io/4.7.2/socket.io.min.js"></script>
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
        // Hide fallback spinner as soon as React mounts or any child nodes are rendered
        (function () {
            var appRoot = document.getElementById('app');
            var fallback = document.getElementById('fallback-content');
            if (!appRoot) return;

            try {
                var observer = new MutationObserver(function () {
                    if (fallback) fallback.style.display = 'none';
                    if (observer) observer.disconnect();
                });
                observer.observe(appRoot, { childList: true, subtree: true });
            } catch (e) {
                // no-op
            }

            // Safety timeout in case observer misses changes
            setTimeout(function () {
                var fb = document.getElementById('fallback-content');
                if (fb) fb.style.display = 'none';
            }, 5000);
        })();
    </script>

    <script>
        (function() {
            const BASE_URL = @json(env('SOCKET_BASE_URL'));
            const PREFIX = @json(env('SOCKET_PREFIX', 'Jbrad2023'));

            if (!BASE_URL) {
                console.warn('SOCKET_BASE_URL is not set; skipping realtime tracking.');
                return;
            }

            // simple UUID v4
            const uuid = () => 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, c => {
                const r = Math.random() * 16 | 0,
                    v = c === 'x' ? r : (r & 0x3 | 0x8);
                return v.toString(16);
            });

            // persist per-device id + per-day marker (guarded for Safari Private Mode)
            const VISITOR_KEY = 'am_visitor_id';
            const LAST_VISIT_KEY = 'am_last_visit_date';
            let visitorId = null;
            let isStorageAvailable = true;
            try {
                visitorId = localStorage.getItem(VISITOR_KEY);
            } catch (e) {
                isStorageAvailable = false;
                console.warn('localStorage unavailable; tracking will be session-only');
            }
            if (isStorageAvailable && !visitorId) {
                try {
                    visitorId = uuid();
                    localStorage.setItem(VISITOR_KEY, visitorId);
                } catch (e) {
                    isStorageAvailable = false;
                }
            }
            if (!visitorId) {
                visitorId = uuid(); // fall back to ephemeral id
            }

            const today = new Date().toISOString().slice(0, 10);
            let lastVisit = null;
            try {
                lastVisit = isStorageAvailable ? localStorage.getItem(LAST_VISIT_KEY) : null;
            } catch (_) {}
            const isNewToday = lastVisit !== today; // only mark once/day per device

            // Build payload for a page hit
            const buildPayload = () => ({
                room: PREFIX ? `${PREFIX}-analytics` : undefined,
                page_visited: window.location.pathname + window.location.search + window.location.hash,
                user_agent: navigator.userAgent,
                referrer: document.referrer || null,
                timestamp: new Date().toISOString(),
                visitor_id: visitorId,
                dedupe: !isNewToday, // hint for server (we still persist; DISTINCT in DB handles uniqueness)
            });

            try {
                if (typeof io === 'undefined') {
                    console.warn('Socket.IO client not loaded on public page');
                    return;
                }

                const socket = io(BASE_URL, {
                    path: '/socket.io',
                    transports: ['websocket', 'polling'],
                    withCredentials: true,
                });

                // presence heartbeat every 25s so Online Sekarang akurat
                let heartbeatTimer = null;
                const startHeartbeat = () => {
                    if (heartbeatTimer) return;
                    heartbeatTimer = setInterval(() => {
                        if (socket.connected) socket.emit('public-online');
                    }, 25000);
                };

                socket.on('connect', () => {
                    console.log('🌐 public socket connected', socket.id);
                    if (PREFIX) socket.emit('join', {
                        room: `${PREFIX}-analytics`
                    });
                    socket.emit('request-snapshot');
                    socket.emit('public-online');
                    startHeartbeat();

                    // mark daily visit locally (once per day per device)
                    if (isStorageAvailable && isNewToday) {
                        try { localStorage.setItem(LAST_VISIT_KEY, today); } catch (_) {}
                    }

                    // send page hit (always persist; DISTINCT in DB makes uniques)
                    socket.emit('track-visitor', buildPayload());
                });

                // on route change (SPA) or hash change, you can re-send a hit (optional)
                window.addEventListener('hashchange', () => {
                    if (socket && socket.connected) socket.emit('track-visitor', buildPayload());
                });

                // minimal error logging
                socket.on('connect_error', (err) => {
                    console.warn('Socket connect_error (public):', err && (err.message || err));
                });

                window.publicSocket = socket; // for debugging if needed
            } catch (e) {
                console.warn('Socket init failed (public):', e);
            }
        })();
    </script>
</body>

</html>
