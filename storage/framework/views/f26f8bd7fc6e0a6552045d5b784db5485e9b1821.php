<!DOCTYPE html>
<html class="semi-dark " lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">




<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="<?php echo e(asset('assets/images/favicon.png')); ?>" type="image/png" />
    <!--plugins-->
    <link href="<?php echo e(asset('assets/plugins/vectormap/jquery-jvectormap-2.0.2.css')); ?>" rel="stylesheet" />
    <link href="<?php echo e(asset('assets/plugins/simplebar/css/simplebar.css')); ?>" rel="stylesheet" />
    <link href="<?php echo e(asset('assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css')); ?>" rel="stylesheet" />
    <link href="<?php echo e(asset('assets/plugins/metismenu/css/metisMenu.min.css')); ?>" rel="stylesheet" />
    <!-- Bootstrap CSS -->
    <link href="<?php echo e(asset('assets/css/bootstrap.min.css')); ?>" rel="stylesheet" />
    <link href="<?php echo e(asset('assets/css/bootstrap-extended.css')); ?>" rel="stylesheet" />
    <link href="<?php echo e(asset('assets/css/style.css')); ?>" rel="stylesheet" />
    <link href="<?php echo e(asset('assets/css/icons.css')); ?>" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <script src="<?php echo e(asset('assets/js/jquery.min.js')); ?>"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" />

    <!-- loader-->
    <link href="<?php echo e(asset('assets/css/pace.min.css')); ?>" rel="stylesheet" />

    <!--Theme Styles-->
    <link href="<?php echo e(asset('assets/css/dark-theme.css')); ?>" rel="stylesheet" />
    <link href="<?php echo e(asset('assets/css/light-theme.css')); ?>" rel="stylesheet" />
    <link href="<?php echo e(asset('assets/css/semi-dark.css')); ?>" rel="stylesheet" />
    <link href="<?php echo e(asset('assets/css/header-colors.css')); ?>" rel="stylesheet" />
    
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>" />

    <title><?php echo e($title); ?> | MPWA Multi device version</title>
</head>

<body>

    
    <div class="wrapper">
        <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.header','data' => []]); ?>
<?php $component->withName('header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes([]); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
        <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.aside','data' => []]); ?>
<?php $component->withName('aside'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes([]); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>

        <!--start content-->
        <main class="page-content">




            <?php echo e($slot); ?>

          
          
         

        </main>
        <!--end page main-->
     
    </div>


    <!-- Javascripts -->



    <!-- Bootstrap bundle JS -->
    <script src="<?php echo e(asset('assets/js/bootstrap.bundle.min.js')); ?>"></script>
    <!--plugins-->

    <script src="<?php echo e(asset('assets/plugins/simplebar/js/simplebar.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/plugins/metismenu/js/metisMenu.min.js')); ?>"></script>
    
    <script src="<?php echo e(asset('assets/js/pace.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/app.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/index.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/plugins/smart-wizard/js/jquery.smartWizard.min.js')); ?>"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <link href="<?php echo e(asset('assets/plugins/smart-wizard/css/smart_wizard_all.min.css')); ?>" rel="stylesheet"
        type="text/css" />
    

    <script>
        toastr.options = {
            closeButton: false,
            debug: false,
            newestOnTop: false,
            progressBar: false,
            positionClass: "toast-top-right",
            preventDuplicates: false,
            onclick: null,
            showDuration: "300",
            hideDuration: "1000",
            timeOut: "5000",
            extendedTimeOut: "1000",
            showEasing: "swing",
            hideEasing: "linear",
            showMethod: "fadeIn",
            hideMethod: "fadeOut",
        };
    </script>
</body>

</html>

<?php /**PATH /Users/iwanbudihalim/Documents/code/docker/wa-gateway/extended-whatsapp-gateway-v-550-multi-device/Code/resources/views/components/layout-dashboard.blade.php ENDPATH**/ ?>