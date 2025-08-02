<div>
    <x-slot name="title">
        {{ $form === 'create' ? 'Tambah Data Pegawai' : 'Ubah Data Pegawai' }}
    </x-slot>

    <x-slot name="breadcrumb">
        @php
            $breadcumb = ['Data', 'Pegawai', 'Formulir'];
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
            <h2 class="text-base font-semibold leading-7 text-gray-900">Formulir data pegawai</h2>
            <p class="mt-1 mb-6 text-sm leading-6 text-gray-600">
                {{ $form === 'create' ? 'Lengkapi data berikut ini untuk membuat data baru' : 'Ubah data bila diperlukan' }}
            </p>

            <div class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6">
                <div class="col-span-5">
                    <label for="employee.name" class="block text-sm font-medium leading-6 text-gray-900">Nama
                        Pegawai <span class="text-xs text-red-500">*</span></label>
                    <div class="mt-2">
                        <input wire:model="employee.name" type="text" id="employee.name" autocomplete="given-name"
                            autofocus
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-sky-500 sm:text-sm sm:leading-6">
                        @error('employee.name')
                            <span class="text-xs text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-span-5 sm:col-span-3">
                    <label for="employee.phone" class="block text-sm font-medium leading-6 text-gray-900">Nomor telp
                        <span class="text-xs text-red-500">*</span></label>
                    <div class="mt-2">
                        <input wire:model="employee.phone" id="employee.phone" type="number"
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-sky-500 sm:text-sm sm:leading-6">
                        @error('employee.phone')
                            <span class="text-xs text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-span-5 col-span-3">
                    <label for="employee.position" class="block text-sm font-medium leading-6 text-gray-900">Jabatan
                        <span class="text-xs text-red-500">*</span></label>
                    <div class="mt-2">
                        <input wire:model="employee.position" id="employee.position" type="text"
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-sky-500 sm:text-sm sm:leading-6">
                        @error('employee.position')
                            <span class="text-xs text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <fieldset class="mt-8">
                <legend class="text-sm font-semibold leading-6 text-gray-900">Status pegawai</legend>
                <p class="mt-1 text-sm leading-6 text-gray-600">Pegawai berstatus aktif akan terdaftar untuk kehadiran
                </p>
                <div class="mt-6 space-y-6">
                    <div class="flex items-center gap-x-3">
                        <input wire:model="employee.active" name="customerStatus" id="active" type="radio"
                            class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-600" value="1">
                        <label for="active" class="block text-sm font-medium leading-6 text-gray-900">Aktif</label>
                    </div>
                    <div class="flex items-center gap-x-3">
                        <input wire:model="employee.active" name="customerStatus" id="nonMember" type="radio"
                            class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-600" value="0">
                        <label for="nonMember" class="block text-sm font-medium leading-6 text-gray-900">Non
                            Aktif</label>
                    </div>
                </div>
                @error('employee.active')
                    <span class="text-xs text-red-500">{{ $message }}</span>
                @enderror
            </fieldset>
        </div>
        <div class="mt-6 flex items-center justify-end gap-x-6">
            <button wire:click="resetInput()" type="button"
                class="text-sm font-semibold leading-6 text-gray-900">Reset</button>
            <button wire:click="save()" type="submit"
                class="rounded-md bg-sky-500 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-sky-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-sky-500">Simpan</button>
        </div>
    </form>
</div>
