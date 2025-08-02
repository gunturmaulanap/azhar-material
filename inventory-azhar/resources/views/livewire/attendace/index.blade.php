<div>
    <x-slot name="title">{{ __('Absensi') }}</x-slot>

    <x-slot name="breadcrumb">
        @php
            $breadcumb = ['Absensi'];
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
    <div class="flex items-center sm:justify-between mb-4 w-full">
        <div class="hidden sm:flex items-center gap-x-4">
            {{-- <input wire:model="search"
                class="flex rounded-md bg-white border-gray-300 px-3 py-1 w-64 text-sm text-gray-800 shadow-sm transition-colors focus:ring-1 h-8 placeholder:text-xs placeholder:text-slate-600"
                placeholder="Cari admin..."> --}}
        </div>
        <div class="flex gap-4 w-full sm:w-fit">
            <select id="monthSelect" wire:model="selectedMonth"
                class="block w-full sm:w-44 py-1.5 text-xs sm:text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                @foreach ($months as $month)
                    <option value="{{ $month }}">{{ \Carbon\Carbon::parse($month . '-01')->format('F Y') }}
                    </option>
                @endforeach
            </select>

            <select id="weekSelect" wire:model="selectedWeek"
                class="block w-full py-1.5 text-xs sm:text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                <option value="1">Minggu 1</option>
                <option value="2">Minggu 2</option>
                <option value="3">Minggu 3</option>
                <option value="4">Minggu 4</option>
                <option value="5">Minggu 5</option>
            </select>
        </div>
    </div>

    <div class="rounded-md border bg-white">
        <div class="relative w-full overflow-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b">
                        <th class="h-10 px-4 text-left whitespace-nowrap">Nama Pegawai</th>
                        @foreach ($weekDates as $date)
                            <th class="h-10 px-4 text-center pt-2">
                                <div class="flex flex-col space-y-0">
                                    <span
                                        class="leading-none">{{ \Carbon\Carbon::parse($date)->translatedFormat('l') }}</span>
                                    <span
                                        class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($date)->translatedFormat('d M Y') }}</span>
                                </div>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($weeklyAttendance as $attendance)
                        <tr class="border-b transition-colors hover:bg-gray-50">
                            <td class="px-4 py-2 whitespace-nowrap">{{ $attendance['employee'] }}</td>
                            @foreach ($attendance['attendance'] as $date => $status)
                                <td class="px-4 py-2 text-center">
                                    <select
                                        wire:change="updateAttendance({{ $attendance['employee_id'] }}, '{{ $date }}', $event.target.value)"
                                        class="mt-1 block xl:w-full pl-3 pr-10 py-1 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value=""></option>
                                        <option value="Full Day" {{ $status === 'Full Day' ? 'selected' : '' }}>Full Day
                                        </option>
                                        <option value="Half Day" {{ $status === 'Half Day' ? 'selected' : '' }}>Half
                                            Day
                                        </option>
                                        <option value="Alpha" {{ $status === 'Alpha' ? 'selected' : '' }}>Alpha
                                        </option>
                                    </select>
                                    {{-- <div class="space-y-2">
                                        <div class="flex items-center gap-x-3">
                                            <input id="push-everything" name="push-notifications" type="radio"
                                                class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-600">
                                            <label for="push-everything"
                                                class="block text-sm/6 font-medium text-gray-900">Hadir</label>
                                        </div>
                                        <div class="flex items-center gap-x-3">
                                            <input id="push-email" name="push-notifications" type="radio"
                                                class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-600">
                                            <label for="push-email"
                                                class="block text-sm/6 font-medium text-gray-900">Izin</label>
                                        </div>
                                        <div class="flex items-center gap-x-3">
                                            <input id="push-nothing" name="push-notifications" type="radio"
                                                class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-600">
                                            <label for="push-nothing"
                                                class="block text-sm/6 font-medium text-gray-900">Alpha</label>
                                        </div>
                                    </div> --}}
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
