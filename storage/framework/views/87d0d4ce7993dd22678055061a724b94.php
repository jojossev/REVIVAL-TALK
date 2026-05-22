<?php $__env->startSection('title'); ?>
    <?php echo e(__('ad_spaces')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><?php echo e(__('create_and_manage') . ' ' . __('ad_spaces')); ?></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item text-dark">
                            <a href="<?php echo e(route('home')); ?>" class="text-dark"><i
                                    class="fas fa-home mr-1"></i><?php echo e(__('dashboard')); ?></a>
                        </li>
                        <li class="breadcrumb-item active"><i class="nav-icon fas fa-ad mr-1"></i><?php echo e(__('ad_spaces')); ?></li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('ad-space-create')): ?>
                <div class="col-md-12 d-flex justify-content-end">
                    <button id="toggleButton" class="btn btn-primary mb-3 ml-1"><i
                            class="fas fa-plus-circle mr-2"></i><?php echo e(__('create') . ' ' . __('ad_spaces')); ?></button>
                </div>
                <?php endif; ?>
                <div class="col-md-12" id="add_card">
                    <div class="card card-secondary">
                        <div class="card-header">
                            <h3 class="card-title"><?php echo e(__('create') . ' ' . __('ad_spaces')); ?></h3>
                        </div>
                        <form id="create_form" action="<?php echo e(url('ad_spaces')); ?>" role="form" method="POST"
                            enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-md-4 col-sm-12">
                                        <label class="required"><?php echo e(__('language')); ?></label>
                                        <select id="language_id" name="language_id" class="form-control" required>
                                            <?php if(count($languageList) > 1): ?>
                                                <option value=""><?php echo e(__('select') . ' ' . __('language')); ?></option>
                                            <?php endif; ?>
                                            <?php $__currentLoopData = $languageList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($row->id); ?>"><?php echo e($row->language); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4 col-sm-12">
                                        <label class="required"><?php echo e(__('select') . ' ' . __('ad_spaces')); ?></label>
                                        <select id="ad_space" name="ad_space" class="form-control" required>
                                            <option value=""><?php echo e(__('select') . ' ' . __('ad_spaces')); ?></option>
                                            <?php $__currentLoopData = $featuredSectionList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php if($row->style_app == 'style_6' || $row->style_web == 'style_web'): ?>
                                                    <option value="featuredsection-<?php echo e($row->id); ?>">Above
                                                        <?php echo e($row->title); ?> (Not applicable)</option>
                                                <?php else: ?>
                                                    <option value="featuredsection-<?php echo e($row->id); ?>">Above
                                                        <?php echo e($row->title); ?></option>
                                                <?php endif; ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <option value="news_details_top"><?php echo e(__('news_details_top')); ?></option>
                                            <option value="news_details_bottom"><?php echo e(__('news_details_bottom')); ?></option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4 col-sm-12">
                                        <label><?php echo e(__('URL')); ?></label>
                                        <input name="ad_url" type="url" class="form-control">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-sm-12 col-md-6">
                                        <label class="required"><?php echo e(__('app_ad_image')); ?> <small
                                                class="text-danger">(<?php echo e(__('size')); ?> 800 * 215)</small></label>
                                        <input name="ad_image" type="file" accept="image/*" class="filepond" required>
                                    </div>
                                    <div class="form-group col-sm-12 col-md-6">
                                        <label class="required"> <?php echo e(__('web_ad_image')); ?> <small
                                                class="text-danger">(<?php echo e(__('size')); ?> 1920 * 160)</small></label>
                                        <input name="web_ad_image" type="file" accept="image/*" class="filepond"
                                            required>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary"><?php echo e(__('submit')); ?></button>
                            </div>
                        </form>
                    </div>
                </div>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('ad-space-list')): ?>
                <div class="col-md-12">
                    <div class="card card-secondary">
                        <div class="card-header">
                            <h3 class="card-title"><?php echo e(__('ad_spaces') . ' ' . __('list')); ?></h3>
                        </div>
                        <div class="card-body">
                            <div id="toolbar" class="d-flex">
                                <div class="mr-3">
                                    <select id="filter_language_id" class="form-control">
                                        <option value="0"><?php echo e(__('select') . ' ' . __('language')); ?></option>
                                        <?php $__currentLoopData = $languageList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($row->id); ?>"><?php echo e($row->language); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <div>
                                    <select id="filter_status" name="status" class="form-control">
                                        <option value=""><?php echo e(__('status')); ?></option>
                                        <option value="1"><?php echo e(__('active')); ?></option>
                                        <option value="0"><?php echo e(__('deactive')); ?></option>
                                    </select>
                                </div>
                            </div>
                            <table aria-describedby="mydesc" id='table' data-toggle="table"
                                data-url="<?php echo e(route('adSpacesList')); ?>" data-click-to-select="true"
                                data-side-pagination="server" data-pagination="true"
                                data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-show-columns="true"
                                data-show-refresh="true" data-toolbar="#toolbar" data-mobile-responsive="true"
                                data-buttons-class="primary" data-trim-on-search="false" data-sort-name="id"
                                data-sort-order="desc" data-query-params="adSpacesQueryParams">
                                <thead>
                                    <tr>
                                        <th scope="col" data-field="id" data-sortable="true" data-visible="false">
                                            <?php echo e(__('id')); ?></th>
                                        <th scope="col" data-field="language"><?php echo e(__('language')); ?></th>
                                        <th scope="col" data-field="ad_space"><?php echo e(__('ad_spaces')); ?></th>
                                        <th scope="col" data-field="ad_featured_section" data-visible="false">
                                            <?php echo e(__('featured_section')); ?></th>
                                        <th scope="col" data-field="ad_image"><?php echo e(__('app_ad_image')); ?></th>
                                        <th scope="col" data-field="web_ad_image"><?php echo e(__('web_ad_image')); ?></th>
                                        <th scope="col" data-field="ad_url" data-sortable="true"
                                            data-visible="false"> <?php echo e(__('URL')); ?></th>
                                        <th scope="col" data-field="status1"><?php echo e(__('status')); ?></th>
                                        <th scope="col" data-field="date" data-sortable="true" data-visible="false">
                                            <?php echo e(__('created_at')); ?></th>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['ad-space-edit', 'ad-space-delete'])): ?>
                                        <th scope="col" data-field="operate" data-events="actionEvents">
                                            <?php echo e(__('operate')); ?></th>
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
                        <h4 class="modal-title"><?php echo e(__('edit') . ' ' . __('ad_spaces')); ?></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="update_form" action="<?php echo e(url('ad_spaces')); ?>" role="form" method="POST"
                        enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <input type='hidden' name="edit_id" id="edit_id" value='' />
                        <input type='hidden' name="ad_image_url" id="ad_image_url" value='' />
                        <input type='hidden' name="web_ad_image_url" id="web_ad_image_url" value='' />
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-6 col-sm-12">
                                    <label class="required"><?php echo e(__('language')); ?></label>
                                    <select id="edit_language_id" name="language_id" class="form-control language_id"
                                        required>
                                        <option value=""><?php echo e(__('select') . ' ' . __('language')); ?></option>
                                        <?php $__currentLoopData = $languageList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($item->id); ?>"><?php echo e($item->language); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-6 col-sm-12">
                                    <label class="required"><?php echo e(__('select') . ' ' . __('ad_spaces')); ?></label>
                                    <select id="edit_ad_spaces" name="ad_space" class="form-control ad_spaces" required>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6 col-sm-12">
                                    <label class="required"><?php echo e(__('app_ad_image')); ?> <small
                                            class="text-danger">(<?php echo e(__('size')); ?> 800 * 215)</small></label>
                                    <input name="ad_image" type="file" accept="image/*" class="filepond">
                                </div>
                                <div class="form-group col-md-6 col-sm-12">
                                    <label class="required"><?php echo e(__('web_ad_image')); ?><small
                                            class="text-danger">(<?php echo e(__('size')); ?> 1920 * 160)</small></label>
                                    <input name="web_ad_image" type="file" accept="image/*" class="filepond">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6 col-sm-12">
                                    <label><?php echo e(__('URL')); ?></label>
                                    <input id="edit_ad_url" type="url" class="form-control" name="ad_url">
                                </div>
                                <div class="form-group col-md-6 col-sm-12">
                                    <label><?php echo e(__('status')); ?></label><br>
                                    <div id="status1" class="btn-group">
                                        <label class="btn btn-success" data-toggle-class="btn-primary"
                                            data-toggle-passive-class="btn-default">
                                            <input class="mr-1" type="radio" name="status" value="1"
                                                checked><?php echo e(__('active')); ?>

                                        </label>
                                        <label class="btn btn-danger" data-toggle-class="btn-primary"
                                            data-toggle-passive-class="btn-default">
                                            <input class="mr-1" type="radio" name="status"
                                                value="0"><?php echo e(__('deactive')); ?>

                                        </label>
                                    </div>
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
        $(document).on('change', '#language_id', function(e) {
            var language_id = $('#language_id').val();
            var data = {
                language_id: language_id,
            };
            var url = '<?php echo e(route('get_featured_sections_by_language')); ?>';
            fetchList(url, data, '#ad_space');
        });

        $(document).on('change', '#edit_language_id', function(e, row_language, row_ad_space) {
            var language_id = $('#edit_language_id').val();
            $.ajax({
                url: '<?php echo e(route('get_featured_sections_by_language')); ?>',
                type: "POST",
                data: {
                    language_id: language_id,
                },
                beforeSend: function() {
                    $('#edit_ad_spaces').html("Please wait..");
                },
                success: function(result) {
                    $('#edit_ad_spaces').html(result);
                    if (language_id == row_language && row_ad_space != 0) {
                        $('#edit_ad_spaces').val(row_ad_space);
                    }
                },
                error: function(errors) {
                    console.log(errors);
                },
            });
        });
    </script>

    <script type="text/javascript">
        window.actionEvents = {
            'click .edit-data': function(e, value, row, index) {
                $('#edit_id').val(row.id);
                $("#edit_language_id").val(row.language_id).trigger('change', [row.language_id, row.ad_space]);
                $("#ad_image_url").val(row.ad_image_url);
                $("#web_ad_image_url").val(row.web_ad_image_url);
                $("#edit_ad_url").val(row.ad_url);
                if (row.status == 1) {
                    $("input[name=status][value=1]").prop('checked', true);
                } else {
                    $("input[name=status][value=0]").prop('checked', true);
                }
            }
        };

        $("#filter_language_id").on("change", function() {
            $('#table').bootstrapTable('refresh');
        });

        $("#filter_status").on("change", function() {
            $('#table').bootstrapTable('refresh');
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/admin/resources/views/ad-spaces.blade.php ENDPATH**/ ?>