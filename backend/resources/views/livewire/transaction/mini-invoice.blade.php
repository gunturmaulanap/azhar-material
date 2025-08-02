<div>
    <div class="max-w-sm mx-auto bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold text-center mb-4">AZHAR MATERIAL</h1>
        <h4 class="capitalize text-center">Sedia Alat listrik dan bahan baku cilacap, karangpucung, citando
        </h4>
        <p class="text-gray-600 text-center">
            <span class="font-semibold">
                {{ \Carbon\Carbon::parse($transaction->created_at)->translatedFormat('d F Y') }}
            </span>
        </p>
        {{-- <p class="text-gray-600 text-center"> <span class="font-semibold">{{ $transaction->id }}</span></p> --}}

        @if ($transaction->id !== 1)
            <div class="mt-6">
                <h2 class="text-lg font-semibold">{{ $transaction->name }}</h2>
                <p class="text-gray-700"><span class="font-semibold">{{ $transaction->phone }}</span></p>
                <p class="text-gray-700">{{ $transaction->address }}</p>
            </div>
        @endif

        <div class="mt-6">
            <h2 class="text-lg font-semibold">Detail transaksi:</h2>
            <table class="min-w-full mt-2 text-sm">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="py-2 text-left">Barang</th>
                        <th class="py-2 text-right">Harga</th>
                        <th class="py-2 text-right">Qty</th>
                        <th class="py-2 text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transaction->goods as $good)
                        <tr>
                            <td class="py-2">{{ $good->name }}</td>
                            <td class="py-2 text-right">@currency($good->pivot->price)</td>
                            <td class="py-2 text-right">{{ $good->pivot->qty }} {{ $good->unit }}</td>
                            <td class="py-2 text-right">@currency($good->pivot->subtotal)</td>
                        </tr>
                        @if ($good->pivot->delivery)
                            <tr>
                                <td>Dikirim</td>
                            </tr>
                        @endif
                    @endforeach
                    <tr class="font-semibold">
                        <td class="py-2 text-right"></td>
                        <td class="py-2">Total</td>
                        <td class="py-2 text-right" colspan="2">@currency($transaction['total'])</td>
                    </tr>
                    @if ($transaction['discount'] > 0)
                        <tr class="font-semibold">
                            <td class="py-2 text-right"></td>
                            <td class="py-2">Potongan</td>
                            <td class="py-2 text-right" colspan="2">@currency($transaction['discount'])</td>
                        </tr>
                    @endif
                    @if ($transaction['balance'] > 0)
                        <tr class="font-semibold">
                            <td class="py-2 text-right"></td>
                            <td class="py-2">Saldo</td>
                            <td class="py-2 text-right" colspan="2">@currency($transaction['balance'])</td>
                        </tr>
                    @endif
                    @if ($transaction['grand_total'] > 0 && $transaction['grand_total'] !== $transaction['total'])
                        <tr class="font-semibold">
                            <td class="py-2 text-right"></td>
                            <td class="py-2">Grand total</td>
                            <td class="py-2 text-right" colspan="2">@currency($transaction['grand_total'])</td>
                        </tr>
                    @endif
                    @if ($transaction['balance'] - ($transaction['total'] - $transaction['discount']) > 0)
                        <tr class="font-semibold">
                            <td class="py-2 text-right"></td>
                            <td class="py-2">Sisa saldo</td>
                            <td class="py-2 text-right" colspan="2">@currency($transaction['balance'] - ($transaction['total'] - $transaction['discount']))</td>
                        </tr>
                    @endif
                    @if ($transaction['bill'] > 0)
                        <tr class="font-semibold">
                            <td class="py-2 text-right"></td>
                            <td class="py-2">Bayar</td>
                            <td class="py-2 text-right" colspan="2">@currency($transaction['bill'])</td>
                        </tr>
                    @endif
                    @if ($transaction['return'] !== 0)
                        <tr class="font-semibold">
                            <td class="py-2 text-right"></td>
                            <td class="py-2 {{ $transaction['return'] > 0 ? 'text-gray-900' : 'text-red-700' }}">
                                {{ $transaction['return'] > 0 ? 'Kembalian' : 'Kurang' }}</td>
                            <td class="py-2 text-right {{ $transaction['return'] > 0 ? 'text-gray-700' : 'text-red-600' }}"
                                colspan="2">@currency($transaction['return'])</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

</div>
