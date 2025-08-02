<div>
    <x-slot name="title">{{ __('Laporan Penjualan') }}</x-slot>

    <x-slot name="breadcrumb">
        @php
            $breadcumb = ['Laporan', 'Laporan Penjualan'];
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
    <div class="flex flex-col sm:flex-row items-center justify-between mb-4 gap-y-3 sm:gap-y-0">
        <div class="flex items-center sm:gap-x-4 w-full">
            {{-- <input wire:model="search"
                class="flex rounded-md bg-white border-gray-300 px-3 py-1 w-64 text-sm text-gray-800 shadow-sm transition-colors focus:ring-1 h-8 placeholder:text-xs placeholder:text-slate-600"
                placeholder="Cari customer..."> --}}
            <select wire:model="perMounth"
                class="flex rounded-md bg-white border-gray-300 px-3 py-1 w-full sm:w-40 text-sm text-gray-800 shadow-sm transition-colors focus:ring-1 h-8 placeholder:text-xs placeholder:text-slate-600">
                <option value="">Pilih Bulan</option>
                @foreach (range(1, 12) as $month)
                    <option value="{{ $month }}">
                        {{ \Carbon\Carbon::create()->month($month)->translatedFormat('F') }}
                    </option>
                @endforeach
            </select>

        </div>
        <div class="flex items-center gap-x-2 sm:gap-x-6 w-full sm:w-fit">
            <label for="startDate"
                class="flex items-center gap-x-2 bg-gradient-to-r from-gray-200 rounded-s-md w-full sm:w-fit">
                <span class="text-xs ps-2">Dari</span>
                <input wire:model="startDate" type="date" id="startDate"
                    class="flex rounded-r-md bg-white border-gray-300 px-3 py-1 text-xs sm:text-sm text-gray-800 shadow-sm transition-colors focus:ring-1 h-8 placeholder:text-xs placeholder:text-slate-600">
            </label>
            <label for="endDate"
                class="flex items-center gap-x-2 bg-gradient-to-r from-gray-200 rounded-s-md w-full sm:w-fit">
                <span class="text-xs ps-2">Sampai</span>
                <input wire:model="endDate" type="date" id="endDate"
                    class="flex rounded-r-md bg-white border-gray-300 px-3 py-1 text-xs sm:text-sm text-gray-800 shadow-sm transition-colors focus:ring-1 h-8 placeholder:text-xs placeholder:text-slate-600">
            </label>
        </div>
    </div>

    {{-- Main Table --}}

    <div class="flex gap-6 w-full overflow-x-auto">
        <!-- Tabel Pemasukan -->
        <div class="sm:w-1/3">
            <div class="border border-gray-300 rounded-md">
                <h2 class="text-lg font-semibold mb-0 p-3 text-center bg-green-50 border-b border-gray-300">
                    Tabel Pemasukan
                </h2>
                <div class="relative w-full overflow-auto h-[500px]">
                    <table class="w-full text-sm border border-gray-300">
                        <thead class="sticky top-0 inset-x-0 bg-gray-50"> <!-- Background di sini -->
                            <tr class="border-b">
                                <th class="h-10 px-4 text-left border-r border-gray-300 bg-white">Tanggal</th>
                                <!-- Border dan bg untuk header -->
                                <th class="h-10 px-4 text-left bg-white">Nominal</th>
                                <!-- Border dan bg untuk header -->
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($data as $item)
                                <tr class="border-b transition-colors hover:bg-gray-50">
                                    <td
                                        class="p-2 px-4 border-r whitespace-nowrap border-gray-300 text-sm text-gray-600">
                                        {{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d F Y') }}
                                    </td>
                                    <td class="p-2 px-4 text-sm text-gray-800">
                                        <a href="{{ route('transaction.detail', ['id' => $item->id]) }}"
                                            class="text-blue-500 hover:underline whitespace-nowrap">
                                            @currency($item->total)
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="py-2 text-red-500 text-center">Data tidak ditemukan
                                    </td>
                                </tr>
                            @endforelse

                            @if ($data->isNotEmpty())
                                <tr class="border-t">
                                    <td
                                        class="p-2 px-4 border-r whitespace-nowrap border-gray-300 text-lg font-bold text-gray-800 bg-gray-100">
                                        Total Pemasukan
                                    </td>
                                    <td class="p-2 px-4 text-lg font-bold text-green-500 bg-gray-100 whitespace-nowrap">
                                        @currency($data->sum('total'))
                                    </td>
                                </tr>
                            @endif
                        </tbody>

                    </table>
                </div>
            </div>
        </div>

        <!-- Tabel Pengeluaran -->
        <div class="sm:w-1/3">
            <div class="border border-gray-300 rounded-md">
                <h2 class="text-lg font-semibold mb-0 p-3 text-center bg-red-50 border-b border-gray-300">
                    Tabel Pengeluaran
                </h2>
                <div class="relative w-full overflow-auto h-[500px]">
                    <table class="w-full text-sm border border-gray-300">
                        <thead class="sticky top-0 inset-x-0 bg-gray-50"> <!-- Background di sini -->
                            <tr class="border-b">
                                <th class="h-10 px-4 text-left border-r border-gray-300 bg-white">Tanggal</th>
                                <!-- Border dan bg untuk header -->
                                <th class="h-10 px-4 text-left bg-white">Nominal</th>
                                <!-- Border dan bg untuk header -->
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($orders as $item)
                                <!-- Loop untuk data pengeluaran -->
                                <tr class="border-b transition-colors hover:bg-gray-50">
                                    <td class="p-2 px-4 border-r whitespace-nowrap border-gray-300">
                                        {{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d F Y') }}
                                    </td>
                                    <td class="p-2 px-4">
                                        <a href="{{ route('order.detail', ['id' => $item->id]) }}"
                                            class="text-blue-500 hover:underline whitespace-nowrap">@currency($item->total)</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="py-2 text-red-500 text-center">Data tidak ditemukan
                                    </td>
                                </tr>
                            @endforelse

                            @if ($orders->isNotEmpty())
                                <tr class="border-t">
                                    <td
                                        class="p-2 px-4 border-r whitespace-nowrap border-gray-300 text-lg font-bold text-gray-800 bg-gray-100">
                                        Total Pengeluaran
                                    </td>
                                    <td class="p-2 px-4 text-lg font-bold text-red-500 bg-gray-100 whitespace-nowrap">
                                        @currency($orders->sum('total'))
                                    </td>
                                </tr>
                            @endif

                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Tabel Hutang -->
        <div class="sm:w-1/3">
            <div class="border border-gray-300 rounded-md">
                <h2 class="text-lg font-semibold mb-0 p-3 text-center bg-indigo-50 border-b border-gray-300">
                    Tabel Hutang
                </h2>
                <div class="relative w-full overflow-auto h-[500px]">
                    <table class="w-full text-sm border border-gray-300">
                        <thead class="sticky top-0 inset-x-0 bg-gray-50"> <!-- Background di sini -->
                            <tr class="border-b">
                                <th class="h-10 px-4 text-left border-r border-gray-300 bg-white">Tanggal</th>
                                <!-- Border dan bg untuk header -->
                                <th class="h-10 px-4 text-left bg-white">Nominal</th>
                                <!-- Border dan bg untuk header -->
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($debt as $item)
                                <tr class="border-b transition-colors hover:bg-gray-50">
                                    <td
                                        class="p-2 px-4 border-r whitespace-nowrap border-gray-300 text-sm text-gray-600">
                                        {{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d F Y') }}
                                    </td>
                                    <td class="p-2 px-4 text-sm text-gray-800">
                                        <a href="{{ route('transaction.detail', ['id' => $item->id]) }}"
                                            class="text-blue-500 hover:underline whitespace-nowrap">
                                            @currency($item->total)
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="py-2 text-red-500 text-center">Data tidak ditemukan
                                    </td>
                                </tr>
                            @endforelse

                            @if ($debt->isNotEmpty())
                                <tr class="border-t">
                                    <td
                                        class="p-2 px-4 border-r whitespace-nowrap border-gray-300 text-lg font-bold text-gray-800 bg-gray-100">
                                        Total Hutang
                                    </td>
                                    <td
                                        class="p-2 px-4 text-lg font-bold text-indigo-500 bg-gray-100 whitespace-nowrap">
                                        @currency($debt->sum('total'))
                                    </td>
                                </tr>
                            @endif
                        </tbody>

                    </table>
                </div>
            </div>
        </div>


    </div>

</div>
