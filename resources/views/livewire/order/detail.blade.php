<div>
    <x-slot name="title">{{ __('Detail Order') }}</x-slot>

    <x-slot name="breadcrumb">
        @php
            $breadcumb = ['Data Order', 'Detail Order'];
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

    <div class="flex flex-col sm:flex-row items-start sm:justify-between">
        <h2 class="text-2xl font-semibold tracking-tight">{{ $order->name }}</h2>
        <div class="sm:max-w-xs sm:text-right mt-3 sm:mt-0">
            <span class="text-md">{{ $order->phone }}</span><br>
            <span class="text-sm text-gray-500">{{ $order->address }}</span>
        </div>
    </div>

    <div class="flex items-end justify-between mt-6">
        <div>
            <h2 class="text-base font-semibold leading-7 text-gray-900">Detail Order</h2>
            <p class="mt-1 mb-6 text-sm leading-6 text-gray-600">
                Informasi detail mengenai barang.
            </p>
        </div>
    </div>
    <div class="flex gap-4">
        <div x-data="{ open: false }" x-init="open = false" class=" mb-6 ">
            <button type="button"
                class="flex items-center gap-x-4 bg-blue-500 rounded-md px-3 py-2 text-white hover:bg-blue-700"
                @click="open = true">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5">
                    <path fill-rule="evenodd"
                        d="M7.5 6a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM3.751 20.105a8.25 8.25 0 0 1 16.498 0 .75.75 0 0 1-.437.695A18.683 18.683 0 0 1 12 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 0 1-.437-.695Z"
                        clip-rule="evenodd" />
                </svg>
                <span class="text-sm text-nowrap">Informasi Retur</span>
            </button>

            {{-- MODAL INFORMASI RETUR --}}

            <div x-show="open" class="fixed inset-0 flex items-center justify-center z-50"
                x-transition:enter="transition-opacity duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity duration-300"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" style="display: none;">
                <div class="fixed inset-0 bg-gray-500 opacity-75" @click="open = false"></div>
                <div
                    class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:w-full sm:max-w-4xl">
                    <button class="absolute inset-x right-0 top-0 rounded-full bg-white" type="button"
                        @click="open = false">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-8 text-red-500">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                    </button>
                    <div class="p-6">
                        <h2 class="text-lg font-semibold">Detail Informasi Retur</h2>
                        <p class="">Berikut adalah daftar transaksi retur dengan data dummy.</p>

                        <div class="rounded-md border bg-white mt-4 max-h-96 overflow-auto">
                            <div class="relative w-full overflow-auto">
                                <table class="w-full text-sm whitespace-nowrap">

                                    <thead>
                                        <tr class="border-b">
                                            <th class="h-10 px-4 text-left">
                                                <span
                                                    class="inline-flex font-medium items-center justify-center px-3 text-sm -ml-3">
                                                    Nama Barang
                                                </span>
                                            </th>
                                            <th class="h-10 px-2 text-center">
                                                <span
                                                    class="inline-flex font-medium items-center justify-center px-3 text-sm -ml-3">
                                                    Harga
                                                </span>
                                            </th>
                                            <th class="h-10 px-2 text-center">
                                                <span
                                                    class="inline-flex font-medium items-center justify-center px-3 text-sm -ml-3">
                                                    Qty
                                                </span>
                                            </th>
                                            <th class="h-10 px-2 text-center">
                                                <span
                                                    class="inline-flex font-medium items-center justify-center px-3 text-sm -ml-3">
                                                    Subtotal
                                                </span>
                                            </th>
                                        </tr>
                                    </thead>
                                    @if (count($returOrder) > 0)
                                        <tbody>
                                            @foreach ($returOrder as $retur)
                                                <tr class="border-b transition-colors hover:bg-gray-50">
                                                    <td class="p-2 px-4 w-[20%]">{{ $retur->goods->name }}

                                                    </td>
                                                    <td class="p-2 text-center">@currency($retur->cost)</td>
                                                    <td class="p-2 text-center">{{ $retur->retur_qty }}
                                                        {{ $retur->goods->unit }}</td>
                                                    <td class="p-2 text-center">@currency($retur->subcashback)</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>

    <div x-data="{ open: false }" x-init="open = false" class="rounded-md bg-white mt-0 border-b pb-4">
        <div class="relative w-full overflow-auto">
            <table class="w-full text-sm whitespace-nowrap">
                <thead>
                    <tr class="border-b">
                        <th class="h-10 text-left">
                            <span class="inline-flex font-medium items-center justify-center px-3 text-sm -ml-3">
                                Nama Barang
                            </span>
                        </th>
                        <th class="h-10 px-2 text-center">
                            <span class="inline-flex font-medium items-center justify-center px-3 text-sm -ml-3">
                                Harga Beli
                            </span>
                        </th>
                        <th class="h-10 px-2 text-center">
                            <span class="inline-flex font-medium items-center justify-center px-3 text-sm -ml-3">
                                Qty
                            </span>
                        </th>
                        <th class="h-10 px-2 text-right">
                            <span class="inline-flex font-medium items-center justify-center px-3 text-sm -ml-3">
                                Subtotal
                            </span>
                        </th>
                    </tr>
                </thead>
                <tbody class="border-b">
                    @foreach ($order->goods as $good)
                        <tr>
                            <td class="p-4 px-0">
                                {{ $good->name }}
                            </td>
                            <td class="p-4 text-center">
                                @currency($good->pivot->cost)
                            </td>
                            <td class="p-4 px-2 text-center">
                                {{ $good->pivot->qty }} {{ $good->unit }}
                            </td>
                            <td class="px-4 text-right">
                                @currency($good->pivot->subtotal)
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-7 mt-4">
            <div class="sm:col-span-4">
                <div class="grid py-3 grid-cols-2">
                    <dt class="font-medium text-gray-900">Status</dt>
                    <dd class="@if ($order->status !== 'selesai') text-red-500 @else text-green-600 @endif mr-4">
                        {{ $order->status }}</dd>
                </div>
                @if ($order->image)
                    <div class="grid py-3 grid-cols-2">
                        <dt class="font-medium text-gray-900">Foto transaksi</dt>
                        <dd>
                            <button type="button" @click="open = true"
                                class="px-2 py-1 flex items-center gap-x-2 rounded-md bg-sky-500 text-white text-xs">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                    class="size-3">
                                    <path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" />
                                    <path fill-rule="evenodd"
                                        d="M1.323 11.447C2.811 6.976 7.028 3.75 12.001 3.75c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113-1.487 4.471-5.705 7.697-10.677 7.697-4.97 0-9.186-3.223-10.675-7.69a1.762 1.762 0 0 1 0-1.113ZM17.25 12a5.25 5.25 0 1 1-10.5 0 5.25 5.25 0 0 1 10.5 0Z"
                                        clip-rule="evenodd" />
                                </svg>
                                Detail
                            </button>
                        </dd>
                    </div>

                    <div x-show="open" class="fixed inset-0 flex items-center justify-center z-50"
                        x-transition:enter="transition-opacity duration-300" x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity duration-300"
                        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                        style="display: none;">
                        <div class="fixed inset-0 bg-gray-500 opacity-75" @click="open = false"></div>
                        <div class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all w-auto">
                            <div class="flex items-center justify-center p-6">


                                <img src="{{ asset('storage/' . $order->image) }}" alt="Image Preview"
                                    class="mt-2 w-80 object-contain object-center">
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            <div class="col-start-6 col-span-2">
                <div class="grid py-3 grid-cols-2 gap-4 text-end">
                    <dt class="font-medium text-gray-900">Total</dt>
                    <dd class="text-gray-700 sm:mr-4">@currency($order['total'])</dd>
                </div>
            </div>
        </div>
    </div>

    <div x-data="{ modal: false }" class="mt-6 flex items-center justify-end gap-x-6">
        <button @click="modal = true" type="button" wire:click="setReturData"
            class="text-sm font-semibold leading-6 text-red-600">Retur</button>
        <button type="button"
            class="rounded-md bg-sky-500 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-sky-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-sky-500">Cetak</button>


        <div x-show="modal" class="fixed inset-0 flex items-center justify-center z-50"
            x-transition:enter="transition-opacity duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity duration-300"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" style="display: none;">
            <div class="fixed inset-0 bg-gray-500 opacity-75" @click="modal = false" wire:click="cancel"></div>
            <div class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:w-full sm:max-w-4xl">
                <div class="p-6">
                    <h2 class="text-lg font-semibold">Daftar Barang</h2>
                    <p class="">Pilih barang untuk retur.</p>

                    <div class="rounded-md border bg-white mt-4 max-h-96 overflow-auto">
                        <div class="relative w-full overflow-auto">
                            <table class="w-full text-sm whitespace-nowrap">
                                <thead>
                                    <thead>
                                        <tr class="border-b">
                                            <th class="ps-4 h-10 text-left">
                                                <span
                                                    class="inline-flex font-medium items-center justify-center px-3 text-sm -ml-3">
                                                    Nama Barang
                                                </span>
                                            </th>
                                            <th class="h-10 px-2 text-center">
                                                <span
                                                    class="inline-flex font-medium items-center justify-center px-3 text-sm -ml-3">
                                                    Harga
                                                </span>
                                            </th>
                                            <th class="h-10 px-2 text-center">
                                                <span
                                                    class="inline-flex font-medium items-center justify-center px-3 text-sm -ml-3">
                                                    Qty
                                                </span>
                                            </th>
                                            <th class="h-10 px-2 text-center">
                                                <span
                                                    class="inline-flex font-medium items-center justify-center px-3 text-sm -ml-3">
                                                    Input Qty Retur
                                                </span>
                                            </th>
                                            <th class="h-10 px-2 text-center">
                                                <span
                                                    class="inline-flex font-medium items-center justify-center px-3 text-sm -ml-3">
                                                    Retur Uang
                                                </span>
                                            </th>
                                        </tr>
                                    </thead>
                                <tbody class="border-b">
                                    @foreach ($order->goods as $index => $good)
                                        @if ($good->pivot->qty !== 0)
                                            <tr>
                                                <td class="p-4">
                                                    {{ $good->name }}
                                                </td>
                                                <td class="p-4 text-center">
                                                    @currency($good->pivot->cost)
                                                </td>
                                                <td class="p-4 px-2 text-center capitalize">
                                                    {{ $good->pivot->qty }} {{ $good->unit }}
                                                </td>
                                                <td>
                                                    <div class="flex items-center justify-center gap-x-2">
                                                        <div class="flex items-center rounded border border-gray-200">
                                                            <button type="button"
                                                                wire:click="decrement({{ $index }})"
                                                                class="size-10 leading-10 text-gray-600 transition hover:opacity-75">
                                                                &minus;
                                                            </button>

                                                            <input type="text" id="retur_qty-{{ $index }}"
                                                                wire:model="returGoods.{{ $index }}.retur_qty"
                                                                class="h-10 w-16 border-transparent text-center [-moz-appearance:_textfield] sm:text-sm [&::-webkit-inner-spin-button]:m-0 [&::-webkit-inner-spin-button]:appearance-none [&::-webkit-outer-spin-button]:m-0 [&::-webkit-outer-spin-button]:appearance-none" />

                                                            <button type="button"
                                                                wire:click="increment({{ $index }})"
                                                                class="size-10 leading-10 text-gray-600 transition hover:opacity-75">
                                                                &plus;
                                                            </button>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-4 text-end">
                                                    @currency($good->pivot->cost * ($returGoods[$index]['retur_qty'] ?? 0))
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="grid grid-cols-7 mt-2">
                            <div class="col-span-7 sm:col-start-6 sm:col-span-2">
                                <div class="grid py-3 grid-cols-2 gap-4 text-end">
                                    <dt class="font-medium text-gray-900 text-end">Total</dt>
                                    <dd class="text-gray-700 mr-4">@currency($cashback)</dd>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 flex items-center justify-end gap-x-6">
                        <button type="button" @click="modal = false" wire:click="cancel"
                            class="text-sm font-semibold leading-6 text-gray-900">Batal</button>
                        <button type="button" @click="modal = false" wire:click="retur"
                            class="rounded-md bg-sky-500 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-sky-400">Submit</button>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(document).on('input', '[id^="retur_qty-"]', function() {
            if ($(this).val() == "") {
                $(this).val(0).select();
            }
        });
    </script>
@endpush
