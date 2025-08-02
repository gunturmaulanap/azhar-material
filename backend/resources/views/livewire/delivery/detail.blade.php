<div>
    <x-slot name="title">{{ __('Detail Pengiriman Barang') }}</x-slot>

    <x-slot name="breadcrumb">
        @php
            $breadcumb = ['Pengiriman Barang', 'Detail Pengiriman Barang'];
        @endphp
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

    <div class="flex flex-col sm:flex-row items-start sm:justify-between">
        <h2 class="text-2xl font-semibold tracking-tight">{{ $delivery->transaction->name }}</h2>
        <div class="sm:max-w-xs sm:text-right mt-3 sm:mt-0">
            <span class="text-md">{{ $delivery->transaction->phone }}</span><br>
            <span class="text-sm text-gray-500">{{ $delivery->transaction->address }}</span>
        </div>
    </div>

    <div class="flex items-end justify-between mt-6">
        <div>
            <h2 class="text-base font-semibold leading-7 text-gray-900">Detail Pengiriman Barang</h2>
            <p class="mt-1 mb-6 text-sm leading-6 text-gray-600">
                Informasi detail mengenai barang yang terkirim.
            </p>
        </div>
    </div>

    <div x-data="{ open: false }" x-init="open = false" class="rounded-md bg-white mt-0 border-b pb-4">
        <div class="relative w-full overflow-auto">
            <table class="w-full text-sm whitespace-nowrap">
                <thead>
                    <tr class="border-b">
                        <th class="h-10 text-left">
                            <span class="inline-flex font-medium items-center justify-center px-3 text-sm -ml-3">
                                Nama Barang
                            </span>
                        </th>
                        <th class="h-10 px-2 text-center">
                            <span class="inline-flex font-medium items-center justify-center px-3 text-sm -ml-3">
                                Qty
                            </span>
                        </th>
                        <th class="h-10 px-2 text-center">
                            <span class="inline-flex font-medium items-center justify-center px-3 text-sm -ml-3">
                                Terkirim
                            </span>
                        </th>
                        <th class="h-10 px-2 text-center w-[20%]">
                            <span class="inline-flex font-medium items-center justify-center px-3 text-sm -ml-3">

                            </span>
                        </th>
                    </tr>
                </thead>
                <tbody class="border-b">
                    @foreach ($deliveryGoods as $index => $good)
                        <tr>
                            <td class="p-4 px-0">
                                {{ $good['name'] }}
                            </td>
                            <td class="p-4 px-2 text-center">
                                {{ $good['qty'] . ' ' . $good['unit'] }}
                            </td>
                            <td class="p-4 px-2 text-center">
                                {{ $good['delivered'] }}
                                @if (isset($actDeliveries[$index]['qty']))
                                    @if ($actDeliveries[$index]['qty'] > 0)
                                        <span class="text-blue-500">+ {{ $actDeliveries[$index]['qty'] }}</span>
                                    @endif
                                @endif
                                {{ $good['unit'] }}
                            </td>
                            <td class="p-4 px-2 text-center w-[20%]">
                                @if (in_array(auth()->user()->role, ['super_admin', 'admin']))
                                    @if ($good['delivered'] < $good['qty'])
                                        <button type="button" @click="open = true"
                                            wire:click="setDetail({{ $index }})"
                                            class="flex items-center gap-x-2 rounded-md bg-yellow-300 px-2 py-1 text-xs font-semibold text-white shadow-sm hover:bg-yellow-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="size-4">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M9 8.25H7.5a2.25 2.25 0 0 0-2.25 2.25v9a2.25 2.25 0 0 0 2.25 2.25h9a2.25 2.25 0 0 0 2.25-2.25v-9a2.25 2.25 0 0 0-2.25-2.25H15m0-3-3-3m0 0-3 3m3-3V15" />
                                            </svg>
                                            Kelola
                                        </button>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div x-show="open" class="fixed inset-0 flex items-center justify-center z-50"
            x-transition:enter="transition-opacity duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity duration-300"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" style="display: none;">
            <div class="fixed inset-0 bg-gray-500 opacity-75" @click="open = false"></div>
            <div class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:w-full sm:max-w-2xl">
                <button class="absolute inset-x right-0 top-0 rounded-full bg-white" type="button"
                    @click="open = false">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-8 text-red-500">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </button>
                <div class="p-6">
                    <h2 class="text-lg font-semibold">Detail Barang</h2>
                    <p class="">Input jumlah barang yang baru terkirim.</p>
                    <div class="flow-root rounded-lg border border-gray-100 py-3 shadow-sm mt-4">
                        <dl class="-my-3 divide-y divide-gray-100 text-sm">
                            <div class="grid p-3 grid-cols-3 gap-4">
                                <dt class="font-medium text-gray-900">Nama Barang</dt>
                                <dd class="text-gray-700 col-span-2">{{ $detail['name'] ?? '' }}</dd>
                            </div>

                            <div class="grid p-3 grid-cols-3 gap-4">
                                <dt class="font-medium text-gray-900">Qty</dt>
                                <dd class="text-gray-700 col-span-2">{{ $detail['qty'] ?? '' }}
                                    {{ $detail['unit'] ?? '' }}</dd>
                            </div>

                            <div class="grid p-3 grid-cols-3 gap-4">
                                <dt class="font-medium text-gray-900">Terkirim</dt>
                                <dd class="text-gray-700 col-span-2">{{ $detail['delivered'] ?? '' }}
                                    {{ $detail['unit'] ?? '' }}</dd>
                            </div>

                            <div class="grid p-3 grid-cols-3 gap-4 items-center">
                                <dt class="font-medium text-gray-900">Baru terkirim</dt>
                                <dd class="text-gray-700 col-span-2">
                                    <div class="flex items-center gap-x-2">
                                        {{-- Untuk tampilan desktop --}}
                                        <input type="number" value="0" wire:model="detail.input"
                                            class="hidden sm:block w-20 rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">

                                        {{-- Untuk tampilan mobile --}}
                                        <div class="flex items-center sm:hidden gap-x-1">
                                            <button type="button" class="px-2 py-1 bg-gray-200 text-black rounded"
                                                wire:click="decrementInput">-
                                            </button>

                                            <input type="number" value="0" wire:model="detail.input"
                                                class="w-14 text-center rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                                min="0" />

                                            <button type="button" class="px-2 py-1 bg-gray-200 text-black rounded"
                                                wire:click="incrementInput">+
                                            </button>
                                        </div>

                                        {{ $detail['unit'] ?? '' }}
                                    </div>
                                </dd>
                            </div>

                        </dl>
                    </div>

                    <div class="mt-6 flex items-center justify-end gap-x-6">
                        <button wire:click="unsetDetail()" type="button" @click="open = false"
                            class="text-sm font-semibold leading-6 text-gray-900">Batal</button>
                        <button type="button" @click="open = false" wire:click="submit()"
                            class="rounded-md bg-sky-500 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-sky-400">Submit</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-end justify-between mt-8">
            <div>
                <h2 class="text-base font-semibold leading-7 text-gray-900">Aktivitas Pengiriman</h2>
                <p class="mt-1 mb-2 text-sm leading-6 text-gray-600">
                    Informasi detail mengenai aktivitas pengiriman barang yang terkirim.
                </p>
            </div>
        </div>

        <div class="p-2 rounded-md">
            <ol class="flex flex-col items-start gap-2 text-sm font-medium text-gray-500 sm:gap-4">
                @if (count($actDeliveries) === 0 && count($history) === 0)
                    <li class="flex items-center justify-center">
                        <span class="text-gray-600">Belum ada aktivitas</span>
                    </li>
                @else
                    @php
                        // Mengelompokkan aktivitas berdasarkan created_at hingga detik
                        $groupedActivities = collect($actDeliveries)->groupBy(function ($activity) {
                            return \Carbon\Carbon::parse($activity['created_at'])->format('Y-m-d H:i:s'); // Format hingga detik
                        });
                    @endphp
                    @if ($details->isNotEmpty())
                    @endif


                    @foreach ($groupedActivities as $dateTime => $activities)
                        @foreach ($activities as $activity)
                            <li class="flex items-center justify-center gap-2">
                                <span
                                    class="px-2 py-0.5 rounded bg-blue-50 text-center text-sm font-bold text-blue-600 w-1/3 sm:w-fit whitespace-nowrap">
                                    {{ $activity['qty'] }} {{ $activity['unit'] }}
                                </span>
                                <span><span class="font-extrabold">{{ $activity['name'] }}</span> proses pengiriman
                                    dibuat oleh {{ Auth::user()->name }}
                                    pada tanggal
                                    {{ \Carbon\Carbon::parse($activity['created_at'])->translatedFormat('d F Y') }}</span>

                            </li>
                        @endforeach
                    @endforeach

                    <div>
                        @foreach ($groupedHistory as $dateTime => $items)
                            @php
                                // Filter item unik hanya untuk grup saat ini
                                $filteredItems = collect($items)->unique('id');

                                // Format waktu untuk memastikan konsistensi (sampai detik)
                                $dateTimeFormatted = \Carbon\Carbon::parse($dateTime)->format('Y-m-d H:i:s');

                                // Cari detail terkait berdasarkan waktu (sampai detik)
                                $relatedDetail = $details->first(function ($detail) use ($dateTimeFormatted) {
                                    return \Carbon\Carbon::parse($detail->created_at)->format('Y-m-d H:i:s') ===
                                        $dateTimeFormatted;
                                });
                            @endphp

                            <div class="flex items-start gap-4 py-2">
                                <!-- Elemen Tanggal -->
                                <div class="flex items-center gap-2">
                                    <li class="font-bold">
                                        {{ \Carbon\Carbon::parse($dateTime)->translatedFormat('l, d F Y H:i:s') }}
                                    </li>

                                    <!-- Tombol Detail untuk Gambar -->
                                    <div x-data="{ openDetail: null }">
                                        @if ($relatedDetail && $relatedDetail->image)
                                            <button type="button" @click="openDetail = true"
                                                class="px-2 py-1 flex items-center gap-x-2 rounded-md bg-sky-500 text-white text-xs">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                    fill="currentColor" class="size-3">
                                                    <path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" />
                                                    <path fill-rule="evenodd"
                                                        d="M1.323 11.447C2.811 6.976 7.028 3.75 12.001 3.75c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113-1.487 4.471-5.705 7.697-10.677 7.697-4.97 0-9.186-3.223-10.675-7.69a1.762 1.762 0 0 1 0-1.113ZM17.25 12a5.25 5.25 0 1 1-10.5 0 5.25 5.25 0 0 1 10.5 0Z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                Detail
                                            </button>

                                            <!-- Modal -->
                                            <div x-show="openDetail"
                                                class="fixed inset-0 flex items-center justify-center z-50"
                                                x-transition:enter="transition-opacity duration-300"
                                                x-transition:enter-start="opacity-0"
                                                x-transition:enter-end="opacity-100"
                                                x-transition:leave="transition-opacity duration-300"
                                                x-transition:leave-start="opacity-100"
                                                x-transition:leave-end="opacity-0" style="display: none;">
                                                <div class="fixed inset-0 bg-gray-500 opacity-75"
                                                    @click="openDetail = null"></div>
                                                <button class="absolute inset-x right-64 top-32 rounded-full bg-white"
                                                    type="button" @click="openDetail = null">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="size-10 text-red-500">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                    </svg>
                                                </button>
                                                <div
                                                    class="relative bg-white rounded-lg overflow-auto shadow-xl transform transition-all w-full max-w-[60vw] max-h-[60vh]">
                                                    <div class="p-6 grid grid-cols-1 gap-4">
                                                        @foreach ($relatedDetail->image as $image)
                                                            <div class="flex items-center justify-center">
                                                                <img src="{{ asset('storage/' . $image) }}"
                                                                    alt="Image Preview"
                                                                    class="mt-2 max-w-full max-h-[70vh] object-contain object-center">
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- List Item History -->
                            <div class="pl-6">
                                @foreach ($filteredItems as $item)
                                    <li class="flex items-center justify-start gap-2">
                                        <span
                                            class="px-2 py-0.5 rounded bg-gray-50 text-center text-sm font-bold text-gray-600">
                                            {{ $item->qty }} {{ $item->goods->unit }}
                                        </span>
                                        <span><span class="font-extrabold">{{ $item->goods->name }}</span> proses
                                            pengiriman
                                            dibuat oleh
                                            {{ $item->user->name }}</span>
                                    </li>
                                @endforeach
                            </div>
                        @endforeach
                    </div>

                @endif
            </ol>
        </div>

    </div>

    <div class="mt-6 flex flex-col sm:flex-row items-center justify-end sm:justify-between w-full">
        <!-- Bagian Upload Image di Rata Kiri -->
        <div class="w-full sm:w-3/4 mb-4 sm:mb-0 justify-center">
            @if ($isUploading)
                @if (empty($detail['images']))
                    <div
                        class="mt-2 flex justify-center rounded-lg border border-dashed border-gray-900/25 px-4 py-2 w-full sm:w-3/4">
                        <div class="text-center">
                            <svg class="mx-auto h-6 w-6 text-gray-300" viewBox="0 0 24 24" fill="currentColor"
                                aria-hidden="true" data-slot="icon">
                                <path fill-rule="evenodd"
                                    d="M1.5 6a2.25 2.25 0 0 1 2.25-2.25h16.5A2.25 2.25 0 0 1 22.5 6v12a2.25 2.25 0 0 1-2.25 2.25H3.75A2.25 2.25 0 0 1 1.5 18V6ZM3 16.06V18c0 .414.336.75.75.75h16.5A.75.75 0 0 0 21 18v-1.94l-2.69-2.689a1.5 1.5 0 0 0-2.12 0l-.88.879.97.97a.75.75 0 1 1-1.06 1.06l-5.16-5.159a1.5 1.5 0 0 0-2.12 0L3 16.061Zm10.125-7.81a1.125 1.125 0 1 1 2.25 0 1.125 1.125 0 0 1-2.25 0Z"
                                    clip-rule="evenodd" />
                            </svg>
                            <div class="mt-4 flex text-sm leading-6 text-gray-600">
                                <label for="image"
                                    class="relative cursor-pointer rounded-md bg-white font-semibold text-indigo-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-indigo-600 focus-within:ring-offset-2 hover:text-indigo-500">
                                    <span>Upload a file</span>
                                    <input wire:model="detail.images" id="image" type="file" accept="image/*"
                                        class="sr-only" multiple>
                                </label>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="mt-2 mb-3 sm:mb-0 grid grid-cols-1 sm:grid-cols-3 gap-4 w-full">
                        @foreach ($detail['images'] as $index => $image)
                            <div class="col-span-1 relative h-52 w-full sm:w-60">
                                <button class="absolute inset-x -right-2 -top-2 rounded-full bg-white" type="button"
                                    wire:click="deleteImage({{ $index }})">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-8 text-red-500">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                </button>
                                @if ($image instanceof \Livewire\TemporaryUploadedFile)
                                    <img src="{{ $image->temporaryUrl() }}" alt="Image Preview"
                                        class="mt-2 w-full h-full object-contain object-center">
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            @endif


        </div>

        <!-- Bagian Reset, Lihat Transaksi, dan Save di Rata Kanan -->
        @if (in_array(auth()->user()->role, ['super_admin', 'admin']))

            <div class="flex items-center gap-x-6 w-full sm:w-fit justify-end">
                <a href="{{ route('transaction.detail', ['id' => $delivery->transaction_id]) }}"
                    class="text-sm font-semibold leading-6 text-yellow-500">Lihat Transaksi</a>
                @if ($delivery->status !== 'selesai')
                    <button wire:click="resetInput()" type="button"
                        class="text-sm font-semibold leading-6 text-gray-900">Reset</button>
                    <button type="button" wire:click="save"
                        class="rounded-md bg-sky-500 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-sky-400">Simpan</button>
                @endif
            </div>
        @else
            <div class="flex items-center gap-x-6 w-full sm:w-fit justify-end">
                <a href="{{ route('customer.transaction.detail', ['id' => $delivery->transaction_id]) }}"
                    class="text-sm font-semibold leading-6 text-yellow-500">Lihat Transaksi</a>

            </div>
        @endif
    </div>


</div>
