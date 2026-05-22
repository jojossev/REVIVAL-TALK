<div class="modal fade" id="editDataModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><?php echo e(__('edit') . ' ' . __('e-news')); ?></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="update_form" action="<?php echo e(url('e-news')); ?>" role="form" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <input type='hidden' name="edit_id" id="edit_id" value='' />
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-md-6 col-sm-12">
                            <label class="required"><?php echo e(__('language')); ?></label>
                            <select id="edit_language" name="language" class="form-control" required>
                                <option value=""><?php echo e(__('select') . ' ' . __('language')); ?></option>
                                <?php $__currentLoopData = $languageList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($item->id); ?>"><?php echo e($item->language); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="form-group col-md-6 col-sm-12">
                            <label class="required"><?php echo e(__('title')); ?></label>
                            <input name="title" id="edit_title" required type="text" class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6 col-sm-12">
                            <label class="required"><?php echo e(__('slug')); ?></label><span
                                class="">(<?php echo e(__('english_only')); ?>)</span>
                            <input id="edit_slug" name="slug" value="<?php echo e(old('slug')); ?>" required type="text"
                                class="form-control">
                            <span class="text-danger"><?php echo e(__('avoid_special_characters')); ?></span>
                        </div>
                        <div class="form-group col-md-6 col-sm-12">
                            <label class="required"><?php echo e(__('date')); ?> </label>
                            <input id="edit_date" name="date" type="date" class="form-control" min="<?php echo e(date('Y-m-d')); ?>">
                        </div>
                        <div class="form-group col-md-6 col-sm-12">
                            <label><?php echo e(__('meta_title')); ?></label>
                            <input type="text" name="meta_title" class="form-control" id="edit_meta_title"
                                oninput="getWordCount('edit_meta_title','edit_meta_title_count','19.9px arial')"
                                placeholder="<?php echo e(__('meta_title')); ?>">
                            <h6 id="edit_meta_title_count">0</h6>
                        </div>
                        <div class="form-group col-md-6 col-sm-12">
                            <?php if (isset($component)) { $__componentOriginal1d7cb8c788a4883e2bea29fb16b78e97 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1d7cb8c788a4883e2bea29fb16b78e97 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.schema-markup-field','data' => ['id' => 'edit_schema_markup']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('schema-markup-field'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'edit_schema_markup']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1d7cb8c788a4883e2bea29fb16b78e97)): ?>
<?php $attributes = $__attributesOriginal1d7cb8c788a4883e2bea29fb16b78e97; ?>
<?php unset($__attributesOriginal1d7cb8c788a4883e2bea29fb16b78e97); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1d7cb8c788a4883e2bea29fb16b78e97)): ?>
<?php $component = $__componentOriginal1d7cb8c788a4883e2bea29fb16b78e97; ?>
<?php unset($__componentOriginal1d7cb8c788a4883e2bea29fb16b78e97); ?>
<?php endif; ?>
                        </div>
                    </div>
                    <div class="row">

                        <div class="form-group col-md-6 col-sm-12">
                            <label><?php echo e(__('meta_keywords')); ?></label>
                            <input id="edit_meta_tags" style="border-radius: 0.25rem" class="w-100" type="text"
                                name="meta_keyword" placeholder="<?php echo e(__('press_enter_add_keywords')); ?>">
                        </div>
                        <div class="form-group col-md-6 col-sm-12">
                            <label><?php echo e(__('meta_description')); ?></label>
                            <textarea id="edit_meta_description" name="meta_description" class="form-control"
                                oninput="getWordCount('edit_meta_description','edit_meta_description_count','12.9px arial')"></textarea>
                            <h6 id="edit_meta_description_count">0</h6>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6 col-sm-12">
                            <label><?php echo e(__('thumbnail')); ?> </label>
                            <input name="thumbnail" type="file" class="filepond">
                        </div>
                        <div class="form-group col-md-6 col-sm-12">
                            <label><?php echo e(__('attachment (PDF)')); ?> </label>
                            <input name="attachment" type="file" class="filepond-pdf" accept="application/pdf">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12 col-sm-12">
                            <label><?php echo e(__('description')); ?></label>
                            <textarea id="edit_des" name="description" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-3 col-sm-12">
                            <div class="form-check form-switch d-flex align-items-center p-0">
                                <label class="mr-2"><?php echo e(__('status')); ?></label>
                                <input type="hidden" id="edit_status" name="status" value="0">
                                <input class="form-check-input me-2 status-switch" type="checkbox"
                                    id="edit_status_switch" name="edit_status_switch">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo e(__('close')); ?></button>
                    <button type="submit" class="btn btn-primary"><?php echo e(__('submit')); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php /**PATH /var/www/admin/resources/views/enews/editModal.blade.php ENDPATH**/ ?>