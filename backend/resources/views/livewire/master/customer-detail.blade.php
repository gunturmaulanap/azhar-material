<div>
    <x-slot name="title">{{ __('Data Customer') }}</x-slot>

    <x-slot name="breadcrumb">
        @php
            $breadcumb = ['Data', 'Customer', 'Detail'];
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
    <div x-data="{ open: false, display: 0 }">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-x-4 gap-y-3 w-full mb-4">
            <article class="flex items-center gap-4 rounded-lg border border-gray-100 bg-white p-6 shadow">
                <span class="rounded-full bg-blue-100 p-3 text-blue-600">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z" />
                    </svg>
                </span>

                <div>
                    <p class="text-2xl font-medium text-gray-900">@currency($customer->balance)</p>

                    <p class="text-sm text-gray-500">Total Saldo</p>
                </div>
            </article>
            <article class="flex items-center gap-4 rounded-lg border border-gray-100 bg-white p-6 shadow">
                <span class="rounded-full bg-blue-100 p-3 text-red-600">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z" />
                    </svg>
                </span>

                <div>
                    <p class="text-2xl font-medium text-gray-900">@currency($customer->debt)</p>

                    <p class="text-sm text-gray-500">Total Hutang</p>
                </div>
            </article>

            <article
                class="flex items-center gap-4 rounded-lg border border-gray-100 bg-white p-6 sm:justify-between shadow">
                <span class="rounded-full bg-blue-100 p-3 text-blue-600 sm:order-last">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 0 1-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 0 1 1.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 0 0-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 0 1-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 0 0-3.375-3.375h-1.5a1.125 1.125 0 0 1-1.125-1.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H9.75" />
                    </svg>
                </span>

                <div>
                    <p class="text-2xl font-medium text-gray-900">{{ $customer->transactions->count() }}</p>

                    <p class="text-sm text-gray-500">Total Transaksi</p>
                </div>
            </article>
        </div>
        <div class="flex flex-col sm:flex-row items-start sm:justify-between w-full">
            <div>
                <h2 class="text-2xl font-semibold tracking-tight">{{ $customer->name }}</h2>
            </div>
            <div class="sm:max-w-xs sm:text-right mt-3 sm:mt-0">
                <span class="text-md">{{ $customer->phone }}</span><br>
                <span class="text-sm text-gray-500">{{ $customer->address }}</span>
            </div>
        </div>

        <div class="flex items-center justify-between gap-x-2">
            <nav class="flex items-center mt-6">
                <button @click="display = 0"
                    :class="[display == 0 ?
                        'border shadow-md rounded-md' :
                        'text-gray-400 hover:text-gray-800'
                    ]"
                    class="px-3 py-2 text-xs sm:text-sm font-medium transition-all">
                    Riwayat Transaksi
                </button>

                <button @click="display = 1"
                    :class="[display == 1 ?
                        'border shadow-md rounded-md' :
                        'text-gray-400 hover:text-gray-800 '
                    ]"
                    class="px-3 py-2 text-xs sm:text-sm font-medium transition-all">
                    Riwayat Isi Saldo
                </button>

            </nav>
            @if (auth()->user()->role === 'super_admin')
                <button type="button" x-show="display === 1" x-transition:enter.duration.700ms @click="open = true"
                    class="inline-flex items-center mt-6 gap-x-2 px-2 py-1.5 text-xs bg-green-500 text-white font-extrabold rounded-md shadow-md text-nowrap">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    Isi Saldo
                </button>
            @endif

            <div x-show="open" class="fixed inset-0 flex items-center justify-center z-50"
                x-transition:enter="transition-opacity duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity duration-300"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" style="display: none;">
                <div class="fixed inset-0 bg-gray-500 opacity-75" @click="open = false"></div>
                <div
                    class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:w-full sm:max-w-4xl">
                    <div class="divide-y p-6">
                        <div>
                            <h2 class="text-lg font-semibold">Formulir isi saldo</h2>
                            <p class="pb-3">Hanya customer member yang dapat mengisi saldo</p>
                        </div>

                        <div>
                            <div class="grid py-3 grid-cols-4 text-start items-center sm:text-center mt-1">
                                <dt class="font-medium text-gray-900 col-span-2">Saldo awal</dt>
                                <dd class="text-gray-700 mr-4 col-span-2 text-end sm:text-center sm:col-span-1">
                                    @currency($customer->balance)</dd>
                            </div>
                            <div class="grid py-3 grid-cols-4 text-start sm:text-center items-center">
                                <dt class="font-medium text-gray-900 col-span-2">Nominal</dt>
                                <dd class="text-gray-700 mr-4 col-span-2 text-end sm:text-center sm:col-span-1">
                                    <div class="mt-2">
                                        <input wire:model="balance" id="balance" type="number"
                                            class="block w-full rounded-md border-0 py-1.5 text-end sm:text-start text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-sky-500 sm:text-sm sm:leading-6">
                                        @error('balance')
                                            <span class="text-xs text-red-500">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </dd>
                            </div>
                            <div class="grid py-3 grid-cols-4 text-center mt-1">
                                <dt class="font-medium text-gray-900 text-start sm:text-center col-span-2">Total saldo
                                </dt>
                                <dd class="text-gray-700 mr-4 col-span-2 text-end sm:text-center sm:col-span-1">
                                    @currency($customer->balance + ($balance ?? 0))</dd>
                            </div>
                        </div>

                        <div class="mt-6 flex items-center justify-end gap-x-6 pt-4">
                            <button wire:click="resetInput()" type="button"
                                class="text-sm font-semibold leading-6 text-gray-900">Reset</button>
                            <button wire:click="save"
                                class="rounded-md bg-sky-500 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-sky-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-sky-500">Submit</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div x-show="display === 0" x-transition:enter.duration.700ms class="rounded-md border bg-white mt-3 sm:mt-4">
            <div class="relative w-full overflow-auto">
                <table class="w-full text-sm whitespace-nowrap">
                    <thead>
                        <tr class="border-b">
                            <th class="h-10 px-4 text-left">
                                <span class="inline-flex font-medium items-center justify-center px-3 text-sm -ml-3">
                                    Jumlah Barang
                                </span>
                            </th>
                            <th class="h-10 px-2 text-left">
                                <span class="inline-flex font-medium items-center justify-center px-3 text-sm -ml-3">
                                    Tagihan
                                </span>
                            </th>
                            <th class="h-10 px-2 text-left">
                                <span class="inline-flex font-medium items-center justify-center px-3 text-sm -ml-3">
                                    Status
                                </span>
                            </th>
                            <th class="h-10 px-2 text-left">
                                <span class="inline-flex font-medium items-center justify-center px-3 text-sm -ml-3">
                                    Tanggal Transaksi
                                </span>
                            </th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transactions as $item)
                            <tr class="border-b transition-colors hover:bg-gray-50">
                                <td class="p-2 px-4">
                                    {{ $item->goods->count() }} Barang
                                </td>
                                <td class="p-2">
                                    @currency($item->total)
                                </td>
                                <td class="p-2">
                                    <span
                                        class="@if ($item->status !== 'selesai') text-red-500 @else text-green-600 @endif">{{ $item->status }}</span>
                                </td>
                                <td class="p-2">
                                    {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
                                </td>
                                <td class="p-2 w-[5%]">
                                    <div class="flex items-center gap-x-4 justify-end">
                                        @if (auth()->user()->role === 'super_admin')
                                            <a href="{{ route('transaction.detail', ['id' => $item->id]) }}"
                                                class="px-2 py-1 flex items-center gap-x-2 rounded-md bg-sky-500 text-white text-xs">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                    fill="currentColor" class="size-3">
                                                    <path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" />
                                                    <path fill-rule="evenodd"
                                                        d="M1.323 11.447C2.811 6.976 7.028 3.75 12.001 3.75c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113-1.487 4.471-5.705 7.697-10.677 7.697-4.97 0-9.186-3.223-10.675-7.69a1.762 1.762 0 0 1 0-1.113ZM17.25 12a5.25 5.25 0 1 1-10.5 0 5.25 5.25 0 0 1 10.5 0Z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                Detail
                                            </a>
                                        @else
                                            <a href="{{ route('customer.transaction.detail', ['id' => $item->id]) }}"
                                                class="px-2 py-1 flex items-center gap-x-2 rounded-md bg-sky-500 text-white text-xs">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                    fill="currentColor" class="size-3">
                                                    <path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" />
                                                    <path fill-rule="evenodd"
                                                        d="M1.323 11.447C2.811 6.976 7.028 3.75 12.001 3.75c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113-1.487 4.471-5.705 7.697-10.677 7.697-4.97 0-9.186-3.223-10.675-7.69a1.762 1.762 0 0 1 0-1.113ZM17.25 12a5.25 5.25 0 1 1-10.5 0 5.25 5.25 0 0 1 10.5 0Z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                Detail
                                            </a>
                                        @endif
                                    </div>
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

        <div x-show="display === 1" x-transition:enter.duration.700ms class="rounded-md border bg-white mt-3 sm:mt-4">
            <div class="relative w-full overflow-auto">
                <table class="w-full text-sm whitespace-nowrap">
                    <thead>
                        <tr class="border-b">
                            <th class="h-10 px-4 text-left">
                                <span class="inline-flex font-medium items-center justify-center px-3 text-sm -ml-3">
                                    Saldo awal
                                </span>
                            </th>
                            <th class="h-10 px-2 text-left">
                                <span class="inline-flex font-medium items-center justify-center px-3 text-sm -ml-3">
                                    Isi saldo
                                </span>
                            </th>
                            <th class="h-10 px-2 text-left">
                                <span class="inline-flex font-medium items-center justify-center px-3 text-sm -ml-3">
                                    Total saldo
                                </span>
                            </th>
                            <th class="h-10 px-2 text-left">
                                <span class="inline-flex font-medium items-center justify-center px-3 text-sm -ml-3">
                                    Tanggal
                                </span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($customer->topups as $topup)
                            <tr class="border-b transition-colors hover:bg-gray-50">
                                <td class="p-2 px-4">
                                    @currency($topup->before)
                                </td>
                                <td class="p-2">
                                    @currency($topup->nominal)
                                </td>
                                <td class="p-2">
                                    @currency($topup->after)
                                </td>
                                <td class="p-2">
                                    {{ \Carbon\Carbon::parse($topup->created_at)->translatedFormat('d F Y') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">
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
</div>

@push('scripts')
    <script>
        $(document).on('input', '#balance', function() {
            if ($(this).val() == "") {
                $(this).val(0).select();
            }
        });
    </script>
@endpush
