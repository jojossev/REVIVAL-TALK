<?php $__env->startSection('title'); ?>
    <?php echo e(__('panel_setting')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6"></div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item text-dark">
                            <a href="<?php echo e(route('home')); ?>" class="text-dark"><i class="fas fa-home mr-1"></i><?php echo e(__('dashboard')); ?></a>
                        </li>
                        <li class="breadcrumb-item text-dark">
                            <a href="<?php echo e(url('system-settings')); ?>" class="text-dark"><i class="nav-icon fas fa-cogs mr-1"></i><?php echo e(__('system_setting')); ?></a>
                        </li>
                        <li class="breadcrumb-item  active"><i class="fas fas fa-cogs mr-1"></i><?php echo e(__('panel_setting')); ?></li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="card card-secondary h-100">
                        <div class="card-header">
                            <h3 class="card-title"><?php echo e(__('system_settings_for_panel')); ?></h3>
                        </div>
                        <form action="<?php echo e(route('panel-settings')); ?>" role="form" method="POST" enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 col-sm-12">
                                        <div class="form-group">
                                            <label for="system_timezone"><?php echo e(__('system_timezone')); ?></label>
                                            <select id="system_timezone" name="system_timezone" required class="form-control">
                                                <?php $__currentLoopData = getTimezoneOptions(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($option['timezone_id']); ?>" <?php if($setting['system_timezone'] == $option['timezone_id']): ?> selected <?php endif; ?> data-gmt="<?php echo e($option['offset']); ?>">
                                                        <?php echo e($option['timezone_id']); ?> - sGMT<?php echo e($option['offset']); ?>-<?php echo e($option['time']); ?>

                                                    </option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label><?php echo e(__('panel_name')); ?></label>
                                            <input type="text" name="app_name" value="<?= $setting['app_name'] ? $setting['app_name'] : '' ?>" class="form-control" required />
                                        </div>
                                        <div class="form-group">
                                            <label><?php echo e(__('primary_color')); ?></label>
                                            <input type="color" name="primary_color" value="<?= isset($setting['primary_color']) ? $setting['primary_color'] : '' ?>" class="form-control" required />
                                        </div>
                                        <div class="form-group">
                                            <label><?php echo e(__('secondary_color')); ?></label>
                                            <input type="color" name="secondary_color" value="<?= isset($setting['secondary_color']) ? $setting['secondary_color'] : '' ?>" class="form-control" required />
                                        </div>
                                        <div class="form-group">
                                            <label><?php echo e(__('auto_delete_expire_news')); ?></label>
                                            <div>
                                                <input type="checkbox" id="is_expire" name="is_expire" class="status-switch" <?php if($setting['auto_delete_expire_news_mode'] == '1'): ?> checked <?php endif; ?>>
                                                <input type="hidden" id="auto_delete_expire_news_mode" class="status-switch" name="auto_delete_expire_news_mode" value="<?php echo e($setting['auto_delete_expire_news_mode'] ? $setting['auto_delete_expire_news_mode'] : 0); ?>">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-sm-12">
                                        <div class="form-group">
                                            <label><?php echo e(__('admin_panel_full_logo')); ?> <small class="text-danger">(<?php echo e(__('size')); ?> 460 * 115)</small></label>
                                            <input name="file1" type="file" class="filepond">
                                            <div class="col-sm-6 col-md-6">
                                                <img src="<?php echo e(url(Storage::url($setting['app_logo_full']))); ?>" width="300" alt="logo" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label><?php echo e(__('admin_panel_favicon')); ?> <small class="text-danger">(<?php echo e(__('size')); ?> 128 * 128)</small></label>
                                            <input name="file" type="file" class="filepond">
                                            <div class="col-sm-6 col-md-6">
                                                <img src="<?php echo e(url(Storage::url($setting['app_logo']))); ?>" height="100" alt="favicon" />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-sm-12">
                                        
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary float-right"><?php echo e(__('submit')); ?></button>
                                    </div>
                                </div>
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
        $(document).ready(function(e) {
            var elems = Array.prototype.slice.call(
                document.querySelectorAll(".status-switch")
            );
            elems.forEach(function(elem) {
                var switchery = new Switchery(elem, {
                    size: "small",
                    color: "#47C363",
                    secondaryColor: "#EB4141",
                    jackColor: "#ffff",
                    jackSecondaryColor: "#ffff",
                });
            });

            /* on change of Location wise news mode btn - switchery js */
            var auto_delete_expire = document.querySelector('#is_expire');
            auto_delete_expire.onchange = function() {
                if (auto_delete_expire.checked) {
                    $('#auto_delete_expire_news_mode').val(1);
                } else {
                    $('#auto_delete_expire_news_mode').val(0);
                }
            };
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/admin/resources/views/panel-setting.blade.php ENDPATH**/ ?>