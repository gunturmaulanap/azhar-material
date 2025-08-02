@if (count($returTransactions) > 0)
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
                @endif

            </x-slot>
            {{-- <x-slot name="content">
                <x-side-dropdown-link :href="route('goods.data')" :active="request()->routeIs('goods.data') ||
                    request()->routeIs('goods.create') ||
                    request()->routeIs('goods.category-create')">
                    Data Barang
                </x-side-dropdown-link> --}}
                {{-- <x-side-dropdown-link :href="route('goods.management')" :active="request()->routeIs('goods.management') ||
                    request()->routeIs('goods.retur') ||
                    request()->routeIs('goods.retur-detail')">
                    Kelola Data Barang
                </x-side-dropdown-link> --}}
            </x-slot>


            <div class="rounded-md border bg-white">
                <div class="relative w-full overflow-auto h-[500px]">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 sticky top-0 inset-x-0">
                            <tr class="border-b">
                                <th class="h-10 px-4 text-left">
                                    <span class="inline-flex font-medium items-center justify-center px-3 text-sm -ml-3">
                                        Nama Customer
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
                                        Tanggal Transaksi
                                    </span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="h-96 overflow-auto">
                            @forelse ($data as $item)
                                <tr class="border-b transition-colors hover:bg-gray-50 divide-x">
                                    <td class="p-2 px-4 w-[20%]">
                                        {{ $item->name }}
                                    </td>
                                    <td class="p-2 text-center w-[15%]">
                                        {{ $item->goods()->count() }}
                                    </td>
                                    <td class="p-2 text-center">
                                        @currency($item->goods->sum('cost'))
                                    </td>
                                    <td class="p-2 text-center">
                                        @currency($item->total)
                                    </td>
                                    <td class="px-4 text-center">
                                        {{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d F Y') }}
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
                            <thead class="bg-gray-50 sticky bottom-0 inset-x-0">
                                <tr class="divide-x">
                                    <th class="p-2 px-4 text-start font-medium">
                                        Total Terjual
                                    </th>
                                    <th class="p-2 text-center font-medium">
                                        {{ $data->sum(fn($item) => $item->goods->sum(fn($good) => $good->pivot->qty)) }}
                                    </th>
                                    <th class="p-2 text-center font-medium">
                                        @currency($data->sum(fn($item) => $item->goods->sum('cost')))
                                    </th>
                                    <th class="p-2 text-center font-medium">
                                        @currency($data->sum('total'))
                                    </th>
                                    <th class="p-2 text-center font-medium">
                                        Keuntungan : @currency($data->sum('total') - $data->sum(fn($item) => $item->goods->sum('cost')))
                                    </th>
                                </tr>
                            </thead>
                        </tbody>
                    </table>
                </div>
            </div>