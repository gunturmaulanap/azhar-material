<script src="{{ asset('vendor/jquery/jquery-3.3.1.min.js') }}"></script>
<script src="{{ asset('vendor/izitoast/dist/js/iziToast.min.js') }}"></script>

<script>
    window.addEventListener('success', event => {
        iziToast.success({
            title: 'Berhasil!',
            message: event.detail.message || 'Data telah terubah!',
        });
    })
    window.addEventListener('info', event => {
        iziToast.info({
            title: 'Perhatian!',
            message: event.detail.message || 'Data telah terubah!',
        });
    })
    window.addEventListener('error', event => {
        iziToast.error({
            title: 'Maaf',
            message: event.detail.message || 'Sesuatu ada yang salah!',
        });
    })
    window.addEventListener('deleted', event => {
        iziToast.warning({
            title: 'Berhasil',
            message: 'Data Terhapus!',
        });
    })

    window.addEventListener('validation', event => {
        iziToast.question({
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

                    Livewire.emit('confirm', event.detail.id);
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
            onClosing: function(instance, toast, closedBy) {
                console.info('Closing | closedBy: ' + closedBy);
            },
            onClosed: function(instance, toast, closedBy) {
                console.info('Closed | closedBy: ' + closedBy);
            }
        });
    })
</script>

<script>
    $(document).ready(function() {
        iziToast.settings({
            timeout: 4000,
            progressBar: true,
            position: 'topCenter',
            transitionIn: 'flipInX',
            transitionOut: 'flipOutX',
        });
    })
    @if (Session::has('success'))
        $(document).ready(function() {
            iziToast.success({
                title: 'Berhasil!',
                message: '{{ session('success') }}',
            });
        })
    @endif

    @if (Session::has('updated'))
        $(document).ready(function() {
            iziToast.success({
                title: 'Berhasil!',
                message: '{{ session('updated') }}',
            });
        })
    @endif
</script>
