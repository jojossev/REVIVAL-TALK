<?php $__env->startSection('title'); ?>
    <?php echo e(__('eNews')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><?php echo e(__('create_and_manage') . ' ' . __('eNews')); ?></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item text-dark">
                            <a href="<?php echo e(route('home')); ?>" class="text-dark"><i
                                    class="fas fa-home mr-1"></i><?php echo e(__('dashboard')); ?></a>
                        </li>
                        <li class="breadcrumb-item active"><i class="nav-icon fas fa-envelope mr-1"></i><?php echo e(__('eNews')); ?>

                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('enews-create')): ?>
                    <div class="col-md-12 d-flex justify-content-end">
                        <button id="toggleButton" class="btn btn-primary mb-3 ml-1"><i
                                class="fas fa-plus-circle mr-2"></i><?php echo e(__('create') . ' ' . __('eNews')); ?></button>
                    </div>
                <?php endif; ?>
                <div class="col-md-12" id="add_card">
                    <div class="card card-secondary">
                        <div class="card-header">
                            <h3 class="card-title"><?php echo e(__('create') . ' ' . __('eNews')); ?></h3>
                        </div>
                        <form id="create_form" action="<?php echo e(route('e-news.store')); ?>" role="form" method="POST"
                            enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-md-3 col-sm-12">
                                        <label class="required"><?php echo e(__('language')); ?></label>
                                        <select id="language" name="language" class="form-control" required>
                                            <?php if(count($languageList) > 1): ?>
                                                <option value=""><?php echo e(__('select') . ' ' . __('language')); ?></option>
                                            <?php endif; ?>
                                            <?php $__currentLoopData = $languageList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($item->id); ?>" data-name="<?php echo e($item->language); ?>">
                                                    <?php echo e($item->language); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3 col-sm-12">
                                        <label class="required"><?php echo e(__('title')); ?></label>
                                        <input id="title" name="title" required type="text" placeholder="<?php echo e(__('title')); ?>"
                                            class="form-control">
                                    </div>
                                    <div class="form-group col-md-3 col-sm-12">
                                        <label class="required"><?php echo e(__('slug')); ?></label><span
                                            class="">(<?php echo e(__('english_only')); ?>)</span>
                                        <input id="slug" name="slug" required type="text" placeholder="<?php echo e(__('slug')); ?>"
                                            class="form-control">
                                        <span class="text-danger"><?php echo e(__('avoid_special_characters')); ?></span>
                                    </div>
                                    <div class="form-group col-md-3 col-sm-12">
                                        
                                        <label class="required"><?php echo e(__('date')); ?> </label>
                                        <input name="date" type="date" class="form-control" min="<?php echo e(date('Y-m-d')); ?>" value="<?php echo e(date('Y-m-d')); ?>">
                                    </div>

                                    <div class="form-group col-md-3 col-sm-12">
                                        <?php if (isset($component)) { $__componentOriginal1d7cb8c788a4883e2bea29fb16b78e97 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1d7cb8c788a4883e2bea29fb16b78e97 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.schema-markup-field','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('schema-markup-field'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1d7cb8c788a4883e2bea29fb16b78e97)): ?>
<?php $attributes = $__attributesOriginal1d7cb8c788a4883e2bea29fb16b78e97; ?>
<?php unset($__attributesOriginal1d7cb8c788a4883e2bea29fb16b78e97); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1d7cb8c788a4883e2bea29fb16b78e97)): ?>
<?php $component = $__componentOriginal1d7cb8c788a4883e2bea29fb16b78e97; ?>
<?php unset($__componentOriginal1d7cb8c788a4883e2bea29fb16b78e97); ?>
<?php endif; ?>
                                    </div>
                                    <div class="form-group col-md-3 col-sm-12">
                                        <label><?php echo e(__('meta_keywords')); ?></label>
                                        <input id="meta_tags" style="border-radius: 0.25rem" class="w-100" type="text"
                                            name="meta_keyword" placeholder="<?php echo e(__('press_enter_add_keywords')); ?>">
                                    </div>
                                    <div class="form-group col-md-3 col-sm-12">
                                        <label><?php echo e(__('meta_title')); ?></label>
                                        <input type="text" name="meta_title" class="form-control" id="meta_title"
                                            oninput="getWordCount('meta_title','meta_title_count','19.9px arial')"
                                            placeholder="<?php echo e(__('meta_title')); ?>">
                                        <h6 id="meta_title_count">0</h6>
                                    </div>
                                    <div class="form-group col-md-3 col-sm-12">
                                        <label><?php echo e(__('meta_description')); ?></label>
                                        <textarea id="meta_description" name="meta_description" class="form-control"
                                            oninput="getWordCount('meta_description','meta_description_count','12.9px arial')"></textarea>
                                        <h6 id="meta_description_count">0</h6>
                                    </div>

                                    <div class="form-group col-md-3 col-sm-12">
                                        <label class="required"><?php echo e(__('thumbnail')); ?> </label>
                                        <input name="thumbnail" type="file" class="filepond">
                                    </div>
                                    <div class="form-group col-md-3 col-sm-12">
                                        <label class="required"><?php echo e(__('attachment (PDF)')); ?> </label>
                                        <input name="attachment" type="file" class="filepond-pdf" accept="application/pdf">
                                    </div>

                                    <div class="form-group  col-md-3 col-sm-12">
                                        <div class="form-check pl-0 form-switch d-flex align-items-center">
                                            <label class="mr-2"><?php echo e(__('status')); ?></label>
                                            <input type="hidden" id="status" name="status" value="1">
                                            <input class="form-check-input status-switch" type="checkbox" id="status_switch"
                                                name="status_switch" checked>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-12 col-sm-12">
                                        <label><?php echo e(__('description')); ?></label>
                                        <textarea id="des" name="description" class="form-control"></textarea>

                                    </div>
                                </div>
                            </div>
                            <div class="card-footer d-flex justify-content-end">
                                <button type="button" class="btn btn-outline-primary" id="generate_meta_fields">
                                    <i class="fas fa-magic"></i><?php echo e(__('generate')); ?>

                                    <button type="submit" class="btn btn-primary ml-2"><?php echo e(__('submit')); ?></button>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('enews-list')): ?>
                    <div class="col-md-12">
                        <div class="card card-secondary">
                            <div class="card-header">
                                <h3 class="card-title"><?php echo e(__('eNews') . ' ' . __('list')); ?></h3>
                            </div>
                            <div class="card-body">
                                <div id="toolbar">
                                    <select id="filter_language_id" name="language" class="form-control">
                                        <option value="0"><?php echo e(__('select') . ' ' . __('language')); ?></option>
                                        <?php $__currentLoopData = $languageList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($item->id); ?>"><?php echo e($item->language); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <table aria-describedby="mydesc" id='table' data-toggle="table"
                                    data-url="<?php echo e(route('e-news.show', 1)); ?>" data-click-to-select="true"
                                    data-side-pagination="server" data-pagination="true"
                                    data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-show-columns="true"
                                    data-show-refresh="true" data-toolbar="#toolbar" data-mobile-responsive="true"
                                    data-buttons-class="primary" data-trim-on-search="false" data-sort-name="id"
                                    data-sort-order="desc" data-query-params="eNewsQueryParams">
                                    <thead>
                                        <tr>
                                            <th scope="col" data-field="id" data-sortable="true"><?php echo e(__('id')); ?></th>
                                            <th scope="col" data-field="language_id" data-visible="false">
                                                <?php echo e(__('language_id')); ?>

                                            </th>
                                            <th scope="col" data-field="language" data-align="center"><?php echo e(__('language')); ?></th>
                                            <th scope="col" data-field="title"><?php echo e(__('title')); ?></th>
                                            <th scope="col" data-field="slug"><?php echo e(__('slug')); ?></th>
                                            <th scope="col" data-field="date" data-align="center" data-formatter="MM_DD_YYYY_dateFormate"><?php echo e(__('date')); ?></th>
                                            <th scope="col" data-field="thumbnail" data-align="center" data-formatter="generalImageFormatter"><?php echo e(__('thumbnail')); ?></th>
                                            <th scope="col" data-field="attachment" data-align="center" data-formatter="attachmentFormatter"><?php echo e(__('attachment')); ?></th>
                                            <th scope="col" data-field="status" data-align="center" data-formatter="eNewsStatusFormatter">
                                                <?php echo e(__('status')); ?></th>
                                            <th scope="col" data-field="schema_markup" data-visible="false">
                                                <?php echo e(__('schema_markup')); ?>

                                            </th>
                                            <th scope="col" data-field="meta_keyword" data-visible="false">
                                                <?php echo e(__('meta_keywords')); ?>

                                            </th>
                                            <th scope="col" data-field="description" data-visible="false">
                                                <?php echo e(__('meta_description')); ?>

                                            </th>
                                            <th scope="col" data-field="meta_title" data-visible="false">
                                                <?php echo e(__('meta_title')); ?>

                                            </th>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['enews-edit', 'enews-delete'])): ?>
                                                <th scope="col" data-field="operate" data-sortable="false" data-align="center"
                                                    data-events="eNewsEvents"><?php echo e(__('operate')); ?></th>
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
        <?php echo $__env->make('enews.editModal', ['languageList' => $languageList], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script type="text/javascript">
        function getSlug(data, title, slug) {
            var title1 = $(title).val();

            if (title1) {
                data['table'] = 'tbl_e_news';
                data['_token'] = "<?php echo e(csrf_token()); ?>";
                $.ajax({
                    url: '<?php echo e(route('get-slug')); ?>',
                    type: "POST",
                    data: data,
                    success: function (result) {
                        if (result) {
                            $(slug).val(result);
                        }
                    },
                    error: function (errors) {
                        console.log(errors);
                    },
                });
            } else {
                $(slug).val('');
            }
        }
        $(document).on('keyup', '#title', function (e) {
            var data = {
                'name': $('#title').val(),
            };
            getSlug(data, '#title', '#slug');
        });

        $(document).on('keyup', '#edit_title', function (e) {
            var data = {
                'name': $('#edit_title').val(),
                'id': $('#edit_id').val(),
            };
            getSlug(data, '#edit_title', '#edit_slug');
        });
    </script>

    <script type="text/javascript">
        $("#filter_language_id").on("change", function () {
            $('#table').bootstrapTable('refresh');
        });

    </script>
    
    <script type="text/javascript">
        $(document).ready(function () {
            // Initialize meta field generation for create form
            initMetaFieldGeneration({
                isEditForm: false,
                buttonSelector: '#generate_meta_fields',
                routeUrl: '<?php echo e(route("generate-all-meta-fields")); ?>',
                csrfToken: '<?php echo e(csrf_token()); ?>',
                titleSelector: '#title',
                languageSelector: '#language',
                includeDescription: true,
                fieldMappings: {
                    metaTags: '#meta_tags',
                    metaTitle: '#meta_title',
                    metaDescription: '#meta_description',
                    description: '#des',
                },
                validationMessages: {
                    selectLanguage: '<?php echo e(__('please_select_language_first')); ?>',
                    enterTitle: '<?php echo e(__('please_enter_title_first')); ?>'
                }
            });

        });

        var switcheryInstances = [];
        $(document).ready(function (e) {
            var elems = Array.prototype.slice.call(document.querySelectorAll(".status-switch"));
            elems.forEach(function (elem) {
                var switchery = new Switchery(elem, {
                    size: "small",
                    color: "#47C363",
                    secondaryColor: "#EB4141",
                    jackColor: "#ffff",
                    jackSecondaryColor: "#ffff",
                });
                switcheryInstances.push(switchery);
            });
        });

        var status_switch = document.querySelector('#status_switch');
        if (status_switch) {
            status_switch.onchange = function () {
                if (status_switch.checked) {
                    $('#status').val(1);
                } else {
                    $('#status').val(0);
                }
            };
        }

        var edit_status_switch = document.querySelector('#edit_status_switch');
        if (edit_status_switch) {
            edit_status_switch.onchange = function () {
                if (edit_status_switch.checked) {
                    $('#edit_status').val(1);
                } else {
                    $('#edit_status').val(0);
                }
            };
        }

        $(document).ready(function() {
            $(document).on('focusin', function(e) {
                if ($(e.target).closest(".tox-tinymce-aux, .moxman-window, .tam-assetmanager-root")
                    .length) {
                    e.stopImmediatePropagation();
                }
            });
            var base_url = "<?php echo e(url('/')); ?>";
            tinymce.init({
                selector: "#des, #edit_des",
                toolbar_mode: "wrap",
                height: 300,
                plugins: [
                    'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                    'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                    'insertdatetime', 'media', 'table', 'wordcount'
                ],
                toolbar: 'undo redo | blocks | bold italic backcolor | alignleft aligncenter alignright alignjustify bullist numlist outdent indent removeformat link image media',
                image_uploadtab: false,
                paste_data_images: false, // Disable image pasting
                images_upload_url: base_url + "/upload_img",
                relative_urls: false,
                remove_script_host: false,
                file_picker_types: 'image media',
                media_poster: false,
                media_alt_source: false,
                file_picker_callback: function(callback, value, meta) {
                    if (meta.filetype == "media" || meta.filetype == "image") {
                        const input = document.createElement('input');
                        input.setAttribute('type', 'file');
                        input.setAttribute('accept', 'image/* audio/* video/*');
                        input.addEventListener('change', (e) => {
                            const file = e.target.files[0];
                            var reader = new FileReader();
                            var fd = new FormData();
                            var files = file;
                            fd.append("file", files);
                            fd.append('filetype', meta.filetype);
                            fd.append("page", 'news');
                            // AJAX
                            jQuery.ajax({
                                url: base_url + "/upload_img",
                                type: "post",
                                data: fd,
                                contentType: false,
                                processData: false,
                                success: function(response) {
                                    const url = base_url + "/storage/" + response;
                                    callback(url);
                                }
                            });
                            reader.onload = function(e) {};
                            reader.readAsDataURL(file);
                        });
                        input.click();
                    }
                },
                setup: function(editor) {
                    editor.on("change keyup", function(e) {
                        editor.save();
                        $(editor.getElement()).trigger('change');
                    });
                    editor.on('dragover drop', function(e) {
                        e.preventDefault(); // Prevent the default drag and drop behavior
                    });
                }
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/admin/resources/views/enews/index.blade.php ENDPATH**/ ?>