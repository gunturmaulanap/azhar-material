<div>
    <x-slot name="title">
        Projects Management
    </x-slot>

    <x-slot name="breadcrumb">
        @php
            $breadcrumb = ['Content', 'Projects'];
        @endphp
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

    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Projects Management</h1>
                <p class="mt-2 text-gray-600">Kelola proyek-proyek Azhar Material</p>
            </div>
            <div class="mt-4 sm:mt-0">
                <a href="{{ route('content-admin.projects.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Add New Project
                </a>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                    <input type="text" wire:model.debounce.300ms="search" id="search"
                        placeholder="Search projects..."
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <div>
                    <label for="statusFilter" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select wire:model="statusFilter" id="statusFilter"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="all">All Status</option>
                        <option value="draft">Draft</option>
                        <option value="published">Published</option>
                    </select>
                </div>

                <div>
                    <label for="sortBy" class="block text-sm font-medium text-gray-700 mb-1">Sort By</label>
                    <select wire:model="sortBy" id="sortBy"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="created_at">Date Created</option>
                        <option value="title">Title</option>
                        <option value="year">Year</option>
                        <option value="sort_order">Sort Order</option>
                    </select>
                </div>
            </div>
        </div>

        @if ($projects->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach ($projects as $project)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                        <div class="relative h-48 bg-gray-200">
                            {{-- Perbaikan di sini: Gunakan accessor display_image_url --}}
                            @if ($project->display_image_url)
                                <img src="{{ $project->display_image_url }}" alt="{{ $project->title }}"
                                    class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gray-100">
                                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                </div>
                            @endif

                            <div class="absolute top-2 left-2">
                                <span
                                    class="px-2 py-1 text-xs font-medium rounded-full
                                    {{ $project->status === 'published' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($project->status) }}
                                </span>
                            </div>

                            @if ($project->featured)
                                <div class="absolute top-2 right-2">
                                    <span
                                        class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">
                                        Featured
                                    </span>
                                </div>
                            @endif
                        </div>

                        <div class="p-4">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2">{{ $project->title }}</h3>
                            <p class="text-gray-600 text-sm mb-3 line-clamp-3">{{ $project->description }}</p>

                            <div class="space-y-1 mb-4">
                                @if ($project->category)
                                    <p class="text-xs text-gray-500">
                                        <span class="font-medium">Category:</span> {{ $project->category }}
                                    </p>
                                @endif
                                @if ($project->client)
                                    <p class="text-xs text-gray-500">
                                        <span class="font-medium">Client:</span> {{ $project->client }}
                                    </p>
                                @endif
                                @if ($project->year)
                                    <p class="text-xs text-gray-500">
                                        <span class="font-medium">Year:</span> {{ $project->year }}
                                    </p>
                                @endif
                                @if ($project->location)
                                    <p class="text-xs text-gray-500">
                                        <span class="font-medium">Location:</span> {{ $project->location }}
                                    </p>
                                @endif
                            </div>

                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2">
                                    <button wire:click="toggleFeatured({{ $project->id }})"
                                        class="p-1 rounded hover:bg-gray-100 transition-colors"
                                        title="{{ $project->featured ? 'Remove from featured' : 'Mark as featured' }}">
                                        <svg class="w-4 h-4 {{ $project->featured ? 'text-yellow-500' : 'text-gray-400' }}"
                                            fill="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                                        </svg>
                                    </button>

                                    <button wire:click="toggleStatus({{ $project->id }})"
                                        class="p-1 rounded hover:bg-gray-100 transition-colors" title="Toggle status">
                                        <svg class="w-4 h-4 {{ $project->status === 'published' ? 'text-green-500' : 'text-gray-400' }}"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                            </path>
                                        </svg>
                                    </button>
                                </div>

                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('content-admin.projects.edit', $project->id) }}"
                                        class="p-1 text-blue-600 hover:bg-blue-50 rounded transition-colors"
                                        title="Edit project">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                    </a>

                                    <button wire:click="deleteProject({{ $project->id }})"
                                        onclick="confirm('Are you sure you want to delete this project?') || event.stopImmediatePropagation()"
                                        class="p-1 text-red-600 hover:bg-red-50 rounded transition-colors"
                                        title="Delete project">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $projects->links() }}
            </div>
        @else
            <div class="bg-white rounded-lg shadow-md p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                    </path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No projects found</h3>
                <p class="text-gray-500 mb-6">Get started by creating your first project.</p>
                <a href="{{ route('content-admin.projects.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Add New Project
                </a>
            </div>
        @endif
    </div>

    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .line-clamp-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</div>

@push('scripts')
    <script>
        document.addEventListener('livewire:load', function() {
            Livewire.on('projectUpdated', message => {
                // You can add toast notification here
                alert(message);
            });

            Livewire.on('projectDeleted', message => {
                // You can add toast notification here
                alert(message);
            });
        });
    </script>
@endpush
