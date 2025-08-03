<div>
    <x-slot name="title">{{ __('Data Order') }}</x-slot>

    <x-slot name="breadcrumb">
        @php
            $data = ['Data Order'];
        @endphp
        @foreach ($data as $item)
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
    <div class="flex flex-col xl:flex-row items-center justify-between mb-4 gap-y-3 gap-x-4 xl:gap-y-0">
        <div class="flex items-center gap-x-2 sm:gap-x-4 w-full xl:w-fit">
            <input wire:model="search"
                class="flex rounded-md bg-white border-gray-300 px-3 py-1 w-full sm:w-64 text-sm text-gray-800 shadow-sm transition-colors focus:ring-1 h-8 placeholder:text-xs placeholder:text-slate-600"
                placeholder="Cari supplier...">
            <select wire:model="perPage"
                class="flex rounded-md bg-white border-gray-300 px-3 py-1 w-20 text-sm text-gray-800 shadow-sm transition-colors focus:ring-1 h-8 placeholder:text-xs placeholder:text-slate-600">
                <option value="2">2</option>
                <option value="10">10</option>
                <option value="50">50</option>
                <option value="100">100</option>
                <option value="250">250</option>
            </select>
        </div>
        <div class="flex flex-wrap items-center justify-start sm:justify-between gap-2 sm:gap-4 w-full ">
            <div class="flex flex-wrap sm:flex-nowrap items-center gap-2 sm:gap-4 ml-auto">
                <label for="startDate" class="flex items-center gap-x-2 bg-gradient-to-r from-gray-200 rounded-s-md">
                    <span class="text-xs ps-2">Dari</span>
                    <input wire:model="startDate" type="date" id="startDate"
                        class="flex rounded-r-md bg-white border-gray-300 px-3 py-1 text-xs sm:text-sm text-gray-800 shadow-sm transition-colors focus:ring-1 h-8 placeholder:text-xs placeholder:text-slate-600">
                </label>
                <label for="endDate" class="flex items-center gap-x-2 bg-gradient-to-r from-gray-200 rounded-s-md">
                    <span class="text-xs ps-2">Sampai</span>
                    <input wire:model="endDate" type="date" id="endDate"
                        class="flex rounded-r-md bg-white border-gray-300 px-3 py-1 text-xs sm:text-sm text-gray-800 shadow-sm transition-colors focus:ring-1 h-8 placeholder:text-xs placeholder:text-slate-600">
                </label>
                <a href="{{ route('order.create') }}"
                    class="inline-flex whitespace-nowrap items-center gap-x-2 px-2 py-1.5 text-xs bg-sky-500 text-white font-extrabold rounded-md shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4">
                        <path fill-rule="evenodd"
                            d="M12 3.75a.75.75 0 0 1 .75.75v6.75h6.75a.75.75 0 0 1 0 1.5h-6.75v6.75a.75.75 0 0 1-1.5 0v-6.75H4.5a.75.75 0 0 1 0-1.5h6.75V4.5a.75.75 0 0 1 .75-.75Z"
                            clip-rule="evenodd" />
                    </svg>
                    Tambah Data
                </a>
            </div>

        </div>
    </div>

    {{-- Main Table --}}
    <div class="rounded-md border bg-white hidden sm:block">
        <div class="relative w-full overflow-auto">
            <table class="w-full text-sm whitespace-nowrap">
                <thead>
                    <tr class="border-b">
                        <th class="h-10 px-4 text-left">
                            <span class="inline-flex font-medium items-center justify-center px-3 text-sm -ml-3">
                                Nama Supplier
                            </span>
                        </th>
                        <th class="h-10 px-2 text-center">
                            <span class="inline-flex font-medium items-center justify-center px-3 text-sm -ml-3">
                                Jumlah Barang
                            </span>
                        </th>
                        <th class="h-10 px-2 text-center">
                            <span class="inline-flex font-medium items-center justify-center px-3 text-sm -ml-3">
                                Tagihan
                            </span>
                        </th>
                        <th class="h-10 px-2 text-right">
                            <span class="inline-flex font-medium items-center justify-center px-3 text-sm -ml-3">
                                Tanggal Order
                            </span>
                        </th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $item)
                        <tr class="border-b transition-colors hover:bg-gray-50">
                            <td class="p-2 px-4 w-[20%]">
                                {{ $item->supplier->name }}
                            </td>
                            <td class="p-2 text-center">
                                {{ $item->goods()->count() }}
                            </td>
                            <td class="p-2 text-center">
                                @currency($item->total)
                            </td>
                            <td class="px-4 text-right">
                                {{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d F Y') }}
                            </td>
                            <td class="py-2">
                                <div class="flex items-center gap-x-4 justify-center">
                                    <a href="{{ route('order.detail', ['id' => $item->id]) }}"
                                        class="px-2 py-1 flex items-center gap-x-2 rounded-md bg-sky-500 text-white text-xs">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                            class="size-3">
                                            <path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" />
                                            <path fill-rule="evenodd"
                                                d="M1.323 11.447C2.811 6.976 7.028 3.75 12.001 3.75c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113-1.487 4.471-5.705 7.697-10.677 7.697-4.97 0-9.186-3.223-10.675-7.69a1.762 1.762 0 0 1 0-1.113ZM17.25 12a5.25 5.25 0 1 1-10.5 0 5.25 5.25 0 0 1 10.5 0Z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        Detail
                                    </a>
                                    @if ($item->status !== 'Selesai')
                                        <button wire:click="validationDelete({{ $item->id }})"
                                            class="px-2 py-1 flex items-center gap-x-2 rounded-md bg-red-500 text-xs text-white">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                fill="currentColor" class="size-3">
                                                <path fill-rule="evenodd"
                                                    d="M16.5 4.478v.227a48.816 48.816 0 0 1 3.878.512.75.75 0 1 1-.256 1.478l-.209-.035-1.005 13.07a3 3 0 0 1-2.991 2.77H8.084a3 3 0 0 1-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 0 1-.256-1.478A48.567 48.567 0 0 1 7.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 0 1 3.369 0c1.603.051 2.815 1.387 2.815 2.951Zm-6.136-1.452a51.196 51.196 0 0 1 3.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 0 0-6 0v-.113c0-.794.609-1.428 1.364-1.452Zm-.355 5.945a.75.75 0 1 0-1.5.058l.347 9a.75.75 0 1 0 1.499-.058l-.346-9Zm5.48.058a.75.75 0 1 0-1.498-.058l-.347 9a.75.75 0 0 0 1.5.058l.345-9Z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            Hapus
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="py-2 text-red-500 text-center">
                                    Data tidak ditemukan
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Mobile Table --}}
    <div class="space-y-3 sm:hidden">
        @foreach ($orders as $item)
            <button class="flex flex-col items-start gap-2 rounded-lg border p-3 text-left text-sm w-full">
                <div class="flex w-full flex-col gap-1">
                    <div class="text-lg font-semibold">{{ $item->supplier->company }}</div>
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
                <div class="flex items-center gap-2 w-full justify-end mt-2">
                    <a href="{{ route('order.detail', ['id' => $item->id]) }}"
                        class="px-2 py-1 flex items-center gap-x-2 rounded-md bg-sky-500 text-white text-xs">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-3">
                            <path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" />
                            <path fill-rule="evenodd"
                                d="M1.323 11.447C2.811 6.976 7.028 3.75 12.001 3.75c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113-1.487 4.471-5.705 7.697-10.677 7.697-4.97 0-9.186-3.223-10.675-7.69a1.762 1.762 0 0 1 0-1.113ZM17.25 12a5.25 5.25 0 1 1-10.5 0 5.25 5.25 0 0 1 10.5 0Z"
                                clip-rule="evenodd" />
                        </svg>
                        Detail
                    </a>
                    @if ($item->status !== 'Selesai')
                        <a wire:click="validationDelete({{ $item->id }})"
                            class="px-2 py-1 flex items-center gap-x-2 rounded-md bg-red-500 text-xs text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                class="size-3">
                                <path fill-rule="evenodd"
                                    d="M16.5 4.478v.227a48.816 48.816 0 0 1 3.878.512.75.75 0 1 1-.256 1.478l-.209-.035-1.005 13.07a3 3 0 0 1-2.991 2.77H8.084a3 3 0 0 1-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 0 1-.256-1.478A48.567 48.567 0 0 1 7.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 0 1 3.369 0c1.603.051 2.815 1.387 2.815 2.951Zm-6.136-1.452a51.196 51.196 0 0 1 3.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 0 0-6 0v-.113c0-.794.609-1.428 1.364-1.452Zm-.355 5.945a.75.75 0 1 0-1.5.058l.347 9a.75.75 0 1 0 1.499-.058l-.346-9Zm5.48.058a.75.75 0 1 0-1.498-.058l-.347 9a.75.75 0 0 0 1.5.058l.345-9Z"
                                    clip-rule="evenodd" />
                            </svg>
                            Hapus
                        </a>
                    @endif
                </div>
            </button>
        @endforeach
    </div>
    <div class="pt-4">
        {{ $orders->links() }}
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Fungsi untuk mendeteksi ukuran layar
        function isMobile() {
            return window.innerWidth <= 768; // Misalkan 768px adalah batas untuk mobile
        }

        // Jika layar dalam mode mobile
        if (isMobile()) {
            // Mengirim nilai ke Livewire
            Livewire.emit('perpage', 2); // Atur perPage menjadi 5 untuk mobile
        } else {
            Livewire.emit('perpage', 10); // Atur perPage menjadi 10 untuk desktop
        }
    });
</script>
