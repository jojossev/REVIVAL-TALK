<?php $__env->startSection('title'); ?>
    <?php echo e(__('breaking_news')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><?php echo e(__('create_and_manage') . ' ' . __('breaking_news')); ?></h1>
                    <?php if(is_breaking_news_enabled() == 0): ?>
                        <label class="badge badge-danger"><?php echo e(__('disabled')); ?></label>
                    <?php endif; ?>
                </div>

                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item text-dark">
                            <a href="<?php echo e(route('home')); ?>" class="text-dark"><i
                                    class="fas fa-home mr-1"></i><?php echo e(__('dashboard')); ?></a>
                        </li>
                        <li class="breadcrumb-item active"><i
                                class="nav-icon fas fa-newspaper mr-1"></i><?php echo e(__('breaking_news')); ?></li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('breaking-news-create')): ?>
                    <div class="col-md-12 d-flex justify-content-end">
                        <button id="toggleButton" class="btn btn-primary mb-3 ml-1"><i
                                class="fas fa-plus-circle mr-2"></i><?php echo e(__('create') . ' ' . __('breaking_news')); ?></button>
                    </div>
                <?php endif; ?>
                <div class="col-md-12" id="add_card">
                    <div class="card card-secondary">
                        <div class="card-header">
                            <h3 class="card-title"><?php echo e(__('create') . ' ' . __('breaking_news')); ?></h3>
                        </div>
                        <form id="create_form" action="<?php echo e(url('breaking_news')); ?>" role="form" method="POST"
                            enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="required"><?php echo e(__('language')); ?></label>
                                            <select id="language" name="language" class="form-control" required>
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
                                            <input id="title" name="title" required type="text"
                                                placeholder="<?php echo e(__('title')); ?>" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label class="required"><?php echo e(__('slug')); ?></label><span
                                                class="">(<?php echo e(__('english_only')); ?>)</span>
                                            <input id="slug" name="slug" required type="text"
                                                placeholder="<?php echo e(__('slug')); ?>" class="form-control">
                                            <span class="text-danger"><?php echo e(__('avoid_special_characters')); ?></span>
                                        </div>
                                        <div class="form-group">
                                            <label class="required"><?php echo e(__('content_type')); ?></label>
                                            <select name="content_type" id="content_type" class="form-control" required>
                                                <option value="standard_post" selected><?php echo e(__('standard_post')); ?></option>
                                                <option value="video_youtube"><?php echo e(__('video_youtube')); ?></option>
                                                <option value="video_other"><?php echo e(__('video_other_url')); ?></option>
                                                <option value="video_upload"><?php echo e(__('video_upload')); ?></option>
                                            </select>
                                        </div>
                                        <div class="form-group video_youtube">
                                            <label class="required"><?php echo e(__('youtube_url')); ?></label>
                                            <input type="url" name="youtube_url" class="form-control">
                                        </div>
                                        <div class="form-group video_other">
                                            <label class="required"><?php echo e(__('other_url')); ?></label>
                                            <input type="url" name="other_url" class="form-control">
                                        </div>
                                        <div class="form-group video_upload">
                                            <label class="required"><?php echo e(__('video_uploads')); ?></label>
                                            <input name="video_file" type="file" class="filepond-video">
                                        </div>
                                        <div class="form-group">
                                            <label class="required"><?php echo e(__('image')); ?> </label>
                                            <input name="file" type="file" class="filepond" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label><?php echo e(__('meta_keywords')); ?></label>
                                            <input id="meta_tags" style="border-radius: 0.25rem" class="w-100"
                                                type="text" name="meta_keyword"
                                                placeholder="<?php echo e(__('press_enter_add_keywords')); ?>">
                                        </div>
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
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="required"><?php echo e(__('description')); ?></label>
                                            <textarea id="des" name="des" class="form-control"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label><?php echo e(__('summarized_description')); ?></label>
                                            
                                                <textarea name="summarized_description" id="summarized_description" cols="30" rows="2" class="form-control"></textarea>
                                        </div>
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
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('breaking-news-list')): ?>
                    <div class="col-md-12">
                        <div class="card card-secondary">
                            <div class="card-header">
                                <h3 class="card-title"><?php echo e(__('breaking_news') . ' ' . __('list')); ?></h3>
                            </div>
                            <div class="card-body">
                                <div id="toolbar" class="d-flex">
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('breaking-news-bulk-delete')): ?>
                                        <div class="mr-3">
                                            <button class="btn bg-primary text-white" type="submit"
                                                id="bulk_order_update"><?php echo e(__('bulk_delete')); ?></button>
                                        </div>
                                    <?php endif; ?>
                                    <div>
                                        <select id="filter_language_id" class="form-control">
                                            <option value="0"><?php echo e(__('select') . ' ' . __('language')); ?></option>
                                            <?php $__currentLoopData = $languageList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($row->id); ?>"><?php echo e($row->language); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                                <table aria-describedby="mydesc" id='table' data-toggle="table"
                                    data-url="<?php echo e(route('breakingNewsList')); ?>" data-click-to-select="true"
                                    data-side-pagination="server" data-pagination="true"
                                    data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-show-columns="true"
                                    data-show-refresh="true" data-toolbar="#toolbar" data-mobile-responsive="true"
                                    data-buttons-class="primary" data-trim-on-search="false" data-sort-name="id"
                                    data-sort-order="desc" data-query-params="queryParams">
                                    <thead>
                                        <tr>
                                            <th class="text-center multi-check" data-checkbox="true">
                                            <th scope="col" data-field="id" data-sortable="true"><?php echo e(__('id')); ?></th>
                                            <th scope="col" data-field="image" data-sortable="false"><?php echo e(__('image')); ?>

                                            </th>
                                            <th scope="col" data-field="language"><?php echo e(__('language')); ?></th>
                                            <th scope="col" data-field="title"><?php echo e(__('title')); ?></th>
                                            <th scope="col" data-field="slug" data-sortable="false"><?php echo e(__('slug')); ?>

                                            </th>
                                            <th scope="col" data-field="content_type"><?php echo e(__('content_type')); ?></th>
                                            <th scope="col" data-field="description"
                                                data-formatter="descriptionFormatter"><?php echo e(__('description')); ?></th>
                                            <th scope="col" data-field="views" data-sortable="false"><?php echo e(__('views')); ?>

                                            </th>
                                            <th scope="col" data-field="schema_markup" data-visible="false">
                                                <?php echo e(__('schema_markup')); ?></th>
                                            <th scope="col" data-field="meta_keyword" data-visible="false">
                                                <?php echo e(__('meta_keywords')); ?></th>
                                            <th scope="col" data-field="meta_title" data-visible="false">
                                                <?php echo e(__('meta_title')); ?></th>
                                            <th scope="col" data-field="meta_description" data-visible="false">
                                                <?php echo e(__('meta_description')); ?></th>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['breaking-news-edit', 'breaking-news-delete'])): ?>
                                                <th scope="col" data-field="operate" data-events="actionEvents">
                                                    <?php echo e(__('operate')); ?></th>
                                            <?php endif; ?>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="modal fade" id="editDataModal">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title"><?php echo e(__('edit') . ' ' . __('breaking_news')); ?></h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="update_form" action="<?php echo e(url('breaking_news')); ?>" role="form" method="POST"
                            enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>
                            <input type='hidden' name="edit_id" id="edit_id" value='' />
                            <input type='hidden' name="image_url" id="image_url" value='' />
                            <input type='hidden' name="video_url" id="video_url" value='' />

                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="required"><?php echo e(__('language')); ?></label>
                                            <select id="edit_language" name="language" class="form-control" required>
                                                <option value=""><?php echo e(__('select') . ' ' . __('language')); ?></option>
                                                <?php $__currentLoopData = $languageList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($item->id); ?>"><?php echo e($item->language); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label class="required"><?php echo e(__('title')); ?></label>
                                            <input id="edit_title" name="title" required type="text"
                                                class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label class="required"><?php echo e(__('slug')); ?></label><span
                                                class="">(<?php echo e(__('english_only')); ?>)</span>
                                            <input id="edit_slug" name="slug" required type="text"
                                                class="form-control">
                                            <span class="text-danger"><?php echo e(__('avoid_special_characters')); ?></span>
                                        </div>
                                        <div class="form-group">
                                            <label class="required"><?php echo e(__('content_type')); ?></label>
                                            <select name="content_type" id="edit_content_type" class="form-control"
                                                required>
                                                <option value="standard_post" selected><?php echo e(__('standard_post')); ?></option>
                                                <option value="video_youtube"><?php echo e(__('video_youtube')); ?></option>
                                                <option value="video_other"><?php echo e(__('video_other_url')); ?></option>
                                                <option value="video_upload"><?php echo e(__('video_upload')); ?></option>
                                            </select>
                                        </div>
                                        <div class="form-group evideo_youtube">
                                            <label class="required"><?php echo e(__('youtube_url')); ?></label>
                                            <input type="url" name="youtube_url" id="youtube_url"
                                                class="form-control">
                                        </div>
                                        <div class="form-group evideo_other">
                                            <label class="required"><?php echo e(__('other_url')); ?></label>
                                            <input type="url" name="other_url" id="other_url" class="form-control">
                                        </div>
                                        <div class="form-group evideo_upload">
                                            <label class="required"><?php echo e(__('video_uploads')); ?></label>
                                            <input name="video_file" type="file" class="filepond-video"
                                                id="exampleVideoInputFile1">
                                        </div>
                                        <div class="form-group">
                                            <label><?php echo e(__('meta_title')); ?></label>
                                            <input type="text" name="meta_title" class="form-control"
                                                id="edit_meta_title"
                                                oninput="getWordCount('edit_meta_title','edit_meta_title_count','19.9px arial')"
                                                placeholder="<?php echo e(__('meta_title')); ?>">
                                            <h6 id="edit_meta_title_count">0</h6>
                                        </div>
                                        <div class="form-group">
                                            <label class="required"><?php echo e(__('image')); ?></label>
                                            <input name="file" type="file" class="filepond">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?php echo e(__('meta_keywords')); ?></label>
                                            <input id="edit_meta_tags" style="border-radius: 0.25rem" class="w-100"
                                                type="text" name="meta_keyword"
                                                placeholder="<?php echo e(__('press_enter_add_keywords')); ?>">
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
                                            <label><?php echo e(__('meta_description')); ?></label>
                                            <textarea id="edit_meta_description" name="meta_description" class="form-control"
                                                oninput="getWordCount('edit_meta_description','edit_meta_description_count','12.9px arial')"></textarea>
                                            <h6 id="edit_meta_description_count">0</h6>
                                        </div>
                                        <div class="form-group">
                                            <label class="required"> <?php echo e(__('description')); ?></label>
                                            <textarea id="edit_des" name="des" class="form-control"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label ><?php echo e(__('summarized_description')); ?></label>
                                            <textarea name="summarized_description" id="edit_summarized_description" cols="30" rows="2" class="form-control"></textarea>
                                        </div>
                                    </div>

                                    

                                    
                                </div>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default"
                                    data-dismiss="modal"><?php echo e(__('close')); ?></button>
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
        function getSlug(data, title, slug) {
            var title1 = $(title).val();
            if (title1) {
                data['table'] = 'tbl_breaking_news';
                data['_token'] = "<?php echo e(csrf_token()); ?>";
                $.ajax({
                    url: '<?php echo e(route('get-slug')); ?>',
                    type: "POST",
                    data: data,
                    success: function(result) {
                        if (result) {
                            $(slug).val(result);
                        }
                    },
                    error: function(errors) {
                        console.log(errors);
                    },
                });
            } else {
                $(slug).val('');
            }
        }
        $(document).on('keyup', '#title', function(e) {
            var data = {
                'name': $('#title').val(),
            };
            getSlug(data, '#title', '#slug');
        });

        $(document).on('keyup', '#edit_title', function(e) {
            var data = {
                'name': $('#edit_title').val(),
                'id': $('#edit_id').val(),
            };
            getSlug(data, '#edit_title', '#edit_slug');
        });
    </script>
    <script type="text/javascript">
        window.actionEvents = {
            'click .edit-data': function(e, value, row, index) {
                $('#edit_id').val(row.id);
                $("#image_url").val(row.image_url);
                $("#edit_language").val(row.language_id);
                $("#edit_title").val(row.title);
                $('#edit_slug').val(row.slug);
                $("#edit_content_type").val(row.content).trigger('change');
                $('#edit_meta_tags').val(row.meta_keyword);
                $('#edit_schema_markup').val(row.schema_markup);
                $('#edit_meta_description').val(row.meta_description);
                $('#edit_meta_title').val(row.meta_title);
                getWordCount('edit_meta_description', 'edit_meta_description_count', '12.9px arial');
                getWordCount('edit_meta_title', 'edit_meta_title_count', '19.9px arial');
                var con_value = row.content_value;
                $('.evideo_youtube').hide();
                $('.evideo_other').hide();
                $('.evideo_upload').hide();
                if (row.content == "video_youtube") {
                    $('.evideo_youtube').show();
                    $('#youtube_url').val(con_value);
                } else if (row.content == "video_other") {
                    $('#other_url').val(con_value);
                    $('.evideo_other').show();
                } else if (row.content == "video_upload") {
                    $('.evideo_upload').show();
                    $("#video_url").val('public/images/breaking_news_video/' + con_value);
                }
                var des1 = tinyMCE.get('edit_des').setContent(row.description);
                $('#edit_des').val(des1);
                $('#edit_summarized_description').val(row.summarized_description);
            }
        };


        $("#filter_language_id").on("change", function() {
            $('#table').bootstrapTable('refresh');
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function(e) {
            $('.video_youtube').hide();
            $('.video_other').hide();
            $('.video_upload').hide();
        });
        $(document).on('change', '#content_type', function() {
            var type = $("#content_type").val();
            $('.video_youtube').hide();
            $('.video_other').hide();
            $('.video_upload').hide();
            if (type == "video_youtube") {
                $('.video_youtube').show();
            } else if (type == "video_other") {
                $('.video_other').show();
            } else if (type == "video_upload") {
                $('.video_upload').show();
            }
        });

        $(document).on('change', '#edit_content_type', function() {
            var type = $("#edit_content_type").val();
            $('.evideo_youtube').hide();
            $('.evideo_other').hide();
            $('.evideo_upload').hide();
            if (type == "video_youtube") {
                $('.evideo_youtube').show();
            } else if (type == "video_other") {
                $('.evideo_other').show();
            } else if (type == "video_upload") {
                $('.evideo_upload').show();
            }
        });
    </script>
    <script type="text/javascript">
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
                            fd.append("page", 'breaking_new');
                            // AJAX
                            jQuery.ajax({
                                url: base_url + "/upload_img",
                                type: "post",
                                data: fd,
                                contentType: false,
                                processData: false,
                                success: function(response) {
                                    const url = base_url + "/storage/" +
                                        response; // Adjust the URL path
                                    callback(url);
                                }
                            });
                            reader.onload = function(e) {
                                // Do something with the reader here if needed
                            };
                            reader.readAsDataURL(file);
                        });
                        input.click();
                    }
                },
                setup: function(editor) {
                    editor.on("change keyup", function(e) {
                        editor.save(); // updates this instance's textarea
                        $(editor.getElement()).trigger('change'); // for garlic to detect change
                    });
                    editor.on('dragover drop', function(e) {
                        e.preventDefault(); // Prevent the default drag and drop behavior
                    });
                }
            });
        });
    </script>
    <script type="text/javascript">
        $('#bulk_order_update').click(function() {
            var request_ids = [];
            selected = $('#table').bootstrapTable('getSelections');
            var arr = Object.values(selected);
            var i;
            var final_selection = [];
            var request_ids = arr.map(({
                id
            }) => id);
            console.log(request_ids);
            if (request_ids.length) {
                Swal.fire({
                    title: trans('are_you_sure'),
                    text: trans('you_wont_be_able_to_revert_this'),
                    icon: 'error',
                    showCancelButton: true,
                    confirmButtonText: trans('yes_proceed') || 'Yes, proceed',
                    cancelButtonText: 'Cancel',
                }).then((result) => {
                    if (result.value) {
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        $.ajax({
                            type: 'POST',
                            url: '<?php echo e(url('bulk_brecking_news_delete')); ?>',
                            data: {
                                request_ids: request_ids,
                            },
                            success: function(response) {
                                if (response.error == false) {
                                    showSuccessToast(response.message)
                                    $('#table').bootstrapTable('refresh');
                                } else {
                                    showErrorToast(response.message);
                                }
                            },
                            error: function(response) {
                                return showToastMessage(response.message, "error");
                            }
                        });
                    }
                });
            } else {
                var message = '<?php echo e(__('select_data_to_delete')); ?>';
                showErrorToast(message);
            }
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
            includeDescription: true,
            includeSummarizedDescription: true,
            fieldMappings: {
                metaTags: '#meta_tags',
                metaTitle: '#meta_title',
                metaDescription: '#meta_description',
                description: '#des',
                summarizedDescription: '#summarized_description',
            },
            validationMessages: {
                selectLanguage: '<?php echo e(__('please_select_language_first')); ?>',
                enterTitle: '<?php echo e(__('please_enter_title_first')); ?>'
            }
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/admin/resources/views/breaking-news.blade.php ENDPATH**/ ?>