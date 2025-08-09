<div>
    <x-slot name="title">
        {{ $isEditing ? 'Edit Project' : 'Create Project' }}
    </x-slot>

    <x-slot name="breadcrumb">
        @php
            $breadcrumb = ['Content', 'Projects', $isEditing ? 'Edit' : 'Create'];
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
                <h1 class="text-3xl font-bold text-gray-900">{{ $isEditing ? 'Edit Project' : 'Create New Project' }}
                </h1>
                <p class="mt-2 text-gray-600">
                    {{ $isEditing ? 'Update project information' : 'Add a new project to showcase' }}</p>
            </div>
            <div class="mt-4 sm:mt-0">
                <a href="{{ route('content-admin.projects') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Projects
                </a>
            </div>
        </div>

        <form wire:submit.prevent="save" class="space-y-8">
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Project Information</h3>
                </div>

                <div class="p-6 space-y-6">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                            Project Title <span class="text-red-500">*</span>
                        </label>
                        <input type="text" wire:model.defer="title" id="title"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('title') border-red-300 @enderror"
                            placeholder="Enter project title">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Description <span class="text-red-500">*</span>
                        </label>
                        <textarea wire:model.defer="description" id="description" rows="4"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description') border-red-300 @enderror"
                            placeholder="Describe the project..."></textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Project Image
                        </label>

                        <div class="mb-4 flex items-center space-x-2">
                            <span class="text-sm text-gray-500">Use File Upload</span>
                            <label for="useExternalImageToggle"
                                class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" id="useExternalImageToggle" class="sr-only peer"
                                    wire:model.live="useExternalImage">
                                <div
                                    class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600">
                                </div>
                            </label>
                            <span class="text-sm text-gray-500">Use External URL</span>
                        </div>

                        @if (!$useExternalImage)
                            @if ($existing_image)
                                <div class="mb-4">
                                    <p class="text-sm text-gray-600 mb-2">Current Image:</p>
                                    <div class="relative inline-block">
                                        <img src="{{ asset('storage/' . $existing_image) }}" alt="Current project image"
                                            class="w-32 h-32 object-cover rounded-lg">
                                        <button type="button" wire:click="removeImage"
                                            class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600"
                                            title="Remove image">
                                            ×
                                        </button>
                                    </div>
                                </div>
                            @endif

                            <input type="file" wire:model="image" accept="image/*"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('image') border-red-300 @enderror">

                            @if ($image)
                                <div class="mt-2">
                                    <p class="text-sm text-gray-600">Preview:</p>
                                    <img src="{{ $image->temporaryUrl() }}" alt="Preview"
                                        class="w-32 h-32 object-cover rounded-lg">
                                </div>
                            @endif

                            @error('image')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">Max file size: 2MB. Accepted formats: JPG, PNG, GIF
                            </p>
                        @else
                            <input type="text" wire:model.defer="external_image_url" id="external_image_url"
                                placeholder="e.g. https://images.unsplash.com/photo-..."
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('external_image_url') border-red-300 @enderror">

                            @if ($external_image_url)
                                <div class="mt-2">
                                    <p class="text-sm text-gray-600">Preview:</p>
                                    <img src="{{ $external_image_url }}" alt="External image preview"
                                        class="w-32 h-32 object-cover rounded-lg">
                                </div>
                            @endif

                            @error('external_image_url')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        @endif
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                                Category
                            </label>
                            <input type="text" wire:model.defer="category" id="category"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('category') border-red-300 @enderror"
                                placeholder="e.g. Construction, Renovation">
                            @error('category')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="client" class="block text-sm font-medium text-gray-700 mb-2">
                                Client
                            </label>
                            <input type="text" wire:model.defer="client" id="client"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('client') border-red-300 @enderror"
                                placeholder="Client name">
                            @error('client')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="location" class="block text-sm font-medium text-gray-700 mb-2">
                                Location
                            </label>
                            <input type="text" wire:model.defer="location" id="location"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('location') border-red-300 @enderror"
                                placeholder="Project location">
                            @error('location')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="year" class="block text-sm font-medium text-gray-700 mb-2">
                                Year
                            </label>
                            <input type="number" wire:model.defer="year" id="year" min="1900"
                                max="{{ date('Y') + 10 }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('year') border-red-300 @enderror"
                                placeholder="{{ date('Y') }}">
                            @error('year')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">
                                Sort Order
                            </label>
                            <input type="number" wire:model.defer="sort_order" id="sort_order" min="0"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('sort_order') border-red-300 @enderror"
                                placeholder="0">
                            @error('sort_order')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">Lower numbers appear first</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select wire:model.defer="status" id="status"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('status') border-red-300 @enderror">
                                <option value="draft">Draft</option>
                                <option value="published">Published</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center pt-6">
                            <input type="checkbox" wire:model.defer="featured" id="featured"
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="featured" class="ml-2 block text-sm font-medium text-gray-700">
                                Mark as Featured Project
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end space-x-4">
                <a href="{{ route('content-admin.projects') }}"
                    class="px-6 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition-colors">
                    Cancel
                </a>
                <button type="submit"
                    class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors">
                    {{ $isEditing ? 'Update Project' : 'Create Project' }}
                </button>
            </div>
        </form>
    </div>
</div>
