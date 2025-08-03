<div>
    <x-slot name="title">{{ __('Retur Barang') }}</x-slot>

    <x-slot name="breadcrumb">
        @php
            $breadcumb = ['Barang', 'Kel. Data Barang', 'Retur Barang'];
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

    <form wire:submit.prevent="save">
        <div class="border-b border-gray-900/10 pb-6">
            <div x-data="{ open: false }" x-init="open = false"
                class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-7">
                <div class="sm:col-span-4">
                    <label for="retur.company" class="block text-sm font-medium leading-6 text-gray-900">Nama
                        perusahaan <span class="text-xs text-red-500">*</span></label>
                    <div class="mt-2">
                        <div class="flex items-center gap-x-4">
                            <input wire:model="retur.company" type="text" id="retur.company" autocomplete="off"
                                disabled
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-sky-500 sm:text-sm sm:leading-6">
                            <button type="button"
                                class="flex items-center gap-x-2 bg-blue-500 rounded-md px-3 py-2 text-white"
                                @click="open = true">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                                </svg>
                                <span class="text-sm text-nowrap">Pilih Supplier</span>
                            </button>
                        </div>
                        @if ($errors->has('retur.company'))
                            <span class="text-xs text-red-500">{{ $errors->first('retur.company') }}</span>
                        @else
                            <span class="text-xs text-gray-500">Input sebagai supplier baru atau pilih supplier</span>
                        @endif
                    </div>
                </div>

                {{-- MODAL SUPPLIER --}}
                <div x-show="open" class="fixed inset-0 flex items-center justify-center z-50"
                    x-transition:enter="transition-opacity duration-300" x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity duration-300"
                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" style="display: none;">
                    <div class="fixed inset-0 bg-gray-500 opacity-75" @click="open = false"></div>
                    <div
                        class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:w-full sm:max-w-4xl">
                        <div class="p-6">
                            <h2 class="text-lg font-semibold">Daftar Supplier</h2>
                            <p class="">Pilih supplier untuk informasi transaksi.</p>

                            <div class="flex items-center justify-between my-4">
                                <div class="flex items-center gap-x-4">
                                    <input wire:model="searchSupplier"
                                        class="flex rounded-md bg-white border-gray-300 px-3 py-1 w-64 text-sm text-gray-800 shadow-sm transition-colors focus:ring-1 h-8 placeholder:text-xs placeholder:text-slate-600"
                                        placeholder="Cari supplier...">
                                </div>
                            </div>

                            <div class="rounded-md border bg-white mt-4 max-h-96 overflow-auto">
                                <div class="relative w-full overflow-auto">
                                    <table class="w-full text-sm">
                                        <thead>
                                            <tr class="border-b">
                                                <th class="h-10 px-4 text-left">
                                                    <span
                                                        class="inline-flex font-medium items-center justify-center px-3 text-sm -ml-3">
                                                        Nama Perusahaan
                                                    </span>
                                                </th>
                                                <th class="h-10 px-4 text-left">
                                                    <span
                                                        class="inline-flex font-medium items-center justify-center px-3 text-sm -ml-3">
                                                        Nama Supplier
                                                    </span>
                                                </th>
                                                <th class="h-10 px-2 text-left">
                                                    <span
                                                        class="inline-flex font-medium items-center justify-center px-3 text-sm -ml-3">
                                                        No Telp
                                                    </span>
                                                </th>
                                                <th class="h-10 px-2 text-left">
                                                    <span
                                                        class="inline-flex font-medium items-center justify-center px-3 text-sm -ml-3">
                                                        Alamat
                                                    </span>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody @click="open = false">
                                            @forelse ($suppliers as $item)
                                                <tr wire:click="setSupplier({{ $item->id }})"
                                                    class="border-b transition-colors hover:bg-gray-50 cursor-pointer">
                                                    <td class="p-2 px-4 w-[18%]">
                                                        {{ $item->company }}
                                                    </td>
                                                    <td class="p-2 px-4 w-[18%]">
                                                        {{ $item->name }}
                                                    </td>
                                                    <td class="p-2">
                                                        {{ $item->phone }}
                                                    </td>
                                                    <td class="p-2 max-w-52">
                                                        <p class="truncate">{{ $item->address }}</p>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4">
                                                        <span
                                                            class="flex items-center justify-center text-red-500 py-1">
                                                            Data tidak ditemukan
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-span-4">
                    <label for="retur.name" class="block text-sm font-medium leading-6 text-gray-900">Nama supplier
                        <span class="text-xs text-red-500">*</span></label>
                    <div class="mt-2">
                        <input wire:model="retur.name" id="retur.name" type="text" disabled
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-sky-500 sm:text-sm sm:leading-6">
                        @error('retur.name')
                            <span class="text-xs text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-span-4">
                    <label for="retur.phone" class="block text-sm font-medium leading-6 text-gray-900">Nomor
                        telp
                        <span class="text-xs text-red-500">*</span></label>
                    <div class="mt-2">
                        <input wire:model="retur.phone" id="retur.phone" type="number" disabled
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-sky-500 sm:text-sm sm:leading-6">
                        @error('retur.phone')
                            <span class="text-xs text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-span-4">
                    <label for="retur.address"
                        class="block text-sm font-medium leading-6 text-gray-900">Alamat</label>
                    <div class="mt-2">
                        <textarea wire:model="retur.address" id="retur.address" rows="3" disabled
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"></textarea>
                    </div>
                </div>
            </div>

            <div class="flex items-end justify-between mt-6">
                <div>
                    <h2 class="text-base font-semibold leading-7 text-gray-900">Detail Transaksi</h2>
                    <p class="mt-1 mb-6 text-sm leading-6 text-gray-600">
                        Pilih barang dan atur Quantity.
                    </p>
                </div>
            </div>

            <div x-data="{ open: false }" x-init="open = false" class="rounded-md bg-white mt-0">
                <div class="relative w-full overflow-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b">
                                <th class="w-[10%] text-left">
                                    <button type="button" @click="open = true"
                                        class="px-4 py-0 bg-blue-500 text-white rounded-md text-lg">+</button>
                                </th>
                                <th class="h-10 text-left">
                                    <span
                                        class="inline-flex font-medium items-center justify-center px-3 text-sm -ml-3">
                                        Nama Barang
                                    </span>
                                </th>
                                <th class="h-10 text-left">
                                    <span
                                        class="inline-flex font-medium items-center justify-center px-3 text-sm -ml-3">
                                        Kategori
                                    </span>
                                </th>
                                <th class="h-10 px-2 text-center">
                                    <span
                                        class="inline-flex font-medium items-center justify-center px-3 text-sm -ml-3">
                                        Qty
                                    </span>
                                </th>
                                <th class="h-10 px-2 text-center">
                                    <span
                                        class="inline-flex font-medium items-center justify-center px-3 text-sm -ml-3">
                                        Satuan
                                    </span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="border-b">
                            @forelse ($goodReturs as $index => $item)
                                <tr>
                                    <th class="w-[10%] text-left px-2">
                                        <button class="text-red-500" type="button"
                                            wire:click="deleteGood({{ $index }})">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                fill="currentColor" class="size-5">
                                                <path fill-rule="evenodd"
                                                    d="M16.5 4.478v.227a48.816 48.816 0 0 1 3.878.512.75.75 0 1 1-.256 1.478l-.209-.035-1.005 13.07a3 3 0 0 1-2.991 2.77H8.084a3 3 0 0 1-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 0 1-.256-1.478A48.567 48.567 0 0 1 7.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 0 1 3.369 0c1.603.051 2.815 1.387 2.815 2.951Zm-6.136-1.452a51.196 51.196 0 0 1 3.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 0 0-6 0v-.113c0-.794.609-1.428 1.364-1.452Zm-.355 5.945a.75.75 0 1 0-1.5.058l.347 9a.75.75 0 1 0 1.499-.058l-.346-9Zm5.48.058a.75.75 0 1 0-1.498-.058l-.347 9a.75.75 0 0 0 1.5.058l.345-9Z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </th>
                                    <td class="">
                                        {{ $item['name'] }}
                                    </td>
                                    <td class="">
                                        {{ $item['category'] }}
                                    </td>
                                    <td class="p-4 px-2 text-center">
                                        <div class="flex items-center justify-center gap-x-2">
                                            <div class="flex items-center rounded border border-gray-200">
                                                <button type="button" wire:click="decrement({{ $index }})"
                                                    class="size-10 leading-10 text-gray-600 transition hover:opacity-75">
                                                    &minus;
                                                </button>

                                                <input type="number" id="qty-{{ $index }}"
                                                    wire:model="goodReturs.{{ $index }}.qty"
                                                    class="h-10 w-16 border-transparent text-center [-moz-appearance:_textfield] sm:text-sm [&::-webkit-inner-spin-button]:m-0 [&::-webkit-inner-spin-button]:appearance-none [&::-webkit-outer-spin-button]:m-0 [&::-webkit-outer-spin-button]:appearance-none" />

                                                <button type="button" wire:click="increment({{ $index }})"
                                                    class="size-10 leading-10 text-gray-600 transition hover:opacity-75">
                                                    &plus;
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center capitalize">
                                        {{ $item['unit'] }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">
                                        <div class="py-2 text-blue-500 text-center">
                                            Pilih barang
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Modal Barang -->
                <div x-show="open" class="fixed inset-0 flex items-center justify-center z-50"
                    x-transition:enter="transition-opacity duration-300" x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity duration-300"
                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" style="display: none;">
                    <div class="fixed inset-0 bg-gray-500 opacity-75" @click="open = false"></div>
                    <div
                        class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:w-full sm:max-w-4xl">
                        <div class="p-6">
                            <h2 class="text-lg font-semibold">Daftar Barang</h2>
                            <p class="">Pilih barang untuk transaksi.</p>

                            <div class="flex items-center justify-between my-4">
                                <div class="flex items-center gap-x-4">
                                    <input wire:model="search"
                                        class="flex rounded-md bg-white border-gray-300 px-3 py-1 w-64 text-sm text-gray-800 shadow-sm transition-colors focus:ring-1 h-8 placeholder:text-xs placeholder:text-slate-600"
                                        placeholder="Cari barang...">
                                    <select wire:model="byCategory"
                                        class="flex rounded-md bg-white border-gray-300 px-3 py-1 w-18 text-sm text-gray-800 shadow-sm transition-colors focus:ring-1 h-8 placeholder:text-xs placeholder:text-slate-600">
                                        <option value="">Semua</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="rounded-md border bg-white mt-4 max-h-96 overflow-auto">
                                <div class="relative w-full overflow-auto">
                                    <table class="w-full text-sm">
                                        <thead>
                                            <tr class="border-b">
                                                <th class="h-10 px-4 text-left">
                                                    <span
                                                        class="inline-flex font-medium items-center justify-center px-3 text-sm -ml-3">
                                                        Nama Barang
                                                    </span>
                                                </th>
                                                <th class="h-10 px-2 text-left">
                                                    <span
                                                        class="inline-flex font-medium items-center justify-center px-3 text-sm -ml-3">
                                                        Kategori
                                                    </span>
                                                </th>
                                                <th class="h-10 px-2 text-left">
                                                    <span
                                                        class="inline-flex font-medium items-center justify-center px-3 text-sm -ml-3">
                                                        Stok
                                                    </span>
                                                </th>
                                                <th class="h-10 px-2 text-center">
                                                    <span
                                                        class="inline-flex font-medium items-center justify-center px-3 text-sm -ml-3">
                                                        Harga Beli
                                                    </span>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody @click="open = false">
                                            @forelse ($goods as $item)
                                                <tr wire:click="addGood({{ $item->id }})"
                                                    class="border-b transition-colors hover:bg-gray-50 cursor-pointer hover:shadow">
                                                    <td class="p-2 px-4">
                                                        {{ $item->name }}
                                                    </td>
                                                    <td class="p-2">
                                                        {{ $item->category->name }}
                                                    </td>
                                                    <td class="p-2 text-left">
                                                        <span class="font-bold">{{ $item->stock }}</span>
                                                        {{ $item->unit }}
                                                    </td>
                                                    <td class="p-2 text-center">
                                                        @currency($item->cost)
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6">
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
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-6 flex items-center justify-end gap-x-6">
            <button wire:click="resetInput()" type="button"
                class="text-sm font-semibold leading-6 text-gray-900">Reset</button>
            <button type="submit"
                class="rounded-md bg-sky-500 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-sky-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-sky-500">Simpan</button>
        </div>
    </form>

</div>

@push('scripts')
    <script>
        $(document).on('input', '[id^="qty-"]', function() {
            if ($(this).val() == "") {
                $(this).val(1).select();
            }
        });
    </script>
@endpush
