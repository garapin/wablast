<?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.layout-dashboard','data' => ['title' => 'Home']]); ?>
<?php $component->withName('layout-dashboard'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['title' => 'Home']); ?>

    <div class="app-content">
        <div class="content-wrapper">
            <div class="container">

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
                    <div class="alert alert-danger">
                        <ul>
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                <?php endif; ?>
                <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-4">
                    <div class="col">
                        <div class="card rounded-4">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="">
                                        <p class="mb-1">Total Devices</p>
                                        <h4 class="mb-0"><?php echo e($user->devices_count); ?></h4>
                                        <p class="mb-0 mt-2 font-13">
                                            <span>Your limit device : <?php echo e($user->limit_device); ?></span></p>
                                    </div>
                                    <div class="ms-auto widget-icon bg-primary text-white">
                                        <i class="bi bi-whatsapp"></i>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card rounded-4">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="">
                                        <p class="mb-1">Blast/Bulk</p>
                                        <p class="mb-0 badge bg-warning"><?php echo e($user->blasts_pending); ?> Wait </p>
                                        <p class="mb-0 badge bg-success"><?php echo e($user->blasts_success); ?> Sent </p>
                                        <p class="mb-0 badge bg-danger"><?php echo e($user->blasts_failed); ?> Fail </p>
                                        <p class="mb-0 mt-2 font-13">
                                        From <?php echo e($user->campaigns_count); ?> Campaigns   
                                        </span></p>
                                    </div>
                                    <div class="ms-auto widget-icon bg-success text-white">
                                        <i class="bi bi-broadcast"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card rounded-4">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="">
                                        <p class="mb-1">Subscription Status</p>
                                        <h4 class="mb-0"><?php echo e($user->subscription_status); ?></h4>
                                        <p class="mb-0 mt-2 font-13"><span>
                                      Expired : <?php echo e($user->expired_subscription_status); ?>    
                                        </span></p>
                                    </div>
                                    <div class="ms-auto widget-icon bg-orange text-white">
                                        <i class="bi bi-emoji-heart-eyes"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card rounded-4">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="">
                                        <p class="mb-1">All Messages Sent</p>
                                        <h4 class="mb-0"><?php echo e($user->message_histories_count); ?></h4>
                                        <p class="mb-0 mt-2 font-13"><span>From messages histories</span></p>
                                    </div>
                                    <div class="ms-auto widget-icon bg-info text-white">
                                        <i class="bi bi-chat-left-text"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!--end row-->
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <h5 class="mb-0">Whatsapp Account</h5>
                            <form class="ms-auto position-relative">
                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#addDevice"><i class="bi bi-plus"></i> Add Device</button>

                            </form>
                        </div>
                        <div class="table-responsive mt-3">
                            <table class="table align-middle">
                                <thead>
                                    <th>Number</th>
                                    <th>Webhook URL</th>
                                    <th>Messages Sent</th>
                                    <th>status</th>
                                    <th>Action</th>
                                </thead>
                                <tbody>
                                    <?php if($numbers->total() == 0): ?>
                                        <?php if (isset($component)) { $__componentOriginal7d2cc6b6ecdefae63f236b635a0696904bd06013 = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\NoData::class, ['colspan' => '5','text' => 'No Device added yet']); ?>
<?php $component->withName('no-data'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7d2cc6b6ecdefae63f236b635a0696904bd06013)): ?>
<?php $component = $__componentOriginal7d2cc6b6ecdefae63f236b635a0696904bd06013; ?>
<?php unset($__componentOriginal7d2cc6b6ecdefae63f236b635a0696904bd06013); ?>
<?php endif; ?>
                                    <?php endif; ?>
                                    <?php $__currentLoopData = $numbers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $number): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>

                                            <td><?php echo e($number['body']); ?></td>
                                            <td>
                                                <form action="" method="post">
                                                    <?php echo csrf_field(); ?>
                                                    <input type="text" 
                                                        class="form-control form-control-solid-bordered webhook-url-form"
                                                        data-id="<?php echo e($number['body']); ?>" name=""
                                                        value="<?php echo e($number['webhook']); ?>" id="">
                                                </form>
                                            </td>
                                            <td><?php echo e($number['message_sent']); ?></td>
                                            <td><span
                                                    class="badge bg-<?php echo e($number['status'] == 'Connected' ? 'success' : 'danger'); ?>"><?php echo e($number['status']); ?></span>
                                            </td>
                                            <td>
                                                <div class="table-actions d-flex align-items-center gap-3 fs-6">
                                                    <a href="<?php echo e(route('scan', $number->body)); ?>" class="text-primary"
                                                        data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                        title="scan"><i class="bi bi-qr-code"></i></a>
                                                    <form action="<?php echo e(route('deleteDevice')); ?>" method="POST">
                                                        <?php echo method_field('delete'); ?>
                                                        <?php echo csrf_field(); ?>
                                                        <input name="deviceId" type="hidden"
                                                            value="<?php echo e($number['id']); ?>">
                                                        <button type="submit" name="delete"
                                                            class="btn  text-danger outline-none"><i
                                                                class="bi bi-trash"></i></button>
                                                    </form>
                                                </div>


                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


                                </tbody>

                            </table>
                        </div>
                      
                        <nav aria-label="Page navigation example">
                            <ul class="pagination">
                                <li class="page-item <?php echo e($numbers->currentPage() == 1 ? 'disabled' : ''); ?>">
                                    <a class="page-link" href="<?php echo e($numbers->previousPageUrl()); ?>">Previous</a>
                                </li>

                                <?php for($i = 1; $i <= $numbers->lastPage(); $i++): ?>
                                    <li class="page-item <?php echo e($numbers->currentPage() == $i ? 'active' : ''); ?>">
                                        <a class="page-link" href="<?php echo e($numbers->url($i)); ?>"><?php echo e($i); ?></a>
                                    </li>
                                <?php endfor; ?>

                                <li
                                    class="page-item <?php echo e($numbers->currentPage() == $numbers->lastPage() ? 'disabled' : ''); ?>">
                                    <a class="page-link" href="<?php echo e($numbers->nextPageUrl()); ?>">Next</a>
                                </li>
                            </ul>
                        </nav>
                       
                    </div>
                </div>


            </div>
        </div>
    </div>

       



    <div class="modal fade" id="addDevice" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Device</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="<?php echo e(route('addDevice')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <label for="sender" class="form-label">Number</label>
                        <input type="number" name="sender" class="form-control" id="nomor" required>
                        <p class="text-small text-danger">*Use Country Code ( without + )</p>
                        <label for="urlwebhook" class="form-label">Link webhook</label>
                        <input type="text" name="urlwebhook" class="form-control" id="urlwebhook">
                        <p class="text-small text-danger">*Optional</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="submit" class="btn btn-primary">Save</button>
                    </form>
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
    var typingTimer; //timer identifier
    var doneTypingInterval = 1000;
  
    $('.webhook-url-form').on('keyup', function() {
        clearTimeout(typingTimer);
        let value = $(this).val();
        let number = $(this).data('id');
  
        typingTimer = setTimeout(function() {

            $.ajax({
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '<?php echo e(route('setHook')); ?>',
                data: {
                    csrf: $('meta[name="csrf-token"]').attr('content'),
                    number: number,
                    webhook: value
                },
                dataType: 'json',
                success: (result) => {
                    toastr.success('Webhook URL has been updated');
                },
                error: (err) => {
                    console.log(err);
                }
            })
        }, doneTypingInterval);
    })
</script>
<?php /**PATH /Users/iwanbudihalim/Documents/code/docker/wa-gateway/extended-whatsapp-gateway-v-550-multi-device/Code/resources/views/home.blade.php ENDPATH**/ ?>