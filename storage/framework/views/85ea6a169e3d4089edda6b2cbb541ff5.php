<?php $__env->startSection('title'); ?>
    <?php echo e(__('rss_fees')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><?php echo e(__('create_and_manage') . ' ' . __('rss_fees')); ?></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item text-dark">
                            <a href="<?php echo e(route('home')); ?>" class="text-dark"><i
                                    class="fas fa-home mr-1"></i><?php echo e(__('dashboard')); ?></a>
                        </li>
                        <li class="breadcrumb-item active"><i
                                class="nav-icon fas fa-newspaper mr-1"></i><?php echo e(__('rss_fees')); ?>

                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('rss-create')): ?>
                <div class="col-md-12 d-flex justify-content-end">
                    <button id="toggleButton" class="btn btn-primary mb-3 ml-1"><i
                            class="fas fa-plus-circle mr-2"></i><?php echo e(__('create') . ' ' . __('rss_fees')); ?></button>
                </div>
                <?php endif; ?>
                <div class="col-md-12" id="add_card">
                    <div class="card card-secondary">
                        <div class="card-header">
                            <h3 class="card-title"><?php echo e(__('create') . ' ' . __('rss_fees')); ?></h3>
                        </div>
                        <div class="card-body">
                            <form id="create_form" action="<?php echo e(url('rss')); ?>" role="form" method="POST"
                                enctype="multipart/form-data">
                                <?php echo csrf_field(); ?>
                                <div class="row">
                                    <div class="form-group col-md-4 col-sm-12">
                                        <label class="required"><?php echo e(__('language')); ?></label>
                                        <select id="language_id" name="language" class="form-control" required>
                                            <?php if(count($languageList) > 1): ?>
                                                <option value=""><?php echo e(__('select') . ' ' . __('language')); ?>

                                                </option>
                                            <?php endif; ?>
                                            <?php $__currentLoopData = $languageList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($row->id); ?>"><?php echo e($row->language); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                    <?php if(is_category_enabled() == 1): ?>
                                        <div class="form-group col-md-4 col-sm-12">
                                            <label class="required"><?php echo e(__('category')); ?></label>
                                            <select id="category_id" name="category_id" class="form-control" required>
                                                <option value=""><?php echo e(__('select') . ' ' . __('category')); ?>

                                                </option>
                                                <?php $__currentLoopData = $categoryList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($row->id); ?>"><?php echo e($row->category_name); ?>

                                                    </option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                        <?php if(is_subcategory_enabled() == 1): ?>
                                            <div class="form-group col-md-4 col-sm-12">
                                                <label><?php echo e(__('subcategory')); ?></label>
                                                <select id="subcategory_id" name="subcategory_id" class="form-control">
                                                    <option value=""><?php echo e(__('select') . ' ' . __('subcategory')); ?>

                                                    </option>
                                                </select>
                                            </div>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                    <div class="form-group col-md-4 col-sm-12">
                                        <label class="required"><?php echo e(__('feed_name')); ?></label>
                                        <input name="feed_name" required type="text" placeholder="<?php echo e(__('feed_name')); ?>"
                                            class="form-control">
                                    </div>
                                    <div class="form-group col-md-4 col-sm-12">
                                        <label class="required"><?php echo e(__('feed_url')); ?></label>
                                        <input name="feed_url" required type="url" placeholder="<?php echo e(__('feed_url')); ?>"
                                            class="form-control">
                                    </div>
                                    <div class="form-group col-md-4 col-sm-12">
                                        <label for="rss_feed_tagify"><?php echo e(__('tag')); ?></label>

                                        <input id="rss_feed_tagify" name="tag_id" class="form-control h-auto" placeholder="<?php echo e(__('select_or_type_tag')); ?>" value="">

                                        
                                    </div>
                                </div>
                                <div class="d-flex col-12 justify-content-end p-0">
                                    <button type="submit" class="btn btn-primary"><?php echo e(__('submit')); ?></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('rss-list')): ?>
                <div class="col-md-12">
                    <div class="card card-secondary">
                        <div class="card-header">
                            <h3 class="card-title"><?php echo e(__('rss_fees') . ' ' . __('list')); ?></h3>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-lg-3 col-md-4 col-sm-12">
                                    <label><?php echo e(__('language')); ?></label>
                                    <select id="filter_language_id" class="form-control">
                                        <option value="0"><?php echo e(__('select') . ' ' . __('language')); ?></option>
                                        <?php $__currentLoopData = $languageList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($row->id); ?>"><?php echo e($row->language); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <?php if(is_category_enabled() == 1): ?>
                                    <div class="col-lg-3 col-md-4 col-sm-12">
                                        <label><?php echo e(__('category')); ?></label>
                                        <select id="filter_category_id" class="form-control">
                                            <option value="0"><?php echo e(__('select') . ' ' . __('category')); ?></option>
                                        </select>
                                    </div>
                                    <?php if(is_subcategory_enabled() == 1): ?>
                                        <div class="col-lg-3 col-md-4 col-sm-12">
                                            <label><?php echo e(__('subcategory')); ?></label>
                                            <select id="filter_subcategory_id" class="form-control">
                                                <option value="0"><?php echo e(__('select') . ' ' . __('subcategory')); ?>

                                                </option>
                                            </select>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('rss-bulk-delete')): ?>
                            <div id="toolbar">
                                <button class="btn bg-primary text-white" type="submit"
                                    id="bulk_delete"><?php echo e(__('bulk_delete')); ?></button>
                            </div>
                            <?php endif; ?>
                            <table aria-describedby="mydesc" id='table' data-toggle="table"
                                data-url="<?php echo e(route('rssList')); ?>" data-click-to-select="true"
                                data-side-pagination="server" data-pagination="true"
                                data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-unique-id="id"
                                data-show-columns="true" data-show-refresh="true" data-toolbar="#toolbar"
                                data-mobile-responsive="true" data-buttons-class="primary" data-trim-on-search="false"
                                data-sort-name="id" data-sort-order="desc" data-query-params="rssQueryParams">
                                <thead>
                                    <tr>
                                        <th class="text-center multi-check" data-checkbox="true">
                                        <th scope="col" data-field="id" data-sortable="true"><?php echo e(__('id')); ?></th>
                                        <th scope="col" data-field="language_id" data-sortable="true"
                                            data-visible="false"><?php echo e(__('language_id')); ?></th>
                                        <th scope="col" data-field="language_name"><?php echo e(__('language')); ?> </th>
                                        <?php if(is_category_enabled() == 1): ?>
                                            <th scope="col" data-field="category_id" data-sortable="true"
                                                data-visible="false"><?php echo e(__('category_id')); ?></th>
                                            <th scope="col" data-field="category_name"><?php echo e(__('category')); ?></th>
                                        <?php endif; ?>
                                        <?php if(is_subcategory_enabled() == 1): ?>
                                            <th scope="col" data-field="subcategory_id" data-visible="false">
                                                <?php echo e(__('subcategory_id')); ?></th>
                                            <th scope="col" data-field="subcategory_name" data-visible="false">
                                                <?php echo e(__('subcategory')); ?></th>
                                        <?php endif; ?>
                                        <th scope="col" data-field="feed_name" data-sortable="true"><?php echo e(__('feed_name')); ?></th>
                                        <th scope="col" data-field="feed_url"><?php echo e(__('feed_url')); ?></th>
                                        <th scope="col" data-field="status_badge"><?php echo e(__('status')); ?></th>
                                        <th scope="col" data-field="tag_id" data-visible="false"><?php echo e(__('tag_id')); ?></th>
                                        <th scope="col" data-field="tag_name" data-visible="false"><?php echo e(__('tags')); ?></th>
                                        <th scope="col" data-field="created_at" data-visible="false"><?php echo e(__('created_at')); ?></th>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['rss-edit', 'rss-delete'])): ?>
                                        <th scope="col" data-field="operate" data-events="actionEvents"><?php echo e(__('operate')); ?></th>
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
                        <h4 class="modal-title"><?php echo e(__('edit') . ' ' . __('rss_fees')); ?></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="update_form" action="<?php echo e(url('rss')); ?>" role="form" method="POST"
                        enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <input type='hidden' name="edit_id" id="edit_id" value='' />
                        <div class="modal-body">
                            <div class="row">
                                <div class="form-group col-md-4 col-sm-12">
                                    <label class="required"><?php echo e(__('language')); ?></label>
                                    <select id="edit_language_id" name="language" class="form-control" required>
                                        <option value=""><?php echo e(__('select') . ' ' . __('language')); ?></option>
                                        <?php $__currentLoopData = $languageList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($row->id); ?>"><?php echo e($row->language); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <?php if(is_category_enabled() == 1): ?>
                                    <div class="form-group col-md-4 col-sm-12">
                                        <label class="required"><?php echo e(__('category')); ?></label>
                                        <select id="edit_category_id" name="category_id" class="form-control" required>
                                            <option value=""><?php echo e(__('select') . ' ' . __('category')); ?></option>
                                        </select>
                                    </div>
                                    <?php if(is_subcategory_enabled() == 1): ?>
                                        <div class="form-group col-md-4 col-sm-12">
                                            <label><?php echo e(__('subcategory')); ?></label>
                                            <select id="edit_subcategory_id" name="subcategory_id" class="form-control">
                                                <option value=""><?php echo e(__('select') . ' ' . __('subcategory')); ?>

                                                </option>
                                            </select>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <div class="form-group col-md-4 col-sm-12">
                                    <label class="required"><?php echo e(__('feed_name')); ?></label>
                                    <input id="edit_feed_name" name="feed_name" type="text" class="form-control"
                                        required>
                                </div>
                                <div class="form-group col-md-4 col-sm-12">
                                    <label class="required"><?php echo e(__('feed_url')); ?></label>
                                    <input id="edit_feed_url" name="feed_url" type="text" class="form-control"
                                        placeholder="<?php echo e(__('feed_url')); ?>" required>
                                </div>
                                <div class="form-group col-md-4 col-sm-12">
                                    <label for="edit_rss_tagify"><?php echo e(__('tag')); ?></label>
                                    <input id="edit_rss_tagify" name="tag_id" class="form-control h-auto" placeholder="<?php echo e(__('select_or_type_tag')); ?>" value="">

                                    
                                </div>
                                <div class="form-group col-md-4 col-sm-12">
                                    <label><?php echo e(__('status')); ?></label><br>
                                    <div id="status1" class="btn-group">
                                        <label class="btn btn-success" data-toggle-class="btn-primary"
                                            data-toggle-passive-class="btn-default">
                                            <input type="radio" name="status" value="1"
                                                checked><?php echo e(__('active')); ?>

                                        </label>
                                        <label class="btn btn-danger" data-toggle-class="btn-primary"
                                            data-toggle-passive-class="btn-default">
                                            <input type="radio" name="status" value="0"><?php echo e(__('deactive')); ?>

                                        </label>
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
        window.actionEvents = {
            'click .edit-data': function(e, value, row, index) {
                $('#edit_id').val(row.id);
                $('#edit_slug').val(row.slug);
                $("#edit_feed_name").val(row.feed_name);
                $("#edit_feed_url").val(row.feed_url);
                if (row.status == '0') {
                    $("input[name=status][value=0]").prop('checked', true);
                } else {
                    $("input[name=status][value=1]").prop('checked', true);
                }

                $("#edit_language_id").val(row.language_id).trigger('change', [row.language_id, row.category_id, row
                    .subcategory_id, row.tag_id
                ]);
            }
        };

    </script>

    <script type="text/javascript">
        $(document).on('change', '#language_id', function(e) {
            var data = {
                language_id: $('#language_id').val(),
            };
            var url = '<?php echo e(route('get_category_by_language')); ?>';
            fetchList(url, data, '#category_id');

            // var url1 = '<?php echo e(route('get_tag_by_language')); ?>';
            // fetchList(url1, data, '#tag_id');

            // Clear all selected tags when language changes
            if (typeof window.RSSFeedTagCustomListSuggestion !== 'undefined' && window.RSSFeedTagCustomListSuggestion) {
                // Try multiple methods to ensure tags are cleared
                try {
                    // Method 1: Try removeAllTags if available
                    if (typeof window.RSSFeedTagCustomListSuggestion.removeAllTags === 'function') {
                        window.RSSFeedTagCustomListSuggestion.removeAllTags();
                    } else {
                        // Method 2: Set value to empty and update
                        window.RSSFeedTagCustomListSuggestion.value = [];
                    }
                } catch (e) {
                    // Method 3: Fallback - set value directly and remove DOM elements
                    try {
                        window.RSSFeedTagCustomListSuggestion.value = [];
                        if (window.RSSFeedTagCustomListSuggestion.removeTagsFromDOM) {
                            window.RSSFeedTagCustomListSuggestion.removeTagsFromDOM();
                        }
                        // window.RSSFeedTagCustomListSuggestion.update();
                    } catch (err) {
                        console.log('Error clearing tags:', err);
                    }
                }
            }
            // $('#tag_id').val(null).trigger('change');
            // var url1 = '<?php echo e(route('get_tag_by_language')); ?>';
            // fetchList(url1, data, '#tag_id');
            var url1 = '<?php echo e(route('get_tag_by_language')); ?>';
            $.ajax({
                url: url1,
                type: 'POST',
                data: data,
                success: function(result) {
                    // Parse the HTML options and extract tag data
                    var $temp = $('<div>').html(result);
                    var tags = [];
                    $temp.find('option').each(function() {
                        var $option = $(this);
                        tags.push({
                            value: $option.data('text'),
                            id: $option.val(),
                            // text: $option.data('text')
                        });
                    });

                    // Update Tagify whitelist
                    if (typeof RSSFeedTagCustomListSuggestion !== 'undefined' && RSSFeedTagCustomListSuggestion) {
                        RSSFeedTagCustomListSuggestion.settings.whitelist = tags;
                        // RSSFeedTagCustomListSuggestion.dropdown.show.call(RSSFeedTagCustomListSuggestion);
                    }
                },
                error: function(errors) {
                    console.log('Error fetching tags:', errors);
                    // Clear whitelist on error
                    if (typeof window.RSSFeedTagCustomListSuggestion !== 'undefined' && window.RSSFeedTagCustomListSuggestion) {
                        window.RSSFeedTagCustomListSuggestion.settings.whitelist = [];
                    }
                }
            });
        });

        $(document).on('change', '#category_id', function(e) {
            var data = {
                category_id: $('#category_id').val(),
            };
            var url = '<?php echo e(route('get_subcategory_by_category')); ?>';
            fetchList(url, data, '#subcategory_id');
        });

        $(document).on('change', '#edit_language_id', function(e, row_language_id, row_category_id, row_subcategory_id,
            row_tag_id) {
            console.log(row_tag_id);

            var language_id = $('#edit_language_id').val();
            $.ajax({
                url: '<?php echo e(route('get_category_by_language')); ?>',
                type: "POST",
                data: {
                    language_id: language_id,
                },
                beforeSend: function() {
                    $('#edit_category_id').html("Please wait..");
                },
                success: function(result) {
                    $('#edit_category_id').html(result);
                    if (language_id == row_language_id && row_category_id != 0) {
                        $('#edit_category_id').val(row_category_id).trigger('change', [row_category_id,
                            row_subcategory_id
                        ]);
                    }
                },
                error: function(errors) {
                    console.log(errors);
                },
            });

             // Clear all selected tags when language changes
             if (typeof window.editRSSFeedTagCustomListSuggestion !== 'undefined' && window.editRSSFeedTagCustomListSuggestion) {
                // Try multiple methods to ensure tags are cleared
                try {
                    // Method 1: Try removeAllTags if available
                    if (typeof window.editRSSFeedTagCustomListSuggestion.removeAllTags === 'function') {
                        window.editRSSFeedTagCustomListSuggestion.removeAllTags();
                    } else {
                        // Method 2: Set value to empty and update
                        window.editRSSFeedTagCustomListSuggestion.value = [];
                    }
                } catch (e) {
                    // Method 3: Fallback - set value directly and remove DOM elements
                    try {
                        window.editRSSFeedTagCustomListSuggestion.value = [];
                        if (window.editRSSFeedTagCustomListSuggestion.removeTagsFromDOM) {
                            window.editRSSFeedTagCustomListSuggestion.removeTagsFromDOM();
                        }
                        // window.editRSSFeedTagCustomListSuggestion.update();
                    } catch (err) {
                        console.log('Error clearing tags:', err);
                    }
                }
            }

            // console.log(selectedTags);

            $.ajax({
                url: '<?php echo e(route('get_tag_by_language')); ?>',
                type: "POST",
                data: {
                    language_id: language_id,
                },
                beforeSend: function() {
                    $('#edit_rss_tagify').html("Please wait..");
                },
                success: function(result) {
                    // $('#edit_tag_id').html(result).trigger("change");
                    // if (language_id == row_language_id && row_tag_id != '') {
                    //     var valueArray = row_tag_id;
                    //     var arrayArea = valueArray.split(',');
                    //     $("#edit_tag_id").val(arrayArea).trigger("change");
                    // }

                    let $temp = $('<div>').html(result);
                    let tags = [];
                    let selectedTags = [];

                    // Convert row_tag_id to an array for exact matching
                    let rowTagIds = [];
                    if (row_tag_id && row_tag_id != '') {
                        if (Array.isArray(row_tag_id)) {
                            rowTagIds = row_tag_id.map(id => String(id));
                        } else {
                            // Split by comma if it's a comma-separated string
                            rowTagIds = String(row_tag_id).split(',').map(id => id.trim());
                        }
                    }

                    $temp.find('option').each(function() {
                        let $option = $(this);
                        let tagObj = {
                            value: $option.data('text'),
                            id: $option.val(),
                            // text: $option.data('text')
                        };
                        tags.push(tagObj);
                        // need to add the attribute value tag where row_tag_id matches

                        // Use exact matching instead of substring matching
                        if (rowTagIds.length > 0 && rowTagIds.includes(String($option.val()))) {
                            console.log(tagObj);

                            selectedTags.push(tagObj);
                        }


                        // Update Tagify whitelist
                        if (typeof editRSSFeedTagCustomListSuggestion !== 'undefined' && editRSSFeedTagCustomListSuggestion) {

                            editRSSFeedTagCustomListSuggestion.removeAllTags();

                            editRSSFeedTagCustomListSuggestion.settings.whitelist = tags;
                            // console.log(editRSSFeedTagCustomListSuggestion.settings);

                            // Add selected tags if any match
                            if (selectedTags.length > 0) {
                                editRSSFeedTagCustomListSuggestion.addTags(selectedTags);
                            }
                            // editRSSFeedTagCustomListSuggestion.dropdown.show.call(editRSSFeedTagCustomListSuggestion);
                        }
                    });
                },
                error: function(errors) {
                    console.log(errors);
                },
            });
        });

        $(document).on('change', '#edit_category_id', function(e, row_category_id, row_subcategory_id) {
            var category_id = $('#edit_category_id').val();
            $.ajax({
                url: '<?php echo e(route('get_subcategory_by_category')); ?>',
                type: "POST",
                data: {
                    category_id: category_id,
                },
                beforeSend: function() {
                    $('#edit_subcategory_id').html("Please wait..");
                },
                success: function(result) {
                    $('#edit_subcategory_id').html(result);
                    if (category_id == row_category_id && row_subcategory_id != 0) {
                        $('#edit_subcategory_id').val(row_subcategory_id);
                    }
                },
                error: function(errors) {
                    console.log(errors);
                },
            });
        });
    </script>

    <script type="text/javascript">
        $(document).on('change', '#filter_language_id', function() {
            $('#table').bootstrapTable('refresh');
            var data = {
                language_id: $('#filter_language_id').val(),
            };
            var url = '<?php echo e(route('get_category_by_language')); ?>';
            fetchList(url, data, '#filter_category_id');
        });
        $(document).on('change', '#filter_category_id', function() {
            $('#table').bootstrapTable('refresh');
            var data = {
                category_id: $('#filter_category_id').val(),
            };
            var url = '<?php echo e(route('get_subcategory_by_category')); ?>';
            fetchList(url, data, '#filter_subcategory_id');
        });
        $(document).on('change', '#filter_subcategory_id', function() {
            $('#table').bootstrapTable('refresh');
        });
    </script>

    <script type="text/javascript">
        $('#bulk_delete').click(function() {
            var request_ids = [];
            selected = $('#table').bootstrapTable('getSelections');
            var arr = Object.values(selected);
            var i;
            var final_selection = [];
            var request_ids = arr.map(({
                id
            }) => id);
            if (request_ids.length) {
                Swal.fire({
                    title: '<?php echo e(__('are_you_sure')); ?>',
                    text: 'You won\'t be able to revert this!',
                    icon: 'error',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, proceed'
                }).then((result) => {
                    if (result.value) {
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        $.ajax({
                            type: 'POST',
                            url: '<?php echo e(url('bulk_rss_delete')); ?>',
                            data: {
                                request_ids: request_ids
                            },
                            success: function(response) {
                                if (response.error == false) {
                                    showSuccessToast(response.message);
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

        function dateFormate(value, row) {
            if (value && value !== '0000-00-00') {
                var date = new Date(value);
                var yy = date.getFullYear();
                var mm = date.getMonth() + 1; // getMonth() is zero-based
                var dd = date.getDate();
                return dd.toString().padStart(2, '0') + '-' + mm.toString().padStart(2, '0') + '-' + yy;
            }
            return '00-00-0000';
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/admin/resources/views/rss.blade.php ENDPATH**/ ?>