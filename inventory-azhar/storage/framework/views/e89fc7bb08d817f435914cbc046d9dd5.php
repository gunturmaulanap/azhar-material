<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag; ?>
<?php foreach($attributes->onlyProps(['active']) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $attributes = $attributes->exceptProps(['active']); ?>
<?php foreach (array_filter((['active']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>

<?php
    $classes =
        $active ?? false
            ? 'inline-flex gap-x-4 items-center px-5 py-1 border-l-8 border-sky-400 whitespace-nowrap text-sm font-bold leading-5 text-gray-950 transition duration-150 ease-in-out'
            : 'inline-flex gap-x-4 items-center px-5 py-1 border-l-8 border-transparent whitespace-nowrap text-sm font-medium leading-5 text-gray-600 hover:text-gray-800 hover:border-sky-200 transition duration-150 ease-in-out';
?>

<div class="flex flex-col" x-data="{ openDropdown: <?php echo e($active ? 'true' : 'false'); ?> }">
    <div class="flex items-center justify-between">
        <button @click="openDropdown = ! openDropdown" <?php echo e($attributes->merge(['class' => $classes])); ?>>
            <?php echo e($trigger); ?>

        </button>
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-3 mx-4">
            <path fill-rule="evenodd"
                d="M12.53 16.28a.75.75 0 0 1-1.06 0l-7.5-7.5a.75.75 0 0 1 1.06-1.06L12 14.69l6.97-6.97a.75.75 0 1 1 1.06 1.06l-7.5 7.5Z"
                clip-rule="evenodd" />
        </svg>
    </div>
    <div x-show="openDropdown" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95" class="text-sm text-gray-600 mx-20">
        <?php echo e($content); ?>

    </div>
</div>
<?php /**PATH /Users/gunturmaulana/inventory-azhar/resources/views/components/side-dropdown.blade.php ENDPATH**/ ?>