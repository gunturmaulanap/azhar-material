<div>
    <x-slot name="title">
        {{ $isEditing ? 'Edit Project' : 'Create Project' }}
    </x-slot>

    <x-slot name="breadcrumb">
        @php $breadcrumb = ['Content','Projects', $isEditing ? 'Edit' : 'Create']; @endphp
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
                    class="block transition hover:text-gray-700 @if ($loop->last) text-gray-950 font-medium @endif">{{ $item }}</span>
            </li>
        @endforeach
    </x-slot>

    <form wire:submit.prevent="save" class="space-y-6">
        <div class="border-b border-gray-900/10 pb-12">
            <div class="px-6 py-5 border-b border-gray-100">
                <h2 class="text-base font-semibold leading-7 text-gray-900">Formulir Data Project</h2>
                <p class="mt-1 text-sm text-gray-500"> Lengkapi data berikut ini untuk
                    {{ $isEditing ? 'Mengubah informasi project' : 'Menambah informasi project' }}
                </p>
            </div>

            <div class="p-6 grid grid-cols-1 lg:grid-cols-12 gap-6">
                {{-- LEFT COLUMN: main info --}}
                <div class="lg:col-span-7 space-y-6">
                    {{-- Title --}}
                    <div>
                        <label for="title"
                            class="flex items-center justify-between text-sm font-medium text-gray-700 mb-2">
                            <span>Nama Projects<span class="text-red-500">*</span></span>
                        </label>
                        <input id="title" type="text" wire:model.defer="title"
                            class="mt-1 block w-full rounded-md border-0 py-1.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300
                                   focus:ring-2 focus:ring-inset focus:ring-sky-500 sm:text-sm sm:leading-6">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Description --}}
                    <div>
                        <label for="description"
                            class="flex items-center justify-between text-sm font-medium text-gray-700 mb-2">
                            <span>Deskripsi Project <span class="text-red-500">*</span></span>
                        </label>
                        <textarea id="description" rows="6" wire:model.defer="description"
                            class="mt-1 block w-full rounded-md border-0 py-1.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300
                                   focus:ring-2 focus:ring-inset focus:ring-sky-500 sm:text-sm sm:leading-6"></textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Meta grid --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                            <input id="category" type="text" wire:model.defer="category"
                                class="mt-1 block w-full rounded-md border-0 py-1.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300
                                   focus:ring-2 focus:ring-inset focus:ring-sky-500 sm:text-sm sm:leading-6"
                                @error('category') border-red-300 @enderror"
                                placeholder="e.g. Construction, Renovation">

                            @error('category')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="client" class="block text-sm font-medium text-gray-700 mb-2">Client</label>
                            <input id="client" type="text" wire:model.defer="client"
                                class="mt-1 block w-full rounded-md border-0 py-1.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300
                                   focus:ring-2 focus:ring-inset focus:ring-sky-500 sm:text-sm sm:leading-6"
                                placeholder="e.g. Partai Gerindra">
                            @error('client')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="location" class="block text-sm font-medium text-gray-700 mb-2">Location</label>
                            <input id="location" type="text" wire:model.defer="location"
                                class="mt-1 block w-full rounded-md border-0 py-1.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300
                                   focus:ring-2 focus:ring-inset focus:ring-sky-500 sm:text-sm sm:leading-6"
                                placeholder="Project location">
                            @error('location')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="year" class="block text-sm font-medium text-gray-700 mb-2">Year</label>
                            <input id="year" type="number" min="1900" max="{{ date('Y') + 10 }}"
                                wire:model.defer="year"
                                class="mt-1 block w-full rounded-md border-0 py-1.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300
                                   focus:ring-2 focus:ring-inset focus:ring-sky-500 sm:text-sm sm:leading-6"
                                placeholder="{{ date('Y') }}">
                            @error('year')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">Sort
                                Order</label>
                            <input id="sort_order" type="number" min="0" wire:model.defer="sort_order"
                                class="mt-1 block w-full rounded-md border-0 py-1.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300
                                   focus:ring-2 focus:ring-inset focus:ring-sky-500 sm:text-sm sm:leading-6"
                                placeholder="0">
                            <p class="mt-1 text-xs text-gray-500">Lower numbers appear first.</p>
                            @error('sort_order')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- RIGHT COLUMN: media & status --}}
                {{-- RIGHT COLUMN: media & visibility --}}
                <div class="lg:col-span-5 space-y-6 mt-8">
                    {{-- Project Image --}}
                    <div class="bg-gray-200 rounded-xl border border-gray-200 p-5">
                        <div class="flex items-center justify-between">
                            <h3 class="text-sm font-semibold text-gray-900">Project Image</h3>
                            <div class="flex items-center gap-2">
                                <span class="text-xs text-gray-500">Upload</span>
                                <label class="relative inline-flex items-center">
                                    <input type="checkbox" wire:model="useExternalImage" class="sr-only peer">
                                    <span
                                        class="h-6 w-11 rounded-full bg-gray-200 ring-1 ring-inset ring-gray-300 transition
                               peer-checked:bg-sky-600
                               after:absolute after:top-[2px] after:left-[2px] after:h-5 after:w-5 after:bg-white after:rounded-full after:shadow
                               after:transition peer-checked:after:translate-x-5">
                                    </span>
                                </label>
                                <span class="text-xs text-gray-500">External URL</span>
                            </div>
                        </div>

                        <div class="mt-4 space-y-3">
                            @if (!$useExternalImage)
                                {{-- preview existing (edit) --}}
                                @if ($existing_image)
                                    <div>
                                        <p class="text-xs text-gray-500 mb-2">Current Image</p>
                                        <div class="relative inline-block">
                                            <img src="{{ asset('storage/' . $existing_image) }}" alt="Current image"
                                                class="w-32 h-32 object-cover rounded-lg ring-1 ring-gray-200">
                                            <button type="button" wire:click="removeImage"
                                                class="absolute -top-2 -right-2 h-6 w-6 rounded-full bg-red-500 text-white text-xs flex items-center justify-center hover:bg-red-600"
                                                title="Remove image">×</button>
                                        </div>
                                    </div>
                                @endif

                                {{-- file input --}}
                                <label class="block">
                                    <input type="file" wire:model="image" accept="image/*"
                                        class="mt-1 block w-full rounded-md border-0 text-gray-900 shadow-sm
                                  ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-sky-500 sm:text-sm
                                  file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:bg-gray-100 file:text-gray-700">
                                </label>
                                @error('image')
                                    <p class="text-sm text-red-600">{{ $message }}</p>
                                @enderror

                                @if ($image)
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Preview</p>
                                        <img src="{{ $image->temporaryUrl() }}" alt="Preview"
                                            class="w-32 h-32 object-cover rounded-lg ring-1 ring-gray-200">
                                    </div>
                                @endif

                                <p class="text-xs text-gray-500">Max 2MB — JPG, PNG, GIF.</p>
                            @else
                                {{-- external url --}}
                                <label for="external_image_url"
                                    class="block text-sm font-medium text-gray-700">External Image URL</label>
                                <input id="external_image_url" type="url" wire:model.defer="external_image_url"
                                    placeholder="https://images.unsplash.com/photo-…"
                                    class="mt-1 block w-full rounded-md border-0 py-1.5 px-3 text-gray-900 shadow-sm
                              ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-sky-500 sm:text-sm">
                                @error('external_image_url')
                                    <p class="text-sm text-red-600">{{ $message }}</p>
                                @enderror

                                @if ($external_image_url)
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Preview</p>
                                        <img src="{{ $external_image_url }}" alt="External preview"
                                            class="w-32 h-32 object-cover rounded-lg ring-1 ring-gray-200"
                                            onerror="this.style.display='none'">
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>

                    {{-- Visibility --}}
                    <div class="bg-gray-200 rounded-xl border border-gray-200 p-5">
                        <h3 class="text-sm font-semibold text-gray-900 mb-3">Visibility</h3>
                        <div class="space-y-4">
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">
                                    Status <span class="text-red-500">*</span>
                                </label>
                                <select id="status" wire:model.defer="status"
                                    class="mt-1 block w-full rounded-md border-0 py-1.5 px-3 text-gray-900 shadow-sm
                               ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-sky-500 sm:text-sm">
                                    <option value="draft">Draft</option>
                                    <option value="published">Published</option>
                                </select>
                                @error('status')
                                    <p class="text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <label class="inline-flex items-center gap-2">
                                <input type="checkbox" wire:model.defer="featured"
                                    class="h-4 w-4 rounded border-gray-300 text-sky-600 focus:ring-sky-500">
                                <span class="text-sm text-gray-700">Mark as Featured Project</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="mt-6 flex items-center justify-end gap-x-6">
            <button type="button" wire:click="resetInput" class="text-sm font-semibold text-gray-900">Reset</button>
            <button type="submit"
                class="rounded-md bg-sky-500 px-3 py-2 text-sm font-semibold text-white shadow-sm
                       hover:bg-sky-400 focus-visible:outline focus-visible:outline-2
                       focus-visible:outline-offset-2 focus-visible:outline-sky-500">
                <span wire:loading.remove>{{ $isEditing ? 'Ubah Project' : 'Tambah Project' }}</span>
                <span wire:loading>Processing...</span> </button>
            {{-- <a href="{{ route('content-admin.projects') }}"
                class="px-4 py-2 rounded-md border border-gray-300 text-gray-700 hover:bg-gray-50 transition">
                Cancel
            </a>

            <button type="submit"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                wire:loading.attr="disabled">
                <svg wire:loading class="animate-spin -ml-1 mr-1 h-4 w-4 text-white"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                        stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4A4 4 0 004 12z"></path>
                </svg>
                <span wire:loading.remove>{{ $isEditing ? 'Update Project' : 'Create Project' }}</span>
                <span wire:loading>Processing...</span>
            </button> --}}
        </div>
    </form>
</div>
