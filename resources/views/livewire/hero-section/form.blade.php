<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">
                {{ $isEdit ? 'Edit Hero Section' : 'Tambah Hero Section' }}
            </h1>
            <p class="text-gray-600">
                {{ $isEdit ? 'Perbarui informasi hero section' : 'Tambah hero section baru' }}
            </p>
        </div>
        <a href="{{ route('content-admin.hero-sections') }}"
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
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                        Judul Hero Section *
                    </label>
                    <input type="text" wire:model="title" id="title"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('title') border-red-500 @enderror"
                        placeholder="Masukkan judul hero section">
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="subtitle" class="block text-sm font-medium text-gray-700 mb-2">
                        Subtitle *
                    </label>
                    <input type="text" wire:model="subtitle" id="subtitle"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('subtitle') border-red-500 @enderror"
                        placeholder="Masukkan subtitle">
                    @error('subtitle')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Deskripsi *
                    </label>
                    <textarea wire:model="description" id="description" rows="4"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror"
                        placeholder="Masukkan deskripsi hero section"></textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="button_text" class="block text-sm font-medium text-gray-700 mb-2">
                        Teks Tombol *
                    </label>
                    <input type="text" wire:model="button_text" id="button_text"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('button_text') border-red-500 @enderror"
                        placeholder="Contoh: Learn More">
                    @error('button_text')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- <div>
                    <label for="button_url" class="block text-sm font-medium text-gray-700 mb-2">
                        URL Tombol *
                    </label>
                    <input type="url" wire:model="button_url" id="button_url"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('button_url') border-red-500 @enderror"
                        placeholder="https://example.com">
                    @error('button_url')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div> --}}

                <div class="md:col-span-2">
                    <label for="background_type" class="block text-sm font-medium text-gray-700 mb-2">
                        Background Type *
                    </label>
                    <select wire:model="background_type" id="background_type"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('background_type') border-red-500 @enderror">
                        <option value="image">Image</option>
                        <option value="video">Video</option>
                    </select>
                    @error('background_type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                @if ($background_type === 'image')
                    <div class="md:col-span-2">
                        <label for="background_image" class="block text-sm font-medium text-gray-700 mb-2">
                            Background Image
                        </label>
                        <input type="file" wire:model="background_image" id="background_image" accept="image/*"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('background_image') border-red-500 @enderror">
                        @error('background_image')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        @if ($background_image)
                            <div class="mt-4">
                                <p class="text-sm text-gray-600 mb-2">Preview:</p>
                                <img src="{{ $background_image->temporaryUrl() }}" alt="Preview"
                                    class="max-w-xs h-32 object-cover rounded-md shadow-md">
                            </div>
                        @elseif ($existing_background_image_path)
                            <div class="mt-4">
                                <p class="text-sm text-gray-600 mb-2">Current Image:</p>
                                <img src="{{ Storage::url($existing_background_image_path) }}" alt="Current Image"
                                    class="max-w-xs h-32 object-cover rounded-md shadow-md">
                            </div>
                        @endif
                    </div>
                @elseif ($background_type === 'video')
                    <div class="md:col-span-2">
                        <label for="background_video" class="block text-sm font-medium text-gray-700 mb-2">
                            Background Video
                        </label>
                        <input type="file" wire:model="background_video" id="background_video" accept="video/*"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('background_video') border-red-500 @enderror">
                        <p class="text-xs text-gray-500 mt-1">Maximum file size: 50MB. Supported formats: MP4, AVI, MOV,
                            WMV, WEBM</p>
                        @error('background_video')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        @if ($background_video)
                            <div class="mt-4">
                                <p class="text-sm text-gray-600 mb-2">Preview:</p>
                                <video controls class="max-w-xs h-32 rounded-md shadow-md">
                                    <source src="{{ $background_video->temporaryUrl() }}" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            </div>
                        @elseif ($existing_background_video_path)
                            <div class="mt-4">
                                <p class="text-sm text-gray-600 mb-2">Current Video:</p>
                                <video controls class="max-w-xs h-32 rounded-md shadow-md">
                                    <source src="{{ Storage::url($existing_background_video_path) }}" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            <div class="flex items-center justify-end mt-6 pt-6 border-t border-gray-200">
                <a href="{{ route('content-admin.hero-sections') }}"
                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md mr-3">
                    Batal
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                    {{ $isEdit ? 'Perbarui' : 'Simpan' }}
                </button>
            </div>
        </form>
    </div>
</div>
