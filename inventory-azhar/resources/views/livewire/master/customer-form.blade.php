<div>
    <x-slot name="title">
        {{ $form === 'create' ? 'Tambah Data Customer' : 'Ubah Data Customer' }}
    </x-slot>

    <x-slot name="breadcrumb">
        @php
            $breadcumb = ['Data', 'Customer', 'Formulir'];
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
            <h2 class="text-base font-semibold leading-7 text-gray-900">Formulir data customer</h2>
            <p class="mt-1 mb-6 text-sm leading-6 text-gray-600">
                {{ $form === 'create' ? 'Lengkapi data berikut ini untuk membuat data baru' : 'Ubah data bila diperlukan' }}
            </p>

            <div class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6">
                <div class="col-span-6 sm:col-span-5">
                    <label for="customer.name" class="block text-sm font-medium leading-6 text-gray-900">Nama
                        lengkap <span class="text-xs text-red-500">*</span></label>
                    <div class="mt-2">
                        <input wire:model="customer.name" type="text" id="customer.name" autocomplete="off"
                            placeholder="Masukkan nama lengkap customer" autofocus
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-sky-500 sm:text-sm sm:leading-6">
                        @error('customer.name')
                            <span class="text-xs text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-span-6 sm:col-span-3">
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

                <div class="col-span-6 sm:col-span-4">
                    <label for="customer.address"
                        class="block text-sm font-medium leading-6 text-gray-900">Alamat</label>
                    <div class="mt-2">
                        <textarea wire:model="customer.address" id="customer.address" rows="3"
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"></textarea>
                    </div>
                    <p class="mt-3 text-sm leading-6 text-gray-600">Berikan setidaknya alamat jalan, kota, dan provinsi
                    </p>
                </div>
                <!-- Input Username -->
                <div class="col-span-6 sm:col-span-3">
                    <label for="customer.username" class="block text-sm font-medium leading-6 text-gray-900">
                        Username <span class="text-xs text-red-500">*</span>
                    </label>
                    <div class="mt-2">
                        <input wire:model="customer.username" type="text" id="customer.username"
                            autocomplete="given-name"
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-sky-500 sm:text-sm sm:leading-6">
                        @error('customer.username')
                            <span class="text-xs text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Input Password -->
                <div class="col-span-6 sm:col-span-3">
                    <label for="customer.password" class="block text-sm font-medium leading-6 text-gray-900">
                        Password
                        {{ $form === 'update' ? 'baru ' : '' }}
                        @if ($form === 'create')
                            <span class="text-xs text-red-500">*</span>
                        @endif
                    </label>
                    <div class="mt-2 relative">
                        <input wire:model="customer.password" id="customer.password" type="{{ $type }}"
                            autocomplete="off"
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-sky-500 sm:text-sm sm:leading-6">
                        <button wire:click="togglePassword()" class="absolute bottom-2 right-2" type="button">
                            @if ($type === 'password')
                                <!-- Icon Mata Terbuka -->
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                    class="size-5">
                                    <path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" />
                                    <path fill-rule="evenodd"
                                        d="M1.323 11.447C2.811 6.976 7.028 3.75 12.001 3.75c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113-1.487 4.471-5.705 7.697-10.677 7.697-4.97 0-9.186-3.223-10.675-7.69a1.762 1.762 0 0 1 0-1.113ZM17.25 12a5.25 5.25 0 1 1-10.5 0 5.25 5.25 0 0 1 10.5 0Z"
                                        clip-rule="evenodd" />
                                </svg>
                            @else
                                <!-- Icon Mata Tertutup -->
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                    class="size-5">
                                    <path
                                        d="M3.53 2.47a.75.75 0 0 0-1.06 1.06l18 18a.75.75 0 1 0 1.06-1.06l-18-18ZM22.676 12.553a11.249 11.249 0 0 1-2.631 4.31l-3.099-3.099a5.25 5.25 0 0 0-6.71-6.71L7.759 4.577a11.217 11.217 0 0 1 4.242-.827c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113Z" />
                                    <path
                                        d="M15.75 12c0 .18-.013.357-.037.53l-4.244-4.243A3.75 3.75 0 0 1 15.75 12ZM12.53 15.713l-4.243-4.244a3.75 3.75 0 0 0 4.244 4.243Z" />
                                    <path
                                        d="M6.75 12c0-.619.107-1.213.304-1.764l-3.1-3.1a11.25 11.25 0 0 0-2.63 4.31c-.12.362-.12.752 0 1.114 1.489 4.467 5.704 7.69 10.675 7.69 1.5 0 2.933-.294 4.242-.827l-2.477-2.477A5.25 5.25 0 0 1 6.75 12Z" />
                                </svg>
                            @endif
                        </button>
                    </div>
                    @error('customer.password')
                        <span class="text-xs text-red-500">{{ $message }}</span>
                    @enderror
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
