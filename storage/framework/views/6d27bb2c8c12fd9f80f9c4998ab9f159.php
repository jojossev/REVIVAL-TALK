<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag; ?>
<?php foreach($attributes->onlyProps([
    'id'    => 'schema_markup',
    'value' => '',
]) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $attributes = $attributes->exceptProps([
    'id'    => 'schema_markup',
    'value' => '',
]); ?>
<?php foreach (array_filter(([
    'id'    => 'schema_markup',
    'value' => '',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>

<label class="mr-2"><?php echo e(__('schema_markup')); ?></label>
<i data-content="<?php echo e(__('schema_text')); ?>.<a href='https://www.rankranger.com/schema-markup-generator' target='_blank'><?php echo e(__('rank_ranger_schema_markup_generator')); ?></a>"
    class="fa fa-question-circle"></i>
<input type="text" name="schema_markup" id="<?php echo e($id); ?>" class="form-control"
    placeholder="<?php echo e(__('schema_markup')); ?>" value="<?php echo e($value); ?>">
<?php /**PATH /var/www/admin/resources/views/components/schema-markup-field.blade.php ENDPATH**/ ?>