<?php $__env->startSection('title'); ?>
    <?php echo e(__('tag')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><?php echo e(__('create_and_manage') . ' ' . __('tag')); ?></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item text-dark">
                            <a href="<?php echo e(route('home')); ?>" class="text-dark"><i
                                    class="fas fa-home mr-1"></i><?php echo e(__('dashboard')); ?></a>
                        </li>
                        <li class="breadcrumb-item active"><i class="nav-icon fas fa-tag mr-1"></i><?php echo e(__('tag')); ?></li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('tag-create')): ?>
                <div class="col-md-12 d-flex justify-content-end">
                    <button id="toggleButton" class="btn btn-primary mb-3 ml-1"><i
                            class="fas fa-plus-circle mr-2"></i><?php echo e(__('create') . ' ' . __('tag')); ?></button>
                </div>
                <?php endif; ?>
                <div class="col-md-12" id="add_card">
                    <div class="card card-secondary">
                        <div class="card-header">
                            <h3 class="card-title"><?php echo e(__('create') . ' ' . __('tag')); ?></h3>
                        </div>
                        <form id="create_form" action="<?php echo e(url('tag')); ?>" role="form" method="POST"
                            enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-md-3 col-sm-12">
                                        <label class="required"><?php echo e(__('language')); ?></label>
                                        <select id="language" name="language" class="form-control" required>
                                            <?php if(count($languageList) > 1): ?>
                                                <option value=""><?php echo e(__('select') . ' ' . __('language')); ?></option>
                                            <?php endif; ?>
                                            <?php $__currentLoopData = $languageList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($item->id); ?>" data-name="<?php echo e($item->language); ?>"><?php echo e($item->language); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3 col-sm-12">
                                        <label class="required"><?php echo e(__('name')); ?></label>
                                        <input id="name" name="name" required type="text"
                                            placeholder="<?php echo e(__('name')); ?>" class="form-control">
                                    </div>
                                    <div class="form-group col-md-3 col-sm-12">
                                        <label class="required"><?php echo e(__('slug')); ?></label><span class="">(<?php echo e(__('english_only')); ?>)</span>
                                        <input id="slug" name="slug" required type="text"
                                            placeholder="<?php echo e(__('slug')); ?>" class="form-control">
                                        <span class="text-danger"><?php echo e(__('avoid_special_characters')); ?></span>
                                    </div>
                                    <div class="form-group col-md-3 col-sm-12">
                                        <?php if (isset($component)) { $__componentOriginal1d7cb8c788a4883e2bea29fb16b78e97 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1d7cb8c788a4883e2bea29fb16b78e97 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.schema-markup-field','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('schema-markup-field'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
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
                                    <div class="form-group col-md-3 col-sm-12">
                                        <label><?php echo e(__('meta_keywords')); ?></label>
                                        <input id="meta_tags" style="border-radius: 0.25rem" class="w-100" type="text"
                                            name="meta_keyword" placeholder="<?php echo e(__('press_enter_add_keywords')); ?>">
                                    </div>
                                    <div class="form-group col-md-3 col-sm-12">
                                        <label><?php echo e(__('meta_title')); ?></label>
                                        <input type="text" name="meta_title" class="form-control" id="meta_title"
                                            oninput="getWordCount('meta_title','meta_title_count','19.9px arial')"
                                            placeholder="<?php echo e(__('meta_title')); ?>">
                                        <h6 id="meta_title_count">0</h6>
                                    </div>
                                    <div class="form-group col-md-3 col-sm-12">
                                        <label><?php echo e(__('meta_description')); ?></label>
                                        <textarea id="meta_description" name="meta_description" class="form-control"
                                            oninput="getWordCount('meta_description','meta_description_count','12.9px arial')"></textarea>
                                        <h6 id="meta_description_count">0</h6>
                                    </div>
                                    <div class="form-group col-md-3 col-sm-12">
                                        <label><?php echo e(__('og_image')); ?> </label>
                                        <input name="file" type="file" class="filepond">
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer d-flex justify-content-end">
                                <button type="button" class="btn btn-outline-primary" id="generate_meta_fields">
                                    <i class="fas fa-magic"></i><?php echo e(__('generate')); ?>

                                <button type="submit" class="btn btn-primary ml-2"><?php echo e(__('submit')); ?></button>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('tag-list')): ?>
                <div class="col-md-12">
                    <div class="card card-secondary">
                        <div class="card-header">
                            <h3 class="card-title"><?php echo e(__('tag') . ' ' . __('list')); ?></h3>
                        </div>
                        <div class="card-body">
                            <div id="toolbar">
                                <select id="filter_language_id" name="language" class="form-control">
                                    <option value="0"><?php echo e(__('select') . ' ' . __('language')); ?></option>
                                    <?php $__currentLoopData = $languageList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($item->id); ?>"><?php echo e($item->language); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <table aria-describedby="mydesc" id='table' data-toggle="table"
                                data-url="<?php echo e(route('tagList')); ?>" data-click-to-select="true"
                                data-side-pagination="server" data-pagination="true"
                                data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-show-columns="true"
                                data-show-refresh="true" data-toolbar="#toolbar" data-mobile-responsive="true"
                                data-buttons-class="primary" data-trim-on-search="false" data-sort-name="id"
                                data-sort-order="desc" data-query-params="queryParams">
                                <thead>
                                    <tr>
                                        <th scope="col" data-field="id" data-sortable="true"><?php echo e(__('id')); ?></th>
                                        <th scope="col" data-field="language_id" data-visible="false">
                                            <?php echo e(__('language_id')); ?></th>
                                        <th scope="col" data-field="language"><?php echo e(__('language')); ?></th>
                                        <th scope="col" data-field="tag_name"><?php echo e(__('name')); ?></th>
                                        <th scope="col" data-field="slug"><?php echo e(__('slug')); ?></th>
                                        <th scope="col" data-field="og_image"><?php echo e(__('og_image')); ?></th>
                                        <th scope="col" data-field="schema_markup" data-visible="false">
                                            <?php echo e(__('schema_markup')); ?></th>
                                        <th scope="col" data-field="meta_keyword" data-visible="false">
                                            <?php echo e(__('meta_keywords')); ?></th>
                                        <th scope="col" data-field="description" data-visible="false">
                                            <?php echo e(__('meta_description')); ?></th>
                                        <th scope="col" data-field="meta_title" data-visible="false">
                                            <?php echo e(__('meta_title')); ?></th>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['tag-edit', 'tag-delete'])): ?>
                                        <th scope="col" data-field="operate" data-sortable="false"
                                            data-events="actionEvents"><?php echo e(__('operate')); ?></th>
                                        <?php endif; ?>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="modal fade" id="editDataModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"><?php echo e(__('edit') . ' ' . __('tag')); ?></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="update_form" action="<?php echo e(url('tag')); ?>" role="form" method="POST"
                        enctype="multipart/form-data">
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
                                    <label class="required"><?php echo e(__('name')); ?></label>
                                    <input name="name" id="edit_name" required type="text" class="form-control">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6 col-sm-12">
                                    <label class="required"><?php echo e(__('slug')); ?></label><span class="">(<?php echo e(__('english_only')); ?>)</span>
                                    <input id="edit_slug" name="slug" required type="text" class="form-control">
                                    <span class="text-danger"><?php echo e(__('avoid_special_characters')); ?></span>
                                </div>
                                <div class="form-group col-md-6 col-sm-12">
                                    <label><?php echo e(__('meta_title')); ?></label>
                                    <input type="text" name="meta_title" class="form-control" id="edit_meta_title"
                                        oninput="getWordCount('edit_meta_title','edit_meta_title_count','19.9px arial')"
                                        placeholder="<?php echo e(__('meta_title')); ?>">
                                    <h6 id="edit_meta_title_count">0</h6>
                                </div>
                            </div>
                            <div class="row">
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
                                <div class="form-group col-md-6 col-sm-12">
                                    <label><?php echo e(__('meta_keywords')); ?></label>
                                    <input id="edit_meta_tags" style="border-radius: 0.25rem" class="w-100"
                                        type="text" name="meta_keyword"
                                        placeholder="<?php echo e(__('press_enter_add_keywords')); ?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6 col-sm-12">
                                    <label><?php echo e(__('meta_description')); ?></label>
                                    <textarea id="edit_meta_description" name="meta_description" class="form-control"
                                        oninput="getWordCount('edit_meta_description','edit_meta_description_count','12.9px arial')"></textarea>
                                    <h6 id="edit_meta_description_count">0</h6>
                                </div>
                                <div class="form-group col-md-6 col-sm-12">
                                    <label><?php echo e(__('og_image')); ?> </label>
                                    <input name="file" type="file" class="filepond">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default"
                                data-dismiss="modal"><?php echo e(__('close')); ?></button>
                            <button type="submit" class="btn btn-primary"><?php echo e(__('submit')); ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script type="text/javascript">
        function getSlug(data, title, slug) {
            var title1 = $(title).val();
            if (title1) {
                data['table'] = 'tbl_tag';
                data['_token'] = "<?php echo e(csrf_token()); ?>";
                $.ajax({
                    url: '<?php echo e(route('get-slug')); ?>',
                    type: "POST",
                    data: data,
                    success: function(result) {
                        if (result) {
                            $(slug).val(result);
                        }
                    },
                    error: function(errors) {
                        console.log(errors);
                    },
                });
            } else {
                $(slug).val('');
            }
        }
        $(document).on('keyup', '#name', function(e) {
            var data = {
                'name': $('#name').val(),
            };
            getSlug(data, '#name', '#slug');
        });

        $(document).on('keyup', '#edit_name', function(e) {
            var data = {
                'name': $('#edit_name').val(),
                'id': $('#edit_id').val(),
            };
            getSlug(data, '#edit_name', '#edit_slug');
        });
    </script>

    <script type="text/javascript">
        window.actionEvents = {
            'click .edit-data': function(e, value, row, index) {
                $('#edit_id').val(row.id);
                $("#edit_name").val(row.tag_name);
                $("#edit_language").val(row.language_id).trigger('change');
                $('#edit_slug').val(row.slug);
                $('#edit_meta_tags').val(row.meta_keyword);
                $('#edit_schema_markup').val(row.schema_markup);
                $('#edit_meta_description').val(row.description);
                $('#edit_meta_title').val(row.meta_title);
                getWordCount('edit_meta_description', 'edit_meta_description_count', '12.9px arial');
                getWordCount('edit_meta_title', 'edit_meta_title_count', '19.9px arial');
            }
        };
    </script>

    <script type="text/javascript">
        $("#filter_language_id").on("change", function() {
            $('#table').bootstrapTable('refresh');
        });

    </script>
    
    <script type="text/javascript">
        $(document).ready(function() {
            // Initialize meta field generation for create form
            initMetaFieldGeneration({
                isEditForm: false,
                buttonSelector: '#generate_meta_fields',
                routeUrl: '<?php echo e(route("generate-all-meta-fields")); ?>',
                csrfToken: '<?php echo e(csrf_token()); ?>',
                titleSelector: '#name',
                languageSelector: '#language',
                fieldMappings: {
                    metaTags: '#meta_tags',
                    metaTitle: '#meta_title',
                    metaDescription: '#meta_description',
                },
                validationMessages: {
                    selectLanguage: '<?php echo e(__('please_select_language_first')); ?>',
                    enterTitle: '<?php echo e(__('please_enter_tag_name_first')); ?>'
                }
            });

            // Initialize meta field generation for edit form (if needed)
            // initMetaFieldGeneration({
            //     isEditForm: true,
            //     buttonSelector: '#edit_generate_meta_fields',
            //     routeUrl: '<?php echo e(route("generate-all-meta-fields")); ?>',
            //     csrfToken: '<?php echo e(csrf_token()); ?>',
            //     validationMessages: {
            //         selectLanguage: '<?php echo e(__('please_select_language_first')); ?>',
            //         enterTitle: '<?php echo e(__('please_enter_tag_name_first')); ?>'
            //     }
            // });
        });
      </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/admin/resources/views/tag.blade.php ENDPATH**/ ?>