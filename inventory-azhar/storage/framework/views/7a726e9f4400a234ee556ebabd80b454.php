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
            ? 'inline-flex gap-x-4 items-center px-5 pt-1 border-l-8 border-sky-400 whitespace-nowrap text-sm font-medium leading-5 text-gray-900 transition duration-150 ease-in-out'
            : 'inline-flex gap-x-4 items-center px-5 pt-1 border-l-8 border-transparent whitespace-nowrap text-sm font-medium leading-5 text-gray-500 hover:text-gray-800 hover:border-sky-200 transition duration-150 ease-in-out';
?>

<a <?php echo e($attributes->merge(['class' => $classes])); ?>>
    <?php echo e($slot); ?>

</a>
<?php /**PATH /Users/gunturmaulana/inventory-azhar/resources/views/components/nav-link.blade.php ENDPATH**/ ?>