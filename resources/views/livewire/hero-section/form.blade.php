<div>
    <x-slot name="title">
        {{ $isEdit ? 'Edit Hero Section' : 'Tambah Hero Section' }}
    </x-slot>

    <x-slot name="breadcrumb">
        @php $breadcrumb = ['Content','Hero Sections', $isEdit ? 'Edit' : 'Create']; @endphp
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

    <form wire:submit.prevent="save" class="space-y-6">
        <div class="border-b border-gray-900/10 pb-12">
            <div class="px-6 py-5 border-b border-gray-100">
                <h2 class="text-base font-semibold leading-7 text-gray-900">Formulir Data Hero Section</h2>
                <p class="mt-1 text-sm text-gray-500">
                    Lengkapi data berikut ini untuk {{ $isEdit ? 'memperbarui' : 'menambahkan' }} hero section.
                </p>
            </div>

            <div class="p-6 grid grid-cols-1 lg:grid-cols-12 gap-6">
                {{-- LEFT COLUMN --}}
                <div class="lg:col-span-7 space-y-6">
                    {{-- Title --}}
                    <div>
                        <label for="title"
                            class="flex items-center justify-between text-sm font-medium text-gray-700 mb-2">
                            <span>Judul <span class="text-red-500">*</span></span>
                        </label>
                        <input id="title" type="text" wire:model.defer="title"
                            class="mt-1 block w-full rounded-md border-0 py-1.5 px-3 text-gray-900 shadow-sm
                                      ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-sky-500
                                      sm:text-sm sm:leading-6">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Subtitle --}}
                    <div>
                        <label for="subtitle"
                            class="flex items-center justify-between text-sm font-medium text-gray-700 mb-2">
                            <span>Subtitle <span class="text-red-500">*</span></span>
                        </label>
                        <input id="subtitle" type="text" wire:model.defer="subtitle"
                            class="mt-1 block w-full rounded-md border-0 py-1.5 px-3 text-gray-900 shadow-sm
                                      ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-sky-500
                                      sm:text-sm sm:leading-6">
                        @error('subtitle')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Description --}}
                    <div>
                        <label for="description"
                            class="flex items-center justify-between text-sm font-medium text-gray-700 mb-2">
                            <span>Deskripsi <span class="text-red-500">*</span></span>
                        </label>
                        <textarea id="description" rows="6" wire:model.defer="description"
                            class="mt-1 block w-full rounded-md border-0 py-1.5 px-3 text-gray-900 shadow-sm
                                         ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-sky-500
                                         sm:text-sm sm:leading-6"></textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Button text --}}
                    <div>
                        <label for="button_text" class="block text-sm font-medium text-gray-700 mb-2">
                            Teks Tombol <span class="text-red-500">*</span>
                        </label>
                        <input id="button_text" type="text" wire:model.defer="button_text"
                            placeholder="Contoh: Lihat Produk"
                            class="mt-1 block w-full rounded-md border-0 py-1.5 px-3 text-gray-900 shadow-sm
                                      ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-sky-500
                                      sm:text-sm sm:leading-6">
                        @error('button_text')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">URL tombol diset default ke <code>#product-preview</code>.
                        </p>
                    </div>
                </div>

                {{-- RIGHT COLUMN --}}
                <div class="lg:col-span-5 space-y-6 mt-7">
                    {{-- Background Media (card) --}}
                    <div class="bg-white rounded-xl border border-gray-200 p-5">
                        <div class="flex items-center justify-between">
                            <h3 class="text-sm font-semibold text-gray-900">Background Media</h3>

                            {{-- Pilihan tipe media (selaras dengan kartu di projects.form) --}}
                            <div class="flex items-center gap-2">
                                <span class="text-xs text-gray-600">Image</span>
                                {{-- gunakan select agar simpel dan stabil di Livewire v2 --}}
                                <select wire:model="background_type"
                                    class="rounded-md border-0 py-1 px-2 text-gray-900 shadow-sm
                                               ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-sky-500
                                               text-xs">
                                    <option value="image">Image</option>
                                    <option value="video">Video</option>
                                </select>
                                <span class="text-xs text-gray-600">Video</span>
                            </div>
                        </div>

                        <div class="mt-4 space-y-3">
                            {{-- ==== IMAGE MODE ==== --}}
                            @if ($background_type === 'image')
                                {{-- existing image preview (edit) --}}
                                @if ($existing_background_image_path)
                                    <div>
                                        <p class="text-xs text-gray-500 mb-2">Current Image</p>
                                        <img src="{{ Storage::url($existing_background_image_path) }}"
                                            alt="Current Image"
                                            class="w-40 h-28 object-cover rounded-lg ring-1 ring-gray-200">
                                    </div>
                                @endif

                                {{-- upload input --}}
                                <label class="block">
                                    <input type="file" wire:model="background_image" accept="image/*"
                                        class="mt-1 block w-full rounded-md border-0 text-gray-900 shadow-sm
                                                  ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-sky-500 sm:text-sm
                                                  file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:bg-gray-100 file:text-gray-700">
                                </label>
                                @error('background_image')
                                    <p class="text-sm text-red-600">{{ $message }}</p>
                                @enderror

                                @if ($background_image)
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Preview</p>
                                        <img src="{{ $background_image->temporaryUrl() }}" alt="Preview"
                                            class="w-40 h-28 object-cover rounded-lg ring-1 ring-gray-200">
                                    </div>
                                @endif

                                <p class="text-xs text-gray-500">Max 5MB — JPG, PNG, JPEG, GIF.</p>
                            @endif

                            {{-- ==== VIDEO MODE ==== --}}
                            @if ($background_type === 'video')
                                {{-- existing video preview (edit) --}}
                                @if ($existing_background_video_path)
                                    <div>
                                        <p class="text-xs text-gray-500 mb-2">Current Video</p>
                                        <video controls class="w-56 h-32 rounded-lg ring-1 ring-gray-200">
                                            <source src="{{ Storage::url($existing_background_video_path) }}"
                                                type="video/mp4">
                                        </video>
                                    </div>
                                @endif

                                {{-- upload input --}}
                                <label class="block">
                                    <input type="file" wire:model="background_video" accept="video/*"
                                        class="mt-1 block w-full rounded-md border-0 text-gray-900 shadow-sm
                                                  ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-sky-500 sm:text-sm">
                                </label>
                                <p class="text-xs text-gray-500">Max 50MB — MP4, AVI, MOV, WMV, WEBM.</p>
                                @error('background_video')
                                    <p class="text-sm text-red-600">{{ $message }}</p>
                                @enderror

                                @if ($background_video)
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Preview</p>
                                        <video controls class="w-56 h-32 rounded-lg ring-1 ring-gray-200">
                                            <source src="{{ $background_video->temporaryUrl() }}" type="video/mp4">
                                        </video>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="mt-6 flex items-center justify-end gap-x-6">
            <a href="{{ route('content-admin.hero-sections') }}"
                class="text-sm font-semibold text-gray-900">Kembali</a>

            <button type="submit"
                class="rounded-md bg-sky-500 px-3 py-2 text-sm font-semibold text-white shadow-sm
                           hover:bg-sky-400 focus-visible:outline focus-visible:outline-2
                           focus-visible:outline-offset-2 focus-visible:outline-sky-500"
                wire:loading.attr="disabled">
                <svg wire:loading class="animate-spin -ml-1 mr-1 h-4 w-4 inline text-white"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                        stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4A4 4 0 004 12z"></path>
                </svg>
                <span wire:loading.remove>{{ $isEdit ? 'Perbarui' : 'Simpan' }}</span>
                <span wire:loading>Processing...</span>
            </button>
        </div>
    </form>

    {{-- iziToast feedback (flash) --}}
    @push('scripts')
        <script>
            document.addEventListener('livewire:load', () => {
                const z = window.iziToast;
                @if (session('success'))
                    z?.success({
                        title: 'Berhasil',
                        message: @json(session('success'))
                    });
                @endif
                @if (session('error'))
                    z?.error({
                        title: 'Gagal',
                        message: @json(session('error'))
                    });
                @endif
            });
        </script>
    @endpush
</div>
