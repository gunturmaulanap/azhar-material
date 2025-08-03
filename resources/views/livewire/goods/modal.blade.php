<div x-show="{{ $open }}" class="fixed inset-0 flex items-center justify-center z-50"
    x-transition:enter="transition-opacity duration-300" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity duration-300"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" style="display: none;">
    <div class="fixed inset-0 bg-gray-500 opacity-75" @click="{{ $close }}"></div>
    <div class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:w-full sm:max-w-4xl">
        <div class="p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-semibold">{{ $title }}</h2>
                    <p class="">{{ $description }}</p>
                </div>
                <a href="{{ $actionUrl }}"
                    class="inline-flex items-center gap-x-2 px-2 py-1.5 text-xs bg-sky-500 text-white font-extrabold rounded-md shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4">
                        <path fill-rule="evenodd"
                            d="M12 3.75a.75.75 0 0 1 .75.75v6.75h6.75a.75.75 0 0 1 0 1.5h-6.75v6.75a.75.75 0 0 1-1.5 0v-6.75H4.5a.75.75 0 0 1 0-1.5h6.75V4.5a.75.75 0 0 1 .75-.75Z"
                            clip-rule="evenodd" />
                    </svg>
                    {{ $buttonLabel }}
                </a>
            </div>

            <div class="rounded-md border bg-white mt-4 max-h-96 overflow-auto">
                <div class="relative w-full overflow-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b">
                                @foreach ($headers as $header)
                                    <th class="h-10 px-4 text-left">{{ $header }}</th>
                                @endforeach
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            {{ $slot }}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
