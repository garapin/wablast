

<div class="alert border-0 bg-light-<?php echo e($type); ?> alert-dismissible fade show py-2">
    <div class="d-flex align-items-center">
        <div class="fs-3 text-<?php echo e($type); ?>">
          <?php if($type == 'success'): ?>
            <i class="bi bi-check-circle-fill"></i>
          <?php elseif($type == 'danger'): ?>
            <i class="bi bi-exclamation-circle-fill"></i>
          <?php elseif($type == 'warning'): ?>
            <i class="bi bi-exclamation-triangle-fill"></i>
          <?php endif; ?>
        </div>
        <div class="ms-3">
            <div class="text-<?php echo e($type); ?>"><?php echo e($msg); ?></div>
        </div>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php /**PATH /Users/iwanbudihalim/Documents/code/docker/wa-gateway/extended-whatsapp-gateway-v-550-multi-device/Code/resources/views/components/alert.blade.php ENDPATH**/ ?>