<?php $__env->startSection('title'); ?>
    <?php echo e(__('system_update')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><?php echo e(__('system_update')); ?></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item text-dark">
                            <a href="<?php echo e(route('home')); ?>" class="text-dark"><i class="fas fa-home mr-1"></i><?php echo e(__('dashboard')); ?></a>
                        </li>
                        <li class="breadcrumb-item text-dark">
                            <a href="<?php echo e(url('system-settings')); ?>" class="text-dark"><i class="nav-icon fas fa-cogs mr-1"></i><?php echo e(__('system_setting')); ?></a>
                        </li>
                        <li class="breadcrumb-item active"><i class="fas fas fa-upload mr-1"></i><?php echo e(__('system_update')); ?></li>
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
                            <h3 class="card-title"><?php echo e(__('system_update')); ?>

                                <small class="text-bold"> <?php echo e(__('current_version')); ?> <?= $setting ? $setting['app_version'] : '' ?></small>
                            </h3>
                        </div>
                        <form id="create_form" action="<?php echo e(route('system-update-operation')); ?>" role="form" method="POST" enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>
                            <div class="card-body">
                                <div class="form-group row">
                                    <div class="col-md-6 col-sm-12">
                                        <label><?php echo e(__('purchase_code')); ?></label>
                                        <input type="text" name="purchase_code" required placeholder="<?php echo e(__('purchase_code')); ?>" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-6 col-sm-12">
                                        <label><?php echo e(__('update_zip')); ?> <small class="text-danger"><?php echo e(__('only_zip_file_allow')); ?> </small></label>
                                        <div class="custom-file">
                                            <input name="file" type="file" required class="form-control">
                                            <small class="text-danger">
                                                <?php echo e($setting ? $setting['app_version'] : ''); ?> <?php echo e(__('update_nearest_version')); ?>

                                            </small>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <button type="submit" class="btn btn-primary"><?php echo e(__('submit')); ?> </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/admin/resources/views/system-updater.blade.php ENDPATH**/ ?>