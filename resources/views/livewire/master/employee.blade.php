<div>
    <x-slot name="title">{{ __('Data Pegawai') }}</x-slot>

    <x-slot name="breadcrumb">
        @php
            $breadcumb = ['Data', 'Pegawai'];
        @endphp
        @foreach ($breadcumb as $item)
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

    {{-- Header Table (Filter Search, Per Page, Create Button) --}}
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-x-4">
            <input wire:model="search"
                class="flex rounded-md bg-white border-gray-300 px-3 py-1 sm:w-64 text-sm text-gray-800 shadow-sm transition-colors focus:ring-1 h-8 placeholder:text-xs placeholder:text-slate-600"
                placeholder="Cari admin...">
            <select wire:model="perPage"
                class="flex rounded-md bg-white border-gray-300 px-3 py-1 sm:w-20 text-sm text-gray-800 shadow-sm transition-colors focus:ring-1 h-8 placeholder:text-xs placeholder:text-slate-600">
                <option value="10">10</option>
                <option value="50">50</option>
                <option value="100">100</option>
                <option value="250">250</option>
            </select>
        </div>
        <a href="{{ route('master.create-employee') }}"
            class="inline-flex items-center gap-x-2 px-2 py-1.5 text-xs bg-sky-500 text-white font-extrabold rounded-md shadow-md">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4">
                <path fill-rule="evenodd"
                    d="M12 3.75a.75.75 0 0 1 .75.75v6.75h6.75a.75.75 0 0 1 0 1.5h-6.75v6.75a.75.75 0 0 1-1.5 0v-6.75H4.5a.75.75 0 0 1 0-1.5h6.75V4.5a.75.75 0 0 1 .75-.75Z"
                    clip-rule="evenodd" />
            </svg>
            <span class="hidden sm:block">Tambah data</span>
            <span class="sm:hidden">Tambah</span>
        </a>
    </div>

    {{-- Main Table --}}
    <div class="rounded-md border bg-white hidden sm:block">
        <div class="relative w-full overflow-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b">
                        <th class="h-10 px-4 text-left">
                            <span
                                class="inline-flex whitespace-nowrap font-medium items-center justify-center px-3 text-sm -ml-3">
                                Nama Pegawai
                            </span>
                        </th>
                        <th class="h-10 px-2 text-left">
                            <span class="inline-flex font-medium items-center justify-center px-3 text-sm -ml-3">
                                Phone
                            </span>
                        </th>
                        <th class="h-10 px-2 text-left">
                            <span class="inline-flex font-medium items-center justify-center px-3 text-sm -ml-3">
                                Posisi
                            </span>
                        </th>
                        <th class="h-10 px-2 text-center">
                            <span class="inline-flex font-medium items-center justify-center px-3 text-sm -ml-3">
                                Status
                            </span>
                        </th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $item)
                        <tr class="border-b transition-colors hover:bg-gray-50">
                            <td class="p-2 px-4 w-[20%]">
                                {{ $item->name }}
                            </td>
                            <td class="p-2 w-[30%]">
                                {{ $item->phone }}
                            </td>
                            <td class="p-2">
                                {{ $item->position }}
                            </td>
                            <td class="p-2 text-center whitespace-nowrap">
                                {{ $item->active ? 'Aktif' : 'Tidak Aktif' }}
                            </td>
                            <td class="py-2">
                                <div class="flex items-center gap-x-4 justify-center">
                                    <a href="{{ route('master.update-employee', ['id' => $item->id]) }}"
                                        class="px-2 py-1 flex items-center gap-x-2 rounded-md bg-gray-100 text-xs">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-3">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                        </svg>
                                        Ubah
                                    </a>
                                    <button wire:click="validationDelete({{ $item->id }})"
                                        class="px-2 py-1 flex items-center gap-x-2 rounded-md bg-red-500 text-xs text-white">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                            class="size-3">
                                            <path fill-rule="evenodd"
                                                d="M16.5 4.478v.227a48.816 48.816 0 0 1 3.878.512.75.75 0 1 1-.256 1.478l-.209-.035-1.005 13.07a3 3 0 0 1-2.991 2.77H8.084a3 3 0 0 1-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 0 1-.256-1.478A48.567 48.567 0 0 1 7.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 0 1 3.369 0c1.603.051 2.815 1.387 2.815 2.951Zm-6.136-1.452a51.196 51.196 0 0 1 3.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 0 0-6 0v-.113c0-.794.609-1.428 1.364-1.452Zm-.355 5.945a.75.75 0 1 0-1.5.058l.347 9a.75.75 0 1 0 1.499-.058l-.346-9Zm5.48.058a.75.75 0 1 0-1.498-.058l-.347 9a.75.75 0 0 0 1.5.058l.345-9Z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Responsive Table --}}
    <div class="space-y-3 sm:hidden">
        @foreach ($data as $item)
            <button class="flex flex-col items-start gap-2 rounded-lg border p-3 text-left text-sm w-full">
                <div class="flex w-full flex-col gap-1">
                    <div class="flex items-center">
                        <div class="flex items-center gap-2">
                            <div class="font-semibold">{{ $item->name }}</div>
                        </div>
                        <div class="ml-auto text-xs text-foreground">{{ $item->phone }}</div>
                    </div>
                    <div class="text-xs font-medium">{{ $item->position }} @if (!$item->active)
                            <span class="text-red-600">Tidak Aktif</span>
                        @endif
                    </div>
                </div>
                <div class="flex items-center gap-2 w-full justify-end">
                    <a href="{{ route('master.update-employee', ['id' => $item->id]) }}"
                        class="px-2 py-1 flex items-center gap-x-2 rounded-md bg-gray-100 text-xs">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-3">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                        </svg>
                        Ubah
                    </a>
                    <a wire:click="validationDelete({{ $item->id }})"
                        class="px-2 py-1 flex items-center gap-x-2 rounded-md bg-red-500 text-xs text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-3">
                            <path fill-rule="evenodd"
                                d="M16.5 4.478v.227a48.816 48.816 0 0 1 3.878.512.75.75 0 1 1-.256 1.478l-.209-.035-1.005 13.07a3 3 0 0 1-2.991 2.77H8.084a3 3 0 0 1-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 0 1-.256-1.478A48.567 48.567 0 0 1 7.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 0 1 3.369 0c1.603.051 2.815 1.387 2.815 2.951Zm-6.136-1.452a51.196 51.196 0 0 1 3.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 0 0-6 0v-.113c0-.794.609-1.428 1.364-1.452Zm-.355 5.945a.75.75 0 1 0-1.5.058l.347 9a.75.75 0 1 0 1.499-.058l-.346-9Zm5.48.058a.75.75 0 1 0-1.498-.058l-.347 9a.75.75 0 0 0 1.5.058l.345-9Z"
                                clip-rule="evenodd" />
                        </svg>
                        Hapus
                    </a>
                </div>
            </button>
        @endforeach
    </div>
    <div class="pt-4">
        {{ $data->links() }}
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Fungsi untuk mendeteksi ukuran layar
        function isMobile() {
            return window.innerWidth <= 768; // Misalkan 768px adalah batas untuk mobile
        }

        // Jika layar dalam mode mobile
        if (isMobile()) {
            // Mengirim nilai ke Livewire
            Livewire.emit('perpage', 5); // Atur perPage menjadi 5 untuk mobile
        } else {
            Livewire.emit('perpage', 10); // Atur perPage menjadi 10 untuk desktop
        }
    });
</script>
