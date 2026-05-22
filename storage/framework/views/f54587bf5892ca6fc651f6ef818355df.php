<?php $__env->startSection('title'); ?>
    <?php echo e(__('system_setting')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><?php echo e(__('system_setting')); ?></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item text-dark">
                            <a href="<?php echo e(route('home')); ?>" class="text-dark"><i class="fas fa-home mr-1"></i><?php echo e(__('dashboard')); ?></a>
                        </li>
                        <li class="breadcrumb-item active"><i class="nav-icon fas fa-cogs mr-1"></i><?php echo e(__('system_setting')); ?></li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('general-settings')): ?>
                <div class="col-lg-3 col-md-4 col-sm-12 mb-1">
                    <a href="<?php echo e(url('general-settings')); ?>" class="card setting_active_tab" style="text-decoration: none;">
                        <div class="content d-flex h-100">
                            <div class="row mx-2 ">
                                <div class="provider_a test">
                                    <i class="fas fa-cogs icon_font_size "></i>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="title"><?php echo e(__('general_setting')); ?></h5>
                            <div class="title"><?php echo e(__('go_to_settings')); ?> <i class="fas fa-arrow-right mt-2 arrow_icon"></i>
                            </div>
                        </div>
                    </a>
                </div>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('panel-settings')): ?>
                <div class="col-lg-3 col-md-4 col-sm-12 mb-1">
                    <a href="<?php echo e(url('panel-settings')); ?>" class="card setting_active_tab" style="text-decoration: none;">
                        <div class="content d-flex h-100">
                            <div class="row mx-2 ">
                                <div class="provider_a test">
                                    <i class="fas fa-cogs icon_font_size "></i>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="title"><?php echo e(__('panel_setting')); ?></h5>
                            <div class="title"><?php echo e(__('go_to_settings')); ?> <i class="fas fa-arrow-right mt-2 arrow_icon"></i>
                            </div>
                        </div>
                    </a>
                </div>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('web-settings')): ?>
                <div class="col-lg-3 col-md-4 col-sm-12 mb-2">
                    <a href="<?php echo e(url('web-settings')); ?>" class="card setting_active_tab" style="text-decoration: none;">
                        <div class="content d-flex h-100">
                            <div class="row mx-2 ">
                                <div class="provider_a test">
                                    <i class="fas fa-tv icon_font_size"></i>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="title"><?php echo e(__('web_setting')); ?></h5>
                            <div class="title"><?php echo e(__('go_to_settings')); ?> <i class="fas fa-arrow-right mt-2 arrow_icon"></i>
                            </div>
                        </div>
                    </a>
                </div>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('app-settings')): ?>
                <div class="col-lg-3 col-md-4 col-sm-12 mb-3">
                    <a href="<?php echo e(url('app-settings')); ?>" class="card setting_active_tab" style="text-decoration: none;">
                        <div class="content d-flex h-100">
                            <div class="row mx-2 ">
                                <div class="provider_a test">
                                    <i class="fas fa-tablet-alt icon_font_size"></i>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="title"><?php echo e(__('app_setting')); ?></h5>
                            <div class="title"><?php echo e(__('go_to_settings')); ?> <i class="fas fa-arrow-right mt-2 arrow_icon"></i>
                            </div>
                        </div>
                    </a>
                </div>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('language-list')): ?>
                <div class="col-lg-3 col-md-4 col-sm-12 mb-3">
                    <a href="<?php echo e(url('language')); ?>" class="card setting_active_tab" style="text-decoration: none;">
                        <div class="content d-flex h-100">
                            <div class="row mx-2 ">
                                <div class="provider_a test">
                                    <i class="fas fa-language icon_font_size"></i>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="title"><?php echo e(__('language_setting')); ?></h5>
                            <div class="title"><?php echo e(__('go_to_settings')); ?> <i class="fas fa-arrow-right mt-2 arrow_icon"></i>
                            </div>
                        </div>
                    </a>
                </div>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('seo-list')): ?>
                <div class="col-lg-3 col-md-4 col-sm-12 mb-3">
                    <a href="<?php echo e(url('seo-setting')); ?>" class="card setting_active_tab" style="text-decoration: none;">
                        <div class="content d-flex h-100">
                            <div class="row mx-2 ">
                                <div class="provider_a test">
                                    <i class="fas fa-chart-bar icon_font_size"></i>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="title"><?php echo e(__('seo_setting')); ?></h5>
                            <div class="title"><?php echo e(__('go_to_settings')); ?> <i class="fas fa-arrow-right mt-2 arrow_icon"></i>
                            </div>
                        </div>
                    </a>
                </div>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('social-media-list')): ?>
                <div class="col-lg-3 col-md-4 col-sm-12 mb-3">
                    <a href="<?php echo e(url('social-media')); ?>" class="card setting_active_tab" style="text-decoration: none;">
                        <div class="content d-flex h-100">
                            <div class="row mx-2 ">
                                <div class="provider_a test">
                                    <i class="fas fa-network-wired icon_font_size"></i>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="title"><?php echo e(__('social_media')); ?></h5>
                            <div class="title"><?php echo e(__('go_to_settings')); ?> <i class="fas fa-arrow-right mt-2 arrow_icon"></i>
                            </div>
                        </div>
                    </a>
                </div>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('firebase-configuration')): ?>
                <div class="col-lg-3 col-md-4 col-sm-12 mb-3">
                    <a href="<?php echo e(url('firebase-configuration')); ?>" class="card setting_active_tab" style="text-decoration: none;">
                        <div class="content d-flex h-100">
                            <div class="row mx-2 ">
                                <div class="provider_a test">
                                    <i class="fas fa-cog icon_font_size"></i>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="title"><?php echo e(__('firebase_configuration')); ?></h5>
                            <div class="title"><?php echo e(__('go_to_settings')); ?> <i class="fas fa-arrow-right mt-2 arrow_icon"></i>
                            </div>
                        </div>
                    </a>
                </div>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('system-update')): ?>
                <div class="col-lg-3 col-md-4 col-sm-12 mb-3">
                    <a href="<?php echo e(route('system-update')); ?>" class="card setting_active_tab" style="text-decoration: none;">
                        <div class="content d-flex h-100">
                            <div class="row mx-2 ">
                                <div class="provider_a test">
                                    <i class="fas fa-upload icon_font_size"></i>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="title"><?php echo e(__('system_update')); ?></h5>
                            <div class="title"><?php echo e(__('go_to_settings')); ?> <i class="fas fa-arrow-right mt-2 arrow_icon"></i>
                            </div>
                        </div>
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/admin/resources/views/system-setting.blade.php ENDPATH**/ ?>