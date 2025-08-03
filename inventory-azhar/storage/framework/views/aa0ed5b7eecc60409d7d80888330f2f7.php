<div>
     <?php $__env->slot('title', null, []); ?> 
        <?php echo e($form === 'create' ? 'Tambah Data brand' : 'Ubah Data Brand'); ?>

     <?php $__env->endSlot(); ?>

     <?php $__env->slot('breadcrumb', null, []); ?> 
        <?php
            $breadcumb = ['Data', 'Barang', 'Tambah Brand'];
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
        <div class="border-b border-gray-900/10 pb-12">
            <h2 class="text-base font-semibold leading-7 text-gray-900">Formulir data Brand</h2>
            <p class="mt-1 mb-6 text-sm leading-6 text-gray-600">
                Lengkapi data berikut ini untuk <?php echo e($form === 'create' ? ' membuat data baru' : ' mengubah data'); ?>

            </p>

            <div class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6">

                <div class="col-span-3">
                    <label for="brand.name" class="block text-sm font-medium leading-6 text-gray-900">Nama Brand
                        <span class="text-xs text-red-500">*</span></label>
                    <div class="mt-2">
                        <input wire:model="brand.name" id="brand.name" type="text"
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-sky-500 sm:text-sm sm:leading-6">
                        <?php $__errorArgs = ['brand.name'];
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

            </div>
        </div>
        <div class="mt-6 flex items-center justify-end gap-x-6">
            <button wire:click="resetInput()" type="button"
                class="text-sm font-semibold leading-6 text-gray-900">Reset</button>
            <button wire:click="save()" type="submit"
                class="rounded-md bg-sky-500 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-sky-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-sky-500">Simpan</button>
        </div>
    </form>
</div>
<?php /**PATH /Users/gunturmaulana/inventory-azhar/resources/views/livewire/brand/form.blade.php ENDPATH**/ ?>