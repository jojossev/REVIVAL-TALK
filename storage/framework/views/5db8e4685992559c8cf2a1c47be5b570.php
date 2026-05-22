<?php $__env->startSection('title'); ?>
    <?php echo e(__('language')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><?php echo e(__('create_and_manage') . ' ' . __('language')); ?></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item text-dark">
                            <a href="<?php echo e(route('home')); ?>" class="text-dark"><i class="fas fa-home mr-1"></i><?php echo e(__('dashboard')); ?></a>
                        </li>
                        <li class="breadcrumb-item text-dark">
                            <a href="<?php echo e('system-settings'); ?>" class="text-dark"><i class="nav-icon fas fa-cogs mr-1"></i><?php echo e(__('system_setting')); ?></a>
                        </li>
                        <li class="breadcrumb-item active"><i class="fas fas fa-language mr-1"></i><?php echo e(__('language')); ?></li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('language-create')): ?>
                <div class="col-md-12 d-flex justify-content-end">
                    <button id="toggleButton" class="btn btn-primary mb-3 ml-1"><i class="fas fa-plus-circle mr-2"></i><?php echo e(__('create') . ' ' . __('language')); ?></button>
                </div>
                <?php endif; ?>
                <div class="col-md-12" id="add_card">
                    <div class="card card-secondary">
                        <div class="card-header">
                            <h3 class="card-title"><?php echo e(__('create') . ' ' . __('language')); ?></h3>
                        </div>
                        <form id="create_form" action="<?php echo e(route('language.store')); ?>" role="form" method="POST" enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-md-3 col-sm-12">
                                        <label class="required"><?php echo e(__('language')); ?></label>
                                        <input name="language" class="form-control" required>
                                    </div>
                                    <div class="form-group col-md-3 col-sm-12">
                                        <label class="required"><?php echo e(__('display_in_app_web')); ?></label>
                                        <input name="display_name" class="form-control" placeholder="<?php echo e(__("example:english")); ?>" required>
                                    </div>
                                    <div class="form-group col-md-3 col-sm-12">
                                        <label class="required"><?php echo e(__('code')); ?></label>
                                        <input id="code" name="code" class="form-control" placeholder="<?php echo e(__("example:en")); ?>" required>
                                    </div>
                                    <div class="form-group col-md-3 col-sm-12">
                                        <div class="form-check">
                                            <label><?php echo e(__('is_rtl')); ?></label>
                                            <div>
                                                <input type="checkbox" id="is_rtl_switch" name="is_rtl_switch" class="status-switch">
                                                <input type="hidden" id="isRTL" name="isRTL" value="0">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-3 col-sm-12">
                                        <label class="required"><?php echo e(__('app_web') . ' ' . __('json_file')); ?></label>
                                        <input name="file" type="file" accept="application/json" class="filepond-json logo" required>
                                        <a class="btn btn-info form-control" href="<?php echo e(url('download-app-web-json/en')); ?>"><?php echo e(__('download_sample_file')); ?></a>
                                    </div>
                                    <div class="form-group col-md-3 col-sm-12">
                                        <label class="required"><?php echo e(__('admin_panel') . ' ' . __('json_file')); ?></label>
                                        <input name="admin_json" type="file" accept="application/json" class="filepond-json logo" id="" required>
                                        <a class="btn btn-info form-control" href="<?php echo e(url('download-panel-json/en')); ?>"><?php echo e(__('download_sample_file')); ?></a>
                                    </div>
                                    <div class="form-group col-md-3 col-sm-12">
                                        <label class="required"><?php echo e(__('flag_image')); ?></label>
                                        <input name="flag" type="file" accept="image/*" class="filepond" required>
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
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('language-list')): ?>
                <div class="col-md-12">
                    <div class="card card-secondary">
                        <div class="card-header">
                            <h3 class="card-title"><?php echo e(__('language') . ' ' . __('list')); ?></h3>
                        </div>
                        <div class="card-body">
                            <table aria-describedby="mydesc" id='table' data-toggle="table" data-url="<?php echo e(route('languageList')); ?>" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-show-columns="true"
                                data-show-refresh="true" data-toolbar="#toolbar" data-mobile-responsive="true" data-buttons-class="primary" data-trim-on-search="false" data-sort-name="status" data-sort-order="desc"
                                data-query-params="queryParams">
                                <thead>
                                    <tr>
                                        <th scope="col" data-field="id" data-sortable="true"><?php echo e(__('id')); ?></th>
                                        <th scope="col" data-field="language" data-sortable="true"><?php echo e(__('language')); ?></th>
                                        <th scope="col" data-field="display_name"><?php echo e(__('display_name')); ?></th>
                                        <th scope="col" data-field="code"><?php echo e(__('code')); ?></th>
                                        <th scope="col" data-field="image"><?php echo e(__('flag_image')); ?></th>
                                        <th scope="col" data-field="default1"><?php echo e(__('default')); ?></th>
                                        <th scope="col" data-field="isRTL"><?php echo e(__('is_rtl')); ?></th>
                                        <th scope="col" data-field="status1"><?php echo e(__('status')); ?></th>
                                        <th scope="col" data-field="app_web_json" data-align="center"><?php echo e(__('app_web') . ' ' . __('json_file')); ?></th>
                                        <th scope="col" data-field="panel_json" data-align="center"><?php echo e(__('admin_panel') . ' ' . __('json_file')); ?></th>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['language-edit', 'language-delete'])): ?>
                                        <th scope="col" data-field="operate" data-events="actionEvents"><?php echo e(__('operate')); ?></th>
                                        <?php endif; ?>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                <div class="modal fade " id="editDataModal">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title"><?php echo e(__('edit') . ' ' . __('language')); ?></h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form id="update_form" action="<?php echo e(url('language')); ?>" role="form" method="POST" enctype="multipart/form-data">
                                <?php echo csrf_field(); ?>
                                <input type='hidden' name="edit_id" id="edit_id" value='' />
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="form-group col-sm-12 col-md-6">
                                            <label class="required"><?php echo e(__('language')); ?></label>
                                            <input id="edit_language" name="language" class="form-control" required>
                                        </div>
                                        <div class="form-group col-sm-12 col-md-6">
                                            <label class="required"><?php echo e(__('display_in_app_web')); ?></label>
                                            <input id="edit_display_name" name="display_name" class="form-control">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-12 col-md-6">
                                            <label class="required"><?php echo e(__('code')); ?></label>
                                            <input id="edit_code" name="code" class="form-control" required>
                                        </div>
                                        <div class="form-group col-sm-12 col-md-6">
                                            <label><?php echo e(__('is_rtl')); ?></label>
                                            <div>
                                                <input type="checkbox" id="edit_isRTL" name="is_rtl_switch" class="status-switch editInModel">
                                                <input type="hidden" id="isRTLedit" name="isRTL" value="0">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-12 col-md-6">
                                            <label><?php echo e(__('app_web') . ' ' . __('json_file')); ?></label>
                                            <input name="file" type="file" accept="application/json" class="filepond-json logo">
                                        </div>
                                        <div class="form-group col-sm-12 col-md-6">
                                            <label><?php echo e(__('admin_panel') . ' ' . __('json_file')); ?></label>
                                            <input name="edit_json_admin" type="file" accept="application/json" class="filepond-json logo" id="">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-12 col-md-6">
                                            <label><?php echo e(__('flag_image')); ?></label>
                                            <input name="flag" type="file" accept="image/*" class="filepond">
                                            <p style="display:none" id="img_error_msg_edit" class="alert alert-danger">
                                            </p>
                                        </div>
                                        <div id="is_default" class="form-group col-sm-12 col-md-6">
                                            <label><?php echo e(__('status')); ?></label><br>
                                            <div id="status1" class="btn-group">
                                                <label class="btn btn-success" data-toggle-class="btn-primary" data-toggle-passive-class="btn-default">
                                                    <input type="radio" name="status" value="1"><?php echo e(__('active')); ?>

                                                </label>
                                                <label class="btn btn-danger" data-toggle-class="btn-primary" data-toggle-passive-class="btn-default">
                                                    <input type="radio" name="status" value="0"><?php echo e(__('deactive')); ?>

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
        });
        var is_rtl_switch = document.querySelector('#is_rtl_switch');
        is_rtl_switch.onchange = function() {
            if (is_rtl_switch.checked) {
                $('#is_rtl').val(1);
            } else {
                $('#is_rtl').val(0);
            }
        };
        var _URL = window.URL || window.webkitURL;
        var isRTL = document.querySelector('#isRTL');
        isRTL.onchange = function() {
            if (isRTL.checked)
                $('#isRTL').val(1);
            else
                $('#isRTL').val(0);
        };
    </script>

    <script type="text/javascript">
        window.actionEvents = {
            'click .edit-data': function(e, value, row, index) {
                $('#edit_id').val(row.id);
                $('#edit_language').val(row.language);
                $('#edit_display_name').val(row.display_name);
                $('#edit_code').val(row.code);

                if (row.status == 0) {
                    $("input[name=status][value=0]").prop('checked', true);
                } else {
                    $("input[name=status][value=1]").prop('checked', true);
                }
                if (row.is_default) {
                    $('#is_default').hide();
                } else {
                    $('#is_default').show();
                }
                setTimeout(function() {
                    if (row.isRtl_value == 1) {
                        $(".editInModel").prop("checked", false).trigger("click");
                    } else {
                        $(".editInModel").prop("checked", true).trigger("click");
                    }
                }, 500);
            }
        }

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

    <script type="text/javascript">
        $(document).on('click', '.store_default_language', function() {
            var id = $(this).data("id");
            $.ajax({
                url: '<?php echo e(url('store_default_language')); ?>',
                type: "POST",
                dataType: "json",
                data: {
                    id: id
                },
                success: function(result) {
                    if (result) {
                        Swal.fire({
                            toast: true,
                            icon: "success",
                            title: result.message,
                            position: "top-end",
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            didOpen: (toast) => {
                                toast.addEventListener("mouseenter", Swal.stopTimer);
                                toast.addEventListener("mouseleave", Swal.resumeTimer);
                            },
                        });
                        $("#table").bootstrapTable("refresh");
                        setTimeout(() => {
                            location.reload();
                        }, 3000);
                    }
                }
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/admin/resources/views/language.blade.php ENDPATH**/ ?>