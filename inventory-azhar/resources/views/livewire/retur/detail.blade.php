<div>
    <x-slot name="title">{{ __('Detail Retur') }}</x-slot>

    <x-slot name="breadcrumb">
        @php
            $breadcumb = ['Data Barang', 'Kel. Data Barang', 'Detail Retur'];
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

    <div class="flex items-start justify-between">
        <h2 class="text-2xl font-semibold tracking-tight">{{ $retur->name }}</h2>
        <div class="max-w-xs text-right">
            <span class="text-md">{{ $retur->phone }}</span><br>
            <span class="text-sm text-gray-500">{{ $retur->address }}</span>
        </div>
    </div>

    <div class="flex items-end justify-between mt-6">
        <div>
            <h2 class="text-base font-semibold leading-7 text-gray-900">Detail Retur</h2>
            <p class="mt-1 mb-6 text-sm leading-6 text-gray-600">
                Informasi detail mengenai barang.
            </p>
        </div>
    </div>

    <div x-data="{ open: false }" x-init="open = false" class="rounded-md bg-white mt-0 pb-4">
        <div class="relative w-full overflow-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b">
                        <th class="h-10 text-left">
                            <span class="inline-flex font-medium items-center justify-center px-3 text-sm -ml-3">
                                Nama Barang
                            </span>
                        </th>
                        <th class="h-10 px-2 text-center">
                            <span class="inline-flex font-medium items-center justify-center px-3 text-sm -ml-3">
                                Kategori
                            </span>
                        </th>
                        <th class="h-10 px-2 text-center">
                            <span class="inline-flex font-medium items-center justify-center px-3 text-sm -ml-3">
                                Qty
                            </span>
                        </th>
                    </tr>
                </thead>
                <tbody class="border-b">
                    @foreach ($retur->goods as $good)
                        <tr>
                            <td class="p-4 px-0">
                                {{ $good->name }}
                            </td>
                            <td class="p-4 text-center">
                                {{ $good->category->name }}
                            </td>
                            <td class="p-4 px-2 text-center">
                                {{ $good->pivot->qty }} {{ $good->unit }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
