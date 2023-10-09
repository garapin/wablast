<?php $__currentLoopData = $phonebooks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $phonebook): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="row ">
        <div class="col-10">
            <a
            onclick="clickPhoneBook(<?php echo e($phonebook->id); ?>,this)"
            href="javascript:;"
            data-phonebook-id="<?php echo e($phonebook->id); ?>"
            

              type="button"
                class=" list-group-item d-flex align-items-center text-start single-phonebook btn-sm"><span><?php echo e($phonebook->name); ?></span></a>
        </div>
        <div class="col-2 border-none d-flex align-items-center justify-content-center">
            <form action="<?php echo e(route('tag.delete')); ?>" method="POST"
                onsubmit="return confirm('do you sure want to delete this tag? ( All contacts in this tag also will delete! )')">
                <?php echo method_field('delete'); ?>
                <?php echo csrf_field(); ?>
                <input type="hidden" name="id" value="<?php echo e($phonebook->id); ?>">
                <button type="submit" name="delete" class="btn text-danger btn-sm">
                  <i class="bi bi-trash"></i> 
                </button>
            </form>
        </div>
    </div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php /**PATH /Users/iwanbudihalim/Documents/code/docker/wa-gateway/extended-whatsapp-gateway-v-550-multi-device/Code/resources/views/pages/phonebook/dataphonebook.blade.php ENDPATH**/ ?>