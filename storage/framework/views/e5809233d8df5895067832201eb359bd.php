<?php $__env->startSection('title'); ?>
    <?php echo e(__('featured_section')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><?php echo e(__('create_and_manage') . ' ' . __('featured_section')); ?></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item text-dark">
                            <a href="<?php echo e(route('home')); ?>" class="text-dark"><i
                                    class="fas fa-home mr-1"></i><?php echo e(__('dashboard')); ?></a>
                        </li>
                        <li class="breadcrumb-item active"><i
                                class="nav-icon fas fa-layer-group mr-1"></i><?php echo e(__('featured_section')); ?></li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('featured-section-create')): ?>
                <div class="col-md-12 d-flex justify-content-end">
                    <button id="toggleButton" class="btn btn-primary mb-3 ml-1"><i
                            class="fas fa-plus-circle mr-2"></i><?php echo e(__('create') . ' ' . __('featured_section')); ?></button>
                </div>
                <?php endif; ?>
                <div class="col-md-12" id="add_card">
                    <div class="card card-secondary">
                        <div class="card-header">
                            <h3 class="card-title"><?php echo e(__('create') . ' ' . __('featured_section')); ?></h3>
                        </div>
                        <form id="create_form" action="<?php echo e(route('featured_sections.store')); ?>" role="form" method="POST"
                            enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 col-sm-12">
                                        <div class="form-group">
                                            <label class="required"><?php echo e(__('language')); ?></label>
                                            <select id="language_id" name="language_id" class="form-control" required>
                                                <option value=""><?php echo e(__('select') . ' ' . __('language')); ?></option>
                                                <?php if(count($languageList) >= 1): ?>
                                                    <?php $__currentLoopData = $languageList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($item->id); ?>" data-name="<?php echo e($item->language); ?>"><?php echo e($item->language); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php endif; ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label class="required"><?php echo e(__('title')); ?></label>
                                            <input id="title" name="title" required placeholder="<?php echo e(__('title')); ?>"
                                                type="text" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label class="required"><?php echo e(__('slug')); ?></label><span class="">(<?php echo e(__('english_only')); ?>)</span>
                                            <input id="slug" name="slug" required placeholder="<?php echo e(__('slug')); ?>"
                                                type="text" class="form-control">
                                                <span class="text-danger"><?php echo e(__('avoid_special_characters')); ?></span>
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
                                            <label><?php echo e(__('display_news_based_user_preference')); ?></label>
                                            <div>
                                                <input type="checkbox" id="is_based_on_user_choice"
                                                    name="is_based_on_user_choice" class="status-switch">
                                                <input type="hidden" id="based_on_user_choice_mode"
                                                    class="based_on_user_choice_mode" name="based_on_user_choice_mode"
                                                    value="0">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div id="filter_section">
                                            <div class="form-group">
                                                <label class="required"><?php echo e(__('news_type')); ?></label>
                                                <select id="news_type" name="news_type" class="form-control news_type"
                                                    required>
                                                    <option value=""><?php echo e(__('select') . ' ' . __('news_type')); ?>

                                                    </option>
                                                    <option value="news"><?php echo e(__('news')); ?></option>
                                                    <?php if(is_breaking_news_enabled() == 1): ?>
                                                        <option value="breaking_news"><?php echo e(__('breaking_news')); ?></option>
                                                    <?php endif; ?>
                                                    <option value="videos"><?php echo e(__('videos')); ?></option>
                                                    <option value="author_news"><?php echo e(__('author_news')); ?></option>
                                                    <option value="rss_feeds_news"><?php echo e(__('rss_feeds_news')); ?></option>
                                                </select>
                                            </div>
                                            <div class="form-group" id="videos_option" style="display: none">
                                                <label class="required"><?php echo e(__('which_videos_want_show')); ?></label>
                                                <select id="videos_type" name="videos_type" class="form-control">
                                                    <option value=""> <?php echo e(__('select_option')); ?></option>
                                                    <option value="news"><?php echo e(__('news')); ?></option>
                                                    <?php if(is_breaking_news_enabled() == 1): ?>
                                                        <option value="breaking_news"><?php echo e(__('breaking_news')); ?></option>
                                                    <?php endif; ?>
                                                </select>
                                            </div>
                                            <div class="form-group" id="filter_type_section">
                                                <label class="required"><?php echo e(__('type_of_filter')); ?></label>
                                                <select id="filter_type" name="filter_type" class="form-control filter_type"
                                                    required>
                                                    <option value=""><?php echo e(__('select_option')); ?></option>
                                                    <option value="most_commented" class="most_commented">
                                                        <?php echo e(__('most_commented')); ?></option>
                                                    <option value="recently_added"><?php echo e(__('recently_added')); ?></option>
                                                    <option value="most_viewed"><?php echo e(__('most_viewed')); ?></option>
                                                    <option value="most_favorite" class="most_favorite">
                                                        <?php echo e(__('most_favorite')); ?></option>
                                                    <option value="most_like" class="most_like"><?php echo e(__('most_like')); ?>

                                                    </option>
                                                    <option value="custom"><?php echo e(__('custom')); ?></option>
                                                </select>
                                            </div>
                                            
                                            <div class="form-group" id="author_news_type_section" style="display: none;">
                                                <label class="required"><?php echo e(__('authors')); ?></label>
                                                <select id="author_news_type" name="author_news_type[]" multiple="multiple" class="form-control author_news_type">

                                                </select>
                                            </div>

                                            <?php if(is_category_enabled() == 1): ?>
                                                <div id="filter_news" class="form-group">
                                                    <label class="required"><?php echo e(__('category')); ?></label>
                                                    <select id="category_ids" name="category_ids[]" class="form-control"
                                                        with="100%" multiple="multiple">
                                                        <option value="0" disabled>
                                                            <?php echo e(__('select') . ' ' . __('category')); ?></option>
                                                        <?php $__currentLoopData = $categoryList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="cat-<?php echo e($row->id); ?>"><?php echo e($row->category_name); ?></option>
                                                            <?php if(is_subcategory_enabled() == 1): ?>
                                                                <?php $__currentLoopData = $row->sub_categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row1): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <option value="subcat-<?php echo e($row1->id); ?>">--<?php echo e($row1->subcategory_name); ?></option>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                            <?php endif; ?>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                </div>
                                                <div id="filter_category_list" class="form-group" style="display: none;">
                                                    <label class="required"><?php echo e(__('category')); ?></label>
                                                    <select id="category_list" name="category_list[]" class="form-control"
                                                        with="100%" multiple="multiple">
                                                        <option value="0" disabled>
                                                            <?php echo e(__('select') . ' ' . __('category')); ?></option>
                                                    </select>
                                                </div>

                                                
                                                <div class="form-group" id="rss_feeds_news_section" style="display: none !important;">
                                                    <label class="required"><?php echo e(__('rss_feeds')); ?></label>
                                                    <i class="fa fa-question-circle" data-content="<?php echo e(__('please_select_1_or_2_rss_feeds_only')); ?>"></i>
                                                    <select id="rss_feeds_news_type" name="rss_feeds_news_type[]" class="form-control rss_feeds_news_type" style="width: 100%" multiple="multiple">
                                                        <option value=""><?php echo e(__('select') . ' ' . __('rss_feeds')); ?></option>
                                                    </select>
                                                </div>
                                            <?php endif; ?>
                                            <div class="form-group" id="custom" style="display: none">
                                                <label class="required"><?php echo e(__('news')); ?></label>
                                                <select id="news_ids" name="news_ids[]" class=" form-control"
                                                    multiple="multiple">
                                                </select>
                                            </div>
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
                                            <label class="required"><?php echo e(__('short_description')); ?></label>
                                            <input type="text" name="short_description" class="form-control"
                                                placeholder="<?php echo e(__('short_description')); ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label><?php echo e(__('meta_keywords')); ?></label>
                                            <input id="meta_tags" style="border-radius: 0.25rem" class="w-100"
                                                type="text" name="meta_keyword"
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
                                            <label><?php echo e(__('og_image')); ?> </label>
                                            <input name="file" type="file" class="filepond">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <div class="col-md-12 col-sm-12">
                                                <label class="required"><?php echo e(__('select_style_for_app_section')); ?></label>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-2 col-sm-2">
                                                <label class="radio-img">
                                                    <input type="radio" name="style_app" value="style_1" required
                                                        class="form-control" />
                                                    <img src="images/app_style/App_Style_1.png" alt="style_1"
                                                        class="style_image">
                                                </label>
                                            </div>
                                            <div class="col-md-2 col-sm-2">
                                                <label class="radio-img">
                                                    <input type="radio" name="style_app" value="style_2" />
                                                    <img src="images/app_style/App_Style_2.png" alt="style_2"
                                                        class="style_image">
                                                </label>
                                            </div>
                                            <div class="col-md-2 col-sm-2">
                                                <label class="radio-img">
                                                    <input type="radio" name="style_app" value="style_3" />
                                                    <img src="images/app_style/App_Style_3.png" alt="style_3"
                                                        class="style_image">
                                                </label>
                                            </div>
                                            <div class="col-md-2 col-sm-2">
                                                <label class="radio-img">
                                                    <input type="radio" name="style_app" value="style_4" />
                                                    <img src="images/app_style/App_Style_4.png" alt="style_4"
                                                        class="style_image">
                                                </label>
                                            </div>
                                            <div class="col-md-2 col-sm-2">
                                                <label class="radio-img">
                                                    <input type="radio" name="style_app" value="style_5" />
                                                    <img src="images/app_style/App_Style_5.png" alt="style_5"
                                                        class="style_image">
                                                </label>
                                            </div>
                                            <div class="col-md-2 col-sm-2">
                                                <label class="radio-img">
                                                    <input type="radio" name="style_app" value="style_6" />
                                                    <img src="images/app_style/App_Style_6.png" alt="style_6"
                                                        class="style_image">
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <div class="col-md-12 col-sm-12">
                                                <label class="required"><?php echo e(__('select_style_for_web_section')); ?></label>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-2 col-sm-2">
                                                <label class="radio-img-web">
                                                    <input type="radio" name="style_web" value="style_1"
                                                        class="form-control" required />
                                                    <img src="images/app_style/Web_Style_1.png" alt="style_1"
                                                        class="style_image">
                                                </label>
                                            </div>
                                            <div class="col-md-2 col-sm-2">
                                                <label class="radio-img-web">
                                                    <input type="radio" name="style_web" value="style_2" />
                                                    <img src="images/app_style/Web_Style_2.png" alt="style_2"
                                                        class="style_image">
                                                </label>
                                            </div>
                                            <div class="col-md-2 col-sm-2">
                                                <label class="radio-img-web">
                                                    <input type="radio" name="style_web" value="style_3" />
                                                    <img src="images/app_style/Web_Style_3.png" alt="style_3"
                                                        class="style_image">
                                                </label>
                                            </div>
                                            <div class="col-md-2 col-sm-2">
                                                <label class="radio-img-web">
                                                    <input type="radio" name="style_web" value="style_4" />
                                                    <img src="images/app_style/Web_Style_4.png" alt="style_4"
                                                        class="style_image">
                                                </label>
                                            </div>
                                            <div class="col-md-2 col-sm-2">
                                                <label class="radio-img-web">
                                                    <input type="radio" name="style_web" value="style_5" />
                                                    <img src="images/app_style/Web_Style_5.png" alt="style_5"
                                                        class="style_image">
                                                </label>
                                            </div>
                                            <div class="col-md-2 col-sm-2">
                                                <label class="radio-img-web">
                                                    <input type="radio" name="style_web" value="style_6" />
                                                    <img src="images/app_style/Web_Style_6.png" alt="style_6"
                                                        class="style_image">
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer d-flex justify-content-end">
                                <button type="button" class="btn btn-outline-primary" id="generate_meta_fields">
                                    <i class="fas fa-magic"></i> <?php echo e(__('generate')); ?>

                                </button>
                                <button type="submit" class="btn btn-primary ml-2"><?php echo e(__('submit')); ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('featured-section-list')): ?>
            <div class="row">
                <div class="col-lg-9 col-md-12 col-sm-12">
                    <div class="card card-secondary">
                        <div class="card-header">
                            <h3 class="card-title"><?php echo e(__('featured_section') . ' ' . __('list')); ?></h3>
                        </div>
                        <div class="card-body">
                            <div id="toolbar" class="d-flex">
                                <div class="mr-3">
                                    <select id="filter_language_id" class="form-control">
                                        <option value="0"><?php echo e(__('select') . ' ' . __('language')); ?></option>
                                        <?php $__currentLoopData = $languageList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($row->id); ?>"><?php echo e($row->language); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <div>
                                    <select id="filter_status" class="form-control">
                                        <option value=""><?php echo e(__('select') . ' ' . __('status')); ?></option>
                                        <option value="1"><?php echo e(__('active')); ?></option>
                                        <option value="0"><?php echo e(__('deactive')); ?></option>
                                    </select>
                                </div>
                            </div>
                            <table aria-describedby="mydesc" id='table' data-toggle="table"
                                data-url="<?php echo e(route('featuredSectionList')); ?>" data-click-to-select="true"
                                data-side-pagination="server" data-pagination="true"
                                data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-show-columns="true"
                                data-show-refresh="true" data-toolbar="#toolbar" data-mobile-responsive="true"
                                data-buttons-class="primary" data-trim-on-search="false" data-sort-name="row_order"
                                data-sort-order="asc" data-query-params="featuredSectionQueryParams">
                                <thead>
                                    <tr>
                                        <th scope="col" data-field="id" data-sortable="true"><?php echo e(__('id')); ?></th>
                                        <th scope="col" data-field="language"><?php echo e(__('language')); ?></th>
                                        <th scope="col" data-field="title" data-sortable="false"><?php echo e(__('title')); ?>

                                        </th>
                                        <th scope="col" data-field="slug" data-sortable="false"><?php echo e(__('slug')); ?>

                                        </th>
                                        <th scope="col" data-field="news_type_badge"><?php echo e(__('news_type')); ?></th>
                                        <th scope="col" data-field="rss_feeds_badge"><?php echo e(__('rss_feeds')); ?></th>
                                        <th scope="col" data-field="video_type_badge"><?php echo e(__('video_type')); ?></th>
                                        <th scope="col" data-field="filter_type_badge"><?php echo e(__('type_of_filter')); ?></th>
                                        <th scope="col" data-field="style_app"><?php echo e(__('app_style')); ?></th>
                                        <th scope="col" data-field="style_web"><?php echo e(__('web_style')); ?></th>
                                        <th scope="col" data-field="status1"><?php echo e(__('status')); ?></th>
                                        <th scope="col" data-field="short_description" data-visible="false">
                                            <?php echo e(__('short_description')); ?></th>
                                        <th scope="col" data-field="schema_markup" data-visible="false">
                                            <?php echo e(__('schema_markup')); ?></th>
                                        <th scope="col" data-field="meta_keyword" data-visible="false">
                                            <?php echo e(__('meta_keywords')); ?></th>
                                        <th scope="col" data-field="og_image" data-visible="false">
                                            <?php echo e(__('og_image')); ?></th>
                                        <th scope="col" data-field="meta_title" data-visible="false">
                                            <?php echo e(__('meta_title')); ?></th>
                                        <th scope="col" data-field="meta_description" data-visible="false">
                                            <?php echo e(__('meta_description')); ?></th>
                                        <th scope="col" data-field="row_order" data-sortable="true">
                                            <?php echo e(__('row_order')); ?></th>
                                        <th scope="col" data-field="created_at" data-visible="false">
                                            <?php echo e(__('created_at')); ?></th>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['featured-section-edit', 'featured-section-delete'])): ?>
                                        <th scope="col" data-field="operate" data-events="actionEvents">
                                            <?php echo e(__('operate')); ?></th>
                                        <?php endif; ?>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('featured-section-order-create')): ?>
                <div class="col-lg-3 col-md-12 col-sm-12">
                    <div class="card card-secondary">
                        <div class="card-header">
                            <h3 class="card-title"><?php echo e(__('featured_section') . ' ' . __('order')); ?></h3>
                        </div>
                        <form id="order_form" action="<?php echo e(route('update_featured_sections_order')); ?>" method="post"
                            onsubmit="return saveOrder()">
                            <?php echo csrf_field(); ?>
                            <div class="card-body">
                                <div class="form-group col-md-12 col-sm-12">
                                    <select id="order_language_id" class="form-control">
                                        <option value="0"><?php echo e(__('select') . ' ' . __('language')); ?></option>
                                        <?php $__currentLoopData = $languageList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($item->id); ?>"><?php echo e($item->language); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <div class="col-md-12 col-sm-12">
                                    <input id="row_order" name="row_order" type="hidden">
                                    <ol id="sortable-row">
                                        <?php $__currentLoopData = $featuredList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li id="<?php echo e($row->id); ?>"><?php echo e($row->title); ?></li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ol>
                                </div>
                                <button type="submit" class="btn btn-primary float-right"><?php echo e(__('submit')); ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
        <div class="modal fade" id="editDataModal">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"><?php echo e(__('edit') . ' ' . __('featured_section')); ?></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="update_form" action="<?php echo e(url('featured_sections')); ?>" role="form" method="POST"
                        enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <input type='hidden' name="edit_id" id="edit_id" value='' />
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="required"><?php echo e(__('language')); ?></label>
                                        <select id="edit_language_id" name="language_id" class="form-control language_id"
                                            required>
                                            <option disabled><?php echo e(__('select') . ' ' . __('language')); ?></option>
                                            <?php $__currentLoopData = $languageList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($item->id); ?>"><?php echo e($item->language); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="required"><?php echo e(__('title')); ?></label>
                                        <input id="edit_title" name="title" type="text" required
                                            class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label class="required"><?php echo e(__('slug')); ?></label><span class="">(<?php echo e(__('english_only')); ?>)</span>
                                        <input id="edit_slug" name="slug" type="text" required
                                            class="form-control">
                                        <span class="text-danger"><?php echo e(__('avoid_special_characters')); ?></span>
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
                                        <label><?php echo e(__('display_news_based_user_preference')); ?></label>
                                        <div>
                                            <input type="checkbox" id="edit_is_based_on_user_choice"
                                                name="edit_is_based_on_user_choice" class="status-switch editInModel">
                                            <input type="hidden" id="edit_based_on_user_choice_mode"
                                                class="edit_based_on_user_choice_mode"
                                                name="edit_based_on_user_choice_mode" value="1">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div id="edit_filter_section">
                                        <div class="form-group">
                                            <label class="required"><?php echo e(__('news_type')); ?></label>
                                            <select id="edit_news_type" name="news_type" class="form-control">
                                                <option value="news"><?php echo e(__('news')); ?></option>
                                                <?php if(is_breaking_news_enabled() == 1): ?>
                                                    <option value="breaking_news"><?php echo e(__('breaking_news')); ?></option>
                                                <?php endif; ?>
                                                <option value="videos"><?php echo e(__('videos')); ?></option>
                                                <option value="author_news"><?php echo e(__('author_news')); ?></option>
                                                <option value="rss_feeds_news"><?php echo e(__('rss_feeds_news')); ?></option>
                                            </select>
                                        </div>
                                        <div id="edit_author_news_type_section" class="form-group">

                                            <label class="required"> <?php echo e(__('authors')); ?></label>
                                            <select id="edit_author_news_type" name="author_news_type[]"  multiple="multiple" class="form-control"
                                                multiple="multiple">
                                            </select>

                                        </div>
                                        <div id="edit_videos_option" class="form-group">
                                            <label><?php echo e(__('which_videos_want_show')); ?></label>
                                            <select id="edit_videos_type" name="videos_type" class="form-control">
                                                <option value=""><?php echo e(__('select')); ?></option>
                                                <option value="news"><?php echo e(__('news')); ?></option>
                                                <?php if(is_breaking_news_enabled() == 1): ?>
                                                    <option value="breaking_news"><?php echo e(__('breaking_news')); ?></option>
                                                <?php endif; ?>
                                            </select>
                                        </div>
                                        <div class="form-group" id="edit_filter_type_section">
                                            <label class="required"><?php echo e(__('type_of_filter')); ?></label>
                                            <select id="edit_filter_type" name="filter_type"
                                                class="form-control filter_type">
                                                <option value=""><?php echo e(__('select_option')); ?></option>
                                                <option value="most_commented" class="most_commented">
                                                    <?php echo e(__('most_commented')); ?></option>
                                                <option value="recently_added"><?php echo e(__('recently_added')); ?></option>
                                                <option value="most_viewed"><?php echo e(__('most_viewed')); ?></option>
                                                <option value="most_favorite" class="most_favorite">
                                                    <?php echo e(__('most_favorite')); ?></option>
                                                <option value="most_like" class="most_like"><?php echo e(__('most_like')); ?>

                                                </option>
                                                <option value="custom"><?php echo e(__('custom')); ?></option>
                                            </select>
                                        </div>
                                        <div class="form-group" id="edit_filter_news_section">
                                            <?php if(is_category_enabled() == 1): ?>
                                                <div id="edit_filter_news" class="form-group">
                                                    <label class="required"><?php echo e(__('category')); ?></label>
                                                    <select id="edit_category_ids" name="category_ids[]"
                                                        class="form-control" multiple="multiple">
                                                    </select>
                                                </div>
                                                <div id="edit_filter_category_list" class="form-group" style="display: none;">
                                                    <label class="required"><?php echo e(__('category List')); ?></label>
                                                    <select id="edit_category_list" name="category_list[]" class="form-control"
                                                        with="100%" multiple="multiple">
                                                        <option value="0" disabled>
                                                            <?php echo e(__('select') . ' ' . __('category')); ?></option>
                                                    </select>
                                                </div>

                                                
                                                <div class="form-group" id="edit_rss_feeds_news_section" style="display: none;">
                                                    <label class="required"><?php echo e(__('rss_feeds')); ?></label>
                                                    <i class="fa fa-question-circle" data-content="<?php echo e(__('please_select_1_or_2_rss_feeds_only')); ?>"></i>
                                                    <select id="edit_rss_feeds_news_type" name="rss_feeds_news_type[]" class="form-control rss_feeds_news_type" style="width: 100%" multiple="multiple">
                                                        <option value=""><?php echo e(__('select') . ' ' . __('rss_feeds')); ?></option>
                                                    </select>
                                                </div>
                                            <?php endif; ?>
                                            <div id="edit_custom" class="form-group">
                                                <label class="required"> <?php echo e(__('news')); ?></label>
                                                <select id="edit_news_ids" name="news_ids[]" class="form-control"
                                                    multiple="multiple">
                                                </select>
                                            </div>
                                        </div>
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
                                        <label class="required"><?php echo e(__('short_description')); ?></label>
                                        <input type="text" id="edit_short_description" name="short_description"
                                            class="form-control" placeholder="<?php echo e(__('short_description')); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label><?php echo e(__('meta_keywords')); ?></label>
                                        <input id="edit_meta_tags" class="w-100" type="text" name="meta_keyword"
                                            style="border-radius: 0.25rem"
                                            placeholder="<?php echo e(__('press_enter_add_keywords')); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label><?php echo e(__('meta_title')); ?></label>
                                        <input id="edit_meta_title" name="meta_title" type="text"
                                            oninput="getWordCount('edit_meta_title','edit_meta_title_count','19.9px arial')"
                                            placeholder="<?php echo e(__('meta_title')); ?>" class="form-control">
                                        <h6 id="edit_meta_title_count">0</h6>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label><?php echo e(__('og_image')); ?> </label>
                                            <input name="file" type="file" class="filepond">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-6 row">
                                    <div class="col-lg-12">
                                        <label class="required"><?php echo e(__('select_style_for_app_section')); ?></label>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="radio-img edit-radio-img">
                                            <input type="radio" name="style_app" value="style_1" required />
                                            <img src="images/app_style/App_Style_1.png" alt="style_1"
                                                class="style_image">
                                        </label>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="radio-img edit-radio-img">
                                            <input type="radio" name="style_app" value="style_2" />
                                            <img src="images/app_style/App_Style_2.png" alt="style_2"
                                                class="style_image">
                                        </label>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="radio-img edit-radio-img">
                                            <input type="radio" name="style_app" value="style_3" />
                                            <img src="images/app_style/App_Style_3.png" alt="style_3"
                                                class="style_image">
                                        </label>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="radio-img edit-radio-img">
                                            <input type="radio" name="style_app" value="style_4" />
                                            <img src="images/app_style/App_Style_4.png" alt="style_4"
                                                class="style_image">
                                        </label>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="radio-img edit-radio-img">
                                            <input type="radio" name="style_app" value="style_5" />
                                            <img src="images/app_style/App_Style_5.png" alt="style_5"
                                                class="style_image">
                                        </label>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="radio-img edit-radio-img">
                                            <input type="radio" name="style_app" value="style_6" />
                                            <img src="images/app_style/App_Style_6.png" alt="style_6"
                                                class="style_image">
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6 row">
                                    <div class="col-lg-12">
                                        <label class="required"> <?php echo e(__('select_style_for_web_section')); ?></label>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="radio-img edit-radio-img-web">
                                            <input type="radio" name="style_web" value="style_1" required />
                                            <img src="images/app_style/Web_Style_1.png" alt="style_1"
                                                class="style_image">
                                        </label>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="radio-img edit-radio-img-web">
                                            <input type="radio" name="style_web" value="style_2" />
                                            <img src="images/app_style/Web_Style_2.png" alt="style_2"
                                                class="style_image">
                                        </label>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="radio-img edit-radio-img-web">
                                            <input type="radio" name="style_web" value="style_3" />
                                            <img src="images/app_style/Web_Style_3.png" alt="style_3"
                                                class="style_image">
                                        </label>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="radio-img edit-radio-img-web">
                                            <input type="radio" name="style_web" value="style_4" />
                                            <img src="images/app_style/Web_Style_4.png" alt="style_4"
                                                class="style_image">
                                        </label>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="radio-img edit-radio-img-web">
                                            <input type="radio" name="style_web" value="style_5" />
                                            <img src="images/app_style/Web_Style_5.png" alt="style_5"
                                                class="style_image">
                                        </label>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="radio-img edit-radio-img-web">
                                            <input type="radio" name="style_web" value="style_6" />
                                            <img src="images/app_style/Web_Style_6.png" alt="style_6"
                                                class="style_image">
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <label class="control-label"> <?php echo e(__('status')); ?></label>
                                <div id="status1" class="btn-group">
                                    <label class="btn btn-success" data-toggle-class="btn-primary"
                                        data-toggle-passive-class="btn-default">
                                        <input class="mr-1" type="radio" name="status"
                                            value="1"><?php echo e(__('active')); ?>

                                    </label>
                                    <label class="btn btn-danger" data-toggle-class="btn-primary"
                                        data-toggle-passive-class="btn-default">
                                        <input class="mr-1" type="radio" name="status"
                                            value="0"><?php echo e(__('deactive')); ?>

                                    </label>
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
            data['table'] = 'tbl_featured_sections';
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
            'name':  $('#title').val(),
        };
        getSlug(data, '#title', '#slug');
    });

    $(document).on('keyup', '#edit_title', function(e) {
        var data = {
            'name':  $('#edit_title').val(),
            'id':  $('#edit_id').val(),
        };
        getSlug(data, '#edit_title', '#edit_slug');
    });
</script>
    <script type="text/javascript">
        window.actionEvents = {
            'click .edit-data': function(e, value, row, index) {
                $("#edit_language_id").val(row.language_id).trigger('change', [row.language_id, row.category_id, row
                    .subcategory_id, row.news_id, row.filter_type, row.news_type, row.user_ids, row.category_list, row.rss_feeds_news_type
                ]);
                $('#edit_id').val(row.id);
                $("#edit_title").val(row.title);
                $('#edit_slug').val(row.slug);
                $("#edit_schema_markup").val(row.schema_markup);
                $("#edit_videos_type").val(row.videos_type);
                if (row.news_type == "videos") {
                    $('#edit_videos_option').show();
                } else {
                    $('#edit_videos_option').hide();
                }
                // Handle author_news type on modal open
                if (row.news_type == "author_news") {
                    $('#edit_author_news_type_section').show();
                    $('#edit_filter_type_section').hide();
                    $('#edit_filter_news').hide();
                } else {
                    $('#edit_author_news_type_section').hide();
                    $('#edit_filter_type_section').show();
                    if (row.news_type != 'breaking_news') {
                        $('#edit_filter_news').show();
                    }
                }

                // if(row.news_type == "rss_feeds_news"){
                //     // working
                //     // console.log("if edit");

                //     $('#edit_filter_category_list').show();
                //     $('#edit_rss_feeds_news_section').show();
                // } else {
                //     $('#edit_filter_category_list').hide();
                //     $('#edit_rss_feeds_news_section').hide();
                // }

                $("#edit_short_description").val(row.short_description_full);
                $('#edit_meta_description').val(row.meta_description);
                $('#edit_meta_title').val(row.meta_title);
                $("#edit_meta_tags").val(row.meta_keyword);
                getWordCount('edit_meta_description', 'edit_meta_description_count', '12.9px arial');
                getWordCount('edit_meta_title', 'edit_meta_title_count', '19.9px arial');
                if (row.status == 0) {
                    $("input[name=status][value=0]").prop('checked', true);
                } else {
                    $("input[name=status][value=1]").prop('checked', true);
                }
                setTimeout(function() {
                    if (row.is_based_on_user_choice == "1") {
                        $(".editInModel").prop("checked", false).trigger("click");
                    } else {
                        $(".editInModel").prop("checked", true).trigger("click");
                    }
                }, 1000);
                $("input[name=style_app][value=" + row.style_app_edit + "]").prop('checked', true);
                $("input[name=style_web][value=" + row.style_web_edit + "]").prop('checked', true);
            },
        };
    </script>

    <script type="text/javascript">
        $("#filter_language_id").on("change", function() {
            $('#table').bootstrapTable('refresh');
        });
        $("#filter_status").on("change", function() {
            $('#table').bootstrapTable('refresh');
        });

        // function queryParams(p) {
        //     return {
        //         limit: p.limit,
        //         order: p.order,
        //         offset: p.offset,
        //         search: p.search,
        //         language_id: $('#filter_language_id').val(),
        //         status: $('#filter_status').val(),
        //     };
        // }
    </script>

    <script type="text/javascript">
        function saveOrder() {
            var selectedLanguage = new Array();
            $('ol#sortable-row li').each(function() {
                selectedLanguage.push($(this).attr("id"));
            });
            document.getElementById("row_order").value = selectedLanguage;
        }

        $('#order_language_id').on('change', function(e) {
            var language_id = $('#order_language_id').val();
            var url = '<?php echo e(route('get_feature_section_by_language')); ?>';
            var data = {
                language_id: language_id,
                sortable: 1
            };
            fetchList(url, data, '#sortable-row');
        });

        $(function() {
            $("#sortable-row").sortable();

            $('.videos').hide();

            // Ensure RSS feeds section is hidden on page load
            $('#rss_feeds_news_section').hide();

            $('#category_ids').select2({
                placeholder: '<?php echo e(__('select') . ' ' . __('category')); ?>'
            });
            $('#author_news_type').select2({
                placeholder: '<?php echo e(__('select') . ' ' . __('author_news')); ?>'
            });
            $('#news_ids').select2({
                placeholder: '<?php echo e(__('select') . ' ' . __('news')); ?>'
            });

            $('#category_list').select2({
                placeholder: '<?php echo e(__('select') . ' ' . __('category')); ?>',
                maximumSelectionLength: 3
            });
            // Initialize RSS feeds Select2 (works even when parent is hidden)
            $('#rss_feeds_news_type').select2({
                placeholder: '<?php echo e(__('select') . ' ' . __('rss_feeds')); ?>',
                width: '100%',
                maximumSelectionLength: 3
            });
            // Ensure RSS feeds section stays hidden after Select2 initialization
            $('#rss_feeds_news_section').hide();
            $('#edit_category_ids').select2({
                placeholder: '<?php echo e(__('select') . ' ' . __('category')); ?>'
            });
            $('#edit_news_ids').select2({
                placeholder: '<?php echo e(__('select') . ' ' . __('news')); ?>'
            });

            $('#edit_author_news_type').select2({
                placeholder: '<?php echo e(__('select') . ' ' . __('author_news')); ?>'
            });
            $('#edit_category_list').select2({
                placeholder: '<?php echo e(__('select') . ' ' . __('category')); ?>',
                maximumSelectionLength: 3
            });
            $('#edit_rss_feeds_news_type').select2({
                placeholder: '<?php echo e(__('select') . ' ' . __('rss_feeds')); ?>',
                maximumSelectionLength: 3
            });

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
    </script>

    <script type="text/javascript">
        $(document).on('change', '#language_id', function(e) {
            var language_id = $('#language_id').val();
            var data = {
                language_id: language_id,
            };
            let newsType = $('#news_type').val();
            if(newsType != "rss_feeds_news"){

                let url = '<?php echo e(route('get_categories_tree')); ?>';
                fetchList(url, data, '#category_ids');
            }
        });


         // type author_news than hide type of filter and category input
        //  $(document).on('change', '#news_type', function(e) {
        //     var news_type = $(this).val();
        //     if (news_type == "author_news") {
        //         $('#author_news_type_section').show();
        //         $('#filter_type_section').hide();
        //         $('#filter_news').hide();
        //     } else {
        //         $('#author_news_type_section').hide();
        //         $('#filter_type_section').show();
        //         $('#filter_news').show();
        //     }
        // });
        // get author list
        $(document).on('change', '#news_type', function(e) {
            var author_news_type = $('#author_news').val();
            let newsType = $('#news_type').val();

            if(newsType == "author_news"){

                var url = '<?php echo e(route('get_author_list')); ?>';

                fetchAuthorList(url, null, '#author_news_type');
            }
        });
        $(document).on('change', '#edit_news_type', function(e) {
            var author_news_type = $('#author_news').val();
            var url = '<?php echo e(route('get_author_list')); ?>';

            fetchEditAuthorList(url, null, '#edit_author_news_type');
        });

        // Todo check the working
        $(document).on('change', '#edit_language_id', function(e) {
            var language_id = $('#edit_language_id').val();
            var url = '<?php echo e(route('get_categories_list', ['language_id' => ':language_id'])); ?>'.replace(':language_id', language_id);
            // let url = `/categories_list/${language_id}`;

            fetchCategoriesList(url, null, '#edit_category_list');

        });


        function fetchAuthorList(url, data, selector) {
            $.ajax({
                url: url,
                type: "GET",
                data: data,
                success: function(result) {
                    $(selector).html(result);
                },
                error: function(errors) {
                    $(selector).html('');
                    // console.log(errors);
                },
            });
        }
        function fetchEditAuthorList(url, data, selector, selectedValues) {
            $.ajax({
                url: url,
                type: "GET",
                data: data,
                success: function(result) {
                    $(selector).html(result);
                    // Set selected values if provided
                    if (selectedValues) {
                        // Convert comma-separated string to array if needed
                        var valuesArray = Array.isArray(selectedValues) ? selectedValues : (typeof selectedValues === 'string' ? selectedValues.split(',') : []);
                        // Filter out empty values and convert to strings/numbers as needed
                        valuesArray = valuesArray.filter(function(val) {
                            return val !== null && val !== undefined && String(val).trim() !== '';
                        }).map(function(val) {
                            // Convert to string and trim
                            return String(val).trim();
                        });
                        if (valuesArray.length > 0) {
                            $(selector).val(valuesArray).trigger('change');
                        }
                    }
                }
            });
        }
        $(document).on('change', '#news_type', function(e) {
            var news_type = $(this).val();
            if (news_type == "videos") {
                $("#videos_type").prop('required', true);
                $('#videos_option').show();
            } else {
                $("#videos_type").prop('required', false);
                $('#videos_option').hide();
            }
            if (news_type == "breaking_news") {
                $('#filter_news').hide();
                $('.most_commented').hide();
                $('.most_like').hide();
                $('.most_favorite').hide();
            } else {
                $('#filter_news').show();
                $('.most_commented').show();
                $('.most_like').show();
                $('.most_favorite').show();
            }
        });

        $(document).on('change', '#filter_type', function(e) {
            var filter_type = $(this).val();
            var news_type = $('#news_type').val();
            var videos_type = $('#videos_type').val();
            var language_id = $('#language_id').val();
            if (news_type == 'news' || news_type == 'videos') {
                if (filter_type == 'custom') {
                    $('#custom').show();
                    $('#filter_news').hide();
                } else {
                    $('#filter_news').show();
                    $('#custom').hide();
                }
            } else {
                if (filter_type == 'custom') {
                    $('#custom').show();
                    $('#filter_news').hide();
                } else {
                    $('#filter_news').hide();
                    $('#custom').hide();
                }
            }
            if (filter_type == 'custom') {
                var data = {
                    language_id: language_id,
                    news_type: news_type,
                    videos_type: videos_type
                };
                var url = '<?php echo e(route('get_custom_news')); ?>';
                fetchList(url, data, '#news_ids');
            }
        });

        $(document).on('change', '#videos_type', function(e) {
            var videos = $(this).val();
            if (videos == "breaking_news") {
                $('.most_commented').hide();
                $('.most_like').hide();
                $('.most_favorite').hide();
            } else {
                $('.most_commented').show();
                $('.most_like').show();
                $('.most_favorite').show();
            }
        });
        // type author_news than hide type of filter and category input
        $(document).on('change', '#news_type', function(e) {
            let news_type = $(this).val();
            if (news_type == "author_news") {
                $('#author_news_type_section').show();
                $('#filter_type_section').hide();
                $('#filter_news').hide();
            } else {
                $('#author_news_type_section').hide();
                $('#filter_type_section').show();
                $('#filter_news').show();
            }
        });
        // type rss_feeds_news // WORKING_RSS
        $(document).on('change', '#news_type', function(e) {
            let news_type = $(this).val();
            // console.log('news_type', news_type);
            if (news_type == "rss_feeds_news") {
                $('#filter_type_section').hide();
                $('#filter_news').hide();
                $('#rss_feeds_news_section').show();
                $('#filter_category_list').show();
            } else {
                $('#rss_feeds_news_section').hide();
                $('#filter_category_list').hide();
            }
        });

        // working here
        /**
         * Handle language change for RSS feeds news type
         * Fetches categories list based on selected language and resets RSS feeds
         */
        $(document).on('change', '#language_id', function (e){
            const newsType = $('#news_type').val();
            const languageId = $('#language_id').val();
            const $categoryList = $('#category_list');
            const $rssFeedsType = $('#rss_feeds_news_type');

            // Only process if news type is RSS feeds and language is selected
            if (!languageId) {
                return;
            }

            // Validate elements exist
            if ($categoryList.length === 0 || $rssFeedsType.length === 0) {
                return;
            }

            // Reset category and RSS feeds selections
            // if ($categoryList.hasClass('select2-hidden-accessible')) {
            //     $categoryList.val(null).trigger('change');
            // } else {
                $categoryList.val(null);
            // }
            $rssFeedsType.html('<option value=""><?php echo e(__('select') . ' ' . __('rss_feeds')); ?></option>');

            // Fetch categories list for the selected language
            const categoriesUrl = '<?php echo e(route('get_categories_list', ['language_id' => ':language_id'])); ?>'.replace(':language_id', languageId);

            fetchCategoriesList(categoriesUrl, null, '#category_list', function() {
                // Reinitialize Select2 after categories are loaded
                const $categorySelect = $('#category_list');
                const $rssFeedsType = $('#rss_feeds_news_type');
                if ($categorySelect.length === 0) {
                    return;
                }

                // Destroy existing Select2 instance if present
                if ($categorySelect.hasClass('select2-hidden-accessible')) {
                    $categorySelect.select2('destroy');
                }
                if ($rssFeedsType.hasClass('select2-hidden-accessible')) {
                    $rssFeedsType.select2('destroy');
                }

                // Initialize Select2 with proper configuration
                $categorySelect.select2({
                    placeholder: '<?php echo e(__('select') . ' ' . __('category')); ?>',
                    allowClear: true,
                    width: '100%',
                    maximumSelectionLength: 3
                });

                // Initialize Select2 with proper configuration
                $rssFeedsType.select2({
                    placeholder: '<?php echo e(__('select') . ' ' . __('rss_feeds')); ?>',
                    allowClear: true,
                    width: '100%',
                    maximumSelectionLength: 3
                });
            });
        });

        /**
         * Handle category selection change for RSS feeds
         * Fetches RSS feeds list based on selected categories
         */
        $(document).on('change', '#category_list', function(e){
            const newsType = $('#news_type').val();
            const $rssFeedsType = $('#rss_feeds_news_type');

            // console.log("this is changes RSS feed list");

            // Only process if news type is RSS feeds
            if (newsType !== "rss_feeds_news") {
                return;
            }

            // Validate RSS feeds dropdown exists
            if ($rssFeedsType.length === 0) {
                return;
            }

            // Get selected category IDs as array
            const categoryIds = $(this).val() || [];

            // Reset RSS feeds dropdown
            $rssFeedsType.html('<option value=""><?php echo e(__('select') . ' ' . __('rss_feeds')); ?></option>');

            // Validate: must have at least one category selected
            if (!Array.isArray(categoryIds) || categoryIds.length === 0) {
                return;
            }

            // Filter out invalid values (like "0", empty strings, or null)
            const validCategoryIds = categoryIds.filter(id => {
                return id !== null && id !== undefined && id !== '' && id !== '0' && String(id).trim() !== '';
            });

            if (validCategoryIds.length === 0) {
                return;
            }

            // Convert array to comma-separated string for URL parameter
            const categoryIdsString = validCategoryIds.join(',');

            // Fetch RSS feeds list
            const rssFeedsUrl = '<?php echo e(route('rss_feeds_list', ['category_ids' => ':category_ids'])); ?>'.replace(':category_ids', categoryIdsString);
            fetchRssFeedsList(rssFeedsUrl, null, '#rss_feeds_news_type');
        });

        // working ends here
        var is_based_on_user_choice = document.querySelector('#is_based_on_user_choice');
        is_based_on_user_choice.onchange = function() {
            if (is_based_on_user_choice.checked) {
                $('#based_on_user_choice_mode').val(1);
                $('#news_type').prop('required', false);
                $('#filter_type').prop('required', false);
                $('#filter_section').hide();
                // $('#filter_type').removeAttr('required');
            } else {
                $('#based_on_user_choice_mode').val(0);
                $('#news_type').prop('required', true);
                $('#filter_type').prop('required', true);
                $('#filter_section').show();
            }
        };

        /* on change of edit_based_on_choice_mode mode btn - switchery js */
        var edit_is_based_on_user_choice = document.querySelector('#edit_is_based_on_user_choice');
        edit_is_based_on_user_choice.onchange = function() {
            if (edit_is_based_on_user_choice.checked) {
                $('#edit_based_on_user_choice_mode').val(1);
                $('#edit_filter_section').hide();
                $('#edit_news_type').val('');
                $('#edit_author_news_type').val('');
                $('#edit_videos_type').val('');
                $('#edit_filter_type').val('');
                $('#edit_category_ids').val('');
                $('#edit_news_ids').val('');
                $('#edit_author_news_type').val('');
                $('#edit_category_list').val('');
                $('#edit_rss_feeds_news_type').val('');
            } else {
                $('#edit_based_on_user_choice_mode').val(0);
                $('#edit_filter_section').show();
            }
        };

        $(document).on('change', '#edit_videos_type', function(e) {
            var videos = $(this).val();
            if (videos == "breaking_news") {
                $('.most_commented').hide();
                $('.most_like').hide();
                $('.most_favorite').hide();
            } else {
                $('.most_commented').show();
                $('.most_like').show();
                $('.most_favorite').show();
            }
        });

        //  $(document).on('change', '#edit_news_type', function(e) {
        //     var news_type = $(this).val();
        //     if (news_type == "author_news") {
        //         $('#edit_author_news_type_section').show();
        //         $('#edit_filter_type_section').hide();
        //         $('#edit_filter_news').hide();
        //     } else {
        //         $('#edit_author_news_type_section').hide();
        //         $('#edit_filter_type_section').show();
        //         $('#edit_filter_news').show();
        //     }
        // });

        // $(document).on('change', '#edit_author_news_type', function(e) {
        //     var author_news_type = $(this).val();
        //     if (author_news_type == "author_news") {
        //         $('#edit_author_news_type_section').show();
        //         $('#edit_filter_type_section').hide();
        //         $('#edit_filter_news').hide();
        //     }
        // });

        $(document).on('change', '#edit_language_id', function(e, row_language_id, row_category_id, row_subcategory_id,
            row_news_id, row_filter_type, row_news_type, row_user_ids, row_category_list, row_rss_feeds_news_type) {
            var language_id = $('#edit_language_id').val();
            $.ajax({
                url: '<?php echo e(route('get_categories_tree')); ?>',
                type: "POST",
                data: {
                    language_id: language_id,
                },
                beforeSend: function() {
                    $('#edit_category_ids').html("Please wait..");
                },
                success: function(result) {
                    $('#edit_category_ids').html(result);
                    var filter_type = $('#edit_filter_type').val();
                    $("#edit_filter_type").val(row_filter_type).trigger('change', [row_filter_type,
                        row_news_id
                    ]);
                    $("#edit_news_type").val(row_news_type).trigger('change', [row_filter_type,
                        row_news_id, row_category_id, row_subcategory_id, row_user_ids, row_category_list, row_rss_feeds_news_type
                    ]);
                },
                error: function(errors) {
                    console.log(errors);
                },
            });
        });

        $(document).on('change', '#edit_news_type', function(e, row_filter_type, row_news_id, row_category_id,
            row_subcategory_id, row_user_ids, row_category_list, row_rss_feeds_news_type) {

                // console.log("on edit ");

            var news_type = $(this).val();
            if (news_type == 'videos') {
                $("#edit_videos_type").prop('required', true);
                $('#edit_videos_option').show();
            } else {
                $("#edit_videos_type").prop('required', false);
                $('edit_videos_option').hide();
            }

            // Handle author_news type
            if (news_type == "author_news") {
                $('#edit_author_news_type_section').show();
                $('#edit_filter_type_section').hide();
                $('#edit_filter_news').hide();
                $('#edit_filter_news_section').hide();
                // Fetch author list and set selected values
                var url = '<?php echo e(route('get_author_list')); ?>';
                fetchEditAuthorList(url, null, '#edit_author_news_type', row_user_ids);
            }
            // else {
            //     $('#edit_author_news_type_section').hide();
            //     $('#edit_filter_type_section').show();
            //     if (news_type != 'breaking_news') {
            //         $('#edit_filter_news').show();
            //     }
            // }

            else if (news_type == "rss_feeds_news") {

                // $('#edit_filter_news').css('display', 'none');
                $('#edit_filter_news').hide();
                $('#edit_filter_type_section').hide();
                $('#edit_filter_category_list').show();
                $('#edit_rss_feeds_news_section').show();
                // adding the css for edit_filter_news


                // Get language_id from the form
                let language_id = $('#edit_language_id').val();

                // const categoriesUrl = '<?php echo e(route('get_categories_list', ['language_id' => ':language_id'])); ?>'.replace(':language_id', language_id);
                // fetchEditCategoryList(categoriesUrl, null, '#edit_filter_category_list', row_category_list);
                // const rssFeedsUrl = '<?php echo e(route('rss_feeds_list', ['category_ids' => ':category_ids'])); ?>'.replace(':category_ids', row_category_list);
                // fetchEditRssFeedsList(rssFeedsUrl, null, '#edit_rss_feeds_news_section', row_rss_feeds_news_type);
                if (language_id && row_category_list) {
                    const categoriesUrl = '<?php echo e(route('get_categories_list', ['language_id' => ':language_id'])); ?>'.replace(':language_id', language_id);
                    fetchEditCategoryList(categoriesUrl, null, '#edit_category_list', row_category_list);
                    const rssFeedsUrl = '<?php echo e(route('rss_feeds_list', ['category_ids' => ':category_ids'])); ?>'.replace(':category_ids', row_category_list);
                    fetchEditRssFeedsList(rssFeedsUrl, null, '#edit_rss_feeds_news_type', row_rss_feeds_news_type);
                } else if (language_id) {
                    // If no category_list yet, just fetch categories
                    const categoriesUrl = '<?php echo e(route('get_categories_list', ['language_id' => ':language_id'])); ?>'.replace(':language_id', language_id);
                    fetchEditCategoryList(categoriesUrl, null, '#edit_category_list', null);
                }
            } else {
                $('#edit_author_news_type_section').hide();
                $('#edit_filter_type_section').show();
                if (news_type != 'breaking_news') {
                    $('#edit_filter_news').show();
                }
            }

            if (row_filter_type == 'custom') {
                $('#edit_filter_news').hide();
                $('#edit_custom').show();
                console.log('row_news_id', row_news_id);

                var valueArray = row_news_id;
                if (valueArray) {
                    var arrayArea = valueArray.split(',');
                }
                $("#edit_news_ids").val(arrayArea).trigger("change");
            } else {
                // $('#edit_filter_news').show();
                $('#edit_custom').hide();

                var category_ids = [];
                var subcategory_ids = [];
                if (row_category_id || row_subcategory_id) {
                    if (row_category_id) {
                        var row_category_id1 = row_category_id.split(',');
                        var prefix = 'cat-';
                        var category_ids = row_category_id1.map(el => prefix + el);
                    }
                    if (row_subcategory_id) {
                        var subcategory_idArea = row_subcategory_id.split(',');
                        var prefix = 'subcat-';
                        var subcategory_ids = subcategory_idArea.map(el => prefix + el);
                        if (row_category_id.length > 0 && row_subcategory_id.length > 0) {
                            var merge = $.merge(category_ids, subcategory_ids);
                        }
                    }
                    $("#edit_category_ids").val(category_ids).trigger("change");
                }
            }
            if (news_type == 'breaking_news') {
                $('.most_commented').hide();
                $('.most_like').hide();
                $('.most_favorite').hide();
                if (row_filter_type == 'custom') {
                    $('#edit_filter_news').hide();
                } else {
                    // $('#edit_filter_news').show();
                }
            } else {
                $('.most_commented').show();
                $('.most_like').show();
                $('.most_favorite').show();
                if (row_filter_type == 'custom') {
                    $('#edit_filter_news').hide();
                } else {
                    // $('#edit_filter_news').show();
                }
            }
        });

        // Handle edit category list change to fetch RSS feeds
        $(document).on('change', '#edit_category_list', function(e){
            const newsType = $('#edit_news_type').val();
            const $rssFeedsType = $('#edit_rss_feeds_news_type');

            // Only process if news type is RSS feeds
            if (newsType !== "rss_feeds_news") {
                return;
            }

            // Validate RSS feeds dropdown exists
            if ($rssFeedsType.length === 0) {
                return;
            }

            // Get selected category IDs as array
            const categoryIds = $(this).val() || [];

            // Reset RSS feeds dropdown
            $rssFeedsType.html('<option value=""><?php echo e(__('select') . ' ' . __('rss_feeds')); ?></option>');

            // Validate: must have at least one category selected
            if (!Array.isArray(categoryIds) || categoryIds.length === 0) {
                return;
            }

            // Filter out invalid values (like "0", empty strings, or null)
            const validCategoryIds = categoryIds.filter(id => {
                return id !== null && id !== undefined && id !== '' && id !== '0' && String(id).trim() !== '';
            });

            if (validCategoryIds.length === 0) {
                return;
            }

            // Convert array to comma-separated string for URL parameter
            const categoryIdsString = validCategoryIds.join(',');

            // Fetch RSS feeds list
            const rssFeedsUrl = '<?php echo e(route('rss_feeds_list', ['category_ids' => ':category_ids'])); ?>'.replace(':category_ids', categoryIdsString);
            fetchEditRssFeedsList(rssFeedsUrl, null, '#edit_rss_feeds_news_type');
        });

        $(document).on('change', '#edit_filter_type', function(e, row_filter_type, row_news_id) {
            var filter_type = $(this).val();
            var news_type = $('#edit_news_type').val();
            var videos_type = $('#edit_videos_type').val();
            var language_id = $('#edit_language_id').val();

            if (filter_type == 'custom') {
                var data = {
                    language_id: language_id,
                    news_type: news_type,
                    videos_type: videos_type
                };
                $.ajax({
                    url: '<?php echo e(route('get_custom_news')); ?>',
                    type: "POST",
                    data: data,
                    beforeSend: function() {
                        $('#edit_news_ids').html("Please wait..");
                    },
                    success: function(result) {
                        $('#edit_news_ids').html(result);
                        console.log('row_news_id1', row_news_id);
                        console.log('filter_type', filter_type);
                        console.log('row_filter_type', row_filter_type);

                        if (filter_type == row_filter_type) {
                            var valueArray = row_news_id;
                            if (valueArray) {
                                var arrayArea = valueArray.split(',');
                            }
                            console.log('arrayArea', arrayArea);
                            $("#edit_news_ids").val(arrayArea).trigger("change");
                        }
                    },
                    error: function(errors) {
                        console.log(errors);
                    },
                });

            }

            if (news_type == 'news' || news_type == 'videos') {
                if (filter_type == 'custom') {
                    $('#edit_custom').show();
                    $('#edit_filter_news').hide();
                } else {
                    $('#edit_filter_news').show();
                    $('#edit_custom').hide();
                }
            } else {
                if (filter_type == 'custom') {
                    $('#edit_custom').show();
                    $('#edit_filter_news').hide();
                } else {
                    $('#edit_filter_news').hide();
                    $('#edit_custom').hide();
                }
            }
        });

        $('.language_id, .news_type, .videos_type').on('change', function(e) {
            $('.filter_type').prop('selected', false).find('option:first').prop('selected', true);
        });

        // Maximum selection length is already set in the initial select2 initialization above
    </script>

    <script type="text/javascript">


    // generate with ai script starts here
    initMetaFieldGeneration({
        isEditForm: false,
        buttonSelector: '#generate_meta_fields',
        routeUrl: '<?php echo e(route('generate-all-meta-fields')); ?>',
        csrfToken: '<?php echo e(csrf_token()); ?>',
        titleSelector: '#title',
        languageSelector: '#language_id',
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

<?php echo $__env->make('layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/admin/resources/views/featured-section.blade.php ENDPATH**/ ?>