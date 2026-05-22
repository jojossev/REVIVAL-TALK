<?php $__env->startSection('title'); ?>
    <?php echo e(__('Staff Management')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><?php echo e(__('create_and_manage') . ' ' . __('staff')); ?></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item text-dark">
                            <a href="<?php echo e(route('home')); ?>" class="text-dark"><i
                                    class="fas fa-home mr-1"></i><?php echo e(__('dashboard')); ?></a>
                        </li>
                        <li class="breadcrumb-item active"><i class="nav-icon fas fa-users mr-1"></i><?php echo e(__('staff')); ?></li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('staff-change-password')): ?>
                    <div class="col-md-12 d-flex justify-content-end">
                        <button id="toggleButton" class="btn btn-primary mb-3 ml-1">
                            <i class="fas fa-plus-circle mr-2"></i><?php echo e(__('create') . ' ' . __('staff')); ?>

                        </button>
                    </div>
                <?php endif; ?>
                <div class="col-md-12" id="add_card">
                    <div class="card card-secondary">
                        <div class="card-header">
                            <h3 class="card-title"><?php echo e(__('create') . ' ' . __('staff')); ?></h3>
                        </div>
                        <form id="create_form" method="POST" action="<?php echo e(route('staff.store')); ?>" role="form">
                            <?php echo csrf_field(); ?>
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label class="required"><?php echo e(__('role')); ?></label>
                                        <select class="form-control" id="role" name="role" required>
                                            <option value=""><?php echo e(__('select') . ' ' . __('role')); ?></option>
                                            <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($role->id); ?>"><?php echo e($role->name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label class="required"><?php echo e(__('username')); ?></label>
                                        <input type="text" class="form-control" id="username" name="username">
                                    </div>
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label class="required"><?php echo e(__('email')); ?></label>
                                        <input type="email" class="form-control" id="email" name="email">
                                    </div>
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label class="required"><?php echo e(__('password')); ?></label>
                                        <input type="password" class="form-control" id="password" name="password" required
                                            minlength="8">
                                    </div>
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label><?php echo e(__('status')); ?></label><br>
                                        <div class="btn-group">
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
                                <button type="submit" class="btn btn-primary float-right"><?php echo e(__('submit')); ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('staff-list')): ?>
                <div class="row">
                    <div class="col-12">
                        <div class="card card-secondary">
                            <div class="card-header">
                                <h3 class="card-title"><?php echo e(__('staff') . ' ' . __('list')); ?></h3>
                            </div>
                            <div class="card-body">
                                <table id="table" class="table table-bordered table-striped" data-toggle="table"
                                    data-url="<?php echo e(route('staff.list')); ?>" data-click-to-select="true"
                                    data-side-pagination="server" data-pagination="true"
                                    data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-unique-id="id"
                                    data-show-columns="true" data-show-refresh="true" data-mobile-responsive="true"
                                    data-buttons-class="primary">
                                    <thead>
                                        <tr>
                                            <th scope="col" data-field="id" data-sortable="true">ID</th>
                                            <th scope="col" data-field="username"><?php echo e(__('username')); ?></th>
                                            <th scope="col" data-field="email"><?php echo e(__('email')); ?></th>
                                            <th scope="col" data-field="role_name"><?php echo e(__('role')); ?></th>
                                            <th scope="col" data-field="status" data-formatter="staffStatusFormatter"><?php echo e(__('status')); ?></th>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['staff-edit', 'staff-change-password', 'staff-delete'])): ?>
                                                <th scope="col" data-field="operate" data-events="staffEvents"><?php echo e(__('actions')); ?></th>
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
    </section>

    <!-- Modal for Edit -->
    <div class="modal fade" id="editDataModal" tabindex="-1" role="dialog" aria-labelledby="editDataModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editDataModalLabel"><?php echo e(__('edit') . ' ' . __('staff')); ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="update_form" action="<?php echo e(url('staff')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <input type='hidden' name="edit_id" id="edit_id" value='' />
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="edit_role" class="required"><?php echo e(__('role')); ?></label>
                            <select class="form-control" id="edit_role" name="role" required>
                                <option value=""><?php echo e(__('select') . ' ' . __('role')); ?></option>
                                <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($role->id); ?>"><?php echo e($role->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="edit_username" class="required"><?php echo e(__('username')); ?></label>
                            <input type="text" class="form-control" id="edit_username" name="username" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_email" class="required"><?php echo e(__('email')); ?></label>
                            <input type="email" class="form-control" id="edit_email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label><?php echo e(__('status')); ?></label><br>
                            <div class="btn-group">
                                <label class="btn btn-success" data-toggle-class="btn-primary"
                                    data-toggle-passive-class="btn-default">
                                    <input class="mr-1" type="radio" name="status" value="1"><?php echo e(__('active')); ?>

                                </label>
                                <label class="btn btn-danger" data-toggle-class="btn-primary"
                                    data-toggle-passive-class="btn-default">
                                    <input class="mr-1" type="radio" name="status" value="0"><?php echo e(__('deactive')); ?>

                                </label>
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

    <!-- Modal for Changing Password -->
    <div class="modal fade" id="changePasswordModal" tabindex="-1" role="dialog" aria-labelledby="changePasswordModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="changePasswordModalLabel"><?php echo e(__('change_password')); ?></h5>`
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="change_password_form" action="<?php echo e(url('staff/change-password')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <input type='hidden' name="staff_id" id="password_staff_id" value='' />
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="new_password" class="required"><?php echo e(__('new_password')); ?></label>
                            <input type="password" class="form-control" id="new_password" name="password" required
                                minlength="8">
                        </div>
                        <div class="form-group">
                            <label for="confirm_password" class="required"><?php echo e(__('confirm_password')); ?></label>
                            <input type="password" class="form-control" id="confirm_password" name="password_confirmation"
                                required minlength="8">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo e(__('close')); ?></button>
                        <button type="submit" class="btn btn-primary"><?php echo e(__('update_password')); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script>
        function operateFormatter(value, row, index) {
            let buttons = '';

            // Edit button
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('staff-edit')): ?>
                buttons += '<button type="button" class="btn btn-sm btn-primary text-white edit-data mr-1" ' +
                    'data-id="' + row.id + '" ' +
                    'data-username="' + row.username + '" ' +
                    'data-email="' + row.email + '" ' +
                    'data-role="' + row.role_id + '" ' +
                    'data-status="' + row.status + '">' +
                    '<i class="fas fa-edit"></i></button>';
            <?php endif; ?>
            // Change password button
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('staff-change-password')): ?>
                buttons += '<button type="button" class="btn btn-sm btn-primary text-white change-password mr-1" ' +
                    'data-id="' + row.id + '">' +
                    '<i class="fas fa-key"></i></button>';
            <?php endif; ?>
            // Delete button - only if not current user and not admin role
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('staff-delete')): ?>
                // if (row.id !== <?php echo e(auth()->id()); ?> && !row.is_admin) {
                //     buttons += '<a data-url="' + baseUrl + '/staff/' + row.id + '" ' +
                //         'class="btn btn-sm btn-primary text-white delete-form" data-id="' + row.id + '">' +
                //         '<i class="fas fa-trash"></i></a>';
                // }
            <?php endif; ?>

            return buttons;
        }

        $(document).ready(function () {
            // Edit Staff Modal handler
            // $(document).on('click', '.edit-data', function () {
            //     var id = $(this).data('id');
            //     var username = $(this).data('username');
            //     var email = $(this).data('email');
            //     var role = $(this).data('role');
            //     var status = $(this).data('status');

            //     $('#edit_id').val(id);
            //     $('#edit_username').val(username);
            //     $('#edit_email').val(email);
            //     $('#edit_role').val(role);
            //     $('input[name="status"][value="' + status + '"]').prop('checked', true);

            //     $('#editDataModal').modal('show');
            // });

            // Change Password Modal handler
            $(document).on('click', '.change-password', function () {
                var id = $(this).data('id');
                $('#password_staff_id').val(id);
                $('#new_password').val('');
                $('#confirm_password').val('');
                $('#changePasswordModal').modal('show');
            });

            // Password confirmation validation
            $('#change_password_form').on('submit', function (e) {
                e.preventDefault();

                var password = $('#new_password').val();
                var confirmPassword = $('#confirm_password').val();

                if (password.length < 8) {
                    showErrorToast('<?php echo e(__("Password must be at least 8 characters")); ?>');
                    return false;
                }

                if (password !== confirmPassword) {
                    showErrorToast('<?php echo e(__("Passwords do not match")); ?>');
                    return false;
                }

                var formData = new FormData(this);
                var url = $(this).attr('action');

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        if (!response.error) {
                            showSuccessToast(response.message);
                            $('#changePasswordModal').modal('hide');
                            $('#table').bootstrapTable('refresh');
                        } else {
                            showErrorToast(response.message);
                        }
                    },
                    error: function (xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            let errorMessage = '';
                            Object.keys(errors).forEach(function (key) {
                                errorMessage += errors[key][0] + '<br>';
                            });
                            showErrorToast(errorMessage);
                        } else {
                            showErrorToast('<?php echo e(__("Something went wrong. Please try again.")); ?>');
                        }
                    }
                });
            });

            // Additional validation for email
            $('#create_form').on('submit', function () {
                var email = $('#email').val();
                var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(email)) {
                    showErrorToast('<?php echo e(__("enter_valid_email")); ?>');
                    return false;
                }

                if ($('#password').val().length < 8) {
                    showErrorToast('<?php echo e(__("password_must_be_at_least_8_characters")); ?>');
                    return false;
                }

                return true;
            });

            $('#update_form').on('submit', function () {
                var email = $('#edit_email').val();
                var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(email)) {
                    showErrorToast('<?php echo e(__("enter_valid_email")); ?>');
                    return false;
                }

                return true;
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/admin/resources/views/staff.blade.php ENDPATH**/ ?>