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
            ? 'flex items-center gap-x-2 w-full py-1 whitespace-nowrap text-gray-900 font-medium duration-150'
            : 'flex items-center gap-x-2 w-full py-1 whitespace-nowrap text-gray-500 hover:text-gray-800 duration-150';
?>

<a <?php echo e($attributes->merge(['class' => $classes])); ?>>
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-2">
        <path fill-rule="evenodd"
            d="M16.28 11.47a.75.75 0 0 1 0 1.06l-7.5 7.5a.75.75 0 0 1-1.06-1.06L14.69 12 7.72 5.03a.75.75 0 0 1 1.06-1.06l7.5 7.5Z"
            clip-rule="evenodd" />
    </svg>
    <?php echo e($slot); ?>

</a>
<?php /**PATH /Users/gunturmaulana/inventory-azhar/resources/views/components/side-dropdown-link.blade.php ENDPATH**/ ?>