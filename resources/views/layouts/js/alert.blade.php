<script>
    (function() {
        // cegah double-binding
        if (window.__toastBound) return;
        window.__toastBound = true;

        function initToast() {
            const z = window.iziToast;
            if (!z) {
                console.warn('iziToast belum ter-load. Pastikan CSS/JS-nya tersedia (CDN/Vite).');
                return;
            }

            // global settings
            z.settings({
                timeout: 4000,
                progressBar: true,
                position: 'topCenter',
                transitionIn: 'flipInX',
                transitionOut: 'flipOutX',
            });

            // helper ambil pesan
            const msg = (e, fallback) => (e && e.detail && e.detail.message) ? e.detail.message : fallback;

            // --- EVENT KUSTOM (bukan event bawaan browser) ---
            window.addEventListener('toast:success', (e) => {
                z.success({
                    title: 'Berhasil!',
                    message: msg(e, 'Berhasil diproses!')
                });
            });

            window.addEventListener('toast:info', (e) => {
                z.info({
                    title: 'Info',
                    message: msg(e, 'Informasi!')
                });
            });

            window.addEventListener('toast:warning', (e) => {
                z.warning({
                    title: 'Peringatan',
                    message: msg(e, 'Perlu perhatian!')
                });
            });

            window.addEventListener('toast:error', (e) => {
                z.error({
                    title: 'Maaf',
                    message: msg(e, 'Sesuatu ada yang salah!')
                });
            });

            window.addEventListener('toast:confirm', (e) => {
                z.question({
                    timeout: 20000,
                    close: false,
                    overlay: true,
                    displayMode: 'once',
                    id: 'confirm',
                    zindex: 999,
                    title: e?.detail?.title ?? 'Peringatan!',
                    message: e?.detail?.message ?? 'Apakah anda yakin?',
                    position: 'center',
                    buttons: [
                        ['<button><b>YA</b></button>', function(instance, toast) {
                            if (window.Livewire) {
                                Livewire.emit('confirm', e?.detail?.id);
                            }
                            instance.hide({
                                transitionOut: 'fadeOut'
                            }, toast, 'button');
                        }, true],
                        ['<button>TIDAK</button>', function(instance, toast) {
                            instance.hide({
                                transitionOut: 'fadeOut'
                            }, toast, 'button');
                        }],
                    ],
                });
            });

            // --- FLASH SESSION SEKALI DI PAGE LOAD ---
            @if (session('success'))
                z.success({
                    title: 'Berhasil!',
                    message: @json(session('success'))
                });
            @endif

            @if (session('updated'))
                z.success({
                    title: 'Berhasil!',
                    message: @json(session('updated'))
                });
            @endif

            @if (session('error'))
                z.error({
                    title: 'Maaf',
                    message: @json(session('error'))
                });
            @endif
        }

        // Livewire v3: livewire:init. Tetap aman kalau belum ada -> fallback DOMContentLoaded
        if (document.readyState === 'complete' || document.readyState === 'interactive') {
            initToast();
        } else {
            document.addEventListener('DOMContentLoaded', initToast, {
                once: true
            });
        }
        document.addEventListener('livewire:init', initToast, {
            once: true
        });
    })();
</script>
