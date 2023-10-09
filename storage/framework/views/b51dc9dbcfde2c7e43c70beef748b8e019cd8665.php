<?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.layout-dashboard','data' => ['title' => 'Test Messages']]); ?>
<?php $component->withName('layout-dashboard'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['title' => 'Test Messages']); ?>

    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Message</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Test</li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->
    <?php if(session()->has('alert')): ?>
        <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.alert','data' => []]); ?>
<?php $component->withName('alert'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes([]); ?>
            <?php $__env->slot('type', session('alert')['type']); ?>
            <?php $__env->slot('msg', session('alert')['msg']); ?>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
    <?php endif; ?>
    <?php if($errors->any()): ?>
        <div class="alert border-0 bg-light-danger alert-dismissible fade show py-2">
            <div class="d-flex align-items-center">
                <div class="fs-3 text-danger">
                    <i class="bi bi-exclamation-circle-fill"></i>

                </div>
                <div class="ms-3">
                  <p>The given data was invalid.</p>
                    <ul>
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>

        </div>
    <?php endif; ?>
    
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header py-3 bg-transparent">
                    <div class="d-sm-flex align-items-center">
                        <h5 class="mb-2 mb-sm-0">Test Message</h5>
                    </div>
                </div>
                <?php if(!session()->has('selectedDevice') || !session()->has('selectedDevice')): ?>
                    <div class="alert alert-danger">
                        <ul>
                            <li>Please select a device and a message to test</li>
                        </ul>
                    </div>
                <?php else: ?>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="card shadow-none bg-light border">
                                <div class="card-body">
                                    <form class="row g-3" action="<?php echo e(route('messagetest')); ?>" method="POST">
                                        <?php echo csrf_field(); ?>
                                        <div class="col-12">
                                            <label class="form-label">Sender</label>
                                            <input name="sender"
                                                value="<?php echo e(session()->get('selectedDevice')['device_body']); ?>"
                                                type="text" class="form-control" readonly>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Receiver Number</label>
                                            <textarea
                                            placeholder="628xxx|628xxx|628xxx"
                                            class="form-control" name="number" id="" cols="20" rows="2"></textarea>
                                            
                                        </div>
                                        <div class="col-12">
                                            <label for="type" class="form-label">Type Message</label>
                                            <select name="type" id="type" class="js-states form-control"
                                                tabindex="-1" required>
                                                <option value="" selected disabled>Select One</option>
                                                <option value="text">Text Message</option>
                                                <option value="media">Media Message</option>
                                                <option value="poll">Poll Message</option>
                                                <option value="button">Button Message</option>
                                                <option value="template">Template Message</option>
                                                <option value="list">List Message</option>
                                            </select>
                                        </div>
                                        <div class="col-12 ajaxplace ">

                                        </div>
                                        
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-info btn-sm text-white px-5">Send
                                                Message</button>
                                        </div>
                                    </form>
                                </div>
                            </div>


                        </div>

                    </div>
                <?php endif; ?>
                <!--end row-->
            </div>
        </div>
    </div>
    </div>
    

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
<script>
    $('#type').on('change', () => {
        const type = $('#type').val();
        $.ajax({
            url: `/form-message/${type}`,

            type: "GET",
            dataType: "html",
            success: (result) => {
                $(".ajaxplace").html(result);
            },
            error: (error) => {
                console.log(error);
            },
        });
    })
</script>
<?php /**PATH /Users/iwanbudihalim/Documents/code/docker/wa-gateway/extended-whatsapp-gateway-v-550-multi-device/Code/resources/views/pages/messagetest.blade.php ENDPATH**/ ?>