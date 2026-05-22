<?php $__env->startSection('title'); ?>
    <?php echo e(__('firebase_configuration')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item text-dark">
                            <a href="<?php echo e(route('home')); ?>" class="text-dark"><i class="fas fa-home mr-1"></i><?php echo e(__('dashboard')); ?></a>
                        </li>
                        <li class="breadcrumb-item text-dark">
                            <a href="<?php echo e(url('system-settings')); ?>" class="text-dark"><i class="nav-icon fas fa-cogs mr-1"></i><?php echo e(__('system_setting')); ?></a>
                        </li>
                        <li class="breadcrumb-item active"><i class="fas fa-cog mr-1"></i><?php echo e(__('firebase_configuration')); ?></li>
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
                            <h3 class="card-title"><?php echo e(__('firebase_configuration')); ?></h3>
                        </div>
                        <form action="<?php echo e(route('firebase-configuration.store')); ?>" role="form" method="POST" enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>
                            <div class="card-body">
                                <div class="row">
                                    
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label class="control-label required"><?php echo e(__('project_id')); ?></label>
                                        <input name="project_id" type="text" value="<?php echo e($project_id?->message ?? ''); ?>" required class="form-control">
                                    </div>
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label class="control-label">
                                            <?php echo e(__('current_file_status')); ?> :
                                            <?php if($is_file): ?>
                                                <small class="badge badge-success"><?php echo e(__('file_exists')); ?></small>
                                            <?php else: ?>
                                                <small class="badge badge-danger"><?php echo e(__('file_not_exists')); ?></small>
                                            <?php endif; ?>
                                        </label>
                                        <input name="file" type="file" required class="form-control">

                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 col-sm-12">
                                        <button type="submit" class="btn btn-primary"><?php echo e(__('submit')); ?></button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <hr>
                        <div class="card-body">
                            <ol>
                                <li><?php echo e(__('open')); ?> <a href="https://console.firebase.google.com/project/_/settings/serviceaccounts/adminsdk" target="_blank">https://console.firebase.google.com/project/_/settings/serviceaccounts/adminsdk </a> <?php echo e(__('and_select_project')); ?></li>
                                <li><?php echo e(__('click_generate_new_private_key')); ?></b>.
                                    <img src="<?php echo e(url('images/generate-key.png')); ?>" width="100%" />
                                </li>
                                
                                
                                    
                                    
                                        <li><?php echo e(__('inside_the_json_file_copy_the_project_id_and_insert_it_in_the_project_id_input_text')); ?></li>
                                    
                                
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/admin/resources/views/firebase-configuration.blade.php ENDPATH**/ ?>