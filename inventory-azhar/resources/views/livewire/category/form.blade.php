<div>
    <x-slot name="title">
        {{ $form === 'create' ? 'Tambah Data Kategori' : 'Ubah Data Kategori' }}
    </x-slot>

    <x-slot name="breadcrumb">
        @php
            $breadcumb = ['Data', 'Barang', 'Tambah Kategori'];
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
            <h2 class="text-base font-semibold leading-7 text-gray-900">Formulir data kategori</h2>
            <p class="mt-1 mb-6 text-sm leading-6 text-gray-600">
                Lengkapi data berikut ini untuk {{ $form === 'create' ? ' membuat data baru' : ' mengubah data' }}
            </p>

            <div class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6">

                <div class="col-span-3">
                    <label for="category.name" class="block text-sm font-medium leading-6 text-gray-900">Nama Kategori
                        <span class="text-xs text-red-500">*</span></label>
                    <div class="mt-2">
                        <input wire:model="category.name" id="category.name" type="text"
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-sky-500 sm:text-sm sm:leading-6">
                        @error('category.name')
                            <span class="text-xs text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
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
