<?php $__env->startSection('title'); ?>
    <?php echo e(__('pages')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><?php echo e(__('create_and_manage') . ' ' . __('pages')); ?></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item text-dark">
                            <a href="<?php echo e(route('home')); ?>" class="text-dark"><i
                                    class="fas fa-home mr-1"></i><?php echo e(__('dashboard')); ?></a>
                        </li>
                        <li class="breadcrumb-item active"><i class="nav-icon fas fa-file mr-1"></i><?php echo e(__('pages')); ?></li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('page-create')): ?>
                    <div class="col-md-12 d-flex justify-content-end">
                        <button id="toggleButton" class="btn btn-primary mb-3 ml-1"><i
                                class="fas fa-plus-circle mr-2"></i><?php echo e(__('create') . ' ' . __('pages')); ?></button>
                    </div>
                <?php endif; ?>
                <div class="col-md-12" id="add_card">
                    <div class="card card-secondary">
                        <div class="card-header">
                            <h3 class="card-title"><?php echo e(__('create') . ' ' . __('pages')); ?></h3>
                        </div>
                        <form id="create_form" action="<?php echo e(route('pages.store')); ?>" role="form" method="POST"
                            enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 col-sm-12">
                                        <div class="form-group">
                                            <label class="required"><?php echo e(__('language')); ?></label>
                                            <select name="language" id="language" class="form-control" required>
                                                <?php if(count($languageList) > 1): ?>
                                                    <option value=""><?php echo e(__('select') . ' ' . __('language')); ?>

                                                    </option>
                                                <?php endif; ?>
                                                <?php $__currentLoopData = $languageList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($item->id); ?>" data-name="<?php echo e($item->language); ?>">
                                                        <?php echo e($item->language); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label class="required"><?php echo e(__('title')); ?></label>
                                            <input type="text" id="title" name="title" class="form-control"
                                                placeholder="<?php echo e(__('title')); ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label class="required"><?php echo e(__('page_type')); ?></label>
                                            <select id="page_type" name="page_type" required class="form-control">
                                                <option value=""><?php echo e(__('select') . ' ' . __('page_type')); ?></option>
                                                <option value="privacy-policy"><?php echo e(__('privacy_policy')); ?></option>
                                                <option value="terms-condition"><?php echo e(__('terms_condition')); ?></option>
                                                <option value="about-us"><?php echo e(__('about_us')); ?></option>
                                                <option value="contact-us"><?php echo e(__('contact_us')); ?></option>
                                                <option value="custom"><?php echo e(__('custom')); ?></option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label class="required"><?php echo e(__('slug')); ?></label><span
                                                class="">(<?php echo e(__('english_only')); ?>)</span>
                                            <input id="slug" name="slug" placeholder="<?php echo e(__('slug')); ?>" type="text"
                                                class="form-control" required>
                                            <span class="text-danger"><?php echo e(__('avoid_special_characters')); ?></span>
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-sm-12">
                                        <div class="form-group">
                                            <label><?php echo e(__('meta_keywords')); ?></label>
                                            <input id="meta_tags" name="meta_keyword" style="border-radius: 0.25rem"
                                                class="w-100" type="text"
                                                placeholder="<?php echo e(__('press_enter_add_keywords')); ?>">
                                        </div>
                                        <div class="form-group">
                                            <label><?php echo e(__('meta_title')); ?></label>
                                            <input type="text" name="meta_title" class="form-control" id="meta_title"
                                                oninput="getWordCount('meta_title','meta_title_count','19.9px arial')"
                                                placeholder="<?php echo e(__('meta_title')); ?>">
                                            <h6 id="meta_title_count">0</h6>
                                        </div>
                                        <div class="form-group">
                                            <label><?php echo e(__('meta_description')); ?></label>
                                            <textarea id="meta_description" name="meta_description" class="form-control"
                                                oninput="getWordCount('meta_description','meta_description_count','12.9px arial')"></textarea>
                                            <h6 id="meta_description_count">0</h6>
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-sm-12">
                                        <div class="form-group">
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
                                        <div class="form-group">
                                            <label><?php echo e(__('og_image')); ?></label>
                                            <input name="og_file" type="file" accept="image/*" class="filepond">
                                        </div>
                                        <div class="form-group">
                                            <label class="required"><?php echo e(__('page_icon')); ?></label>
                                            <input name="file" type="file" accept="image/*" class="filepond" required>
                                            <span class="text-danger"><?php echo e(__('note_for_page_icon')); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-12 col-sm-12">
                                        <label class="required"><?php echo e(__('page_content')); ?></label>
                                        <textarea id="page_content" name="page_content" class="form-control"
                                            required></textarea>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end">
                                    
                                        <button type="button" class="btn btn-outline-primary" id="generate_meta_fields">
                                            <i class="fas fa-magic"></i> <?php echo e(__('generate')); ?>

                                        </button>
                                        <button type="submit" class="btn btn-primary ml-2"><?php echo e(__('submit')); ?></button>
                                        
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('page-list')): ?>
                    <div class="col-md-12">
                        <div class="card card-secondary">
                            <div class="card-header">
                                <h3 class="card-title"><?php echo e(__('pages') . ' ' . __('list')); ?> </h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 col-sm-12">
                                        <label><?php echo e(__('language')); ?></label>
                                        <select id="filter_language_id" class="form-control">
                                            <option value="0"><?php echo e(__('select') . ' ' . __('language')); ?></option>
                                            <?php $__currentLoopData = $languageList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($item->id); ?>"><?php echo e($item->language); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                    <div class="col-md-4 col-sm-12">
                                        <label><?php echo e(__('page_type')); ?></label>
                                        <select id="filter_page_type" class="form-control">
                                            <option value=""><?php echo e(__('select') . ' ' . __('page_type')); ?></option>
                                            <option value="privacy-policy"><?php echo e(__('privacy_policy')); ?></option>
                                            <option value="terms-condition"><?php echo e(__('terms_condition')); ?></option>
                                            <option value="about-us"><?php echo e(__('about_us')); ?></option>
                                            <option value="contact-us"><?php echo e(__('contact_us')); ?></option>
                                            <option value="custom"><?php echo e(__('custom')); ?></option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 col-sm-12">
                                        <label><?php echo e(__('status')); ?></label>
                                        <select id="filter_status" class="form-control">
                                            <option value=""><?php echo e(__('status')); ?></option>
                                            <option value="1"><?php echo e(__('active')); ?></option>
                                            <option value="0"><?php echo e(__('deactive')); ?></option>
                                        </select>
                                    </div>
                                </div>
                                <table aria-describedby="mydesc" id='table' data-toggle="table"
                                    data-url="<?php echo e(route('pagesList')); ?>" data-click-to-select="true"
                                    data-side-pagination="server" data-pagination="true"
                                    data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-show-columns="true"
                                    data-show-refresh="true" data-toolbar="#toolbar" data-mobile-responsive="true"
                                    data-buttons-class="primary" data-trim-on-search="false" data-sort-name="id"
                                    data-sort-order="desc" data-query-params="pageQueryParams">
                                    <thead>
                                        <tr>
                                            <th scope="col" data-field="id" data-sortable="true"><?php echo e(__('id')); ?></th>
                                            <th scope="col" data-field="image"><?php echo e(__('image')); ?></th>
                                            <th scope="col" data-field="language"><?php echo e(__('language')); ?></th>
                                            <th scope="col" data-field="title" data-sortable="true"><?php echo e(__('title')); ?>

                                            </th>
                                            <th scope="col" data-field="slug"><?php echo e(__('slug')); ?></th>
                                            <th scope="col" data-field="page_type"><?php echo e(__('page_type')); ?></th>
                                            <th scope="col" data-field="status1"><?php echo e(__('status')); ?></th>
                                            <th scope="col" data-field="page_content" data-visible="false">
                                                <?php echo e(__('page_content')); ?>

                                            </th>
                                            <th scope="col" data-field="og_image" data-visible="false">
                                                <?php echo e(__('og_image')); ?>

                                            </th>
                                            <th scope="col" data-field="schema_markup" data-visible="false">
                                                <?php echo e(__('schema_markup')); ?>

                                            </th>
                                            <th scope="col" data-field="meta_keyword" data-visible="false">
                                                <?php echo e(__('meta_keywords')); ?>

                                            </th>
                                            <th scope="col" data-field="meta_title" data-visible="false">
                                                <?php echo e(__('meta_title')); ?>

                                            </th>
                                            <th scope="col" data-field="meta_description" data-visible="false">
                                                <?php echo e(__('meta_description')); ?>

                                            </th>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['page-edit', 'page-delete'])): ?>
                                                <th scope="col" data-field="operate" data-events="actionEvents">
                                                    <?php echo e(__('operate')); ?>

                                                </th>
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
        <div class="modal fade" id="editDataModal">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"><?php echo e(__('edit') . ' ' . __('pages')); ?></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="update_form" action="<?php echo e(url('pages')); ?>" role="form" method="POST"
                        enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>
                        <input type='hidden' name="edit_id" id="edit_id" value='' />
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="required"><?php echo e(__('language')); ?></label>
                                        <select id="edit_language" name="language" class="form-control" required>
                                            <option value=""><?php echo e(__('select') . ' ' . __('language')); ?></option>
                                            <?php $__currentLoopData = $languageList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($row->id); ?>"><?php echo e($row->language); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="required"><?php echo e(__('title')); ?></label>
                                        <input type="text" name="title" id="edit_title" class="form-control"
                                            placeholder="<?php echo e(__('title')); ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="required"><?php echo e(__('page_type')); ?></label>
                                        <select id="edit_page_type" name="page_type" required class="form-control">
                                            <option value=""><?php echo e(__('select') . ' ' . __('page_type')); ?></option>
                                            <option value="privacy-policy"><?php echo e(__('privacy_policy')); ?></option>
                                            <option value="terms-condition"><?php echo e(__('terms_condition')); ?></option>
                                            <option value="about-us"><?php echo e(__('about_us')); ?></option>
                                            <option value="contact-us"><?php echo e(__('contact_us')); ?></option>
                                            <option value="custom"><?php echo e(__('custom')); ?></option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="required"><?php echo e(__('slug')); ?></label><span
                                            class="">(<?php echo e(__('english_only')); ?>)</span>
                                        <input type="text" name="slug" id="edit_slug" class="form-control"
                                            placeholder="<?php echo e(__('slug')); ?>" required>
                                        <span class="text-danger"><?php echo e(__('avoid_special_characters')); ?></span>
                                    </div>

                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><?php echo e(__('meta_keywords')); ?></label>
                                        <input id="edit_meta_tags" style="border-radius: 0.25rem" class="w-100" type="text"
                                            name="meta_keyword" placeholder="<?php echo e(__('press_enter_add_keywords')); ?>">
                                    </div>
                                    <div class="form-group">
                                        <?php if (isset($component)) { $__componentOriginal1d7cb8c788a4883e2bea29fb16b78e97 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1d7cb8c788a4883e2bea29fb16b78e97 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.schema-markup-field','data' => ['id' => 'edit_schema_markup']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('schema-markup-field'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'edit_schema_markup']); ?>
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
                                    <div class="form-group">
                                        <label><?php echo e(__('meta_title')); ?></label>
                                        <input type="text" name="meta_title" class="form-control" id="edit_meta_title"
                                            oninput="getWordCount('edit_meta_title','edit_meta_title_count','19.9px arial')"
                                            placeholder="<?php echo e(__('meta_title')); ?>">
                                        <h6 id="edit_meta_title_count">0</h6>
                                    </div>
                                    <div class="form-group">
                                        <label><?php echo e(__('meta_description')); ?></label>
                                        <textarea id="edit_meta_description" name="meta_description" class="form-control"
                                            oninput="getWordCount('edit_meta_description','edit_meta_description_count','12.9px arial')"></textarea>
                                        <h6 id="edit_meta_description_count">0</h6>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><?php echo e(__('og_image')); ?></label>
                                        <input name="og_file" type="file" accept="image/*" class="filepond">
                                    </div>
                                    <div class="form-group">
                                        <label class="required"><?php echo e(__('page_icon')); ?></label>
                                        <input name="file" type="file" class="filepond">
                                        <span class="text-danger"><?php echo e(__('note_for_page_icon')); ?></span>
                                    </div>
                                    <div class="form-group">
                                        <label><?php echo e(__('status')); ?></label><br>
                                        <div id="status1" class="btn-group">
                                            <label class="btn btn-success" data-toggle-class="btn-primary"
                                                data-toggle-passive-class="btn-default">
                                                <input class="mr-1" type="radio" name="status" value="1"><?php echo e(__('active')); ?>

                                            </label>
                                            <label class="btn btn-danger" data-toggle-class="btn-primary"
                                                data-toggle-passive-class="btn-default">
                                                <input class="mr-1" type="radio" name="status"
                                                    value="0"><?php echo e(__('deactive')); ?>

                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12 col-sm-12">
                                    <label class="required"><?php echo e(__('page_content')); ?></label>
                                    <textarea id="edit_page_content" name="page_content" class="form-control"
                                        required></textarea>
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
    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script type="text/javascript">
        $(document).on('change', '#page_type', function () {
            var page_type = $('#page_type').val();
            if (page_type == 'custom') {
                $('#slug').val('').prop('readonly', false);
            } else {
                $('#slug').val(page_type).prop('readonly', true);
            }
        });
        $(document).on('change', '#edit_page_type', function () {
            var page_type = $('#edit_page_type').val();
            if (page_type == 'custom') {
                $('#edit_slug').prop('readonly', false);
            } else {
                $('#edit_slug').prop('readonly', true);
            }
        });
    </script>
    <script type="text/javascript">
        window.actionEvents = {
            'click .edit-data': function (e, value, row, index) {
                $('#edit_id').val(row.id);
                $("#edit_language").val(row.language_id).trigger('change');
                $("#edit_title").val(row.title);
                $("#edit_slug").val(row.slug);
                $('#edit_page_type').val(row.page_type).attr('readonly', row.readonly).trigger('change');
                var des1 = tinyMCE.get('edit_page_content').setContent(row.page_content);
                $('#edit_page_content').val(des1);
                $('#edit_meta_tags').val(row.meta_keyword);
                $('#edit_schema_markup').val(row.schema_markup);
                $('#edit_meta_description').val(row.meta_description);
                $('#edit_meta_title').val(row.meta_title);
                getWordCount('edit_meta_description', 'edit_meta_description_count', '12.9px arial');
                getWordCount('edit_meta_title', 'edit_meta_title_count', '19.9px arial');
                $("#edit_meta_keywords").val(row.meta_keywords);
                if (row.status == 0) {
                    $("input[name=status][value=0]").prop("checked", true);
                } else {
                    $("input[name=status][value=1]").prop('checked', true);
                }
                $("input[name=status]").prop('disabled', row.readonly);
            }
        };
    </script>
    <script type="text/javascript">
        $("#filter_language_id").on("change", function () {
            $('#table').bootstrapTable('refresh');
        });
        $("#filter_status").on("change", function () {
            $('#table').bootstrapTable('refresh');
        });
        $("#filter_page_type").on("change", function () {
            $('#table').bootstrapTable('refresh');
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function () {
            $(document).on('focusin', function (e) {
                if ($(e.target).closest(".tox-tinymce-aux, .moxman-window, .tam-assetmanager-root")
                    .length) {
                    e.stopImmediatePropagation();
                }
            });
            var base_url = "<?php echo e(url('/')); ?>";
            tinymce.init({
                selector: "#page_content, #edit_page_content",
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
                file_picker_callback: function (callback, value, meta) {
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
                            fd.append("page", 'pages');
                            // AJAX
                            jQuery.ajax({
                                url: base_url + "/upload_img",
                                type: "post",
                                data: fd,
                                contentType: false,
                                processData: false,
                                success: function (response) {
                                    const url = base_url + "/storage/" +
                                        response; // Adjust the URL path
                                    callback(url);
                                }
                            });
                            reader.onload = function (e) { };
                            reader.readAsDataURL(file);
                        });
                        input.click();
                    }
                },
                setup: function (editor) {
                    editor.on("change keyup", function (e) {
                        editor.save();
                        $(editor.getElement()).trigger('change');
                    });
                    editor.on('dragover drop', function (e) {
                        e.preventDefault(); // Prevent the default drag and drop behavior
                    });
                }
            });

        });
    </script>


    <script type="text/javascript">

        // generate with ai script starts here
        initMetaFieldGeneration({
            isEditForm: false,
            buttonSelector: '#generate_meta_fields',
            routeUrl: '<?php echo e(route('generate-all-meta-fields')); ?>',
            csrfToken: '<?php echo e(csrf_token()); ?>',
            titleSelector: '#title',
            languageSelector: '#language',
            includeDescription: false,
            includeSummarizedDescription: false,
            fieldMappings: {
                metaTags: '#meta_tags',
                metaTitle: '#meta_title',
                metaDescription: '#meta_description',
            },
            validationMessages: {
                selectLanguage: '<?php echo e(__('please_select_language_first')); ?>',
                enterTitle: '<?php echo e(__('please_enter_title_first')); ?>'
            }
        });
    </script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/admin/resources/views/pages.blade.php ENDPATH**/ ?>