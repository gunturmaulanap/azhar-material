<div>
    <x-slot name="title">{{ __('Riwayat Transaksi') }}</x-slot>

    <x-slot name="breadcrumb">
        @php $breadcumb = ['Transaksi', 'Riwayat Transaksi']; @endphp
        @foreach ($breadcumb as $item)
            <li class="rtl:rotate-180">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd" />
                </svg>
            </li>
            <li>
                <span
                    class="block transition hover:text-gray-700 @if ($loop->last) text-gray-950 font-medium @endif">
                    {{ $item }}
                </span>
            </li>
        @endforeach
    </x-slot>

    {{-- Header Table (Filter Search, Per Page, Date Filter) --}}
    <div class="flex flex-col sm:flex-row items-center justify-between mb-4">
        <div class="flex items-center gap-x-2 xl:gap-x-4 w-full">
            <input wire:model="search"
                class="flex rounded-md bg-white border-gray-300 px-3 py-1 w-full sm:w-64 text-xs sm:text-sm text-gray-800 shadow-sm transition-colors focus:ring-1 h-8 placeholder:text-xs placeholder:text-slate-600"
                placeholder="Cari customer...">
            <select wire:model="perPage"
                class="flex rounded-md bg-white border-gray-300 px-3 py-1 sm:w-20 text-xs sm:text-sm text-gray-800 shadow-sm transition-colors focus:ring-1 h-8 placeholder:text-xs placeholder:text-slate-600">
                <option value="2">2</option>
                <option value="10">10</option>
                <option value="50">50</option>
                <option value="100">100</option>
                <option value="250">250</option>
            </select>
        </div>
        <div class="flex items-center mt-3 sm:mt-0 gap-x-2 xl:gap-x-6 w-full sm:w-fit">
            <label for="startDate"
                class="flex items-center gap-x-2 bg-gradient-to-r from-gray-200 rounded-s-md w-full sm:w-fit">
                <span class="text-xs ps-2">Dari</span>
                <input wire:model="startDate" type="date" id="startDate"
                    class="rounded-r-md bg-white border-gray-300 px-3 py-1 text-xs sm:text-sm text-gray-800 shadow-sm transition-colors focus:ring-1 h-8 placeholder:text-xs placeholder:text-slate-600">
            </label>
            <label for="endDate"
                class="flex items-center gap-x-2 bg-gradient-to-r from-gray-200 rounded-s-md w-full sm:w-fit">
                <span class="text-xs ps-2">Sampai</span>
                <input wire:model="endDate" type="date" id="endDate"
                    class="rounded-r-md bg-white border-gray-300 px-3 py-1 text-xs sm:text-sm text-gray-800 shadow-sm transition-colors focus:ring-1 h-8 placeholder:text-xs placeholder:text-slate-600">
            </label>
        </div>
    </div>

    {{-- Main Table --}}
    <div class="rounded-md border bg-white hidden sm:block">
        <div class="relative w-full overflow-auto">
            <table class="w-full text-sm whitespace-nowrap">
                <thead>
                    <tr class="border-b">
                        <th class="h-10 px-4 text-left">
                            <span class="inline-flex font-medium items-center justify-center px-3 text-sm -ml-3">Nama
                                Customer</span>
                        </th>
                        <th class="h-10 px-2 text-center">
                            <span class="inline-flex font-medium items-center justify-center px-3 text-sm -ml-3">Jumlah
                                <span class="hidden sm:flex">Barang</span></span>
                        </th>
                        <th class="h-10 px-2 text-center">
                            <span
                                class="inline-flex font-medium items-center justify-center px-3 text-sm -ml-3">Tagihan</span>
                        </th>
                        <th class="h-10 px-2 text-center">
                            <span
                                class="inline-flex font-medium items-center justify-center px-3 text-sm -ml-3">Status</span>
                        </th>
                        <th class="h-10 px-2 text-center">
                            <span
                                class="inline-flex font-medium items-center justify-center px-3 text-sm -ml-3">Pengiriman</span>
                        </th>
                        <th class="h-10 px-2 text-right">
                            <span class="inline-flex font-medium items-center justify-center px-3 text-sm -ml-3">Tanggal
                                Transaksi</span>
                        </th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($data as $item)
                        <tr class="border-b transition-colors hover:bg-gray-50" wire:key="tx-{{ $item->id }}">
                            <td class="p-2 px-4 w-[20%]">{{ $item->name }}</td>
                            <td class="p-2 text-center">{{ $item->goods()->count() }}</td>
                            <td class="p-2 text-center">@currency($item->total)</td>
                            <td
                                class="p-2 text-center capitalize @if ($item->status !== 'selesai') text-yellow-600 @else text-green-600 @endif">
                                {{ $item->status }}</td>
                            <td class="p-2 text-center"><span
                                    class="capitalize">{{ $item->delivery->status ?? '' }}</span></td>
                            <td class="px-4 text-right">
                                {{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d F Y') }}</td>
                            <td class="py-2">
                                <div class="flex items-center gap-x-4 justify-center">
                                    <a href="{{ route(str_replace('_', '', auth()->user()->role) . '.transaction.detail', ['id' => $item->id]) }}"
                                        class="px-2 py-1 flex items-center gap-x-2 rounded-md bg-sky-500 text-white text-xs">
                                        {{-- icon --}} Detail
                                    </a>

                                    @if (auth()->user()->role === 'super_admin')
                                        <button type="button" wire:click="validationDelete({{ $item->id }})"
                                            wire:loading.attr="disabled"
                                            class="px-2 py-1 flex items-center gap-x-2 rounded-md bg-red-500 text-xs text-white">
                                            {{-- icon --}} Hapus
                                        </button>
                                    @endif

                                    @if ($item->status == 'draft')
                                        <a href="{{ route('master.update-customer', ['id' => $item->id]) }}"
                                            class="px-2 py-1 flex items-center gap-x-2 rounded-md bg-gray-100 text-xs">
                                            {{-- icon --}} Ubah
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="py-2 text-red-500 text-center">Data tidak ditemukan</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Mobile Table --}}
    <div class="space-y-3 sm:hidden">
        @foreach ($data as $item)
            <div class="flex flex-col items-start gap-2 rounded-lg border p-3 text-left text-sm w-full"
                wire:key="txm-{{ $item->id }}">
                <div class="flex w-full flex-col gap-1">
                    <div class="text-lg font-semibold">{{ $item->name }}</div>
                </div>
                <div class="flex items-center justify-between text-sm w-full pb-2 border-b border-slate-100">
                    <span class="text-gray-700">Tanggal Transaksi</span>
                    <span>{{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d F Y') }}</span>
                </div>
                <div class="flex items-center justify-between text-sm w-full pb-2 border-b border-slate-100">
                    <span class="text-gray-700">Jumlah Barang</span>
                    <span>{{ $item->goods()->count() }} Barang <span
                            class=" text-yellow-600">{{ $item->delivery->status ?? '' }}</span></span>
                </div>
                <div class="flex items-center justify-between text-sm w-full pb-2 border-b border-slate-100">
                    <span>Tagihan</span>
                    <span class="font-bold">@currency($item->total)</span>
                </div>
                <div class="flex items-center justify-between text-sm w-full pb-2 border-b border-slate-100">
                    <span>Status</span>
                    <span
                        class="font-normal capitalize {{ $item->status == 'selesai' ? 'text-green-600' : 'text-yellow-600' }}">{{ $item->status }}</span>
                </div>
                <div class="flex items-center gap-2 w-full justify-end mt-2">
                    <a href="{{ route(str_replace('_', '', auth()->user()->role) . '.transaction.detail', ['id' => $item->id]) }}"
                        class="px-2 py-1 flex items-center gap-x-2 rounded-md bg-sky-500 text-white text-xs">Detail</a>

                    @if (auth()->user()->role === 'super_admin')
                        <button type="button" wire:click="validationDelete({{ $item->id }})"
                            wire:loading.attr="disabled"
                            class="px-2 py-1 flex items-center gap-x-2 rounded-md bg-red-500 text-xs text-white">
                            Hapus
                        </button>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <div class="pt-4">
        {{ $data->links() }}
    </div>
</div>

@push('scripts')
    <script>
        // Set perPage berdasarkan lebar layar (tanpa membuat root kedua & tanpa listener ganda)
        (function() {
            function applyPerPage() {
                var isMobile = window.matchMedia('(max-width: 768px)').matches;
                try {
                    @this.set('perPage', isMobile ? 2 : 10);
                } catch (e) {
                    // no-op
                }
            }
            document.addEventListener('livewire:load', function() {
                applyPerPage();
                window.addEventListener('resize', applyPerPage, {
                    passive: true
                });
            }, {
                once: true
            });
        })();
    </script>
@endpush
