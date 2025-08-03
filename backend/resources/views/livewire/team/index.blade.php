@section('title', 'Kelola Tim')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Kelola Tim</h1>
                <p class="text-gray-600">Kelola data tim perusahaan</p>
            </div>
            <a href="{{ route('content.teams.create') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                Tambah Tim
            </a>
        </div>

        @if (session()->has('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('message') }}
            </div>
        @endif

        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="p-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <input wire:model.debounce.300ms="search" type="text" placeholder="Cari nama atau posisi..."
                            class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer"
                                wire:click="sortBy('name')">
                                Nama
                                @if ($sortField === 'name')
                                    @if ($sortDirection === 'asc')
                                        ↑
                                    @else
                                        ↓
                                    @endif
                                @endif
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer"
                                wire:click="sortBy('position')">
                                Posisi
                                @if ($sortField === 'position')
                                    @if ($sortDirection === 'asc')
                                        ↑
                                    @else
                                        ↓
                                    @endif
                                @endif
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Email
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer"
                                wire:click="sortBy('order')">
                                Urutan
                                @if ($sortField === 'order')
                                    @if ($sortDirection === 'asc')
                                        ↑
                                    @else
                                        ↓
                                    @endif
                                @endif
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
                        @forelse($teams as $team)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        @if ($team->image)
                                            <img class="h-10 w-10 rounded-full object-cover"
                                                src="{{ asset('storage/' . $team->image) }}" alt="{{ $team->name }}">
                                        @else
                                            <div
                                                class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                                <span
                                                    class="text-gray-600 font-medium">{{ substr($team->name, 0, 1) }}</span>
                                            </div>
                                        @endif
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $team->name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $team->position }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $team->email ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $team->order }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <button wire:click="toggleStatus({{ $team->id }})"
                                        class="px-2 py-1 text-xs rounded-full {{ $team->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $team->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </button>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('content.teams.edit', $team->id) }}"
                                        class="text-blue-600 hover:text-blue-900 mr-3">Edit</a>
                                    <button wire:click="delete({{ $team->id }})"
                                        class="text-red-600 hover:text-red-900"
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                        Hapus
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                    Tidak ada data tim ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-3 border-t border-gray-200">
                {{ $teams->links() }}
            </div>
        </div>
    </div>
