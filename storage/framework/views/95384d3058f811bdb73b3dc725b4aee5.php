<?php $__env->startSection('title'); ?>
    <?php echo e(__('subcategory')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><?php echo e(__('create_and_manage') . ' ' . __('subcategory')); ?></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item text-dark">
                            <a href="<?php echo e(route('home')); ?>" class="text-dark"><i
                                    class="fas fa-home mr-1"></i><?php echo e(__('dashboard')); ?></a>
                        </li>
                        <li class="breadcrumb-item active"><i class="nav-icon fas fa-cubes mr-1"></i><?php echo e(__('subcategory')); ?>

                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('sub-category-create')): ?>
                <div class="col-md-12 d-flex justify-content-end">
                    <button id="toggleButton" class="btn btn-primary mb-3 ml-1"><i
                            class="fas fa-plus-circle mr-2"></i><?php echo e(__('create') . ' ' . __('subcategory')); ?></button>
                </div>
                <?php endif; ?>
                <div class="col-md-12" id="add_card">
                    <div class="card card-secondary">
                        <div class="card-header">
                            <h3 class="card-title"><?php echo e(__('create') . ' ' . __('subcategory')); ?></h3>
                        </div>
                        <form id="create_form" action="<?php echo e(route('sub_category.store')); ?>" role="form" method="POST"
                            enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-md-3 col-sm-12">
                                        <label class="required"><?php echo e(__('language')); ?></label>
                                        <select id="language_id" name="language" class="form-control" required>
                                            <?php if(count($languageList) > 1): ?>
                                                <option value=""><?php echo e(__('select') . ' ' . __('language')); ?></option>
                                            <?php endif; ?>
                                            <?php $__currentLoopData = $languageList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($row->id); ?>"><?php echo e($row->language); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3 col-sm-12">
                                        <label class="required"><?php echo e(__('category')); ?></label>
                                        <select id="category_id" name="category" class="form-control" required>
                                            <option value=""><?php echo e(__('select') . ' ' . __('category')); ?></option>
                                            <?php $__currentLoopData = $categoryList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($row->id); ?>"><?php echo e($row->category_name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3 col-sm-12">
                                        <label class="required"><?php echo e(__('name')); ?></label>
                                        <input id="name" name="name" required placeholder="<?php echo e(__('name')); ?>"
                                            type="text" class="form-control">
                                    </div>
                                    <div class="form-group col-md-3 col-sm-12">
                                        <label class="required"><?php echo e(__('slug')); ?></label><span class="">(<?php echo e(__('english_only')); ?>)</span>
                                        <input id="slug" name="slug" required placeholder="<?php echo e(__('slug')); ?>"
                                            type="text" class="form-control">
                                        <span class="text-danger"><?php echo e(__('avoid_special_characters')); ?></span>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary float-right"><?php echo e(__('submit')); ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="row">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('sub-category-list')): ?>
                <div class="col-lg-8 col-md-12 col-sm-12">
                    <div class="card card-secondary">
                        <div class="card-header">
                            <h3 class="card-title"><?php echo e(__('subcategory') . ' ' . __('list')); ?></h3>
                        </div>
                        <div class="card-body">
                            <div id="toolbar" class="d-flex">
                                <div class="mr-3">
                                    <select id="filter_language_id" class="form-control" required>
                                        <option value="0"><?php echo e(__('select') . ' ' . __('language')); ?></option>
                                        <?php $__currentLoopData = $languageList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($row->id); ?>"><?php echo e($row->language); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <div>
                                    <select id="filter_category_id" class="form-control">
                                        <option value="0"><?php echo e(__('select') . ' ' . __('category')); ?></option>
                                    </select>
                                </div>
                            </div>
                            <table aria-describedby="mydesc" id='table' data-toggle="table"
                                data-url="<?php echo e(route('subcategoryList')); ?>" data-click-to-select="true"
                                data-side-pagination="server" data-pagination="true"
                                data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-show-columns="true"
                                data-show-refresh="true" data-toolbar="#toolbar" data-mobile-responsive="true"
                                data-buttons-class="primary" data-trim-on-search="false" data-sort-name="row_order"
                                data-sort-order="asc" data-query-params="subcategoryQueryParams">
                                <thead>
                                    <tr>
                                        <th scope="col" data-field="id" data-sortable="true"><?php echo e(__('id')); ?></th>
                                        <th scope="col" data-field="language_id" data-visible="false">
                                            <?php echo e(__('language_id')); ?></th>
                                        <th scope="col" data-field="language"><?php echo e(__('language')); ?></th>
                                        <th scope="col" data-field="category_id" data-visible="false"
                                            data-sortable="true"><?php echo e(__('category_id')); ?></th>
                                        <th scope="col" data-field="category_name"><?php echo e(__('category')); ?></th>
                                        <th scope="col" data-field="subcategory_name"><?php echo e(__('name')); ?></th>
                                        <th scope="col" data-field="slug" data-sortable="false"><?php echo e(__('slug')); ?>

                                        </th>
                                        <th scope="col" data-field="row_order" data-sortable="true">
                                            <?php echo e(__('row_order')); ?></th>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['sub-category-edit', 'sub-category-delete'])): ?>
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
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('sub-category-order-create')): ?>
                <div class="col-lg-4 col-md-12 col-sm-12">
                    <div class="card card-secondary">
                        <div class="card-header">
                            <h3 class="card-title"><?php echo e(__('subcategory') . ' ' . __('order')); ?></h3>
                        </div>
                        <div class="card-body">
                            <form id="order_form" action="<?php echo e(route('update_subcategory_order')); ?>" method="post"
                                onsubmit="return saveOrder()">
                                <?php echo csrf_field(); ?>
                                <div class="form-group col-md-12 col-sm-12">
                                    <select id="order_language_id" class="form-control">
                                        <option value="0"><?php echo e(__('select') . ' ' . __('language')); ?></option>
                                        <?php $__currentLoopData = $languageList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($row->id); ?>"><?php echo e($row->language); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-12 col-sm-12">
                                    <select id="order_category_id" class="form-control">
                                        <option value="0"><?php echo e(__('select') . ' ' . __('category')); ?></option>
                                    </select>
                                </div>
                                <div class="col-md-12 col-sm-12">
                                    <input id="row_order" name="row_order" required type="hidden">
                                    <ol id="sortable-row">
                                        <?php $__currentLoopData = $subcategoryList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li id="<?php echo e($row->id); ?>"><?php echo e($row->subcategory_name); ?></li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ol>
                                </div>
                                <button type="submit" class="btn btn-primary float-right"><?php echo e(__('submit')); ?></button>
                            </form>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="modal fade" id="editDataModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"><?php echo e(__('edit') . ' ' . __('subcategory')); ?></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="update_form" action="<?php echo e(url('sub_category')); ?>" role="form" method="POST"
                        enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <input type='hidden' name="edit_id" id="edit_id" value='' />
                        <input type='hidden' name="image_url" id="image_url" value='' />
                        <div class="modal-body">
                            <div class="row">
                                <div class="form-group col-md-12 col-sm-12">
                                    <label class="required"><?php echo e(__('language')); ?></label>
                                    <select id="edit_language_id" name="language" class="form-control" required>
                                        <option value=""><?php echo e(__('select') . ' ' . __('language')); ?></option>
                                        <?php $__currentLoopData = $languageList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($item->id); ?>"><?php echo e($item->language); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12 col-sm-12">
                                    <label class="required"><?php echo e(__('category')); ?></label>
                                    <select id="edit_category_id" name="category" class="form-control" required>
                                        <option value=""><?php echo e(__('select') . ' ' . __('category')); ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12 col-sm-12">
                                    <label class="required"><?php echo e(__('name')); ?></label>
                                    <input id="edit_name" name="name" type="text" required class="form-control"
                                        placeholder="<?php echo e(__('name')); ?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12 col-sm-12">
                                    <label class="required"><?php echo e(__('slug')); ?></label><span class="">(<?php echo e(__('english_only')); ?>)</span>
                                    <input id="edit_slug" name="slug" required placeholder="<?php echo e(__('slug')); ?>"
                                        type="text" class="form-control">
                                    <span class="text-danger"><?php echo e(__('avoid_special_characters')); ?></span>
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
        $(function() {
            $("#sortable-row").sortable();
        });

        function saveOrder() {
            var selectedLanguage = new Array();
            $('ol#sortable-row li').each(function() {
                selectedLanguage.push($(this).attr("id"));
            });
            document.getElementById("row_order").value = selectedLanguage;
        }

        $(document).on('change', '#language_id', function(e) {
            var language_id = $('#language_id').val();
            var data = {
                language_id: language_id,
            };
            var url = '<?php echo e(route('get_category_by_language')); ?>';
            fetchList(url, data, '#category_id');
        });

        $(document).on('change', '#filter_language_id', function(e) {
            var language_id = $('#filter_language_id').val();
            var data = {
                language_id: language_id,
            };
            var url = '<?php echo e(route('get_category_by_language')); ?>';
            fetchList(url, data, '#filter_category_id');
        });

        $(document).on('change', '#order_language_id', function(e) {
            var language_id = $('#order_language_id').val();
            var data = {
                language_id: language_id,
            };
            var url = '<?php echo e(route('get_category_by_language')); ?>';
            fetchList(url, data, '#order_category_id');

            data['sortable'] = 1;
            var url1 = '<?php echo e(route('get_subcategory_by_category')); ?>';
            fetchList(url1, data, '#sortable-row');
        });

        $(document).on('change', '#order_category_id', function(e) {
            var category_id = $('#order_category_id').val();
            var data = {
                category_id: category_id,
                sortable: 1
            };
            var url = '<?php echo e(route('get_subcategory_by_category')); ?>';
            fetchList(url, data, '#sortable-row');
        });

        $(document).on('change', '#edit_language_id', function(e, row_language, row_category) {
            var language_id = $('#edit_language_id').val();
            var data = {
                language_id: language_id,
            };
            $.ajax({
                url: '<?php echo e(route('get_category_by_language')); ?>',
                type: "POST",
                data: {
                    language_id: language_id,
                },
                beforeSend: function() {
                    $('#edit_category_id').html("Please wait..");
                },
                success: function(result) {
                    $('#edit_category_id').html(result);
                    if (language_id == row_language && row_category != 0) {
                        $('#edit_category_id').val(row_category);
                    }
                },
                error: function(errors) {
                    console.log(errors);
                },
            });
        });
    </script>

    <script type="text/javascript">
        function getSlug(data, title, slug) {
            var title1 = $(title).val();
            if (title1) {
                data['table'] = 'tbl_subcategory';
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
                $("#image_url").val(row.image_url);
                $("#edit_language_id").val(row.language_id).trigger('change', [row.language_id, row.category_id]);
                $("#edit_name").val(row.subcategory_name);
                $('#edit_slug').val(row.slug);
            }
        };
    </script>

    <script type="text/javascript">
        $("#filter_language_id").on("change", function() {
            $('#table').bootstrapTable('refresh');
        });
        $("#filter_category_id").on("change", function() {
            $('#table').bootstrapTable('refresh');
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/admin/resources/views/subcategory.blade.php ENDPATH**/ ?>