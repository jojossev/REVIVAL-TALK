<?php $__env->startSection('title'); ?>
    <?php echo e(__('author')); ?>

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
                        <li class="breadcrumb-item active"><i class="nav-icon fas fa-user mr-1"></i><?php echo e(__('author') . ' ' . __('list')); ?></li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-secondary">
                        <div class="card-header">
                            <h3 class="card-title"><?php echo e(__('author') . ' ' . __('list')); ?> </h3>
                        </div>
                        <div class="card-body">
                            <div id="toolbar">
                                <select id="filter_status" name="status" class="form-control">
                                    <option value=""><?php echo e(__('select') . ' ' . __('status')); ?></option>
                                    <option value="approved"><?php echo e(__('approved')); ?></option>
                                    <option value="pending"><?php echo e(__('pending')); ?></option>
                                    <option value="rejected"><?php echo e(__('rejected')); ?></option>
                                </select>
                            </div>
                            <table aria-describedby="mydesc" id='table' data-toggle="table"
                            data-url="<?php echo e(route('author.show',1)); ?>" data-click-to-select="true"
                            data-side-pagination="server" data-pagination="true"
                            data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true"
                            data-show-columns="true" data-show-refresh="true"
                            data-toolbar="#toolbar" data-mobile-responsive="true"
                            data-buttons-class="primary" data-trim-on-search="false"
                            data-sort-name="id" data-sort-order="desc" data-query-params="authorQueryParams">
                                <thead>
                                    <tr>
                                        <th scope="col" data-field="id" data-sortable="true"><?php echo e(__('id')); ?></th>
                                        <th scope="col" data-field="user_name"><?php echo e(__('user_name')); ?></th>
                                        <th scope="col" data-field="user.email"><?php echo e(__('email')); ?></th>
                                        <th scope="col" data-field="bio" data-formatter="textFormatter"><?php echo e(__('bio')); ?></th>
                                        
                                        <th scope="col" data-field="status" data-formatter="statusFormatter"><?php echo e(__('status')); ?></th>

                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('author-edit')): ?>
                                        <th scope="col" data-field="operate" data-events="authorEvents"><?php echo e(__('operate')); ?></th>
                                        <?php endif; ?>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="modal fade" id="editDataModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"><?php echo e(__('verify_author')); ?></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <form id="update_form" action="<?php echo e(url('author')); ?>" role="form" method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <input type='hidden' name="edit_id" id="edit_id" value='' />
                        <input type='hidden' name="user_id" id="user_id" value='' />
                        <div class="modal-body">
                            <div class="row">
                                

                                    <div class="form-group col-md-12 col-sm-12">
                                        <label><?php echo e(__('telegram_link')); ?></label>
                                        <input type="text" name="telegram_link" id="telegram_link" class="form-control" readonly>
                                    </div>
                                    <div class="form-group col-md-12 col-sm-12">
                                        <label><?php echo e(__('whatsapp_link')); ?></label>
                                        <input type="text" name="whatsapp_link" id="whatsapp_link" class="form-control" readonly>
                                    </div>
                                    <div class="form-group col-md-12 col-sm-12">
                                        <label><?php echo e(__('linkedin_link')); ?></label>
                                        <input type="text" name="linkedin_link" id="linkedin_link" class="form-control" readonly>
                                    </div>
                                    <div class="form-group col-md-12 col-sm-12">
                                        <label><?php echo e(__('facebook_link')); ?></label>
                                        <input type="text" name="facebook_link" id="facebook_link" class="form-control" readonly>
                                    </div>

                                <div class="form-group col-md-12 col-sm-12">
                                    <label><?php echo e(__('author_status')); ?></label><br>
                                    <div class="btn-group">
                                        <label class="btn btn-success" data-toggle-class="btn-primary" data-toggle-passive-class="btn-default">
                                            <input class="mr-1" type="radio" name="author_status" value="approved"><?php echo e(__('approved')); ?>

                                        </label>
                                        <label class="btn btn-warning" data-toggle-class="btn-primary" data-toggle-passive-class="btn-default">
                                            <input class="mr-1" type="radio" name="author_status" value="pending" checked><?php echo e(__('pending')); ?>

                                        </label>
                                        <label class="btn btn-danger" data-toggle-class="btn-primary" data-toggle-passive-class="btn-default">
                                            <input class="mr-1" type="radio" name="author_status" value="rejected"><?php echo e(__('rejected')); ?>

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
     $("#filter_status").on("change", function() {
            $('#table').bootstrapTable('refresh');
        });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/admin/resources/views/author/index.blade.php ENDPATH**/ ?>