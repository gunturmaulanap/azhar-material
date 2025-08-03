<div>
    <x-slot name="title">{{ __('Detail Transaksi') }}</x-slot>

    <x-slot name="breadcrumb">
        @php
            $breadcrumb = ['Riwayat Transaksi', 'Detail Transaksi'];
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

    <div class="flex flex-col sm:flex-row items-start sm:justify-between">
        <h2 class="text-2xl font-semibold tracking-tight">{{ $transaction->name }}</h2>
        <div class="sm:max-w-xs sm:text-right mt-3 sm:mt-0">
            <span class="text-md">{{ $transaction->phone }}</span><br>
            <span class="text-sm text-gray-500">{{ $transaction->address }}</span>
        </div>
    </div>

    <div class="flex flex-col sm:flex-row items-end sm:justify-between mt-6">
        <div class="w-full sm:w-fit">
            <h2 class="text-base font-semibold leading-7 text-gray-900">Detail Transaksi</h2>
            <p class="mt-1 mb-6 text-sm leading-6 text-gray-600">
                Informasi detail mengenai barang.
            </p>
        </div>
        <div class="flex gap-4 w-full sm:w-fit">
            @if (in_array(auth()->user()->role, ['super_admin', 'admin']))
                @if (isset($transaction->delivery->id))
                    <a href="{{ route('delivery.detail', ['id' => $transaction->delivery->id]) }}"
                        class="flex items-center gap-x-2 rounded-md bg-yellow-300 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-yellow-400 mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
                        </svg>
                        <span>Kelola Pengiriman</span>
                    </a>
                @endif
            @else
                @if (isset($transaction->delivery->id))
                    <a href="{{ route('customer.delivery.detail', ['id' => $transaction->delivery->id]) }}"
                        class="flex items-center gap-x-2 rounded-md bg-yellow-300 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-yellow-400 mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
                        </svg>
                        <span>Lihat Pengiriman</span>
                    </a>
                @endif
            @endif


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
                        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                        style="display: none;">
                        <div class="fixed inset-0 bg-gray-500 opacity-75" @click="open = false"></div>
                        <div
                            class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:w-full sm:max-w-4xl">
                            <button class="absolute inset-x right-0 top-0 rounded-full bg-white" type="button"
                                @click="open = false">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-8 text-red-500">
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
                                            @if (count($returTransactions) > 0)
                                                <tbody>
                                                    @foreach ($returTransactions as $retur)
                                                        <tr class="border-b transition-colors hover:bg-gray-50">
                                                            <td class="p-2 px-4 w-[20%]">{{ $retur->goods->name }}</td>
                                                            <td class="p-2 text-center">@currency($retur->price)</td>
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

        </div>
    </div>

    <div x-data="{ open: false }" x-init="open = false" class="mt-0 border-b pb-4">
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
                                Harga
                            </span>
                        </th>
                        <th class="h-10 px-2 text-center">
                            <span class="inline-flex font-medium items-center justify-center px-3 text-sm -ml-3">
                                Qty
                            </span>
                        </th>
                        <th class="h-10 px-2 text-center">
                            <span class="inline-flex font-medium items-center justify-center px-3 text-sm -ml-3">
                                Terkirim
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
                    @foreach ($transaction->goods as $good)
                        @if ($good->pivot->qty > 0)
                            <tr>
                                <td class="p-4 px-0">
                                    {{ $good->name }}
                                </td>
                                <td class="p-4 text-center">
                                    @currency($good->pivot->price)
                                </td>
                                <td class="p-4 text-center capitalize">
                                    {{ $good->pivot->qty }} {{ $good->unit }}
                                </td>
                                <td class="p-4 text-center">
                                    @if ($good->pivot->delivery)
                                        {{ \App\Models\DeliveryGoods::where('goods_id', $good->id)->whereHas('delivery', function ($query) use ($transaction) {
                                                $query->where('transaction_id', $transaction->id);
                                            })->sum('delivered') }}
                                        {{ $good->unit }}
                                    @endif
                                </td>
                                <td class="px-4 text-right">
                                    @currency($good->pivot->subtotal)
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
                {{-- @if (count($returTransactions) > 0)
                    <thead class="bg-amber-50">
                        <th class="h-10 text-start" colspan="4">
                            <span class="inline-flex font-medium items-center justify-start px-3 text-sm -ml-3">
                                Barang Retur
                            </span>
                        </th>
                        <th class="h-10 text-right">
                            <span class="inline-flex font-medium items-center justify-start px-3 text-sm -ml-3">
                                -
                            </span>
                        </th>
                    </thead>
                    <tbody class="border-b">
                        @foreach ($returTransactions as $retur)
                            <tr>
                                <td class="p-4 px-0">
                                    {{ $retur->goods->name }}
                                </td>
                                <td class="p-4 text-center">
                                    @currency($retur->price)
                                </td>
                                <td class="p-4 text-center capitalize">
                                    {{ $retur->retur_qty }} {{ $retur->goods->unit }}
                                </td>
                                <td class="p-4 text-center">

                                </td>
                                <td class="px-4 text-right">
                                    @currency($retur->subcashback)
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                @endif --}}
            </table>
        </div>

        <div x-data="{ open: false }" class="grid grid-cols-1 sm:grid-cols-7 mt-4">
            <div class="sm:col-span-4">

                <div class="grid py-3 grid-cols-2 sm:grid-cols-3 text-end sm:text-start">
                    <dt class="font-medium text-gray-900">Status</dt>
                    <dd
                        class="@if ($transaction->status !== 'selesai') text-yellow-600 @else text-green-600 @endif capitalize">
                        {{ $transaction->status }}</dd>
                </div>
                @if ($transaction->image)
                    <div class="grid py-3 grid-cols-2 sm:grid-cols-3">
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
                                <img src="{{ asset('storage/' . $transaction->image) }}" alt="Image Preview"
                                    class="mt-2 w-80 object-contain object-center">
                            </div>
                        </div>
                    </div>
                @endif
                @if ($transaction->actDebts->count() > 0)
                    <h3 class="font-medium text-gray-900">Riwayat pelunasan</h3>
                    <ol class="flex flex-col mt-2 items-start gap-2 text-sm font-medium text-gray-500">
                        @foreach ($transaction->actDebts as $act)
                            <li class="flex items-center justify-center gap-2">
                                <span
                                    class="px-2 py-0.5 rounded bg-blue-50 text-center text-sm font-bold text-blue-600">
                                    @currency($act->pay)
                                </span>

                                <span>Telah dibayarkan pada
                                    {{ \Carbon\Carbon::parse($act->created_at)->translatedFormat('d F Y') }}</span>
                            </li>
                        @endforeach
                    </ol>
                @endif
            </div>
            <div class="sm:col-start-6 sm:col-span-2">
                <div class="grid py-3 grid-cols-2 gap-4 text-end">
                    <dt class="font-extrabold text-gray-950">Total</dt>
                    <dd class="font-extrabold text-gray-900">@currency($transaction['total'])</dd>
                </div>
                @if ($transaction['discount'] > 0)
                    <div class="grid py-3 grid-cols-2 gap-4 text-end">
                        <dt class="font-light text-gray-900">Potongan</dt>
                        <dd class="text-gray-700">@currency($transaction['discount'])</dd>
                    </div>
                @endif
                @if ($transaction['balance'] > 0)
                    <div class="grid py-3 grid-cols-2 gap-4 text-end">
                        <dt class="font-light text-gray-900">Saldo</dt>
                        <dd class="text-gray-700">@currency($transaction['balance'])</dd>
                    </div>
                @endif
                @if ($transaction['grand_total'] > 0 && $transaction['grand_total'] !== $transaction['total'])
                    <div class="grid py-3 grid-cols-2 gap-4 text-end">
                        <dt class="font-extrabold text-gray-950">Grand total</dt>
                        <dd class="font-extrabold text-gray-900">@currency($transaction['grand_total'])</dd>
                    </div>
                @endif
                @if ($transaction['balance'] - ($transaction['total'] - $transaction['discount']) > 0)
                    <div class="grid py-3 grid-cols-2 gap-4 text-end">
                        <dt class="font-light text-gray-900">Sisa saldo</dt>
                        <dd class="text-gray-700">@currency($transaction['balance'] - ($transaction['total'] - $transaction['discount']))</dd>
                    </div>
                @endif
                @if ($transaction['bill'] > 0)
                    <div class="grid py-3 grid-cols-2 gap-4 text-end">
                        <dt class="font-light text-gray-900">Bayar</dt>
                        <dd class="text-gray-700">@currency($transaction['bill'])</dd>
                    </div>
                @endif
                @if ($transaction['return'] !== 0)
                    <div class="grid py-3 grid-cols-2 gap-4 text-end">
                        <dt class="font-light {{ $transaction['return'] > 0 ? 'text-gray-900' : 'text-red-700' }}">
                            {{ $transaction['return'] > 0 ? 'Kembalian' : 'Kurang' }}
                        </dt>
                        <dd class="{{ $transaction['return'] > 0 ? 'text-gray-700' : 'text-red-600' }}">
                            @currency(abs($transaction['return']))
                        </dd>
                    </div>
                @endif
                @if (auth()->user()->role === 'super_admin')
                    @if ($transaction['status'] == 'hutang' && $transaction['total'] > 0 && $transaction['return'] < 0)
                        <div x-data="{ open: false }" class="flex justify-end mt-2">
                            <button type="button" @click="open = true"
                                class="flex items-center gap-x-2 rounded-md bg-green-500 px-2 py-1 text-xs font-semibold text-white shadow-sm hover:bg-green-400">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-4">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z" />
                                </svg>
                                Bayar
                            </button>

                            <div x-show="open" class="fixed inset-0 flex items-center justify-center z-50"
                                x-transition:enter="transition-opacity duration-300"
                                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                x-transition:leave="transition-opacity duration-300"
                                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                style="display: none;">
                                <div class="fixed inset-0 bg-gray-500 opacity-75" @click="open = false"></div>
                                <div
                                    class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:w-full sm:max-w-2xl">
                                    <button class="absolute inset-x right-0 top-0 rounded-full bg-white"
                                        type="button" @click="open = false">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-8 text-red-500">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                        </svg>
                                    </button>
                                    <div class="p-6">
                                        <h2 class="text-lg font-semibold">Bayar hutang</h2>
                                        <p class="">Transaksi akan selesai jika status tidak hutang</p>
                                        <div class="flow-root rounded-lg border border-gray-100 py-3 shadow-sm mt-4">
                                            <dl class="-my-3 divide-y divide-gray-100 text-sm">
                                                <div class="grid p-3 grid-cols-3 gap-4">
                                                    <dt class="font-medium text-gray-900">Tagihan</dt>
                                                    <dd class="text-gray-700 col-span-2">
                                                        @currency(abs($transaction->return))
                                                    </dd>
                                                </div>

                                                <div class="grid p-3 grid-cols-3 gap-4">
                                                    <dt class="font-medium text-gray-900">Bayar</dt>
                                                    <dd class="text-gray-700 col-span-2">
                                                        <input wire:model="input.bill" type="number" id="bill"
                                                            class="block w-full rounded-md border-0 ms-1 py-1 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-sky-500 sm:text-sm sm:leading-6"
                                                            min="0"
                                                            oninput="this.value = Math.min(Math.abs(this.value), {{ abs((int) $transaction['return']) }});" />
                                                    </dd>
                                                </div>
                                            </dl>
                                        </div>

                                        <div class="mt-6 flex items-center justify-end gap-x-6">
                                            <button type="button" @click="open = false"
                                                class="text-sm font-semibold leading-6 text-gray-900">Batal</button>
                                            <button type="button" @click="open = false" wire:click="pay"
                                                class="rounded-md bg-sky-500 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-sky-400">Submit</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
    @if (in_array(auth()->user()->role, ['super_admin', 'admin']))

        <div x-data="{ modal: false }" class="mt-6 flex items-center justify-end gap-x-6">
            <button @click="modal = true" type="button" wire:click="setReturData"
                class="text-sm font-semibold leading-6 text-red-600">Retur</button>
            <a href="{{ route('transaction.mini-invoice', ['id' => $transaction->id]) }}" target="_blank"
                class="text-sm font-semibold">Nota kecil</a>
            <a href="{{ route('transaction.invoice', ['id' => $transaction->id]) }}" target="_blank"
                class="rounded-md bg-sky-500 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-sky-400">Cetak</a>

            <div x-show="modal" class="fixed inset-0 flex items-center justify-center z-50"
                x-transition:enter="transition-opacity duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity duration-300"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" style="display: none;">
                <div class="fixed inset-0 bg-gray-500 opacity-75" @click="modal = false" wire:click="cancel"></div>
                <div
                    class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:w-full sm:max-w-4xl">
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
                                                        Terkirim
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
                                        @foreach ($transaction->goods as $index => $good)
                                            @if ($good->pivot->qty !== 0)
                                                <tr>
                                                    <td class="p-4">
                                                        {{ $good->name }}
                                                    </td>
                                                    <td class="p-4 text-center">
                                                        @currency($good->pivot->price)
                                                    </td>
                                                    <td class="p-4 px-2 text-center capitalize">
                                                        {{ $good->pivot->qty }} {{ $good->unit }}
                                                    </td>
                                                    <td class="p-4 px-2 text-center capitalize">
                                                        @if ($good->pivot->delivery)
                                                            {{ \App\Models\DeliveryGoods::where('goods_id', $good->id)->whereHas('delivery', function ($query) use ($transaction) {
                                                                    $query->where('transaction_id', $transaction->id);
                                                                })->sum('delivered') }}
                                                            {{ $good->unit }}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="flex items-center justify-center gap-x-2">
                                                            <div
                                                                class="flex items-center rounded border border-gray-200">
                                                                <button type="button"
                                                                    wire:click="decrement({{ $index }})"
                                                                    class="size-10 leading-10 text-gray-600 transition hover:opacity-75">
                                                                    &minus;
                                                                </button>

                                                                <input type="text"
                                                                    id="retur_qty-{{ $index }}"
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
                                                    <td class="px-4 text-center">
                                                        @currency($good->pivot->price * ($returGoods[$index]['retur_qty'] ?? 0))
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="grid grid-cols-7 mt-2">
                                <div class="col-span-7 sm:col-start-6 sm:col-span-2">
                                    <div class="grid py-3 grid-cols-2 gap-4 text-end sm:mr-4">
                                        <dt class="font-medium text-gray-900">Total</dt>
                                        <dd class="text-gray-700">@currency($total)</dd>
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
    @endif

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
