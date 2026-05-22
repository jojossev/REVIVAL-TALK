<?php $__env->startSection('title'); ?>
    <?php echo e(__('web_setting')); ?>

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
                        <li class="breadcrumb-item  active"><i class="fas fas fa-tv mr-1"></i><?php echo e(__('web_setting')); ?></li>
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
                            <h3 class="card-title"><?php echo e(__('system_settings_for_web')); ?>

                                <small class="text-bold"><?php echo e(__('directly_reflect_changes_in_web')); ?> </small>
                            </h3>
                        </div>
                        <form action="<?php echo e(route('web-settings.store')); ?>" role="form" method="POST" enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label><?php echo e(__('web_name')); ?></label>
                                        <input type="text" name="web_name" value="<?= $setting['web_name'] ? $setting['web_name'] : '' ?>" class="form-control" required />
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label><?php echo e(__('accept_cookie')); ?></label>
                                        <div>
                                            <input type="checkbox" id="is_accept_cookie" name="is_accept_cookie" class="status-switch" <?php if(isset($setting['accept_cookie']) && $setting['accept_cookie'] == '1'): ?> checked <?php endif; ?>>
                                            <input type="hidden" id="accept_cookie" name="accept_cookie" value="<?php echo e($setting['accept_cookie'] ?? 0); ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-12 col-sm-12">
                                        <label><?php echo e(__('footer_description')); ?></label>
                                        <textarea name="web_footer_description" class="form-control"><?php echo e($setting['web_footer_description'] ? $setting['web_footer_description'] : ''); ?></textarea>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-12 col-sm-12">
                                        <label><?php echo e(__('google_adsense')); ?></label>
                                        <textarea name="google_adsense" class="form-control"><?php echo e(isset($setting['google_adsense']) ? $setting['google_adsense'] : ''); ?></textarea>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-sm-12">
                                        <div class="row text-center">
                                            <div class="col-md-12 col-sm-12">
                                                <h5 class="text-bold"><?php echo e(__('light_theme')); ?></h5>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label><?php echo e(__('body_color')); ?></label>
                                                <input name="light_body_color" value="<?= isset($setting['light_body_color']) ? $setting['light_body_color'] : '' ?>" type="color" required class="form-control" />
                                            </div>
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label><?php echo e(__('hover_color')); ?></label>
                                                <input name="light_hover_color" value="<?= isset($setting['light_hover_color']) ? $setting['light_hover_color'] : '' ?>" type="color" required class="form-control" />
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label><?php echo e(__('primary_color')); ?></label>
                                                <input name="light_primary_color" value="<?= isset($setting['light_primary_color']) ? $setting['light_primary_color'] : '' ?>" type="color" required class="form-control" />
                                            </div>
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label><?php echo e(__('secondary_color')); ?></label>
                                                <input name="light_secondary_color" value="<?= isset($setting['light_secondary_color']) ? $setting['light_secondary_color'] : '' ?>" type="color" required class="form-control" />
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label><?php echo e(__('text_primary_color')); ?></label>
                                                <input name="light_text_primary_color" value="<?= isset($setting['light_text_primary_color']) ? $setting['light_text_primary_color'] : '' ?>" type="color" required class="form-control" />
                                            </div>
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label><?php echo e(__('text_secondary_color')); ?></label>
                                                <input name="light_text_secondary_color" value="<?= isset($setting['light_text_secondary_color']) ? $setting['light_text_secondary_color'] : '' ?>" type="color" required class="form-control" />
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label><?php echo e(__('header_logo')); ?> <small class="text-danger">(<?php echo e(__('size')); ?> 180 * 60)</small></label>
                                                <input name="light_header_logo" type="file" class="filepond">
                                            </div>
                                            <div class="form-group col-md-6 col-sm-12">
                                                <?php if(isset($setting['light_header_logo'])): ?>
                                                    <img src="<?php echo e(url(Storage::url($setting['light_header_logo']))); ?>" width="100" />
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label><?php echo e(__('footer_logo')); ?><small class="text-danger">(<?php echo e(__('size')); ?> 180 * 60)</small></label>
                                                <input name="light_footer_logo" type="file" class="filepond">
                                            </div>
                                            <div class="form-group col-md-6 col-sm-12">
                                                <?php if(isset($setting['light_footer_logo'])): ?>
                                                    <img src="<?php echo e(url(Storage::url($setting['light_footer_logo']))); ?>" height="100" />
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label><?php echo e(__('placeholder_image')); ?></label>
                                                <input name="light_placeholder_image" type="file" class="filepond">
                                            </div>
                                            <div class="form-group col-md-6 col-sm-12">
                                                <?php if(isset($setting['light_placeholder_image'])): ?>
                                                    <img src="<?php echo e(url(Storage::url($setting['light_placeholder_image']))); ?>" height="100" />
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="row text-center">
                                            <div class="col-md-12 col-sm-12">
                                                <h5 class="text-bold"><?php echo e(__('dark_theme')); ?></h5>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label><?php echo e(__('body_color')); ?></label>
                                                <input name="dark_body_color" value="<?= isset($setting['dark_body_color']) ? $setting['dark_body_color'] : '' ?>" type="color" required class="form-control" />
                                            </div>
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label><?php echo e(__('hover_color')); ?></label>
                                                <input name="dark_hover_color" value="<?= isset($setting['dark_hover_color']) ? $setting['dark_hover_color'] : '' ?>" type="color" required class="form-control" />
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label><?php echo e(__('primary_color')); ?></label>
                                                <input name="dark_primary_color" value="<?= isset($setting['dark_primary_color']) ? $setting['dark_primary_color'] : '' ?>" type="color" required class="form-control" />
                                            </div>
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label><?php echo e(__('secondary_color')); ?></label>
                                                <input name="dark_secondary_color" value="<?= isset($setting['dark_secondary_color']) ? $setting['dark_secondary_color'] : '' ?>" type="color" required class="form-control" />
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label><?php echo e(__('text_primary_color')); ?></label>
                                                <input name="dark_text_primary_color" value="<?= isset($setting['dark_text_primary_color']) ? $setting['dark_text_primary_color'] : '' ?>" type="color" required class="form-control" />
                                            </div>
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label><?php echo e(__('text_secondary_color')); ?></label>
                                                <input name="dark_text_secondary_color" value="<?= isset($setting['dark_text_secondary_color']) ? $setting['dark_text_secondary_color'] : '' ?>" type="color" required class="form-control" />
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label><?php echo e(__('header_logo')); ?> <small class="text-danger">(<?php echo e(__('size')); ?> 180 * 60)</small></label>
                                                <input name="dark_header_logo" type="file" class="filepond">
                                            </div>
                                            <div class="form-group col-md-6 col-sm-12">
                                                <?php if(isset($setting['dark_header_logo'])): ?>
                                                    <img src="<?php echo e(url(Storage::url($setting['dark_header_logo']))); ?>" width="100" />
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label><?php echo e(__('footer_logo')); ?><small class="text-danger">(<?php echo e(__('size')); ?> 180 * 60)</small></label>
                                                <input name="dark_footer_logo" type="file" class="filepond">
                                            </div>
                                            <div class="form-group col-md-6 col-sm-12">
                                                <?php if(isset($setting['dark_footer_logo'])): ?>
                                                    <img src="<?php echo e(url(Storage::url($setting['dark_footer_logo']))); ?>" height="100" />
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label><?php echo e(__('placeholder_image')); ?></label>
                                                <input name="dark_placeholder_image" type="file" class="filepond">
                                            </div>
                                            <div class="form-group col-md-6 col-sm-12">
                                                <?php if(isset($setting['dark_placeholder_image'])): ?>
                                                    <img src="<?php echo e(url(Storage::url($setting['dark_placeholder_image']))); ?>" height="100" />
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-3 col-sm-12">
                                        <label><?php echo e(__('favicon_icon')); ?></label>
                                        <input name="favicon_icon" type="file" class="filepond">
                                    </div>
                                    <div class="form-group col-md-3 col-sm-12">
                                        <?php if(isset($setting['favicon_icon']) && Storage::disk('public')->exists($setting['favicon_icon'])): ?>
                                            <img src="<?php echo e(url(Storage::url($setting['favicon_icon']))); ?>" height="100" />
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 col-sm-12">
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
            /* on change of accept_cookie mode btn - switchery js */
            var is_accept_cookie = document.querySelector('#is_accept_cookie');
            is_accept_cookie.onchange = function() {
                if (is_accept_cookie.checked)
                    $('#accept_cookie').val(1);
                else
                    $('#accept_cookie').val(0);
            };

        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/admin/resources/views/web-setting.blade.php ENDPATH**/ ?>