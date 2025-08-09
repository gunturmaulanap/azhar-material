<div>
    <x-slot name="title">
        {{ $form === 'create' ? 'Tambah Data Barang' : 'Ubah Data Barang' }}
    </x-slot>

    <x-slot name="breadcrumb">
        @php
            $breadcumb = ['Data', 'Barang', 'Tambah'];
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
        @if (auth()->user()->role !== 'content-admin')
            <div class="border-b border-gray-900/10 pb-12">
                <h2 class="text-base font-semibold leading-7 text-gray-900">Formulir data barang</h2>
                <p class="mt-1 mb-6 text-sm leading-6 text-gray-600">
                    Lengkapi data berikut ini untuk {{ $form === 'create' ? ' membuat data baru' : ' mengubah data' }}
                </p>

                <div class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6">

                    <div class="col-span-3">
                        <label for="good.name" class="block text-sm font-medium leading-6 text-gray-900">Nama Barang
                            <span class="text-xs text-red-500">*</span></label>
                        <div class="mt-2">
                            <input wire:model="good.name" id="good.name" type="text"
                                class="block w-full px-3 rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-sky-500 sm:text-sm sm:leading-6">
                            @error('good.name')
                                <span class="text-xs text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-span-3">
                        <label for="good.category_id" class="block text-sm font-medium leading-6 text-gray-900">Kategori
                            <span class="text-xs text-red-500">*</span></label>
                        <div class="mt-2">
                            <select wire:model="good.category_id" id="good.category_id"
                                class="block w-full px-3 rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-sky-500 sm:text-sm sm:leading-6">
                                <option value="">Pilih Kategori</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('good.category_id')
                                <span class="text-xs text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-span-3">
                        <label for="good.brand_id" class="block text-sm font-medium leading-6 text-gray-900">
                            Brand <span class="text-xs text-red-500">*</span>
                        </label>
                        <div class="mt-2">
                            <select wire:model="good.brand_id" id="good.brand_id"
                                class="block w-full px-3 rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-sky-500 sm:text-sm sm:leading-6">
                                <option value="">Pilih Merk</option>
                                @foreach ($brands as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                @endforeach
                            </select>
                            @error('good.brand_id')
                                <span class="text-xs text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-span-2">
                        <label for="good.unit" class="block text-sm font-medium leading-6 text-gray-900">Satuan
                            <span class="text-xs text-red-500">*</span></label>
                        <div class="mt-2">
                            <input wire:model="good.unit" id="good.unit" type="text"
                                class="block w-full px-3 rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-sky-500 sm:text-sm sm:leading-6">
                            @error('good.unit')
                                <span class="text-xs text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-span-3">
                        <label for="good.cost" class="block text-sm font-medium leading-6 text-gray-900">Harga beli
                            <span class="text-xs text-red-500">*</span></label>
                        <div class="mt-2">
                            <div class="flex items-center">
                                <span
                                    class="bg-gray-100 flex items-center justify-center p-2 border rounded-l-md text-sm">Rp.</span>
                                <input wire:model="good.cost" id="good.cost" type="number"
                                    class="block w-full px-3 rounded-r-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset-y ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-sky-500 sm:text-sm sm:leading-6">
                            </div>
                            @error('good.cost')
                                <span class="text-xs text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-span-3">
                        <label for="good.price" class="block text-sm font-medium leading-6 text-gray-900">Harga Jual
                            <span class="text-xs text-red-500">*</span></label>
                        <div class="mt-2">
                            <div class="flex items-center">
                                <span
                                    class="bg-gray-100 flex items-center justify-center p-2 border rounded-l-md text-sm">Rp.</span>
                                <input wire:model="good.price" id="good.price" type="number"
                                    class="block w-full px-3 rounded-r-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset-y ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-sky-500 sm:text-sm sm:leading-6">
                            </div>
                            @error('good.price')
                                <span class="text-xs text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    @if (auth()->user()->role === 'owner')
                        <div class="col-span-2">
                            <label for="good.stock" class="block text-sm font-medium leading-6 text-gray-900">
                                <div class="flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                        class="w-4 h-4 text-amber-600">
                                        <path fill-rule="evenodd"
                                            d="M7.5 6v.75H5.513c-.96 0-1.764.724-1.865 1.679l-1.263 12A1.875 1.875 0 0 0 4.25 22.5h15.5a1.875 1.875 0 0 0 1.865-2.071l-1.263-12a1.875 1.875 0 0 0-1.865-1.679H16.5V6a4.5 4.5 0 1 0-9 0ZM12 3a3 3 0 0 0-3 3v.75h6V6a3 3 0 0 0-3-3Zm-3 8.25a3 3 0 1 0 6 0v-.75a.75.75 0 0 1 1.5 0v.75a4.5 4.5 0 1 1-9 0v-.75a.75.75 0 0 1 1.5 0v.75Z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Stok Tersedia
                                    <span class="text-xs text-red-500">*</span>
                                </div>
                            </label>
                            <div class="mt-2">
                                <div class="relative">
                                    <input wire:model="good.stock" id="good.stock" type="number" min="0"
                                        step="1"
                                        class="block w-full px-3 rounded-md border-0 py-1.5 pl-3 pr-12 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm sm:leading-6"
                                        placeholder="0">
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                        <span class="text-gray-500 text-sm font-medium">pcs</span>
                                    </div>
                                </div>
                                @error('good.stock')
                                    <span class="text-xs text-red-500">{{ $message }}</span>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"
                                        class="w-3 h-3 inline mr-1">
                                        <path fill-rule="evenodd"
                                            d="M15 8A7 7 0 1 1 1 8a7 7 0 0 1 14 0ZM9 5a1 1 0 1 1-2 0 1 1 0 0 1 2 0ZM6.75 8a.75.75 0 0 0 0 1.5h.75v1.75a.75.75 0 0 0 1.5 0v-2.5A.75.75 0 0 0 8.25 8h-1.5Z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Hanya Owner yang dapat mengedit stok barang
                                </p>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        @else
            <div class="border-b border-gray-900/10 pb-12">
                <h2 class="text-base font-semibold leading-7 text-gray-900">Formulir data barang</h2>
                <p class="mt-1 mb-6 text-sm leading-6 text-gray-600">
                    Lengkapi data berikut ini untuk {{ $form === 'create' ? ' membuat data baru' : ' mengubah data' }}
                </p>

                <div class="border-b border-gray-900/10 pb-12">
                    <div class="col-span-3">
                        <label for="good.name" class="block text-sm font-medium leading-6 text-gray-900">Nama
                            Barang
                            <span class="text-xs text-red-500">*</span></label>
                        <div class="mt-2">
                            <input wire:model="good.name" id="good.name" type="text" disabled
                                class="block w-full px-3 rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-sky-500 sm:text-sm sm:leading-6 ">
                            @error('good.name')
                                <span class="text-xs text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-span-3">
                        <label for="good.category_id"
                            class="block text-sm font-medium leading-6 text-gray-900">Kategori
                            <span class="text-xs text-red-500">*</span></label>
                        <div class="mt-2">
                            <select wire:model="good.category_id" id="good.category_id" disabled
                                class="block w-full px-3 rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-sky-500 sm:text-sm sm:leading-6">
                                <option value="">Pilih Kategori</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('good.category_id')
                                <span class="text-xs text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-span-3">
                        <label for="good.brand_id" class="block text-sm font-medium leading-6 text-gray-900">
                            Brand <span class="text-xs text-red-500">*</span>
                        </label>
                        <div class="mt-2">
                            <select wire:model="good.brand_id" id="good.brand_id"disabled
                                class="block w-full px-3 rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-sky-500 sm:text-sm sm:leading-6">
                                <option value="">Pilih Merk</option>
                                @foreach ($brands as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                @endforeach
                            </select>
                            @error('good.brand_id')
                                <span class="text-xs text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <h2 class="text-base font-semibold leading-7 text-gray-900 mt-4">Upload Gambar Barang</h2>
                    <p class="mt-1 mb-6 text-sm leading-6 text-gray-600">Khusus role <strong>content-admin</strong>
                        hanya
                        dapat mengunggah gambar barang.</p>

                    <div class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6">
                        <div class="col-span-3">
                            <label for="good.image" class="block text-sm font-medium leading-6 text-gray-900">Gambar
                                Barang
                                <span class="text-xs text-red-500">*</span></label>
                            <div class="mt-2">
                                <input wire:model="good.image" id="good.image" type="file" accept="image/*"
                                    class="block w-full px-3 rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-sky-500 sm:text-sm sm:leading-6">
                                @error('good.image')
                                    <span class="text-xs text-red-500">{{ $message }}</span>
                                @enderror
                            </div>
                            @isset($good['image_path'])
                                <p class="text-xs text-gray-500 mt-2">Gambar saat ini: <span
                                        class="font-medium">{{ $good['image_path'] }}</span></p>
                            @endisset
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <div class="mt-6 flex items-center justify-end gap-x-6">
            <button wire:click="resetInput()" type="button"
                class="text-sm font-semibold leading-6 text-gray-900">Reset</button>
            <button wire:click="save()" type="submit"
                class="rounded-md bg-sky-500 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-sky-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-sky-500">Simpan</button>
        </div>
    </form>
</div>
