<div>
    {{-- Title --}}
    <x-slot name="title">
        {{ $form === 'create' ? 'Tambah Data Barang' : 'Ubah Data Barang' }}
    </x-slot>

    {{-- Breadcrumb --}}
    <x-slot name="breadcrumb">
        @php
            $breadcrumb = ['Data', 'Barang', $form === 'create' ? 'Tambah' : 'Ubah'];
        @endphp
        @foreach ($breadcrumb as $item)
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

    {{-- Form --}}
    <form wire:submit.prevent="save">
        @if (auth()->user()->role !== 'content-admin')
            {{-- Form untuk Owner/Admin --}}
            <div class="border-b border-gray-900/10 pb-12">
                <h2 class="text-base font-semibold leading-7 text-gray-900">Formulir Data Barang</h2>
                <p class="mt-1 mb-6 text-sm leading-6 text-gray-600">
                    Lengkapi data berikut ini untuk {{ $form === 'create' ? 'membuat data baru' : 'mengubah data' }}.
                </p>

                <div class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6">

                    {{-- Nama Barang --}}
                    <div class="col-span-3">
                        <label for="good.name" class="block text-sm font-medium text-gray-900">Nama Barang <span
                                class="text-red-500">*</span></label>
                        <input type="text" id="good.name" wire:model="good.name"
                            class="mt-1 block w-full rounded-md border-0 py-1.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300
                                   focus:ring-2 focus:ring-inset focus:ring-sky-500 sm:text-sm sm:leading-6">
                        @error('good.name')
                            <span class="text-xs text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Kategori --}}
                    <div class="col-span-3">
                        <label for="good.category_id" class="block text-sm font-medium text-gray-900">
                            Kategori <span class="text-red-500">*</span>
                        </label>
                        <select id="good.category_id" wire:model="good.category_id"
                            class="mt-1 block w-full rounded-md border-0 py-1.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300
                                   focus:ring-2 focus:ring-inset focus:ring-sky-500 sm:text-sm sm:leading-6">
                            <option value="">Pilih Kategori</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('good.category_id')
                            <span class="text-xs text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Brand --}}
                    <div class="col-span-3">
                        <label for="good.brand_id" class="block text-sm font-medium text-gray-900">
                            Brand <span class="text-red-500">*</span>
                        </label>
                        <select id="good.brand_id" wire:model="good.brand_id"
                            class="mt-1 block w-full rounded-md border-0 py-1.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300
                                   focus:ring-2 focus:ring-inset focus:ring-sky-500 sm:text-sm sm:leading-6">
                            <option value="">Pilih Merk</option>
                            @foreach ($brands as $brand)
                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                            @endforeach
                        </select>
                        @error('good.brand_id')
                            <span class="text-xs text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Satuan --}}
                    <div class="col-span-2">
                        <label for="good.unit" class="block text-sm font-medium text-gray-900">Satuan <span
                                class="text-red-500">*</span></label>
                        <input type="text" id="good.unit" wire:model="good.unit"
                            class="mt-1 block w-full rounded-md border-0 py-1.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300
                                   focus:ring-2 focus:ring-inset focus:ring-sky-500 sm:text-sm sm:leading-6">
                        @error('good.unit')
                            <span class="text-xs text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Harga Beli --}}
                    <div class="col-span-3">
                        <label for="good.cost" class="block text-sm font-medium text-gray-900">Harga Beli <span
                                class="text-red-500">*</span></label>
                        <input type="number" id="good.cost" wire:model="good.cost"
                            class="mt-1 block w-full rounded-md border-0 py-1.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300
                                   focus:ring-2 focus:ring-inset focus:ring-sky-500 sm:text-sm sm:leading-6">
                        @error('good.cost')
                            <span class="text-xs text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Harga Jual --}}
                    <div class="col-span-3">
                        <label for="good.price" class="block text-sm font-medium text-gray-900">Harga Jual <span
                                class="text-red-500">*</span></label>
                        <input type="number" id="good.price" wire:model="good.price"
                            class="mt-1 block w-full rounded-md border-0 py-1.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300
                                   focus:ring-2 focus:ring-inset focus:ring-sky-500 sm:text-sm sm:leading-6">
                        @error('good.price')
                            <span class="text-xs text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Stok (Khusus Owner) --}}
                    @if (auth()->user()->role === 'owner')
                        <div class="col-span-2">
                            <label for="good.stock" class="block text-sm font-medium text-gray-900">Stok Tersedia <span
                                    class="text-red-500">*</span></label>
                            <div class="mt-1 relative">
                                <input type="number" id="good.stock" min="0" wire:model="good.stock"
                                    class="block w-full rounded-md border-0 py-1.5 px-3 pr-12 text-gray-900 shadow-sm
                                           ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm sm:leading-6"
                                    placeholder="0">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                    <span class="text-gray-500 text-sm font-medium">pcs</span>
                                </div>
                            </div>
                            @error('good.stock')
                                <span class="text-xs text-red-500">{{ $message }}</span>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Hanya Owner yang dapat mengedit stok barang.</p>
                        </div>
                    @endif
                </div>
            </div>
        @else
            {{-- Form khusus Content Admin --}}
            <div class="border-b border-gray-900/10 pb-12">
                <h2 class="text-base font-semibold leading-7 text-gray-900">Formulir Data Barang</h2>
                <p class="mt-1 mb-6 text-sm leading-6 text-gray-600">
                    Lengkapi data berikut ini untuk {{ $form === 'create' ? 'membuat data baru' : 'mengubah data' }}.
                </p>

                {{-- Input disabled --}}
                <label class="block text-sm font-medium text-gray-900">Nama Barang</label>
                <input type="text" wire:model="good.name" id="good.name" disabled
                    class="mt-1 block w-full rounded-md border-0 py-1.5 px-3 bg-gray-100 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 sm:text-sm sm:leading-6">

                <label class="block text-sm font-medium text-gray-900 mt-4">Kategori</label>
                <select wire:model="good.category_id" id="good.category_id" disabled
                    class="mt-1 block w-full rounded-md border-0 py-1.5 px-3 bg-gray-100 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 sm:text-sm sm:leading-6">
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>

                <label class="block text-sm font-medium text-gray-900 mt-4">Brand</label>
                <select wire:model="good.brand_id" id="good.brand_id" disabled
                    class="mt-1 block w-full rounded-md border-0 py-1.5 px-3 bg-gray-100 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 sm:text-sm sm:leading-6">
                    @foreach ($brands as $brand)
                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                    @endforeach
                </select>

                {{-- Upload Gambar --}}
                <h2 class="text-base font-semibold leading-7 text-gray-900 mt-6">Upload Gambar Barang</h2>
                <p class="mt-1 mb-6 text-sm leading-6 text-gray-600">
                    Khusus role <strong>content-admin</strong> hanya dapat mengunggah gambar barang.
                </p>

                <input wire:model="good.image" id="good.image" type="file" accept="image/*"
                    class="block w-full rounded-md border-0 py-1.5 px-3 text-gray-900 shadow-sm
                           ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-sky-500 sm:text-sm sm:leading-6">
                @error('good.image')
                    <span class="text-xs text-red-500">{{ $message }}</span>
                @enderror

                {{-- Preview --}}
                @if (isset($good['image']) && $good['image'] instanceof \Livewire\TemporaryUploadedFile)
                    <div class="mt-3">
                        <p class="text-xs text-gray-500 mb-1">Preview gambar baru:</p>
                        <img src="{{ $good['image']->temporaryUrl() }}" class="h-32 rounded shadow">
                    </div>
                @endif
                @isset($good['image_path'])
                    <div class="mt-3">
                        <p class="text-xs text-gray-500 mb-1">Gambar saat ini:</p>
                        <img src="{{ asset('storage/' . $good['image_path']) }}" class="h-32 rounded shadow">
                    </div>
                @endisset
            </div>
        @endif

        {{-- Action Buttons --}}
        <div class="mt-6 flex items-center justify-end gap-x-6">
            <button type="button" wire:click="resetInput" class="text-sm font-semibold text-gray-900">Reset</button>
            <button type="submit"
                class="rounded-md bg-sky-500 px-3 py-2 text-sm font-semibold text-white shadow-sm
                       hover:bg-sky-400 focus-visible:outline focus-visible:outline-2
                       focus-visible:outline-offset-2 focus-visible:outline-sky-500">
                Simpan
            </button>
        </div>
    </form>
</div>
