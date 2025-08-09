@section('title', 'Kelola Hero Section')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Kelola Hero Section</h1>
                <p class="text-gray-600">Kelola konten hero section website</p>
            </div>
            <a href="{{ route('content-admin.hero-sections.create') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md inline-flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6">
                    </path>
                </svg>
                Tambah Hero Section
            </a>
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
                                    @if ($hero->background_type === 'video' && $hero->background_video)
                                        <div class="relative h-16 w-24 rounded overflow-hidden bg-black">
                                            <video class="h-full w-full object-cover" muted>
                                                <source src="{{ asset('storage/' . $hero->background_video) }}"
                                                    type="video/mp4">
                                            </video>
                                            <div
                                                class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center">
                                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M8 5v10l7-5-7-5z" />
                                                </svg>
                                            </div>
                                        </div>
                                    @elseif ($hero->background_type === 'image' && $hero->background_image)
                                        <img class="h-16 w-24 rounded object-cover"
                                            src="{{ asset('storage/' . $hero->background_image) }}"
                                            alt="{{ $hero->title }}">
                                    @else
                                        <div class="h-16 w-24 rounded bg-gray-300 flex items-center justify-center">
                                            <span class="text-gray-600 text-xs">No Media</span>
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
                                    <a href="{{ route('content-admin.hero-sections.edit', $hero->id) }}"
                                        class="text-blue-600 hover:text-blue-900 mr-3 inline-flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                        Edit
                                    </a>
                                    <button wire:click="delete({{ $hero->id }})"
                                        class="text-red-600 hover:text-red-900 inline-flex items-center"
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus hero section ini?')">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
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
