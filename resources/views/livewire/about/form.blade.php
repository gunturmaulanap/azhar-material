@section('title', $aboutId ? 'Edit About' : 'Tambah About')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-900">{{ $aboutId ? 'Edit About' : 'Tambah About' }}</h1>
                <p class="text-gray-600">
                    {{ $aboutId ? 'Edit informasi tentang perusahaan' : 'Tambah informasi perusahaan baru' }}</p>
            </div>

            <div class="bg-white shadow-md rounded-lg p-6">
                <form wire:submit.prevent="save">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Judul -->
                        <div class="md:col-span-2">
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Judul *</label>
                            <input type="text" id="title" wire:model="title"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('title')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Deskripsi -->
                        <div class="md:col-span-2">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi
                                *</label>
                            <textarea id="description" wire:model="description" rows="6"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Deskripsi tentang perusahaan..."></textarea>
                            @error('description')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Nama Perusahaan -->
                        <div class="md:col-span-2">
                            <label for="company_name" class="block text-sm font-medium text-gray-700 mb-2">Nama Perusahaan
                                *</label>
                            <input type="text" id="company_name" wire:model="company_name"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('company_name')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Alamat -->
                        <div class="md:col-span-2">
                            <label for="company_address" class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                            <textarea id="company_address" wire:model="company_address" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Alamat perusahaan..."></textarea>
                            @error('company_address')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Telepon -->
                        <div>
                            <label for="company_phone" class="block text-sm font-medium text-gray-700 mb-2">Telepon</label>
                            <input type="text" id="company_phone" wire:model="company_phone"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="+62 123 456 789">
                            @error('company_phone')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="company_email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input type="email" id="company_email" wire:model="company_email"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="info@perusahaan.com">
                            @error('company_email')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Website -->
                        <div class="md:col-span-2">
                            <label for="company_website"
                                class="block text-sm font-medium text-gray-700 mb-2">Website</label>
                            <input type="url" id="company_website" wire:model="company_website"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="https://www.perusahaan.com">
                            @error('company_website')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Visi -->
                        <div class="md:col-span-2">
                            <label for="vision" class="block text-sm font-medium text-gray-700 mb-2">Visi</label>
                            <textarea id="vision" wire:model="vision" rows="4"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Visi perusahaan..."></textarea>
                            @error('vision')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Misi -->
                        <div class="md:col-span-2">
                            <label for="mission" class="block text-sm font-medium text-gray-700 mb-2">Misi</label>
                            <textarea id="mission" wire:model="mission" rows="4"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Misi perusahaan..."></textarea>
                            @error('mission')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Image -->
                        <div class="md:col-span-2">
                            <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Gambar</label>
                            <input type="file" id="image" wire:model="image" accept="image/*"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('image')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                            @if ($image)
                                <div class="mt-2">
                                    <img src="{{ $image->temporaryUrl() }}" alt="Preview"
                                        class="h-32 w-32 object-cover rounded">
                                </div>
                            @endif
                        </div>

                        <!-- Status -->
                        <div class="md:col-span-2">
                            <label class="flex items-center">
                                <input type="checkbox" wire:model="is_active"
                                    class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-700">Aktif</span>
                            </label>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-4 mt-6">
                        <a href="{{ route('content-admin.about') }}"
                            class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                            Batal
                        </a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            {{ $aboutId ? 'Update' : 'Simpan' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
