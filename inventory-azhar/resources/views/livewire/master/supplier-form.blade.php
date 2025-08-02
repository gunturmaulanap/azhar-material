<div>
    <x-slot name="title">
        {{ $form === 'create' ? 'Tambah Data Supplier' : 'Ubah Data Supplier' }}
    </x-slot>

    <x-slot name="breadcrumb">
        @php
            $breadcumb = ['Data', 'Supplier', 'Tambah'];
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
            <h2 class="text-base font-semibold leading-7 text-gray-900">Formulir data supplier</h2>
            <p class="mt-1 mb-6 text-sm leading-6 text-gray-600">
                {{ $form === 'create' ? 'Lengkapi data berikut ini untuk membuat data baru' : 'Isi password bila ingin mengubah password baru' }}
            </p>

            <div class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6">
                <div class="col-span-6 sm:col-span-3">
                    <label for="supplier.company" class="block text-sm font-medium leading-6 text-gray-900">Nama
                        perusahaan <span class="text-xs text-red-500">*</span></label>
                    <div class="mt-2">
                        <input wire:model="supplier.company" type="text" id="supplier.company" autofocus
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-sky-500 sm:text-sm sm:leading-6">
                        @error('supplier.company')
                            <span class="text-xs text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-span-6 sm:col-span-3">
                    <label for="supplier.name" class="block text-sm font-medium leading-6 text-gray-900">Nama
                        supplier <span class="text-xs text-red-500">*</span></label>
                    <div class="mt-2">
                        <input wire:model="supplier.name" type="text" id="supplier.name" autocomplete="given-name"
                            autofocus
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-sky-500 sm:text-sm sm:leading-6">
                        @error('supplier.name')
                            <span class="text-xs text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-span-6 sm:col-span-3">
                    <label for="supplier.phone" class="block text-sm font-medium leading-6 text-gray-900">Nomor telp
                        <span class="text-xs text-red-500">*</span></label>
                    <div class="mt-2">
                        <input wire:model="supplier.phone" id="supplier.phone" type="number"
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-sky-500 sm:text-sm sm:leading-6">
                        @error('supplier.phone')
                            <span class="text-xs text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-span-6 sm:col-span-3">
                    <label for="supplier.phone" class="block text-sm font-medium leading-6 text-gray-900">Keterangan
                        <span class="text-xs text-red-500">*</span></label>
                    <div class="mt-2">
                        <input wire:model="supplier.keterangan" id="supplier.keterangan" type="text"
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-sky-500 sm:text-sm sm:leading-6">
                        @error('supplier.keterangan')
                            <span class="text-xs text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-span-6 xl:col-span-4">
                    <label for="supplier.address"
                        class="block text-sm font-medium leading-6 text-gray-900">Alamat</label>
                    <div class="mt-2">
                        <textarea wire:model="supplier.address" id="supplier.address" rows="3"
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"></textarea>
                    </div>
                    <p class="mt-3 text-sm leading-6 text-gray-600">Berikan setidaknya alamat jalan, kota, dan provinsi
                    </p>
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
