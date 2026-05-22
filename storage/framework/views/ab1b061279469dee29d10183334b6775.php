<!DOCTYPE html>
<?php
    use App\Models\Language;
    use App\Models\Settings;

    $setting = Settings::where('type', 'app_logo')->first();
    $appLogoPath = optional($setting)->message;

    // Get current locale from session or fall back to app default
    $currentLang = session('locale') ?? app()->getLocale();

    // Get the language from DB or fallback to null
    $language = Language::where('code', $currentLang)->first();

    // Determine if it's an RTL language
    $isRTL = optional($language)->isRTL == 1;
?>

<html lang="<?php echo e($currentLang); ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" type="image/x-icon" href="<?php echo e(url(Storage::url($appLogoPath))); ?>" />

    <title><?php echo $__env->yieldContent('title'); ?> || <?php echo e(config('app.name')); ?></title>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>" />
    <?php echo $__env->make('layouts.header_script', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->yieldContent('css'); ?>

    <?php
        $setting = getSetting();
        $primary_color = isset($setting['primary_color']) ? $setting['primary_color'] : '#1B2D51';
        $secondary_color = isset($setting['secondary_color']) ? $setting['secondary_color'] : '#EE2934';
    ?>

    <style>
        :root {
            --primary-color: <?php echo $primary_color; ?>;
            --secondary-color: <?php echo $secondary_color; ?>;
        }
    </style>

</head>

<body class="hold-transition sidebar-mini layout-fixed" style="height: auto;">
    <div class="wrapper">
        <?php echo $__env->make('layouts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <?php echo $__env->make('layouts.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <div class="content-wrapper">
            <?php echo $__env->yieldContent('content'); ?>
        </div>

    </div>

    <?php echo $__env->make('layouts.footer_script', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <?php echo $__env->yieldContent('js'); ?>
    <?php echo $__env->yieldContent('script'); ?>
</body>

</html>
<?php /**PATH /var/www/admin/resources/views/layouts/main.blade.php ENDPATH**/ ?>