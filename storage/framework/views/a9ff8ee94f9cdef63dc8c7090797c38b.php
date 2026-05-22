<?php $__env->startSection('title'); ?>
    <?php echo e(__('social_media')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><?php echo e(__('create_and_manage') . ' ' . __('social_media')); ?></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item text-dark">
                            <a href="<?php echo e(route('home')); ?>" class="text-dark"><i class="fas fa-home mr-1"></i><?php echo e(__('dashboard')); ?></a>
                        </li>
                        <li class="breadcrumb-item text-dark">
                            <a href="<?php echo e(url('system-settings')); ?>" class="text-dark"><i class="nav-icon fas fa-cogs mr-1"></i><?php echo e(__('system_setting')); ?></a>
                        </li>
                        <li class="breadcrumb-item active"><i class="nav-icon fas fa-cube mr-1"></i><?php echo e(__('social_media')); ?>

                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('social-media-create')): ?>
                <div class="col-md-12 d-flex justify-content-end">
                    <button id="toggleButton" class="btn btn-primary mb-3 ml-1"><i
                            class="fas fa-plus-circle mr-2"></i><?php echo e(__('create') . ' ' . __('social_media')); ?></button>
                </div>
                <?php endif; ?>
                <div class="col-md-12" id="add_card">
                    <div class="card card-secondary">
                        <div class="card-header">
                            <h3 class="card-title"><?php echo e(__('create') . ' ' . __('social_media')); ?></h3>
                        </div>
                        <form id="create_form" action="<?php echo e(route('social-media.store')); ?>" role="form" method="POST"
                            enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label class="required"><?php echo e(__('link')); ?></label>
                                        <input name="link" type="url" required placeholder="<?php echo e(__('link')); ?>"
                                            class="form-control">
                                    </div>
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label class="required"><?php echo e(__('image')); ?> </label>
                                        <input name="file" type="file" class="filepond" required>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary float-right"><?php echo e(__('submit')); ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('social-media-list')): ?>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="card card-secondary">
                        <div class="card-header">
                            <h3 class="card-title"><?php echo e(__('social_media') . ' ' . __('list')); ?></h3>
                        </div>
                        <div class="card-body">
                            <table aria-describedby="mydesc" id='table' data-toggle="table"
                                data-url="<?php echo e(route('socialMediaList')); ?>" data-click-to-select="true"
                                data-side-pagination="server" data-pagination="true"
                                data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-show-columns="true"
                                data-show-refresh="true" data-toolbar="#toolbar" data-mobile-responsive="true"
                                data-buttons-class="primary" data-trim-on-search="false" data-sort-name="row_order"
                                data-sort-order="asc" data-query-params="queryParams">
                                <thead>
                                    <tr>
                                        <th scope="col" data-field="id" data-sortable="true"><?php echo e(__('id')); ?></th>
                                        <th scope="col" data-field="image"><?php echo e(__('image')); ?></th>
                                        <th scope="col" data-field="link"><?php echo e(__('link')); ?></th>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['social-media-edit', 'social-media-delete'])): ?>
                                        <th scope="col" data-field="operate" data-events="actionEvents"><?php echo e(__('operate')); ?></th>
                                        <?php endif; ?>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>                
            </div>
            <?php endif; ?>
        </div>
        <div class="modal fade" id="editDataModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"><?php echo e(__('edit') . ' ' . __('social_media')); ?></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="update_form" action="<?php echo e(url('social-media')); ?>" role="form" method="POST"
                        enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <input type='hidden' name="edit_id" id="edit_id" value='' />
                        <div class="modal-body">
                            <div class="row">
                                <div class="form-group col-md-12 col-sm-12">
                                    <label class="required"><?php echo e(__('link')); ?></label>
                                    <input id="edit_link" name="link" type="url" required
                                        placeholder="<?php echo e(__('link')); ?>" class="form-control">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12 col-sm-12">
                                    <label><?php echo e(__('image')); ?> </label>
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
        $(function() {
            $("#sortable-row").sortable();
        });

        function saveOrder() {
            var selectedData = new Array();
            $('ol#sortable-row li').each(function() {
                selectedData.push($(this).attr("id"));
            });
            document.getElementById("row_order").value = selectedData;
        };
    </script>

    <script type="text/javascript">
        window.actionEvents = {
            'click .edit-data': function(e, value, row, index) {
                $('#edit_id').val(row.id);
                $('#edit_link').val(row.link);        
            }
        }
    </script>
    <script type="text/javascript">
        function queryParams(p) {
            return {
                sort: p.sort,
                order: p.order,
                limit: p.limit,
                offset: p.offset,
                search: p.search,
            };
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/admin/resources/views/social-media.blade.php ENDPATH**/ ?>