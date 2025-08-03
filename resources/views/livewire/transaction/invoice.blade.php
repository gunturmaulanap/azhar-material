<div class="h-screen w-screen overflow-auto">
    <div class="w-screen bg-gray-100 min-h-screen overflow-auto">
        <div class="flex justify-center bg-gray-100 w-screen">
            <div class="bg-white px-12 py-4">
                <span class="text-2xl">RECIPE</span>
            </div>
        </div>
        <div class="flex justify-between items-center px-8 py-6">
            <div class="flex flex-col">
                <span class="text-4xl">
                    AZHAR MATERIAL
                </span>
                <span class="capitalize">Sedia Alat listrik dan bahan baku <br> cilacap, karangpucung, citando</span>
            </div>
            <span>{{ \Carbon\Carbon::parse($transaction->created_at)->translatedFormat('d F Y') }}</span>
        </div>

        <div class="bg-white p-8">
            <div class="flex items-start justify-between">
                <h2 class="text-2xl font-semibold tracking-tight">{{ $transaction->name }}</h2>
                <div class="max-w-xs text-right">
                    <span class="text-md">{{ $transaction->phone }}</span><br>
                    <span class="text-md text-gray-500">{{ $transaction->address }}</span>
                </div>
            </div>

            <div class="flex items-end justify-between mt-6">
                <div>
                    <h2 class="text-base font-semibold text-gray-900">Detail Transaksi</h2>
                    <p class="mb-6 text-md text-gray-600">
                        Informasi detail mengenai barang.
                    </p>
                </div>
            </div>

            <div class="mt-0 border-b pb-4">
                <!-- Tambahkan overflow-auto di wrapper tabel -->
                <div class="relative w-full max-w-full overflow-auto">
                    <table class="w-full text-sm max-h-[500px] overflow-auto">
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
                                        Status
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
                                                Dikirim
                                            @endif
                                        </td>
                                        <td class="px-4 text-right">
                                            @currency($good->pivot->subtotal)
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="grid grid-cols-7 mt-4">
                    <div class="col-start-6 col-span-2">
                        <div class="grid py-3 grid-cols-2 gap-4 text-end">
                            <dt class="font-extrabold text-gray-950">Total</dt>
                            <dd class="font-extrabold text-gray-900 mr-4">@currency($transaction['total'])</dd>
                        </div>
                        @if ($transaction['discount'] > 0)
                            <div class="grid py-3 grid-cols-2 gap-4 text-end">
                                <dt class="font-light text-gray-900">Potongan</dt>
                                <dd class="text-gray-700 mr-4">@currency($transaction['discount'])</dd>
                            </div>
                        @endif
                        @if ($transaction['balance'] > 0)
                            <div class="grid py-3 grid-cols-2 gap-4 text-end">
                                <dt class="font-light text-gray-900">Saldo</dt>
                                <dd class="text-gray-700 mr-4">@currency($transaction['balance'])</dd>
                            </div>
                        @endif
                        @if ($transaction['grand_total'] > 0 && $transaction['grand_total'] !== $transaction['total'])
                            <div class="grid py-3 grid-cols-2 gap-4 text-end">
                                <dt class="font-extrabold text-gray-950">Grand total</dt>
                                <dd class="font-extrabold text-gray-900 mr-4">@currency($transaction['grand_total'])</dd>
                            </div>
                        @endif
                        @if ($transaction['balance'] - ($transaction['total'] - $transaction['discount']) > 0)
                            <div class="grid py-3 grid-cols-2 gap-4 text-end">
                                <dt class="font-light text-gray-900">Sisa saldo</dt>
                                <dd class="text-gray-700 mr-4">@currency($transaction['balance'] - ($transaction['total'] - $transaction['discount']))</dd>
                            </div>
                        @endif
                        @if ($transaction['bill'] > 0)
                            <div class="grid py-3 grid-cols-2 gap-4 text-end">
                                <dt class="font-light text-gray-900">Bayar</dt>
                                <dd class="text-gray-700 mr-4">@currency($transaction['bill'])</dd>
                            </div>
                        @endif
                        @if ($transaction['return'] !== 0)
                            <div class="grid py-3 grid-cols-2 gap-4 text-end">
                                <dt class="font-light {{ $transaction['return'] > 0 ? 'text-gray-900' : 'text-red-700' }}">
                                    {{ $transaction['return'] > 0 ? 'Kembalian' : 'Kurang' }}
                                </dt>
                                <dd class="{{ $transaction['return'] > 0 ? 'text-gray-700' : 'text-red-600' }} mr-4">
                                    @currency(abs($transaction['return']))
                                </dd>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>