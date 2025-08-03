<div>
    <x-slot name="title">{{ __('Detail Absensi') }}</x-slot>

    <x-slot name="breadcrumb">
        @php
            $breadcumb = ['Absensi', 'Detail Absensi'];
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

    <div>
        <h2 class="text-base font-semibold leading-7 text-gray-900">Daftar Pegawai</h2>
        <p class="mt-1 mb-6 text-sm leading-6 text-gray-600">
            Berikut adalah daftar kehadiran pada {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
        </p>
    </div>

    {{-- Main Table --}}
    <div class="rounded-md border bg-white">
        <div class="relative w-full overflow-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b">
                        <th class="h-10 px-4 text-left">
                            <span class="inline-flex font-medium items-center justify-center px-3 text-sm -ml-3">
                                Nama Pegawai
                            </span>
                        </th>
                        <th class="h-10 px-2 text-center">
                            <span class="inline-flex font-medium items-center justify-center px-3 text-sm -ml-3">
                                Posisi
                            </span>
                        </th>
                        <th>

                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($attendance->employees as $employee)
                        <tr class="border-b transition-colors hover:bg-gray-50">
                            <td class="p-2 px-4">
                                {{ $employee['name'] }}
                            </td>
                            <td class="p-2 text-center">
                                {{ $employee['potition'] }}
                            </td>
                            <td class="py-2 w-[40%] text-center">
                                <label for="absen.{{ $employee->id }}"
                                    class="relative inline-block h-8 w-14 cursor-pointer rounded-full bg-gray-300 transition [-webkit-tap-highlight-color:_transparent] has-[:checked]:bg-green-500">
                                    <input type="checkbox" id="absen.{{ $employee->id }}" disabled
                                        {{ $employee->pivot->absen ? 'checked' : '' }} class="peer sr-only" />

                                    <span
                                        class="absolute inset-y-0 start-0 m-1 size-6 rounded-full bg-white transition-all peer-checked:start-6"></span>
                                </label>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="py-2 text-red-500 text-center">
                                    Data tidak ditemukan
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
