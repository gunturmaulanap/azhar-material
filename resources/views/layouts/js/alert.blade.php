<script>
    // jalan setelah Livewire siap (supaya Livewire.emit dijamin ada)
    document.addEventListener('livewire:load', () => {
        const z = window.iziToast;

        if (!z) {
            console.warn('iziToast belum ter-load. Pastikan CDN/Vite untuk iziToast sudah disertakan.');
            return;
        }

        // global settings (pengganti $(document).ready)
        z.settings({
            timeout: 4000,
            progressBar: true,
            position: 'topCenter',
            transitionIn: 'flipInX',
            transitionOut: 'flipOutX',
        });

        // helper baca message aman
        const msg = (e, fallback) => (e?.detail?.message ?? fallback);

        window.addEventListener('success', (e) => {
            z.success({
                title: 'Berhasil!',
                message: msg(e, 'Data telah terubah!')
            });
        });

        window.addEventListener('info', (e) => {
            z.info({
                title: 'Perhatian!',
                message: msg(e, 'Data telah terubah!')
            });
        });

        window.addEventListener('error', (e) => {
            z.error({
                title: 'Maaf',
                message: msg(e, 'Sesuatu ada yang salah!')
            });
        });

        window.addEventListener('deleted', () => {
            z.warning({
                title: 'Berhasil',
                message: 'Data Terhapus!'
            });
        });

        window.addEventListener('validation', (e) => {
            z.question({
                timeout: 20000,
                close: false,
                overlay: true,
                displayMode: 'once',
                id: 'question',
                zindex: 999,
                title: 'Peringatan!',
                message: 'Apakah anda yakin?',
                position: 'center',
                buttons: [
                    ['<button><b>YA</b></button>', function(instance, toast) {
                        // pastikan Livewire ada
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

        // tampilkan flash dari session (tanpa jQuery)
        @if (Session::has('success'))
            z.success({
                title: 'Berhasil!',
                message: @json(session('success'))
            });
        @endif

        @if (Session::has('updated'))
            z.success({
                title: 'Berhasil!',
                message: @json(session('updated'))
            });
        @endif
    });
</script>
