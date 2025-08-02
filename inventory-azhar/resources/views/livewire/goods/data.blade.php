<div>
    <x-slot name="title">{{ __('Data Barang') }}</x-slot>

    <x-slot name="breadcrumb">
        @php
            $breadcumb = ['Data', 'Barang'];
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

    {{-- Header Table (Filter Search, Per Page, Create Button) --}}
    <div x-data="{ open: false }" x-init="open = false"
        class="flex flex-col xl:flex-row items-center justify-between gap-y-3 mb-4">
        <div class="flex flex-col sm:flex-row items-center gap-y-3 sm:gap-y-0 sm:gap-x-4 w-full sm:w-fit">
            <div class="flex items-center gap-x-2 sm:gap-x-4 w-full">
                <input wire:model="search"
                    class="flex rounded-md bg-white border-gray-300 px-3 py-1 w-full sm:w-64 text-sm text-gray-800 shadow-sm transition-colors focus:ring-1 h-8 placeholder:text-xs placeholder:text-slate-600"
                    placeholder="Cari barang...">
                <select wire:model="perPage"
                    class="flex rounded-md bg-white border-gray-300 px-3 py-1 sm:w-20 text-sm text-gray-800 shadow-sm transition-colors focus:ring-1 h-8 placeholder:text-xs placeholder:text-slate-600">
                    <option value="3">3</option>
                    <option value="10">10</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                    <option value="250">250</option>
                </select>


                <select wire:model="byCategory"
                    class="flex rounded-md bg-white border-gray-300 px-3 py-1 w-full sm:w-18 text-sm text-gray-800 shadow-sm transition-colors focus:ring-1 h-8 placeholder:text-xs placeholder:text-slate-600">
                    <option value="">Semua Kategori</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                <select wire:model="byBrand"
                    class="flex rounded-md bg-white border-gray-300 px-3 py-1 w-full sm:w-18 text-sm text-gray-800 shadow-sm transition-colors focus:ring-1 h-8 placeholder:text-xs placeholder:text-slate-600">
                    <option value="">Semua Merk</option>
                    @foreach ($brands as $brand)
                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="flex items-center gap-x-2" x-data="{ openBrand: false }">
            <button type="button" @click="open = true"
                class="inline-flex items-center gap-x-2 px-2 py-1.5 text-xs bg-yellow-500 text-white font-extrabold rounded-md shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4">
                    <path fill-rule="evenodd"
                        d="M3.792 2.938A49.069 49.069 0 0 1 12 2.25c2.797 0 5.54.236 8.209.688a1.857 1.857 0 0 1 1.541 1.836v1.044a3 3 0 0 1-.879 2.121l-6.182 6.182a1.5 1.5 0 0 0-.439 1.061v2.927a3 3 0 0 1-1.658 2.684l-1.757.878A.75.75 0 0 1 9.75 21v-5.818a1.5 1.5 0 0 0-.44-1.06L3.13 7.938a3 3 0 0 1-.879-2.121V4.774c0-.897.64-1.683 1.542-1.836Z"
                        clip-rule="evenodd" />
                </svg>
                Kelola Kategori
            </button>
            <button type="button" @click="openBrand = true"
                class="inline-flex items-center gap-x-2 px-2 py-1.5 text-xs bg-yellow-500 text-white font-extrabold rounded-md shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4">
                    <path fill-rule="evenodd"
                        d="M3.792 2.938A49.069 49.069 0 0 1 12 2.25c2.797 0 5.54.236 8.209.688a1.857 1.857 0 0 1 1.541 1.836v1.044a3 3 0 0 1-.879 2.121l-6.182 6.182a1.5 1.5 0 0 0-.439 1.061v2.927a3 3 0 0 1-1.658 2.684l-1.757.878A.75.75 0 0 1 9.75 21v-5.818a1.5 1.5 0 0 0-.44-1.06L3.13 7.938a3 3 0 0 1-.879-2.121V4.774c0-.897.64-1.683 1.542-1.836Z"
                        clip-rule="evenodd" />
                </svg>
                Kelola Merk
            </button>
            <div x-show="openBrand" class="fixed inset-0 flex items-center justify-center z-50"
                x-transition:enter="transition-opacity duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity duration-300"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" style="display: none;">
                <div class="fixed inset-0 bg-gray-500 opacity-75" @click="openBrand = null"></div>
                <div
                    class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:w-full sm:max-w-4xl">
                    <button class="absolute inset-x right-0 top-0 rounded-full bg-white" type="button"
                        @click="openBrand = false">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-8 text-red-500">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                    </button>
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-lg font-semibold">Daftar Merk</h2>
                                <p class="">Setiap merk dimiliki beberapa barang.</p>
                            </div>
                            <a href="{{ route('goods.brand-create') }}"
                                class="inline-flex items-center gap-x-2 px-2 py-1.5 text-xs bg-sky-500 text-white font-extrabold rounded-md shadow-md">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                    class="size-4">
                                    <path fill-rule="evenodd"
                                        d="M12 3.75a.75.75 0 0 1 .75.75v6.75h6.75a.75.75 0 0 1 0 1.5h-6.75v6.75a.75.75 0 0 1-1.5 0v-6.75H4.5a.75.75 0 0 1 0-1.5h6.75V4.5a.75.75 0 0 1 .75-.75Z"
                                        clip-rule="evenodd" />
                                </svg>
                                Tambah Data
                            </a>
                        </div>

                        <div class="rounded-md border bg-white mt-4 max-h-96 overflow-auto">
                            <div class="relative w-full overflow-auto">
                                <table class="w-full text-sm">
                                    <thead>
                                        <tr class="border-b">
                                            <th class="h-10 px-4 text-left">
                                                <span
                                                    class="inline-flex font-medium items-center justify-center px-3 text-sm -ml-3">
                                                    Nama Merk
                                                </span>
                                            </th>
                                            <th class="h-10 px-2 text-left">
                                                <span
                                                    class="inline-flex font-medium items-center justify-center px-3 text-sm -ml-3">
                                                    Barang-barang
                                                </span>
                                            </th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($brands as $brand)
                                            <tr class="border-b transition-colors hover:bg-gray-50">
                                                <td class="p-2 px-4">
                                                    {{ $brand->name }}
                                                </td>
                                                <td class="p-2 truncate">
                                                    <div class="grid grid-cols-3 gap-2 w-full overflow-x-auto">
                                                        @foreach ($brand->goods as $good)
                                                            <div class="text-xs truncate">{{ $good->name }}</div>
                                                        @endforeach
                                                    </div>
                                                </td>
                                                <td class="py-2">
                                                    <div class="flex items-center gap-x-4 justify-center">
                                                        <a href="{{ route('goods.brand-update', ['id' => $brand->id]) }}"
                                                            class="px-2 py-1 flex items-center gap-x-2 rounded-md bg-gray-100 text-xs">
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                viewBox="0 0 24 24" stroke-width="1.5"
                                                                stroke="currentColor" class="size-3">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                                            </svg>
                                                            Ubah
                                                        </a>
                                                        <button wire:click="validationDelete({{ $brand->id }})"
                                                            class="px-2 py-1 flex items-center gap-x-2 rounded-md bg-red-500 text-xs text-white">
                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                viewBox="0 0 24 24" fill="currentColor"
                                                                class="size-3">
                                                                <path fill-rule="evenodd"
                                                                    d="M16.5 4.478v.227a48.816 48.816 0 0 1 3.878.512.75.75 0 1 1-.256 1.478l-.209-.035-1.005 13.07a3 3 0 0 1-2.991 2.77H8.084a3 3 0 0 1-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 0 1-.256-1.478A48.567 48.567 0 0 1 7.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 0 1 3.369 0c1.603.051 2.815 1.387 2.815 2.951Zm-6.136-1.452a51.196 51.196 0 0 1 3.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 0 0-6 0v-.113c0-.794.609-1.428 1.364-1.452Zm-.355 5.945a.75.75 0 1 0-1.5.058l.347 9a.75.75 0 1 0 1.499-.058l-.346-9Zm5.48.058a.75.75 0 1 0-1.498-.058l-.347 9a.75.75 0 0 0 1.5.058l.345-9Z"
                                                                    clip-rule="evenodd" />
                                                            </svg>
                                                            Hapus
                                                        </button>
                                                    </div>
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
            <a href="{{ route('goods.create') }}"
                class="inline-flex items-center gap-x-2 px-2 py-1.5 text-xs bg-sky-500 text-white font-extrabold rounded-md shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4">
                    <path fill-rule="evenodd"
                        d="M12 3.75a.75.75 0 0 1 .75.75v6.75h6.75a.75.75 0 0 1 0 1.5h-6.75v6.75a.75.75 0 0 1-1.5 0v-6.75H4.5a.75.75 0 0 1 0-1.5h6.75V4.5a.75.75 0 0 1 .75-.75Z"
                        clip-rule="evenodd" />
                </svg>
                Tambah Data
            </a>
        </div>

        {{-- MODAL KATEGORI --}}
        <div x-show="open" class="fixed inset-0 flex items-center justify-center z-50"
            x-transition:enter="transition-opacity duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity duration-300"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" style="display: none;">
            <div class="fixed inset-0 bg-gray-500 opacity-75" @click="open = false"></div>
            <div class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:w-full sm:max-w-4xl">
                <button class="absolute inset-x right-0 top-0 rounded-full bg-white" type="button"
                    @click="open = false">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-8 text-red-500">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </button>
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-semibold">Daftar Kategori</h2>
                            <p class="">Setiap kategori dimiliki beberapa barang.</p>
                        </div>
                        <a href="{{ route('goods.category-create') }}"
                            class="inline-flex items-center gap-x-2 px-2 py-1.5 text-xs bg-sky-500 text-white font-extrabold rounded-md shadow-md">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                class="size-4">
                                <path fill-rule="evenodd"
                                    d="M12 3.75a.75.75 0 0 1 .75.75v6.75h6.75a.75.75 0 0 1 0 1.5h-6.75v6.75a.75.75 0 0 1-1.5 0v-6.75H4.5a.75.75 0 0 1 0-1.5h6.75V4.5a.75.75 0 0 1 .75-.75Z"
                                    clip-rule="evenodd" />
                            </svg>
                            Tambah Data
                        </a>
                    </div>

                    <div class="rounded-md border bg-white mt-4 max-h-96 overflow-auto">
                        <div class="relative w-full overflow-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="border-b">
                                        <th class="h-10 px-4 text-left">
                                            <span
                                                class="inline-flex font-medium items-center justify-center px-3 text-sm -ml-3">
                                                Nama Kategori
                                            </span>
                                        </th>
                                        <th class="h-10 px-2 text-left">
                                            <span
                                                class="inline-flex font-medium items-center justify-center px-3 text-sm -ml-3">
                                                Barang-barang
                                            </span>
                                        </th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($categories as $category)
                                        <tr class="border-b transition-colors hover:bg-gray-50">
                                            <td class="p-2 px-4">
                                                {{ $category->name }}
                                            </td>
                                            <td class="p-2 truncate">
                                                <div class="grid grid-cols-3 gap-2 w-full overflow-x-auto">
                                                    @foreach ($category->goods as $good)
                                                        <div class="text-xs truncate">{{ $good->name }}</div>
                                                    @endforeach
                                                </div>
                                            </td>
                                            <td class="py-2">
                                                <div class="flex items-center gap-x-4 justify-center">
                                                    <a href="{{ route('goods.category-update', ['id' => $category->id]) }}"
                                                        class="px-2 py-1 flex items-center gap-x-2 rounded-md bg-gray-100 text-xs">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                            viewBox="0 0 24 24" stroke-width="1.5"
                                                            stroke="currentColor" class="size-3">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                                        </svg>
                                                        Ubah
                                                    </a>
                                                    <button wire:click="validationDelete({{ $category->id }})"
                                                        class="px-2 py-1 flex items-center gap-x-2 rounded-md bg-red-500 text-xs text-white">
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                            fill="currentColor" class="size-3">
                                                            <path fill-rule="evenodd"
                                                                d="M16.5 4.478v.227a48.816 48.816 0 0 1 3.878.512.75.75 0 1 1-.256 1.478l-.209-.035-1.005 13.07a3 3 0 0 1-2.991 2.77H8.084a3 3 0 0 1-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 0 1-.256-1.478A48.567 48.567 0 0 1 7.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 0 1 3.369 0c1.603.051 2.815 1.387 2.815 2.951Zm-6.136-1.452a51.196 51.196 0 0 1 3.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 0 0-6 0v-.113c0-.794.609-1.428 1.364-1.452Zm-.355 5.945a.75.75 0 1 0-1.5.058l.347 9a.75.75 0 1 0 1.499-.058l-.346-9Zm5.48.058a.75.75 0 1 0-1.498-.058l-.347 9a.75.75 0 0 0 1.5.058l.345-9Z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                        Hapus
                                                    </button>
                                                </div>
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

    {{-- Main Table --}}
    <div class="rounded-md border bg-white hidden sm:block">
        <div class="relative w-full overflow-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b">
                        <th class="h-10 px-4 text-left">
                            <span class="inline-flex font-medium items-center justify-center px-3 text-sm -ml-3">
                                Nama Barang
                            </span>
                        </th>
                        <th class="h-10 px-2 text-left">
                            <span class="inline-flex font-medium items-center justify-center px-3 text-sm -ml-3">
                                Kategori
                            </span>
                        </th>
                        <th class="h-10 px-2 text-left">
                            <span class="inline-flex font-medium items-center justify-center px-3 text-sm -ml-3">
                                Merk
                            </span>
                        </th>
                        <th class="h-10 px-2 text-center">
                            <span class="inline-flex font-medium items-center justify-center px-3 text-sm -ml-3">
                                Stok
                            </span>
                        </th>
                        <th class="h-10 px-2 text-left">
                            <span class="inline-flex font-medium items-center justify-center px-3 text-sm -ml-3">
                                Satuan
                            </span>
                        </th>
                        @if (auth()->user()->role === 'super_admin')
                            <th class="h-10 px-2 text-center">
                                <span class="inline-flex font-medium items-center justify-center px-3 text-sm -ml-3">
                                    Harga Beli
                                </span>
                            </th>
                        @endif
                        <th class="h-10 px-2 text-center">
                            <span class="inline-flex font-medium items-center justify-center px-3 text-sm -ml-3">
                                Harga Jual
                            </span>
                        </th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($data as $item)
                        <tr class="border-b transition-colors hover:bg-gray-50">
                            <td class="p-2 px-4">
                                {{ $item->name }}
                            </td>
                            <td class="p-2">
                                {{ $item->category->name }}
                            </td>
                            <td class="p-2">
                                @isset($item->brand)
                                    {{ $item->brand->name }}
                                @else
                                    Tidak ada merk
                                @endisset
                            </td>
                            <td class="p-2 text-center">
                                {{ $item->stock }}
                            </td>
                            <td class="p-2 ">
                                {{ $item->unit }}
                            </td>
                            @if (auth()->user()->role === 'super_admin')
                                <td class="p-2 text-center">
                                    @currency($item->cost)
                                </td>
                            @endif

                            <td class="p-2 text-center">
                                @currency($item->price)
                            </td>
                            <td class="py-2">
                                <div class="flex items-center gap-x-4 justify-center">
                                    <a href="{{ route('goods.update', ['id' => $item->id]) }}"
                                        class="px-2 py-1 flex items-center gap-x-2 rounded-md bg-gray-100 text-xs">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-3">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                        </svg>
                                        Ubah
                                    </a>
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
                                </div>
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

    {{-- Mobile Table --}}
    <div class="space-y-3 sm:hidden">
        @foreach ($data as $item)
            <button class="flex flex-col items-start gap-2 rounded-lg border p-3 text-left text-sm w-full">
                <div class="flex w-full flex-col gap-1">
                    <div class="flex items-center">
                        <div class="flex flex-col items-start gap-2 w-2/3">
                            <div class="font-semibold text-base">{{ $item->name }}</div>
                            <div class="line-clamp-2 text-xs text-muted-foreground">
                                <div class="grid grid-cols-3">
                                    <div class="col-span-1 mr-4">Kategori</div>
                                    <div class="col-span-2">: {{ $item->category->name }}</div>
                                </div>
                                <div class="grid grid-cols-3">
                                    <div class="col-span-1 mr-4">Merk</div>
                                    <div class="col-span-2">: {{ $item->brand->name }}</div>
                                </div>
                                <div class="grid grid-cols-3">
                                    <div class="col-span-1 mr-4">Harga Beli</div>
                                    <div class="col-span-2">: @currency($item->cost)</div>
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-col ml-auto text-sm text-end">
                            <span><span class="font-extrabold">{{ $item->stock }}</span>
                                {{ $item->unit }}</span>
                            <span class="text-base font-medium">@currency($item->price)</span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-2 w-full justify-end mt-1">
                    <a href="{{ route('goods.update', ['id' => $item->id]) }}"
                        class="px-2 py-1 flex items-center gap-x-2 rounded-md bg-gray-100 text-xs">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor" class="size-3">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                        </svg>
                        Ubah
                    </a>
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
                </div>
            </button>
        @endforeach
    </div>
    <div class="pt-4">
        {{ $data->links() }}
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
            Livewire.emit('perpage', 3); // Atur perPage menjadi 5 untuk mobile
        } else {
            Livewire.emit('perpage', 10); // Atur perPage menjadi 10 untuk desktop
        }
    });
</script>
