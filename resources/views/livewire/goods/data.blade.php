<div x-data="{ open: false, openBrand: false }" @keydown.escape.window="open=false; openBrand=false">
    @push('styles')
        <style>
            [x-cloak] {
                display: none !important
            }
        </style>
    @endpush

    @php
        // Normalisasi role & prefix rute
        $role = str_replace('_', '', auth()->user()->role); // mis. super_admin -> superadmin
        $prefix = $role;
    @endphp

    <x-slot name="title">{{ __('Data Barang') }}</x-slot>

    <x-slot name="breadcrumb">
        @php $breadcumb = ['Data', 'Barang']; @endphp
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

    {{-- Header Table (Filter Search, Per Page, Filters) --}}
    <div class="flex flex-col xl:flex-row items-center justify-between gap-y-3 mb-4">
        <div class="flex flex-col sm:flex-row items-center gap-y-3 sm:gap-y-0 sm:gap-x-4 w-full sm:w-fit">
            <div class="flex items-center gap-x-2 sm:gap-x-4 w-full">
                <input wire:model.debounce.400ms="search"
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

        {{-- Tombol aksi disembunyikan untuk content-admin --}}
        @if ($role !== 'content-admin')
            <div class="flex items-center gap-x-2">
                <button type="button" @click="open = true"
                    class="inline-flex items-center gap-x-2 px-2 py-1.5 text-xs bg-yellow-500 text-white font-extrabold rounded-md shadow-md">
                    Kelola Kategori
                </button>
                <button type="button" @click="openBrand = true"
                    class="inline-flex items-center gap-x-2 px-2 py-1.5 text-xs bg-yellow-500 text-white font-extrabold rounded-md shadow-md">
                    Kelola Merk
                </button>
                <a href="{{ route($prefix . '.goods.create') }}"
                    class="inline-flex items-center gap-x-2 px-2 py-1.5 text-xs bg-sky-500 text-white font-extrabold rounded-md shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4">
                        <path fill-rule="evenodd"
                            d="M12 3.75a.75.75 0 0 1 .75.75v6.75h6.75a.75.75 0 0 1 0 1.5h-6.75v6.75a.75.75 0 0 1-1.5 0v-6.75H4.5a.75.75 0 0 1 0-1.5h6.75V4.5a.75.75 0 0 1 .75-.75Z"
                            clip-rule="evenodd" />
                    </svg>
                    Tambah Data
                </a>
            </div>
        @endif
    </div>

    {{-- ===================== MODAL KATEGORI (hidden untuk content-admin) ===================== --}}
    @if ($role !== 'content-admin')
        <div x-cloak x-show="open" class="fixed inset-0 flex items-center justify-center z-50"
            x-transition:enter="transition-opacity duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity duration-300"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            <div class="fixed inset-0 bg-gray-500/75" @click="open = false"></div>

            <div
                class="relative bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:w-full sm:max-w-4xl">
                {{-- <button class="absolute right-2 top-2 rounded-full bg-white" type="button" @click="open = false">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-8 text-red-500" fill="none"
                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </button> --}}

                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-semibold">Daftar Kategori</h2>
                            <p>Setiap kategori dimiliki beberapa barang.</p>
                        </div>
                        <a href="{{ route($prefix . '.category.create') }}"
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
                                        <th class="h-10 px-4 text-left"><span
                                                class="inline-flex font-medium px-3 text-sm -ml-3">Nama Kategori</span>
                                        </th>
                                        <th class="h-10 px-2 text-left"><span
                                                class="inline-flex font-medium px-3 text-sm -ml-3">Barang-barang</span>
                                        </th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($categories as $category)
                                        <tr class="border-b transition-colors hover:bg-gray-50">
                                            <td class="p-2 px-4">{{ $category->name }}</td>
                                            <td class="p-2 truncate">
                                                <div class="grid grid-cols-3 gap-2 w-full overflow-x-auto">
                                                    @foreach ($category->goods as $good)
                                                        <div class="text-xs truncate">{{ $good->name }}</div>
                                                    @endforeach
                                                </div>
                                            </td>
                                            <td class="py-2">
                                                <div class="flex items-center gap-x-4 justify-center">
                                                    <a href="{{ route($prefix . '.category.update', ['id' => $category->id]) }}"
                                                        class="px-2 py-1 rounded-md bg-gray-100 text-xs">Ubah</a>
                                                    <button
                                                        wire:click="validationDelete({{ $category->id }}, 'category')"
                                                        class="px-2 py-1 rounded-md bg-red-500 text-xs text-white">Hapus</button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6">
                                                <div class="py-2 text-red-500 text-center">Data tidak ditemukan</div>
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

        {{-- ===================== MODAL BRAND / MERK ===================== --}}
        <div x-cloak x-show="openBrand" class="fixed inset-0 flex items-center justify-center z-50"
            x-transition:enter="transition-opacity duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity duration-300"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            <div class="fixed inset-0 bg-gray-500/75" @click="openBrand = false"></div>

            <div
                class="relative bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:w-full sm:max-w-4xl">
                {{-- <button class="absolute right-2 top-2 rounded-full bg-white" type="button"
                    @click="openBrand = false">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-8 text-red-500" fill="none"
                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </button> --}}

                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-semibold">Daftar Merk</h2>
                            <p>Setiap merk dimiliki beberapa barang.</p>
                        </div>
                        <a href="{{ route($prefix . '.brand.create') }}"
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
                                        <th class="h-10 px-4 text-left"><span
                                                class="inline-flex font-medium px-3 text-sm -ml-3">Nama Merk</span>
                                        </th>
                                        <th class="h-10 px-2 text-left"><span
                                                class="inline-flex font-medium px-3 text-sm -ml-3">Barang-barang</span>
                                        </th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($brands as $brand)
                                        <tr class="border-b transition-colors hover:bg-gray-50">
                                            <td class="p-2 px-4">{{ $brand->name }}</td>
                                            <td class="p-2 truncate">
                                                <div class="grid grid-cols-3 gap-2 w-full overflow-x-auto">
                                                    @foreach ($brand->goods as $good)
                                                        <div class="text-xs truncate">{{ $good->name }}</div>
                                                    @endforeach
                                                </div>
                                            </td>
                                            <td class="py-2">
                                                <div class="flex items-center gap-x-4 justify-center">
                                                    <a href="{{ route($prefix . '.brand.update', ['id' => $brand->id]) }}"
                                                        class="px-2 py-1 rounded-md bg-gray-100 text-xs">Ubah</a>
                                                    <button
                                                        wire:click="validationDelete({{ $brand->id }}, 'brand')"
                                                        class="px-2 py-1 rounded-md bg-red-500 text-xs text-white">Hapus</button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6">
                                                <div class="py-2 text-red-500 text-center">Data tidak ditemukan</div>
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
    @endif

    {{-- ===================== MAIN TABLE ===================== --}}
    @if ($role === 'content-admin')
        {{-- Tabel khusus content-admin: Nama, Kategori, Merk, Gambar --}}
        <div class="rounded-md border bg-white hidden sm:block">
            <div class="relative w-full overflow-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b">
                            <th class="h-10 px-4 text-left"><span
                                    class="inline-flex font-medium px-3 text-sm -ml-3">Nama Barang</span></th>
                            <th class="h-10 px-2 text-left"><span
                                    class="inline-flex font-medium px-3 text-sm -ml-3">Kategori</span></th>
                            <th class="h-10 px-2 text-left"><span
                                    class="inline-flex font-medium px-3 text-sm -ml-3">Merk</span></th>
                            <th class="h-10 px-2 text-left"><span
                                    class="inline-flex font-medium px-3 text-sm -ml-3">Gambar</span></th>
                            <th class="h-10 px-2 text-left"><span
                                    class="inline-flex font-medium px-3 text-sm -ml-3"></span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data as $item)
                            <tr class="border-b transition-colors hover:bg-gray-50"
                                wire:key="goods-{{ $item->id }}">
                                <td class="p-2 px-4">{{ $item->name }}</td>
                                <td class="p-2">{{ $item->category->name ?? '-' }}</td>
                                <td class="p-2">{{ optional($item->brand)->name ?? 'Tidak ada merk' }}</td>
                                <td class="p-2">
                                    <div
                                        class="h-12 w-12 rounded overflow-hidden bg-gray-100 flex items-center justify-center">
                                        @if ($item->image)
                                            <img src="{{ asset('storage/' . $item->image) }}"
                                                alt="{{ $item->name }}" class="h-full w-full object-cover"
                                                onerror="this.style.display='none'; this.closest('div').innerHTML=`<svg class='w-6 h-6 text-gray-400' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z'/></svg>`;">
                                        @else
                                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        @endif
                                    </div>
                                <td class="p-2">
                                    <div class="flex items-center gap-x-4 justify-center">
                                        <a href="{{ route($prefix . '.goods.update', ['id' => $item->id]) }}"
                                            class="px-2 py-1 flex items-center gap-x-2 rounded-md bg-gray-100 text-xs">Ubah</a>
                                        {{-- <button wire:click="validationDelete({{ $item->id }}, 'goods')"
                                            class="px-2 py-1 flex items-center gap-x-2 rounded-md bg-red-500 text-xs text-white">Hapus</button> --}}
                                    </div>
                                </td>


                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">
                                    <div class="py-10 text-red-500 text-center">Data tidak ditemukan</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Mobile: content-admin --}}
        <div class="space-y-3 sm:hidden">
            @foreach ($data as $item)
                <div class="flex flex-col items-start gap-2 rounded-lg border p-3 text-left text-sm w-full"
                    wire:key="goods-m-{{ $item->id }}">
                    <div class="flex w-full flex-col gap-1">
                        <div class="flex items-center">
                            <div class="flex flex-col items-start gap-2 w-2/3">
                                <div class="font-semibold text-base">{{ $item->name }}</div>
                                <div class="text-xs text-muted-foreground mt-1">
                                    <div class="grid grid-cols-3">
                                        <div class="col-span-1 mr-4">Kategori</div>
                                        <div class="col-span-2">: {{ $item->category->name ?? '-' }}</div>
                                    </div>
                                    <div class="grid grid-cols-3">
                                        <div class="col-span-1 mr-4">Merk</div>
                                        <div class="col-span-2">:
                                            {{ optional($item->brand)->name ?? 'Tidak ada merk' }}</div>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    @if ($item->image)
                                        <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}"
                                            class="h-20 w-20 object-cover rounded">
                                    @else
                                        <span class="text-gray-400 italic">No image</span>
                                    @endif
                                </div>
                            </div>
                            <div class="flex items-center gap-2 w-full justify-end mt-1">
                                <a href="{{ route($prefix . '.goods.update', ['id' => $item->id]) }}"
                                    class="px-2 py-1 flex items-center gap-x-2 rounded-md bg-gray-100 text-xs">Ubah</a>
                                {{-- <button type="button" wire:click="validationDelete({{ $item->id }}, 'goods')"
                                    class="px-2 py-1 flex items-center gap-x-2 rounded-md bg-red-500 text-xs text-white">Hapus</button> --}}
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        {{-- Role selain content-admin: tampilan lengkap seperti semula --}}
        <div class="rounded-md border bg-white hidden sm:block">
            <div class="relative w-full overflow-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b">
                            <th class="h-10 px-4 text-left"><span
                                    class="inline-flex font-medium px-3 text-sm -ml-3">Nama Barang</span></th>
                            <th class="h-10 px-2 text-left"><span
                                    class="inline-flex font-medium px-3 text-sm -ml-3">Kategori</span></th>
                            <th class="h-10 px-2 text-left"><span
                                    class="inline-flex font-medium px-3 text-sm -ml-3">Merk</span></th>
                            <th class="h-10 px-2 text-center"><span
                                    class="inline-flex font-medium px-3 text-sm -ml-3">Stok</span></th>
                            <th class="h-10 px-2 text-left"><span
                                    class="inline-flex font-medium px-3 text-sm -ml-3">Satuan</span></th>
                            @if (auth()->user()->role === 'super_admin')
                                <th class="h-10 px-2 text-center"><span
                                        class="inline-flex font-medium px-3 text-sm -ml-3">Harga Beli</span></th>
                            @endif
                            <th class="h-10 px-2 text-center"><span
                                    class="inline-flex font-medium px-3 text-sm -ml-3">Harga Jual</span></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data as $item)
                            <tr class="border-b transition-colors hover:bg-gray-50"
                                wire:key="goods-{{ $item->id }}">
                                <td class="p-2 px-4">{{ $item->name }}</td>
                                <td class="p-2">{{ $item->category->name ?? '-' }}</td>
                                <td class="p-2">{{ optional($item->brand)->name ?? 'Tidak ada merk' }}</td>
                                <td class="p-2 text-center">{{ $item->stock }}</td>
                                <td class="p-2 ">{{ $item->unit }}</td>
                                @if (auth()->user()->role === 'super_admin')
                                    <td class="p-2 text-center">@currency($item->cost)</td>
                                @endif
                                <td class="p-2 text-center">@currency($item->price)</td>
                                <td class="py-2">
                                    <div class="flex items-center gap-x-4 justify-center">
                                        <a href="{{ route($prefix . '.goods.update', ['id' => $item->id]) }}"
                                            class="px-2 py-1 flex items-center gap-x-2 rounded-md bg-gray-100 text-xs">Ubah</a>
                                        <button wire:click="validationDelete({{ $item->id }}, 'goods')"
                                            class="px-2 py-1 flex items-center gap-x-2 rounded-md bg-red-500 text-xs text-white">Hapus</button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8">
                                    <div class="py-2 text-red-500 text-center">Data tidak ditemukan</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Mobile: role selain content-admin --}}
        <div class="space-y-3 sm:hidden">
            @foreach ($data as $item)
                <div class="flex flex-col items-start gap-2 rounded-lg border p-3 text-left text-sm w-full"
                    wire:key="goods-m-{{ $item->id }}">
                    <div class="flex w-full flex-col gap-1">
                        <div class="flex items-center">
                            <div class="flex flex-col items-start gap-2 w-2/3">
                                <div class="font-semibold text-base">{{ $item->name }}</div>
                                <div class="line-clamp-2 text-xs text-muted-foreground">
                                    <div class="grid grid-cols-3">
                                        <div class="col-span-1 mr-4">Kategori</div>
                                        <div class="col-span-2">: {{ $item->category->name ?? '-' }}</div>
                                    </div>
                                    <div class="grid grid-cols-3">
                                        <div class="col-span-1 mr-4">Merk</div>
                                        <div class="col-span-2">:
                                            {{ optional($item->brand)->name ?? 'Tidak ada merk' }}</div>
                                    </div>
                                    @if (auth()->user()->role === 'super_admin')
                                        <div class="grid grid-cols-3">
                                            <div class="col-span-1 mr-4">Harga Beli</div>
                                            <div class="col-span-2">: @currency($item->cost)</div>
                                        </div>
                                    @endif
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
                        <a href="{{ route($prefix . '.goods.update', ['id' => $item->id]) }}"
                            class="px-2 py-1 flex items-center gap-x-2 rounded-md bg-gray-100 text-xs">Ubah</a>
                        <button type="button" wire:click="validationDelete({{ $item->id }}, 'goods')"
                            class="px-2 py-1 flex items-center gap-x-2 rounded-md bg-red-500 text-xs text-white">Hapus</button>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <div class="pt-4">
        {{ $data->links() }}
    </div>

    @push('scripts')
        <script>
            document.addEventListener('livewire:init', function() {
                const isMobile = () => window.innerWidth <= 768;
                Livewire.emit('perpage', isMobile() ? 3 : 10);
            });
        </script>
    @endpush
</div>
