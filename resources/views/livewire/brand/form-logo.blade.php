<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">
                {{ $isEdit ? 'Edit Logo Brand' : 'Upload Logo Brand' }}
            </h1>
            <p class="text-gray-600">
                {{ $isEdit ? 'Perbarui informasi dan logo brand' : 'Tambah brand baru dengan logo' }}
            </p>
        </div>
        <a href="{{ route('content.brand.index') }}"
            class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md">
            Kembali
        </a>
    </div>

    @if (session()->has('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <form wire:submit.prevent="save" class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Brand *
                    </label>
                    <input type="text" wire:model="name" id="name"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror"
                        placeholder="Masukkan nama brand">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Deskripsi Brand
                    </label>
                    <textarea wire:model="description" 
                              id="description"
                              rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror"
                              placeholder="Masukkan deskripsi brand (opsional)"></textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div> --}}

                <div class="md:col-span-2">
                    <label for="website_url" class="block text-sm font-medium text-gray-700 mb-2">
                        Website URL
                    </label>
                    <input type="url" wire:model="website_url" id="website_url"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('website_url') border-red-500 @enderror"
                        placeholder="https://example.com">
                    @error('website_url')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="logo" class="block text-sm font-medium text-gray-700 mb-2">
                        Logo Brand
                    </label>
                    <div
                        class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                        <div class="space-y-1 text-center">
                            @if ($logo)
                                <div class="mb-4">
                                    <img src="{{ $logo->temporaryUrl() }}" alt="Preview"
                                        class="mx-auto h-32 w-auto object-contain rounded-md">
                                </div>
                            @elseif ($isEdit && !$logo)
                                @php
                                    $brand = \App\Models\Brand::find($brandId);
                                @endphp
                                @if ($brand && $brand->logo)
                                    <div class="mb-4">
                                        <img src="{{ asset('storage/' . $brand->logo) }}" alt="Current Logo"
                                            class="mx-auto h-32 w-auto object-contain rounded-md">
                                        <p class="text-sm text-gray-500 mt-2">Logo saat ini</p>
                                    </div>
                                @endif
                            @endif

                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                                viewBox="0 0 48 48">
                                <path
                                    d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="logo"
                                    class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                    <span>Upload logo</span>
                                    <input id="logo" wire:model="logo" type="file" class="sr-only"
                                        accept="image/*">
                                </label>
                                <p class="pl-1">atau drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500">PNG, JPG, JPEG, GIF, SVG hingga 2MB</p>
                        </div>
                    </div>
                    @error('logo')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    <!-- Important Instructions -->
                    <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-md">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800">
                                    Ketentuan Upload Logo Brand
                                </h3>
                                <div class="mt-2 text-sm text-blue-700">
                                    <ul class="list-disc pl-5 space-y-1">
                                        <li><strong>Format yang dianjurkan:</strong> PNG dengan latar belakang
                                            transparan</li>
                                        <li><strong>Ukuran file:</strong> Maksimal 2MB</li>
                                        <li><strong>Dimensi:</strong> Disarankan rasio 1:1 atau logo persegi</li>
                                        <li><strong>Kualitas:</strong> Upload logo dengan resolusi tinggi untuk hasil
                                            terbaik</li>
                                        <li><strong>Logo hanya berisi:</strong> Logo brand tanpa latar belakang untuk
                                            tampilan yang professional</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="md:col-span-2">
                    <div class="flex items-center">
                        <input type="checkbox" wire:model="is_active" id="is_active"
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="is_active" class="ml-2 block text-sm text-gray-900">
                            Brand aktif
                        </label>
                    </div>
                    @error('is_active')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center justify-end mt-6 pt-6 border-t border-gray-200">
                <a href="{{ route('content.brand.index') }}"
                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md mr-3">
                    Batal
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                    {{ $isEdit ? 'Perbarui Brand' : 'Simpan Brand' }}
                </button>
            </div>
        </form>
    </div>
</div>
