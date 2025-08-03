<div>
    <x-slot name="title">{{ __('Transaksi Lama') }}</x-slot>

    <x-slot name="breadcrumb">
        @php
            $breadcumb = ['Transaksi Lama'];
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
        <div class="border-b border-gray-900/10 pb-12">
            <h2 class="text-base font-semibold leading-7 text-gray-900">Formulir Transaksi</h2>
            <p class="mt-1 mb-6 text-sm leading-6 text-gray-600">
                Lengkapi data untuk melakukan transaksi
            </p>
            <div class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-7">
                <div class="sm:col-span-4">
                    <label for="user.name" class="block text-sm font-medium leading-6 text-gray-900">Nama
                        customer <span class="text-xs text-red-500">*</span></label>
                    <div class="mt-2">
                        <input wire:model="user.name" type="text" id="user.name" autocomplete="given-name" autofocus
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-sky-500 sm:text-sm sm:leading-6">
                        @error('user.name')
                            <span class="text-xs text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-span-4">
                    <label for="customer.phone" class="block text-sm font-medium leading-6 text-gray-900">Nomor telp
                        <span class="text-xs text-red-500">*</span></label>
                    <div class="mt-2">
                        <input wire:model="customer.phone" id="customer.phone" type="number"
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-sky-500 sm:text-sm sm:leading-6">
                        @error('customer.phone')
                            <span class="text-xs text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-span-4">
                    <label for="user.address" class="block text-sm font-medium leading-6 text-gray-900">Alamat</label>
                    <div class="mt-2">
                        <textarea wire:model="user.address" id="user.address" rows="3"
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"></textarea>
                    </div>
                </div>
            </div>

            <div class="mt-6">
                <h2 class="text-base font-semibold leading-7 text-gray-900">Detail Transaksi</h2>
                <p class="mt-1 mb-6 text-sm leading-6 text-gray-600">
                    Pilih barang dan atur Quantity.
                </p>
            </div>

            <div class="rounded-md bg-white my-6">
                <div class="relative w-full overflow-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b">
                                <th class="h-10 px-4 text-left w-[10%]">
                                    <span
                                        class="inline-flex font-medium items-center justify-center px-3 text-sm -ml-3">
                                        Kode Barang
                                    </span>
                                </th>
                                <th class="h-10 px-4 text-left">
                                    <span
                                        class="inline-flex font-medium items-center justify-center px-3 text-sm -ml-3">
                                        Nama Barang
                                    </span>
                                </th>
                                <th class="h-10 px-2 text-left">
                                    <span
                                        class="inline-flex font-medium items-center justify-center px-3 text-sm -ml-3">
                                        Harga
                                    </span>
                                </th>
                                <th class="h-10 px-2 text-left">
                                    <span
                                        class="inline-flex font-medium items-center justify-center px-3 text-sm -ml-3">
                                        Qty
                                    </span>
                                </th>
                                <th class="h-10 px-2 text-right">
                                    <span
                                        class="inline-flex font-medium items-center justify-center px-3 text-sm -ml-3">
                                        Subtotal
                                    </span>
                                </th>
                                <th class="w-[10%]">
                                    <button class="px-4 py-0 bg-blue-500 text-white rounded-md text-lg">+</button>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="p-4 text-gray-500">
                                    H2YI64JN8PWOFM97
                                </td>
                                <td class="p-4">
                                    Semen Tiga Roda
                                </td>
                                <td class="p-4 px-2">
                                    @currency(23000)
                                </td>
                                <td class="p-4 px-2">
                                    2 Sak
                                </td>
                                <td class="p-4 px-4 text-right">
                                    @currency(46000)
                                </td>
                                <th class="w-[10%]">
                                    <button class="px-4 py-0 bg-red-500 text-white rounded-md text-lg">x</button>
                                </th>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>



        </div>
        <div class="mt-6 flex items-center justify-end gap-x-6">
            <button wire:click="resetInput()" type="button"
                class="text-sm font-semibold leading-6 text-gray-900">Reset</button>
            <button wire:click="save()" type="submit"
                class="rounded-md bg-sky-500 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-sky-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-sky-500">Simpan</button>
        </div>
    </form>
</div>
