<div>
    <x-slot name="title">
        {{ $isEdit ? 'Edit Brand' : 'Tambah Brand' }}
    </x-slot>

    <x-slot name="breadcrumb">
        @php $breadcrumb = ['Content','Brands', $isEdit ? 'Edit' : 'Create']; @endphp
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
                <h2 class="text-base font-semibold leading-7 text-gray-900">Formulir Data Brand</h2>
                <p class="mt-1 text-sm text-gray-500">
                    Lengkapi data berikut ini untuk {{ $isEdit ? 'memperbarui' : 'menambahkan' }} brand.
                </p>
            </div>

            <div class="p-6 grid grid-cols-1 lg:grid-cols-12 gap-6">
                {{-- LEFT COLUMN --}}
                <div class="lg:col-span-12 space-y-6">
                    {{-- Nama Brand --}}
                    <div>
                        <label for="name"
                            class="flex items-center justify-between text-sm font-medium text-gray-700 mb-2">
                            <span>Nama Brand <span class="text-red-500">*</span></span>
                        </label>
                        <input id="name" type="text" wire:model.defer="name" placeholder="Masukkan nama brand"
                            class="mt-1 block w-full rounded-md border-0 py-1.5 px-3 text-gray-900 shadow-sm
                                      ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-sky-500
                                      sm:text-sm sm:leading-6 @error('name') ring-red-300 @enderror">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Website URL --}}
                    <div>
                        <label for="website_url"
                            class="flex items-center justify-between text-sm font-medium text-gray-700 mb-2">
                            <span>Website URL</span>
                        </label>
                        <input id="website_url" type="url" wire:model.defer="website_url"
                            placeholder="https://example.com"
                            class="mt-1 block w-full rounded-md border-0 py-1.5 px-3 text-gray-900 shadow-sm
                                      ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-sky-500
                                      sm:text-sm sm:leading-6 @error('website_url') ring-red-300 @enderror">
                        @error('website_url')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="bg-white rounded-xl border border-gray-300 p-5">
                        <div class="flex items-center justify-between">
                            <h3 class="text-sm font-semibold text-gray-900">Logo Brand</h3>
                        </div>

                        <div class="mt-4 space-y-3">
                            {{-- Preview logo saat ini (mode edit) --}}
                            @if ($isEdit && !$logo && $existing_logo_path)
                                <div>
                                    <p class="text-xs text-gray-500 mb-2">Logo Saat Ini</p>
                                    <img src="{{ asset('storage/' . $existing_logo_path) }}" alt="Current Logo"
                                        class="w-36 h-36 object-contain rounded-lg ring-1 ring-gray-300 bg-white p-2">
                                </div>
                            @endif

                            {{-- Input file --}}
                            <label class="block">
                                <input type="file" id="logo" wire:model="logo" accept="image/*"
                                    class="mt-1 block w-full rounded-md border-0 text-gray-900 shadow-sm
                                              ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-sky-500 sm:text-sm
                                              file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:bg-gray-100 file:text-gray-700">
                            </label>
                            @error('logo')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror

                            {{-- Preview upload baru --}}
                            @if ($logo)
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Preview</p>
                                    <img src="{{ $logo->temporaryUrl() }}" alt="Preview"
                                        class="w-36 h-36 object-contain rounded-lg ring-1 ring-gray-300 bg-white p-2">
                                </div>
                            @endif

                            <p class="text-xs text-gray-500">Max 2MB — PNG, JPG, JPEG, GIF, SVG.</p>

                            {{-- Tips upload (sama seperti sebelumnya, dibersihkan tampilannya) --}}
                            <div class="mt-3 p-3 rounded-md bg-blue-50 border border-blue-200">
                                <h4 class="text-xs font-semibold text-blue-800 mb-1">Ketentuan Upload Logo</h4>
                                <ul class="list-disc pl-5 space-y-0.5 text-xs text-blue-700">
                                    <li>Disarankan PNG dengan latar transparan</li>
                                    <li>Rasio 1:1 (persegi) untuk hasil terbaik</li>
                                    <li>Gunakan resolusi yang cukup tinggi</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl border border-gray-300 p-5">
                        <h3 class="text-sm font-semibold text-gray-900 mb-3">Status</h3>
                        <label for="is_active" class="inline-flex items-center gap-2">
                            <input type="checkbox" id="is_active" wire:model.defer="is_active"
                                class="h-4 w-4 rounded border-gray-300 text-sky-600 focus:ring-sky-500">
                            <span class="text-sm text-gray-700">Brand aktif</span>
                        </label>
                        @error('is_active')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- RIGHT COLUMN --}}

                {{-- Kartu Logo Brand (media) --}}


                {{-- Status / Visibility --}}

            </div>
        </div>

        {{-- Actions --}}
        <div class="mt-6 flex items-center justify-end gap-x-6">
            <a href="{{ route('content-admin.brand.index') }}" class="text-sm font-semibold text-gray-900">Kembali</a>

            <button type="submit"
                class="rounded-md bg-sky-500 px-3 py-2 text-sm font-semibold text-white shadow-sm
                           hover:bg-sky-400 focus-visible:outline focus-visible:outline-2
                           focus-visible:outline-offset-2 focus-visible:outline-sky-500"
                wire:loading.attr="disabled">
                <svg wire:loading class="animate-spin -ml-1 mr-1 h-4 w-4 inline text-white"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                        stroke-width="4">
                    </circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4A4 4 0 004 12z"></path>
                </svg>
                <span wire:loading.remove>{{ $isEdit ? 'Perbarui Brand' : 'Simpan Brand' }}</span>
                <span wire:loading>Processing...</span>
            </button>
        </div>
    </form>

    {{-- iziToast flash (opsional; akan tampil jika ada session) --}}
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
                @if (session('message'))
                    z?.success({
                        title: 'Berhasil',
                        message: @json(session('message'))
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
