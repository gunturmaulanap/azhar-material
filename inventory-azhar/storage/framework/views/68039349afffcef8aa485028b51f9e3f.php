<div>
     <?php $__env->slot('title', null, []); ?> <?php echo e(__('Order')); ?> <?php $__env->endSlot(); ?>

     <?php $__env->slot('breadcrumb', null, []); ?> 
        <?php
            $breadcumb = ['Data Order', 'Order'];
        ?>
        <?php $__currentLoopData = $breadcumb; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li class="rtl:rotate-180">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd" />
                </svg>
            </li>

            <li>
                <span
                    class="block transition hover:text-gray-700 <?php if($loop->last): ?> text-gray-950 font-medium <?php endif; ?>">
                    <?php echo e($item); ?>

                </span>
            </li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
     <?php $__env->endSlot(); ?>

    <form wire:submit.prevent="save">
        <div class="border-b border-gray-900/10 pb-6">
            <div x-data="{ open: false }" x-init="open = false"
                class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-7">
                <div class="col-span-7 sm:col-span-4">
                    <label for="order.company" class="block text-sm font-medium leading-6 text-gray-900">Nama
                        perusahaan <span class="text-xs text-red-500">*</span></label>
                    <div class="mt-2">
                        <div class="flex items-center gap-x-4">
                            <input wire:model="order.company" type="text" id="order.company" autocomplete="off"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-sky-500 sm:text-sm sm:leading-6">
                            <button type="button"
                                class="flex items-center gap-x-2 bg-blue-500 rounded-md px-3 py-2 text-white"
                                @click="open = true">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                                </svg>
                                <span class="text-sm text-nowrap">Pilih Supplier</span>
                            </button>
                        </div>
                        <?php if($errors->has('order.company')): ?>
                            <span class="text-xs text-red-500"><?php echo e($errors->first('order.company')); ?></span>
                        <?php else: ?>
                            <span class="text-xs text-gray-500">Input sebagai supplier baru atau pilih supplier</span>
                        <?php endif; ?>
                    </div>
                </div>

                <div x-show="open" class="fixed inset-0 flex items-center justify-center z-50"
                    x-transition:enter="transition-opacity duration-300" x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity duration-300"
                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" style="display: none;">
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
                            <h2 class="text-lg font-semibold">Daftar Supplier</h2>
                            <p class="">Pilih supplier untuk informasi transaksi.</p>

                            <div class="flex items-center justify-between my-4">
                                <div class="flex items-center gap-x-4">
                                    <input wire:model="searchSupplier"
                                        class="flex rounded-md bg-white border-gray-300 px-3 py-1 w-64 text-sm text-gray-800 shadow-sm transition-colors focus:ring-1 h-8 placeholder:text-xs placeholder:text-slate-600"
                                        placeholder="Cari Supplier...">
                                </div>
                            </div>

                            <div class="rounded-md border-0 sm:border bg-white mt-4 max-h-96 overflow-auto">
                                <div class="relative w-full overflow-auto hidden sm:block">
                                    <table class="w-full text-sm">
                                        <thead>
                                            <tr class="border-b">
                                                <th class="h-10 px-4 text-left">
                                                    <span
                                                        class="inline-flex font-medium items-center justify-center px-3 text-sm -ml-3 whitespace-nowrap">
                                                        Nama Perusahaan
                                                    </span>
                                                </th>
                                                <th class="h-10 px-4 text-left">
                                                    <span
                                                        class="inline-flex font-medium items-center justify-center px-3 text-sm -ml-3 whitespace-nowrap">
                                                        Nama Supplier
                                                    </span>
                                                </th>
                                                <th class="h-10 px-2 text-left">
                                                    <span
                                                        class="inline-flex font-medium items-center justify-center px-3 text-sm -ml-3">
                                                        No Telp
                                                    </span>
                                                </th>
                                                <th class="h-10 px-2 text-left">
                                                    <span
                                                        class="inline-flex font-medium items-center justify-center px-3 text-sm -ml-3">
                                                        Alamat
                                                    </span>
                                                </th>
                                                <th class="h-10 px-2 text-left">
                                                    <span
                                                        class="inline-flex font-medium items-center justify-center px-3 text-sm -ml-3">
                                                        Keterangan
                                                    </span>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody @click="open = false">
                                            <?php $__empty_1 = true; $__currentLoopData = $suppliers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                <tr wire:click="setSupplier(<?php echo e($item->id); ?>)"
                                                    class="border-b transition-colors hover:bg-gray-50 cursor-pointer">
                                                    <td class="p-2 px-4 w-[18%] whitespace-nowrap">
                                                        <?php echo e($item->company); ?>

                                                    </td>
                                                    <td class="p-2 px-4 w-[18%] whitespace-nowrap">
                                                        <?php echo e($item->name); ?>

                                                    </td>
                                                    <td class="p-2">
                                                        <?php echo e($item->phone); ?>

                                                    </td>
                                                    <td class="p-2">
                                                        <p class="truncate"><?php echo e($item->address); ?></p>
                                                    </td>
                                                    <td class="p-2">
                                                        <p class="truncate"><?php echo e($item->keterangan); ?></p>
                                                    </td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                <tr>
                                                    <td colspan="4">
                                                        <span
                                                            class="flex items-center justify-center text-red-500 py-1">
                                                            Data tidak ditemukan
                                                        </span>
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="space-y-3 sm:hidden" @click="open = false">
                                    <?php $__currentLoopData = $suppliers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div wire:click="setSupplier(<?php echo e($item->id); ?>)"
                                            class="flex flex-col items-start
                                        gap-2 rounded-lg border p-3 text-left text-sm w-full">
                                            <div class="flex w-full flex-col gap-1">
                                                <div class="flex items-center">
                                                    <div class="flex items-center gap-2">
                                                        <div class="font-semibold"><?php echo e($item->company); ?></div>
                                                    </div>
                                                    <div class="ml-auto text-xs text-muted-foreground">
                                                        <?php echo e($item->phone); ?>

                                                    </div>
                                                </div>
                                                <div class="text-xs font-medium"><?php echo e($item->name); ?></div>
                                            </div>
                                            <div class="line-clamp-2 text-xs text-muted-foreground">
                                                <?php echo e($item->address); ?>

                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-span-7 sm:col-span-4">
                    <label for="order.name" class="block text-sm font-medium leading-6 text-gray-900">Nama supplier
                        <span class="text-xs text-red-500">*</span></label>
                    <div class="mt-2">
                        <input wire:model="order.name" id="order.name" type="text"
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-sky-500 sm:text-sm sm:leading-6">
                        <?php $__errorArgs = ['order.name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="text-xs text-red-500"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <div class="col-span-7 sm:col-span-4">
                    <label for="order.phone" class="block text-sm font-medium leading-6 text-gray-900">Nomor
                        telp
                        <span class="text-xs text-red-500">*</span></label>
                    <div class="mt-2">
                        <input wire:model="order.phone" id="order.phone" type="number"
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-sky-500 sm:text-sm sm:leading-6">
                        <?php $__errorArgs = ['order.phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="text-xs text-red-500"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
                <div class="col-span-7 sm:col-span-4">
                    <label for="order.keterangan" class="block text-sm font-medium leading-6 text-gray-900">Keterangan
                        <span class="text-xs text-red-500">*</span></label>
                    <div class="mt-2">
                        <input wire:model="order.keterangan" id="order.keterangan" type="text"
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-sky-500 sm:text-sm sm:leading-6">
                        <?php $__errorArgs = ['order.keterangan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="text-xs text-red-500"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
                <div class="col-span-7 sm:col-span-4">
                    <label for="order.address"
                        class="block text-sm font-medium leading-6 text-gray-900">Alamat</label>
                    <div class="mt-2">
                        <textarea wire:model="order.address" id="order.address" rows="3"
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"></textarea>
                    </div>
                </div>
            </div>

            <div class="flex items-end justify-between mt-6">
                <div>
                    <h2 class="text-base font-semibold leading-7 text-gray-900">Detail Transaksi</h2>
                    <p class="mt-1 mb-6 text-sm leading-6 text-gray-600">
                        Pilih barang dan atur Quantity.
                    </p>
                </div>
            </div>

            <div x-data="{ open: false }" x-init="open = false" class="rounded-md bg-white mt-0">
                
                <div class="relative w-full overflow-auto hidden sm:block">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b">
                                <th class="w-[10%] text-left">
                                    <button type="button" @click="open = true"
                                        class="flex items-center gap-x-2 px-4 py-1.5 sm:mr-4 sm:py-0 bg-blue-500 text-white rounded-md text-xs sm:text-lg whitespace-nowrap">+
                                        <span class="sm:text-xs">Pilih
                                            Barang</span></button>
                                </th>
                                <th class="h-10 text-left">
                                    <span
                                        class="inline-flex font-medium items-center justify-center px-3 text-sm -ml-3">
                                        Nama Barang
                                    </span>
                                </th>
                                <th class="h-10 px-2 text-center">
                                    <span
                                        class="inline-flex font-medium items-center justify-center px-3 text-sm -ml-3">
                                        Harga Beli
                                    </span>
                                </th>
                                <th class="h-10 px-2 text-center">
                                    <span
                                        class="inline-flex font-medium items-center justify-center px-3 text-sm -ml-3">
                                        Qty
                                    </span>
                                </th>
                                <th class="h-10 px-2 text-right">
                                    <span
                                        class="inline-flex font-medium items-center justify-center px-3 text-sm -ml-3">
                                        Subtotal
                                    </span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="border-b">
                            <?php $__empty_1 = true; $__currentLoopData = $goodOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <th class="w-[10%] text-left px-2">
                                        <button class="text-red-500" type="button"
                                            wire:click="deleteGood(<?php echo e($index); ?>)">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                fill="currentColor" class="size-5">
                                                <path fill-rule="evenodd"
                                                    d="M16.5 4.478v.227a48.816 48.816 0 0 1 3.878.512.75.75 0 1 1-.256 1.478l-.209-.035-1.005 13.07a3 3 0 0 1-2.991 2.77H8.084a3 3 0 0 1-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 0 1-.256-1.478A48.567 48.567 0 0 1 7.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 0 1 3.369 0c1.603.051 2.815 1.387 2.815 2.951Zm-6.136-1.452a51.196 51.196 0 0 1 3.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 0 0-6 0v-.113c0-.794.609-1.428 1.364-1.452Zm-.355 5.945a.75.75 0 1 0-1.5.058l.347 9a.75.75 0 1 0 1.499-.058l-.346-9Zm5.48.058a.75.75 0 1 0-1.498-.058l-.347 9a.75.75 0 0 0 1.5.058l.345-9Z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </th>
                                    <td class="">
                                        <?php echo e($item['name']); ?>

                                    </td>
                                    <td class="p-4 px-2 text-center">
                                        <div class="flex items-center justify-center text-center">
                                            <div class="relative mt-2 rounded-md shadow-sm bg-blue-500">
                                                <div
                                                    class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                                    <span class="text-gray-500 sm:text-sm">Rp. </span>
                                                </div>
                                                <input type="text" id="cost-<?php echo e($index); ?>"
                                                    wire:model="goodOrders.<?php echo e($index); ?>.cost"
                                                    class="block w-40 rounded-md border-0 py-1.5 pl-9 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-4 px-2 text-center">
                                        <div class="flex items-center justify-center gap-x-2">
                                            <div class="flex items-center rounded border border-gray-200">
                                                <button type="button" wire:click="decrement(<?php echo e($index); ?>)"
                                                    class="size-10 leading-10 text-gray-600 transition hover:opacity-75">
                                                    &minus;
                                                </button>

                                                <input type="number" id="qty-<?php echo e($index); ?>"
                                                    wire:model="goodOrders.<?php echo e($index); ?>.qty" min="0"
                                                    onkeydown="return event.keyCode !== 69 && event.keyCode !== 189 && event.keyCode !== 187"
                                                    oninput="if (this.value === '') this.value = 1"
                                                    class="h-10 w-16 border-transparent text-center [-moz-appearance:_textfield] sm:text-sm [&::-webkit-inner-spin-button]:m-0 [&::-webkit-inner-spin-button]:appearance-none [&::-webkit-outer-spin-button]:m-0 [&::-webkit-outer-spin-button]:appearance-none" />

                                                <button type="button" wire:click="increment(<?php echo e($index); ?>)"
                                                    class="size-10 leading-10 text-gray-600 transition hover:opacity-75">
                                                    &plus;
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-4 px-4 text-right">
                                        Rp. <?php echo number_format($item['subtotal'],0,',','.'); ?>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="6">
                                        <div class="py-2 text-blue-500 text-center">
                                            Pilih barang
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                
                <div class="space-y-3 sm:hidden">
                    <button type="button" @click="open = true"
                        class="px-4 py-1.5 sm:py-0 bg-blue-500 text-white rounded-md text-xs sm:text-lg whitespace-nowrap">+
                        Pilih Barang</button>
                    <?php $__empty_1 = true; $__currentLoopData = $goodOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="flex flex-col items-start gap-3 rounded-lg border p-3 text-left text-sm w-full">
                            <div class="font-semibold text-base"><?php echo e($item['name']); ?></div>
                            <div class="grid grid-cols-3 items-center justify-between w-full">
                                <div class="">Harga</div>
                                <div class="col-span-2 w-full relative mt-2 rounded-md shadow-sm">
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                        <span class="text-gray-500 text-xs">Rp. </span>
                                    </div>
                                    <input type="number" id="cost-<?php echo e($index); ?>"
                                        wire:model="goodOrders.<?php echo e($index); ?>.cost"
                                        class="block w-full rounded-md border-0 py-1.5 pl-9 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                </div>
                            </div>
                            <div class="grid grid-cols-3 items-center justify-between w-full">
                                <div class="">Qty</div>
                                <div class="col-span-2 w-full flex items-center justify-center gap-x-2">
                                    <div class="flex items-center rounded border border-gray-200">
                                        <button type="button" wire:click="decrement(<?php echo e($index); ?>)"
                                            class="size-10 leading-10 text-gray-600 transition hover:opacity-75">
                                            &minus;
                                        </button>

                                        <input type="number" id="qty-<?php echo e($index); ?>"
                                            wire:model="goodOrders.<?php echo e($index); ?>.qty" min="0"
                                            onkeydown="return event.keyCode !== 69 && event.keyCode !== 189 && event.keyCode !== 187"
                                            oninput="if (this.value === '') this.value = 1"
                                            class="h-10 w-full border-transparent text-center [-moz-appearance:_textfield] sm:text-sm [&::-webkit-inner-spin-button]:m-0 [&::-webkit-inner-spin-button]:appearance-none [&::-webkit-outer-spin-button]:m-0 [&::-webkit-outer-spin-button]:appearance-none" />

                                        <button type="button" wire:click="increment(<?php echo e($index); ?>)"
                                            class="size-10 leading-10 text-gray-600 transition hover:opacity-75">
                                            &plus;
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="grid grid-cols-3 items-center justify-between w-full">
                                <div class="">Subtotal</div>
                                <span class="col-span-2 w-full text-end text-base">Rp. <?php echo number_format($item['subtotal'],0,',','.'); ?></span>
                            </div>
                            <div class="flex items-center gap-2 w-full justify-end mt-1">
                                <a class="text-red-500" type="button" wire:click="deleteGood(<?php echo e($index); ?>)">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                        class="size-5">
                                        <path fill-rule="evenodd"
                                            d="M16.5 4.478v.227a48.816 48.816 0 0 1 3.878.512.75.75 0 1 1-.256 1.478l-.209-.035-1.005 13.07a3 3 0 0 1-2.991 2.77H8.084a3 3 0 0 1-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 0 1-.256-1.478A48.567 48.567 0 0 1 7.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 0 1 3.369 0c1.603.051 2.815 1.387 2.815 2.951Zm-6.136-1.452a51.196 51.196 0 0 1 3.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 0 0-6 0v-.113c0-.794.609-1.428 1.364-1.452Zm-.355 5.945a.75.75 0 1 0-1.5.058l.347 9a.75.75 0 1 0 1.499-.058l-.346-9Zm5.48.058a.75.75 0 1 0-1.498-.058l-.347 9a.75.75 0 0 0 1.5.058l.345-9Z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-7 mt-4">
                    <div class="col-span-1 sm:col-span-3">
                        <label for="cover-photo" class="block text-sm font-medium leading-6 text-gray-900">Sertakan
                            foto bila diperlukan</label>
                        <?php if(!$imagePreview): ?>
                            <div
                                class="mt-2 flex justify-center rounded-lg border border-dashed border-gray-900/25 px-4 py-6">
                                <div class="text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-300" viewBox="0 0 24 24"
                                        fill="currentColor" aria-hidden="true" data-slot="icon">
                                        <path fill-rule="evenodd"
                                            d="M1.5 6a2.25 2.25 0 0 1 2.25-2.25h16.5A2.25 2.25 0 0 1 22.5 6v12a2.25 2.25 0 0 1-2.25 2.25H3.75A2.25 2.25 0 0 1 1.5 18V6ZM3 16.06V18c0 .414.336.75.75.75h16.5A.75.75 0 0 0 21 18v-1.94l-2.69-2.689a1.5 1.5 0 0 0-2.12 0l-.88.879.97.97a.75.75 0 1 1-1.06 1.06l-5.16-5.159a1.5 1.5 0 0 0-2.12 0L3 16.061Zm10.125-7.81a1.125 1.125 0 1 1 2.25 0 1.125 1.125 0 0 1-2.25 0Z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <div class="mt-4 flex text-sm leading-6 text-gray-600">
                                        <label for="image"
                                            class="relative cursor-pointer rounded-md bg-white font-semibold text-indigo-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-indigo-600 focus-within:ring-offset-2 hover:text-indigo-500">
                                            <span>Upload a file</span>
                                            <input wire:model="order.image" id="image" type="file"
                                                accept="image/*" class="sr-only">
                                        </label>
                                        <p class="pl-1">or drag and drop</p>
                                    </div>
                                    <p class="text-xs leading-5 text-gray-600">PNG, JPG, GIF up to 10MB</p>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="relative mt-2 h-52 w-60">
                                <button class="absolute inset-x -right-2 -top-2 rounded-full bg-white" type="button"
                                    wire:click="deleteImage">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-8 text-red-500">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                </button>
                                <img src="<?php echo e($imagePreview); ?>" alt="Image Preview"
                                    class="mt-2 w-full h-full object-contain object-center">
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="col-span-1 sm:col-start-4 sm:col-span-4 xl:col-start-5 xl:col-span-3 mt-4 sm:mt-0">
                        <div class="grid py-3 grid-cols-3 sm:grid-cols-2 gap-4 text-end">
                            <dt class="text-start sm:text-end font-extrabold text-gray-950">Total</dt>
                            <dd class="col-span-2 sm:col-span-1 font-extrabold text-gray-900 mr-4">Rp. <?php echo number_format($order['total'] ?? 0,0,',','.'); ?>
                        </div>
                    </div>
                </div>

                <!-- Modal Barang -->
                <div x-show="open" class="fixed inset-0 flex items-center justify-center z-50"
                    x-transition:enter="transition-opacity duration-300" x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity duration-300"
                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" style="display: none;">
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
                            <h2 class="text-lg font-semibold">Daftar Barang</h2>
                            <p class="">Pilih barang untuk transaksi.</p>

                            <div class="flex items-center justify-between my-4">
                                <div
                                    class="flex flex-col sm:flex-row items-center justify-between gap-y-4 sm:gap-y-0 sm:gap-x-4 w-full">
                                    <input wire:model="search"
                                        class="flex rounded-md bg-white border-gray-300 px-3 py-1 w-full sm:w-64 text-sm text-gray-800 shadow-sm transition-colors focus:ring-1 h-8 placeholder:text-xs placeholder:text-slate-600"
                                        placeholder="Cari barang...">

                                    <select wire:model="byCategory"
                                        class="flex rounded-md bg-white border-gray-300 px-3 py-1 w-full sm:w-18 text-sm text-gray-800 shadow-sm transition-colors focus:ring-1 h-8 placeholder:text-xs placeholder:text-slate-600">
                                        <option value="">Semua Kategori</option>
                                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($category->id); ?>"><?php echo e($category->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <select wire:model="byBrand"
                                        class="flex rounded-md bg-white border-gray-300 px-3 py-1 w-full sm:w-18 text-sm text-gray-800 shadow-sm transition-colors focus:ring-1 h-8 placeholder:text-xs placeholder:text-slate-600">
                                        <option value="">Semua Merk</option>
                                        <?php $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($brand->id); ?>"><?php echo e($brand->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>

                            <div class="rounded-md border-0 sm:border bg-white mt-4 max-h-96 overflow-auto ">
                                <div class="relative w-full  overflow-auto hidden sm:block">
                                    <table class="w-full text-sm">
                                        <thead>
                                            <tr class="border-b">
                                                <th class="h-10 px-4 text-left">
                                                    <span
                                                        class="inline-flex font-medium items-center justify-center px-3 text-sm -ml-3">
                                                        Nama Barang
                                                    </span>
                                                </th>
                                                <th class="h-10 px-2 text-left">
                                                    <span
                                                        class="inline-flex font-medium items-center justify-center px-3 text-sm -ml-3">
                                                        Merk
                                                    </span>
                                                </th>
                                                <th class="h-10 px-2 text-left">
                                                    <span
                                                        class="inline-flex font-medium items-center justify-center px-3 text-sm -ml-3">
                                                        Kategori
                                                    </span>
                                                </th>
                                                <th class="h-10 px-2 text-left">
                                                    <span
                                                        class="inline-flex font-medium items-center justify-center px-3 text-sm -ml-3">
                                                        Stok
                                                    </span>
                                                </th>
                                                <th class="h-10 px-2 text-center">
                                                    <span
                                                        class="inline-flex font-medium items-center justify-center px-3 text-sm -ml-3">
                                                        Harga Beli
                                                    </span>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody @click="open = false">
                                            <?php $__empty_2 = true; $__currentLoopData = $goods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                                                <tr wire:click="addGood(<?php echo e($item->id); ?>)"
                                                    class="border-b transition-colors hover:bg-gray-50 cursor-pointer hover:shadow">
                                                    <td class="p-2 px-4">
                                                        <?php echo e($item->name); ?>

                                                    </td>
                                                    <td class="p-2">

                                                        <?php echo e($item->brand->name); ?>

                                                    </td>
                                                    <td class="p-2">
                                                        <?php echo e($item->category->name); ?>

                                                    </td>
                                                    <td class="p-2 text-left">
                                                        <span class="font-bold"><?php echo e($item->stock); ?></span>
                                                        <?php echo e($item->unit); ?>

                                                    </td>
                                                    <td class="p-2 text-center">
                                                        Rp. <?php echo number_format($item->cost,0,',','.'); ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                                                <tr>
                                                    <td colspan="6">
                                                        <div class="py-2 text-red-500 text-center">
                                                            Data tidak ditemukan
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>

                                
                                <div class="space-y-3 sm:hidden" @click="open = false">
                                    <?php $__currentLoopData = $goods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div wire:click="addGood(<?php echo e($item->id); ?>)"
                                            class="flex flex-col items-start gap-2 rounded-lg border p-3 text-left text-sm w-full">
                                            <div class="flex w-full flex-col gap-1">
                                                <div class="flex items-center">
                                                    <div class="flex flex-col items-start gap-2 w-2/3">
                                                        <div class="font-semibold text-base"><?php echo e($item->name); ?></div>
                                                        <div class="line-clamp-2 text-xs text-muted-foreground">
                                                            (<?php echo e($item->category->name); ?>)
                                                            <?php echo e($item->brand->name); ?>

                                                        </div>
                                                    </div>
                                                    <div class="flex flex-col ml-auto text-sm text-end">
                                                        <span><span class="font-extrabold"><?php echo e($item->stock); ?></span>
                                                            <?php echo e($item->unit); ?></span>
                                                        <span class="text-base font-medium">Rp. <?php echo number_format($item->cost,0,',','.'); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="mt-6 flex items-center justify-end gap-x-6">
            <button wire:click="resetInput()" type="button"
                class="text-sm font-semibold leading-6 text-gray-900">Reset</button>
            <button type="submit"
                class="rounded-md bg-sky-500 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-sky-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-sky-500">Simpan</button>
        </div>
    </form>

</div>

<?php $__env->startPush('scripts'); ?>
    <script>
        $(document).on('input', '[id^="cost-"]', function() {
            if ($(this).val() == "") {
                $(this).val(0).select();
            }
        });
    </script>
<?php $__env->stopPush(); ?>
<?php /**PATH /Users/gunturmaulana/inventory-azhar/resources/views/livewire/order/create.blade.php ENDPATH**/ ?>