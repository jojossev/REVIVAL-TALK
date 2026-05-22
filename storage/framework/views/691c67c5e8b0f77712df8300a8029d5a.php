<!-- Google Font: Source Sans Pro -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
<!-- Font Awesome -->
<link href="<?php echo e(url('assets/plugins/fontawesome-free/css/all.min.css')); ?>" rel="stylesheet">
<!-- iCheck -->
<link href="<?php echo e(url('assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css')); ?>" rel="stylesheet">
<!-- JQVMap -->
<link href="<?php echo e(url('assets/plugins/jqvmap/jqvmap.min.css')); ?>" rel="stylesheet">


<?php
    $currentLang = session('locale');

if (!$currentLang) {
    $defaultLang = get_default_language();
    $currentLang = $defaultLang?->code ?? 'en';
    session(['locale' => $currentLang]);
}

$language = \App\Models\Language::where('code', $currentLang)->first();
$isRTL = $language && $language->isRTL == 1;
?>
 <!-- Base Theme style -->
<link href="<?php echo e(url('assets/dist/css/adminlte.min.css')); ?>" rel="stylesheet">
<link href="<?php echo e(url('assets/dist/css/adminlte.css')); ?>" rel="stylesheet">

<!-- overlayScrollbars -->
<link href="<?php echo e(url('assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css')); ?>" rel="stylesheet">


<link href="<?php echo e(url('assets/plugins/bootstrap-switch/css/bootstrap3/bootstrap-switch.css')); ?>" rel="stylesheet">

<link href="<?php echo e(url('assets/custom/css/sweetalert2.min.css')); ?>" rel="stylesheet">
<link href="<?php echo e(url('assets/plugins/select2/css/select2.min.css')); ?>" rel="stylesheet">

<link href="<?php echo e(url('assets/plugins/ekko-lightbox/ekko-lightbox.css')); ?>" rel="stylesheet">
<link href="<?php echo e(url('assets/custom/css/switchery.min.css')); ?>" rel="stylesheet">
<link href="<?php echo e(url('assets/plugins/tagify/tagify.css')); ?>"  rel="stylesheet">

<!-- filepond Css -->
<link href="<?php echo e(url('assets/plugins/filepond/css/filepond.min.css')); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo e(url('assets/plugins/filepond/css/filepond-plugin-image-preview.min.css')); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo e(url('assets/plugins/filepond/css/filepond-plugin-media-preview.min.css')); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo e(url('assets/plugins/filepond/css/filepond-plugin-pdf-preview.min.css')); ?>" rel="stylesheet" type="text/css" />

<link href="<?php echo e(url('assets/plugins/bootstrap-table/dist/bootstrap-table.min.css')); ?>" rel="stylesheet">
<link href="<?php echo e(url('assets/plugins/bootstrap-table/extensions/fixed-columns/bootstrap-table-fixed-columns.min.css')); ?>" rel="stylesheet">

<!-- Custom styles should be loaded last -->
<link href="<?php echo e(url('assets/custom/css/custom.css')); ?>" rel="stylesheet">
<?php if($isRTL): ?>
<link href="<?php echo e(url('assets/custom/css/rtl.css')); ?>" rel="stylesheet">
<?php endif; ?>
<?php /**PATH /var/www/admin/resources/views/layouts/header_script.blade.php ENDPATH**/ ?>