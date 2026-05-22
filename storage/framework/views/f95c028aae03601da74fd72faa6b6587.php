<!-- Main Sidebar Container -->
<?php
    $currentUrl = url()->current();
?>

<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="<?php echo e(url('home')); ?>" class="brand-link">
        <img src="<?php echo e(url(Storage::url($setting['app_logo']))); ?>" alt="Logo" class="brand-image" style="opacity:.8">
        <span
            class="brand-text text-bold"><?php echo e(isset($setting['app_name']) ? $setting['app_name'] : env('APP_NAME')); ?></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <li class="nav-item">
                    <a href="<?php echo e(url('home')); ?>" class="nav-link  <?php echo e($currentUrl == url('home') ? 'active' : ''); ?>">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p><?php echo e(__('dashboard')); ?></p>
                    </a>
                </li>
                
                <div class="sidebar-new-title">
                    <?php echo e(__('news_management')); ?>

                </div>
                <?php if(getSettingMode('category_mode') == 1): ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['category-list', 'category-create', 'category-edit', 'category-delete'])): ?>
                    <li class="nav-item">
                        <a href="<?php echo e(url('category')); ?>"
                            class="nav-link  <?php echo e($currentUrl == url('category') ? 'active' : ''); ?>">
                            <i class="nav-icon fas fa-cube"></i>
                            <p><?php echo e(__('category')); ?></p>
                        </a>
                    </li>
                    <?php endif; ?>
                <?php endif; ?>
                <?php if(getSettingMode('subcategory_mode') == 1): ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['sub-category-list', 'sub-category-create', 'sub-category-edit', 'sub-category-delete', 'sub-category-order-create'])): ?>
                    <li class="nav-item">
                        <a href="<?php echo e(url('sub_category')); ?>"
                            class="nav-link  <?php echo e($currentUrl == url('sub_category') ? 'active' : ''); ?>">
                            <i class="nav-icon fas fa-cubes"></i>
                            <p><?php echo e(__('subcategory')); ?></p>
                        </a>
                    </li>
                    <?php endif; ?>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['tag-list', 'tag-create', 'tag-edit', 'tag-delete'])): ?>
                <li class="nav-item">
                    <a href="<?php echo e(url('tag')); ?>" class="nav-link <?php echo e($currentUrl == url('tag') ? 'active' : ''); ?>">
                        <i class="nav-icon fas fa-tag"></i>
                        <p><?php echo e(__('tag')); ?></p>
                    </a>
                </li>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['news-list', 'news-create', 'news-edit', 'news-delete','news-edit-description','news-clone', 'news-bulk-delete'])): ?>
                <li class="nav-item">
                    <a href="<?php echo e(url('news')); ?>" class="nav-link <?php echo e($currentUrl == url('news') ? 'active' : ''); ?>">
                        <i class="nav-icon fas fa-newspaper"></i>
                        <p><?php echo e(__('news')); ?></p>
                    </a>
                </li>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['enews-list', 'enews-create', 'enews-edit', 'enews-delete'])): ?>
                <li class="nav-item">
                    <a href="<?php echo e(route('e-news.index')); ?>" class="nav-link <?php echo e($currentUrl == route('e-news.index') ? 'active' : ''); ?>">
                        <i class="nav-icon fas fa-envelope"></i>
                        <p><?php echo e(__('eNews')); ?></p>
                    </a>
                </li>
                <?php endif; ?>
                <?php if(getSettingMode('breaking_news_mode') == 1): ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['breaking-news-list', 'breaking-news-create', 'breaking-news-edit', 'breaking-news-delete', 'breaking-news-bulk-delete'])): ?>
                    <li class="nav-item">
                        <a href="<?php echo e(url('breaking_news')); ?>"
                            class="nav-link <?php echo e($currentUrl == url('breaking_news') ? 'active' : ''); ?>">
                            <i class="nav-icon fas fa-newspaper"></i>
                            <p><?php echo e(__('breaking_news')); ?></p>
                        </a>
                    </li>
                    <?php endif; ?>
                <?php endif; ?>
                <?php if(getSettingMode('live_streaming_mode') == 1): ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['live-streaming-list', 'live-streaming-create', 'live-streaming-edit', 'live-streaming-delete'])): ?>
                    <li class="nav-item">
                        <a href="<?php echo e(url('live_streaming')); ?>"
                            class="nav-link <?php echo e($currentUrl == url('live_streaming') ? 'active' : ''); ?>">
                            <i class="nav-icon fas fa-stream"></i>
                            <p><?php echo e(__('live_streaming')); ?></p>
                        </a>
                    </li>
                    <?php endif; ?>
                <?php endif; ?>
                <?php if(getSettingMode('rss_feed_mode') == 1): ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['rss-list', 'rss-create', 'rss-edit', 'rss-delete', 'rss-bulk-delete'])): ?>
                    <li class="nav-item">
                        <a href="<?php echo e(url('rss')); ?>"
                            class="nav-link <?php echo e($currentUrl == url('rss') ? 'active' : ''); ?>">
                            <i class="nav-icon fas fa-newspaper"></i>
                            <p><?php echo e(__('rss_fees')); ?></p>
                        </a>
                    </li>
                    <?php endif; ?>
                <?php endif; ?>
                

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['featured-section-list', 'featured-section-create', 'featured-section-edit', 'featured-section-delete', 'ad-space-list', 'ad-space-create', 'ad-space-edit', 'ad-space-delete'])): ?>
                <div class="sidebar-new-title">
                    <?php echo e(__('home_screen_management')); ?>

                </div>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['featured-section-list', 'featured-section-create', 'featured-section-edit', 'featured-section-delete'])): ?>
                <li class="nav-item">
                    <a href="<?php echo e(url('featured_sections')); ?>"
                        class="nav-link <?php echo e($currentUrl == url('featured_sections') ? 'active' : ''); ?>">
                        <i class="nav-icon fas fa-layer-group"></i>
                        <p><?php echo e(__('featured_section')); ?></p>
                    </a>
                </li>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['ad-space-list', 'ad-space-create', 'ad-space-edit', 'ad-space-delete'])): ?>
                <li class="nav-item">
                    <a href="<?php echo e(url('ad_spaces')); ?>"
                        class="nav-link <?php echo e($currentUrl == url('ad_spaces') ? 'active' : ''); ?>">
                        <i class="nav-icon fas fa-ad"></i>
                        <p> <?php echo e(__('ad_spaces')); ?> </p>
                    </a>
                </li>
                <?php endif; ?>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['user-list', 'user-edit', 'comment-list', 'comment-delete', 'comment-bulk-delete', 'comment-flag-list', 'comment-flag-delete', 'notification-list', 'notification-create', 'notification-delete', 'survey-list', 'survey-create', 'survey-edit', 'survey-view','survey-delete', 'survey-bulk-delete'])): ?>
                <div class="sidebar-new-title">
                    <?php echo e(__('user_management')); ?>

                </div>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['user-list', 'user-edit'])): ?>
                <li class="nav-item">
                    <a href="<?php echo e(url('app_users')); ?>"
                        class="nav-link <?php echo e($currentUrl == url('app_users') ? 'active' : ''); ?>">
                        <em class="fas fa-user nav-icon"></em>
                        <p><?php echo e(__('user')); ?></p>
                    </a>
                </li>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['author-list', 'author-edit'])): ?>
                <li class="nav-item">
                    <a href="<?php echo e(route('author.index')); ?>"
                        class="nav-link <?php echo e($currentUrl == route('author.index') ? 'active' : ''); ?>">
                        <em class="fas fa-user-tie nav-icon"></em>
                        <p><?php echo e(__('author')); ?></p>
                    </a>
                </li>
                <?php endif; ?>
                
                <?php if(getSettingMode('comments_mode') == 1): ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['comment-list', 'comment-delete', 'comment-bulk-delete'])): ?>
                    <li class="nav-item">
                        <a href="<?php echo e(url('comments')); ?>"
                            class="nav-link <?php echo e($currentUrl == url('comments') ? 'active' : ''); ?>">
                            <em class="nav-icon fas fa-comments"></em>
                            <p> <?php echo e(__('comment')); ?> </p>
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['comment-flag-list', 'comment-flag-delete'])): ?>
                    <li class="nav-item">
                        <a href="<?php echo e(route('comments_flag')); ?>"
                            class="nav-link <?php echo e($currentUrl == url('comments_flag') ? 'active' : ''); ?>">
                            <em class="nav-icon fas fa-flag"></em>
                            <p> <?php echo e(__('comment_flag')); ?> </p>
                        </a>
                    </li>
                    <?php endif; ?>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['notification-list', 'notification-create', 'notification-delete'])): ?>
                <li class="nav-item">
                    <a href="<?php echo e(url('notifications')); ?>"
                        class="nav-link <?php echo e($currentUrl == url('notifications') ? 'active' : ''); ?>">
                        <em class="nav-icon fas fa-bullhorn"></em>
                        <p> <?php echo e(__('send_notification')); ?> </p>
                    </a>
                </li>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['survey-list', 'survey-create', 'survey-edit', 'survey-view', 'survey-delete', 'survey-bulk-delete'])): ?>
                <li class="nav-item">
                    <a href="<?php echo e(url('survey')); ?>" class="nav-link <?php echo e($currentUrl == url('survey') ? 'active' : ''); ?>">
                        <em class="nav-icon fas fa-poll-h"></em>
                        <p> <?php echo e(__('survey')); ?> </p>
                    </a>
                </li>
                <?php endif; ?>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['location-list', 'location-create', 'location-edit', 'location-delete', 'page-list', 'page-create', 'page-edit', 'page-delete'])): ?>
                <div class="sidebar-new-title">
                    <?php echo e(__('others')); ?>

                </div>
                <?php if(getSettingMode('location_news_mode') == 1): ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['location-list', 'location-create', 'location-edit', 'location-delete'])): ?>
                    <li class="nav-item">
                        <a href="<?php echo e(url('location')); ?>"
                            class="nav-link  <?php echo e($currentUrl == url('location') ? 'active' : ''); ?>">
                            <i class="nav-icon fas fa-map-marker"></i>
                            <p><?php echo e(__('location')); ?></p>
                        </a>
                    </li>
                    <?php endif; ?>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['page-list', 'page-create', 'page-edit', 'page-delete'])): ?>
                <li class="nav-item">
                    <a href="<?php echo e(url('pages')); ?>" class="nav-link  <?php echo e($currentUrl == url('pages') ? 'active' : ''); ?>">
                        <i class="nav-icon fas fa-file"></i>
                        <p><?php echo e(__('pages')); ?></p>
                    </a>
                </li>
                <?php endif; ?>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['role-list', 'role-create', 'role-edit', 'role-view', 'role-delete', 'staff-list', 'staff-create', 'staff-edit', 'staff-change-password','staff-delete'])): ?>
                <div class="sidebar-new-title">
                    <?php echo e(__('staff_management')); ?>

                </div>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['role-list', 'role-create', 'role-edit','role-view', 'role-delete'])): ?>
                <li class="nav-item">
                    <a href="<?php echo e(route('roles.index')); ?>"
                        class="nav-link <?php echo e(request()->is('roles*') ? 'active' : ''); ?>">
                        <i class="fas fa-user-cog"></i>
                        <p><?php echo e(__('roles')); ?></p>
                    </a>
                </li>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['staff-list', 'staff-create', 'staff-edit','staff-change-password', 'staff-delete'])): ?>
                <li class="nav-item">
                    <a href="<?php echo e(url('staff')); ?>" class="nav-link  <?php echo e($currentUrl == url('staff') ? 'active' : ''); ?>">
                        <i class="fas fa-user-cog"></i>
                        <p><?php echo e(__('staff_management')); ?></p>
                    </a>
                </li>
                <?php endif; ?>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['general-settings', 'panel-settings', 'web-settings', 'app-settings', 'language-list', 'language-create', 'language-edit', 'language-delete', 'seo-list', 'seo-create', 'seo-edit', 'seo-delete', 'firebase-configuration', 'social-media-list', 'social-media-create', 'social-media-edit', 'social-media-delete', 'system-update'])): ?>
                <div class="sidebar-new-title">
                    <?php echo e(__('system_configuration')); ?>

                </div>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['general-settings', 'panel-settings', 'web-settings', 'app-settings', 'language-list', 'language-create', 'language-edit', 'language-delete', 'seo-list', 'seo-create', 'seo-edit', 'seo-delete', 'firebase-configuration', 'social-media-list', 'social-media-create', 'social-media-edit', 'social-media-delete', 'system-update'])): ?>
                <li class="nav-item">
                    <a href="<?php echo e(url('system-settings')); ?>"
                        class="nav-link  <?php echo e($currentUrl == url('system-settings') ? 'active' : ''); ?>">
                        <i class="nav-icon fas fa-cogs"></i>
                        <p><?php echo e(__('system_setting')); ?></p>
                    </a>
                </li>
                <?php endif; ?>
                <?php endif; ?>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
<?php $__env->startSection('script'); ?>
    <script type="text/javascript">
        // Add this script to open the dropdown
        document.addEventListener('DOMContentLoaded', function() {
            var menuOpenElement = document.querySelector('.nav-item.has-treeview.menu-open > ul');
            if (menuOpenElement) {
                menuOpenElement.style.display = 'block';
            }
        });
    </script>
<?php $__env->stopSection(); ?>
<?php /**PATH /var/www/admin/resources/views/layouts/sidebar.blade.php ENDPATH**/ ?>