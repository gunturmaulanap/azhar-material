<div>
    <x-slot name="title">{{ __('Laporan Barang') }}</x-slot>

    <x-slot name="breadcrumb">
        @php
            $breadcumb = ['Laporan', 'Laporan Barang'];
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

    {{-- Header Table (Filter Search, Per Page, Date Filter) --}}
    <div class="flex flex-col sm:flex-row items-center sm:justify-between mb-4 gap-y-3 sm:gap-y-0">
        <div class="flex items-center gap-x-2 sm:gap-x-4 w-full">
            <!-- Input Search -->
            <input wire:model.debounce.100ms="search"
                class="flex rounded-md bg-white border-gray-300 px-3 py-1 w-full sm:w-64 text-sm text-gray-800 shadow-sm transition-colors focus:ring-1 h-8 placeholder:text-xs placeholder:text-slate-600"
                placeholder="Cari berdasarkan nama...">


            <!-- Dropdown Bulan -->
            <select wire:model="perMounth"
                class="flex rounded-md bg-white border-gray-300 px-3 py-1 w-full sm:w-40 text-xs sm:text-sm text-gray-800 shadow-sm transition-colors focus:ring-1 h-8 placeholder:text-xs placeholder:text-slate-600">
                <option value="">Pilih Bulan</option>
                @foreach (range(1, 12) as $month)
                    <option value="{{ $month }}" @if ($month == now()->month) selected @endif>
                        {{ \Carbon\Carbon::create()->month($month)->translatedFormat('F') }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="flex items-center gap-x-2 sm:gap-x-6 w-full sm:w-fit">
            <!-- Input Tanggal Mulai -->
            <label for="startDate"
                class="flex items-center gap-x-2 bg-gradient-to-r from-gray-200 rounded-s-md w-full sm:w-fit">
                <span class="text-xs ps-2">Dari</span>
                <input wire:model="startDate" type="date" id="startDate"
                    class="flex rounded-r-md bg-white border-gray-300 px-3 py-1 text-xs sm:text-sm text-gray-800 shadow-sm transition-colors focus:ring-1 h-8 placeholder:text-xs placeholder:text-slate-600">
            </label>

            <!-- Input Tanggal Akhir -->
            <label for="endDate"
                class="flex items-center gap-x-2 bg-gradient-to-r from-gray-200 rounded-s-md w-full sm:w-fit">
                <span class="text-xs ps-2">Sampai</span>
                <input wire:model="endDate" type="date" id="endDate"
                    class="flex rounded-r-md bg-white border-gray-300 px-3 py-1 text-xs sm:text-sm text-gray-800 shadow-sm transition-colors focus:ring-1 h-8 placeholder:text-xs placeholder:text-slate-600">
            </label>
        </div>

    </div>

    {{-- Main Table --}}
    <div class="rounded-md border bg-white whitespace-nowrap">
        <div class="relative w-full overflow-auto h-[500px]">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 sticky top-0 inset-x-0">
                    <tr class="border-b">
                        <th class="h-10 px-4 text-left">
                            <span class="inline-flex font-medium items-center justify-center px-3 text-sm -ml-3">
                                Nama Barang
                            </span>
                        </th>

                        <th class="h-10 px-2 text-center">
                            <span class="inline-flex font-medium items-center justify-center px-3 text-sm -ml-3">
                                Modal Satuan
                            </span>
                        </th>
                        <th class="h-10 px-2 text-center">
                            <span class="inline-flex font-medium items-center justify-center px-3 text-sm -ml-3">
                                Jumlah Barang
                            </span>
                        </th>
                        <th class="h-10 px-2 text-center">
                            <span class="inline-flex font-medium items-center justify-center px-3 text-sm -ml-3">
                                Total Modal
                            </span>
                        </th>
                        <th class="h-10 px-2 text-center">
                            <span class="inline-flex font-medium items-center justify-center px-3 text-sm -ml-3">
                                Total Harga Jual
                            </span>
                        </th>
                        <th class="h-10 px-2 text-center">
                            <span class="inline-flex font-medium items-center justify-center px-3 text-sm -ml-3">
                                Keuntungan
                            </span>
                        </th>
                    </tr>
                </thead>
                <tbody class="h-96 overflow-auto">
                    @forelse($data as $item)
                        <tr class="border-b">
                            <td class="h-10 px-4">{{ $item['name'] }}</td>
                            <td class="h-10 px-2 text-center">
                                @currency($item['cost'])
                            </td>
                            <td class="h-10 px-2 text-center">{{ $item['qty'] }}</td>
                            <td class="h-10 px-2 text-center">
                                @currency($item['total_cost'])
                            </td>
                            <td class="h-10 px-2 text-center">
                                @currency($item['total_price'])
                            </td>
                            <td class="h-10 px-2 text-center">
                                @currency($item['profit'])
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="py-2 text-red-500 text-center">
                                    Data tidak ditemukan
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    <thead class="bg-gray-50 sticky bottom-0 inset-x-0">
                        <tr class="divide-x">
                            <th class="p-2 px-4 text-start font-medium" colspan="2">
                                Total Terjual
                            </th>
                            <th class="p-2 text-center font-medium">
                                {{ $data->sum('qty') }}
                            </th>
                            <th class="p-2 text-center font-medium">
                                @currency($data->sum('total_cost'))
                            </th>
                            <th class="p-2 text-center font-medium">
                                @currency($data->sum('total_price'))
                            </th>
                            <th class="p-2 text-center font-medium">
                                @currency($data->sum('profit'))
                            </th>
                        </tr>
                    </thead>
                </tbody>
            </table>
        </div>
    </div>
</div>
