<div>
     <?php $__env->slot('title', null, []); ?> <?php echo e(__('Absensi')); ?> <?php $__env->endSlot(); ?>

     <?php $__env->slot('breadcrumb', null, []); ?> 
        <?php
            $breadcumb = ['Absensi'];
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

    
    <div class="flex items-center sm:justify-between mb-4 w-full">
        <div class="hidden sm:flex items-center gap-x-4">
            
        </div>
        <div class="flex gap-4 w-full sm:w-fit">
            <select id="monthSelect" wire:model="selectedMonth"
                class="block w-full sm:w-44 py-1.5 text-xs sm:text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                <?php $__currentLoopData = $months; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $month): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($month); ?>"><?php echo e(\Carbon\Carbon::parse($month . '-01')->format('F Y')); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>

            <select id="weekSelect" wire:model="selectedWeek"
                class="block w-full py-1.5 text-xs sm:text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                <option value="1">Minggu 1</option>
                <option value="2">Minggu 2</option>
                <option value="3">Minggu 3</option>
                <option value="4">Minggu 4</option>
                <option value="5">Minggu 5</option>
            </select>
        </div>
    </div>

    <div class="rounded-md border bg-white">
        <div class="relative w-full overflow-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b">
                        <th class="h-10 px-4 text-left whitespace-nowrap">Nama Pegawai</th>
                        <?php $__currentLoopData = $weekDates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $date): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <th class="h-10 px-4 text-center pt-2">
                                <div class="flex flex-col space-y-0">
                                    <span
                                        class="leading-none"><?php echo e(\Carbon\Carbon::parse($date)->translatedFormat('l')); ?></span>
                                    <span
                                        class="text-xs text-gray-500"><?php echo e(\Carbon\Carbon::parse($date)->translatedFormat('d M Y')); ?></span>
                                </div>
                            </th>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $weeklyAttendance; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attendance): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="border-b transition-colors hover:bg-gray-50">
                            <td class="px-4 py-2 whitespace-nowrap"><?php echo e($attendance['employee']); ?></td>
                            <?php $__currentLoopData = $attendance['attendance']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $date => $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <td class="px-4 py-2 text-center">
                                    <select
                                        wire:change="updateAttendance(<?php echo e($attendance['employee_id']); ?>, '<?php echo e($date); ?>', $event.target.value)"
                                        class="mt-1 block xl:w-full pl-3 pr-10 py-1 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value=""></option>
                                        <option value="Full Day" <?php echo e($status === 'Full Day' ? 'selected' : ''); ?>>Full Day
                                        </option>
                                        <option value="Half Day" <?php echo e($status === 'Half Day' ? 'selected' : ''); ?>>Half
                                            Day
                                        </option>
                                        <option value="Alpha" <?php echo e($status === 'Alpha' ? 'selected' : ''); ?>>Alpha
                                        </option>
                                    </select>
                                    
                                </td>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
<?php /**PATH /Users/gunturmaulana/inventory-azhar/resources/views/livewire/attendace/index.blade.php ENDPATH**/ ?>