@section('title', 'Kelola Hero Section')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Kelola Hero Section</h1>
                <p class="text-gray-600">Kelola konten hero section website</p>
            </div>
            <button wire:click="create" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md"
                data-bs-toggle="modal" data-bs-target="#heroModal">
                Tambah Hero Section
            </button>
        </div>

        @if (session()->has('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('message') }}
            </div>
        @endif

        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Background
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Title
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Subtitle
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Button
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($heroSections as $hero)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($hero->background_image)
                                        <img class="h-16 w-24 rounded object-cover"
                                            src="{{ asset('storage/' . $hero->background_image) }}"
                                            alt="{{ $hero->title }}">
                                    @else
                                        <div class="h-16 w-24 rounded bg-gray-300 flex items-center justify-center">
                                            <span class="text-gray-600 text-xs">No Image</span>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900 max-w-xs">
                                        {{ $hero->title }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 max-w-xs">
                                        {{ $hero->subtitle }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $hero->button_text }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $hero->button_url }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-2 py-1 text-xs rounded-full {{ $hero->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $hero->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button wire:click="edit({{ $hero->id }})"
                                        class="text-blue-600 hover:text-blue-900 mr-3" data-bs-toggle="modal"
                                        data-bs-target="#heroModal">
                                        Edit
                                    </button>
                                    <button wire:click="delete({{ $hero->id }})"
                                        class="text-red-600 hover:text-red-900"
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus hero section ini?')">
                                        Hapus
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                    Tidak ada data hero section ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-3 border-t border-gray-200">
                {{ $heroSections->links() }}
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="heroModal" tabindex="-1" aria-labelledby="heroModalLabel" aria-hidden="true"
        wire:ignore.self>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="heroModalLabel">
                        {{ $isEdit ? 'Edit Hero Section' : 'Tambah Hero Section' }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form wire:submit.prevent="{{ $isEdit ? 'update' : 'store' }}">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="title" class="form-label">Title *</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror"
                                wire:model="title" id="title" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="subtitle" class="form-label">Subtitle *</label>
                            <input type="text" class="form-control @error('subtitle') is-invalid @enderror"
                                wire:model="subtitle" id="subtitle" required>
                            @error('subtitle')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi *</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" wire:model="description" id="description"
                                rows="4" required></textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="button_text" class="form-label">Button Text *</label>
                                    <input type="text" class="form-control @error('button_text') is-invalid @enderror"
                                        wire:model="button_text" id="button_text" required>
                                    @error('button_text')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="button_url" class="form-label">Button URL *</label>
                                    <input type="url" class="form-control @error('button_url') is-invalid @enderror"
                                        wire:model="button_url" id="button_url" required>
                                    @error('button_url')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="background_image" class="form-label">Background Image</label>
                            <input type="file" class="form-control @error('background_image') is-invalid @enderror"
                                wire:model="background_image" id="background_image" accept="image/*">
                            @error('background_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            @if ($background_image)
                                <div class="mt-2">
                                    <img src="{{ $background_image->temporaryUrl() }}" class="img-thumbnail"
                                        style="max-width: 300px;">
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            {{ $isEdit ? 'Update' : 'Simpan' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
