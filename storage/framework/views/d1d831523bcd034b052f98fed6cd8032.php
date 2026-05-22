<?php $__env->startSection('title'); ?>
    <?php echo e(__('user') . ' ' . __('list')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item text-dark">
                            <a href="<?php echo e(route('home')); ?>" class="text-dark"><i class="fas fa-home mr-1"></i><?php echo e(__('dashboard')); ?></a>
                        </li>
                        <li class="breadcrumb-item active"><i class="nav-icon fas fa-user mr-1"></i><?php echo e(__('user') . ' ' . __('list')); ?></li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('user-list')): ?>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-secondary">
                        <div class="card-header">
                            <h3 class="card-title"><?php echo e(__('user') . ' ' . __('list')); ?> </h3>
                        </div>
                        <div class="card-body">
                            <table aria-describedby="mydesc" id='table' data-toggle="table" data-url="<?php echo e(route('usersList')); ?>" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-show-columns="true"
                                data-show-refresh="true" data-toolbar="#toolbar" data-mobile-responsive="true" data-buttons-class="primary" data-trim-on-search="false" data-sort-name="id" data-sort-order="desc" data-query-params="commonQueryParams">
                                <thead>
                                    <tr>
                                        <th scope="col" data-field="id" data-sortable="true"><?php echo e(__('id')); ?></th>
                                        <th scope="col" data-field="profile"><?php echo e(__('profile')); ?></th>
                                        <th scope="col" data-field="name" data-sortable="true"><?php echo e(__('name')); ?></th>
                                        <th scope="col" data-field="email" data-sortable="true"><?php echo e(__('email')); ?></th>
                                        <th scope="col" data-field="type"><?php echo e(__('type')); ?></th>
                                        <th scope="col" data-field="mobile"><?php echo e(__('mobile')); ?></th>
                                        <th scope="col" data-field="status1"><?php echo e(__('status')); ?></th>
                                        <th scope="col" data-field="date" data-sortable="true"><?php echo e(__('created_at')); ?></th>
                                        <th scope="col" data-field="role"><?php echo e(__('create_and_manage') . ' ' . __('news')); ?></th>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['user-edit'])): ?>
                                        <th scope="col" data-field="operate" data-events="actionEvents"><?php echo e(__('operate')); ?></th>
                                        <?php endif; ?>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
        <div class="modal fade" id="editDataModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"><?php echo e(__('edit') . ' ' . __('user')); ?></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="update_form" action="<?php echo e(url('app_users')); ?>" role="form" method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <input type='hidden' name="edit_id" id="edit_id" value='' />
                        <div class="modal-body">
                            <div class="row">
                                
                                <div class="form-group col-md-12 col-sm-12">
                                    <label><?php echo e(__('status')); ?></label><br>
                                    <div class="btn-group">
                                        <label class="btn btn-success" data-toggle-class="btn-primary" data-toggle-passive-class="btn-default">
                                            <input class="mr-1" type="radio" name="edit_status" value="1"><?php echo e(__('active')); ?>

                                        </label>
                                        <label class="btn btn-danger" data-toggle-class="btn-primary" data-toggle-passive-class="btn-default">
                                            <input class="mr-1" type="radio" name="edit_status" value="0"><?php echo e(__('deactive')); ?>

                                        </label>
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
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script type="text/javascript">
        window.actionEvents = {
            'click .edit-data': function(e, value, row, index) {
                $('#edit_id').val(row.id);
                if (row.role_id) {
                    $("input[name=edit_role]").prop("checked", true);
                } else {
                    $("input[name=edit_role]").prop("checked", false);
                }
                if (row.status == 0) {
                    $("input[name=edit_status][value=0]").prop("checked", true);
                } else {
                    $("input[name=edit_status][value=1]").prop('checked', true);
                }
            }
        };

    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/admin/resources/views/users.blade.php ENDPATH**/ ?>