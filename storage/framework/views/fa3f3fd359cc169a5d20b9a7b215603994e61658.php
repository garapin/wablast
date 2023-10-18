       <!--start sidebar -->
       <aside class="sidebar-wrapper" data-simplebar="true">
           <div class="sidebar-header">
               <div>
                   <img src="<?php echo e(asset('assets/images/logo-icon.png')); ?>" class="logo-icon" alt="logo icon">
               </div>
               <div>
                   <h4 class="logo-text">WA Blaster</h4>
               </div>
               <div class="toggle-icon ms-auto"> <i class="bi bi-list"></i>
               </div>
           </div>
           <!--navigation-->
           <ul class="metismenu" id="menu">
               
               <li class="<?php echo e(request()->is('home') ? 'active' : ''); ?>">
                   <a href="<?php echo e(route('home')); ?>">
                       <div class="parent-icon"><i class="bi bi-house-fill"></i>
                       </div>
                       <div class="menu-title">Dashboard</div>
                   </a>

               </li>
               
               <li class="<?php echo e(request()->is('file-manager') ? 'active' : ''); ?>">
                   <a href="<?php echo e(route('file-manager')); ?>">
                       <div class="parent-icon"><i class="bi bi-file-earmark-fill"></i>
                       </div>
                       <div class="menu-title">File Manager</div>
                   </a>

               </li>
               
               <li class="<?php echo e(request()->is('phonebook') ? 'active' : ''); ?>">
                   <a href="<?php echo e(route('phonebook')); ?>">
                       <div class="parent-icon"><i class="bi bi-telephone-fill"></i>
                       </div>
                       <div class="menu-title">Phone Book</div>
                   </a>
               </li>
               
               <li>
                   <a href="javascript:;" class="has-arrow">
                       <div class="parent-icon">
                           
                           <i class="bi bi-file-earmark-bar-graph-fill"></i>
                       </div>
                       <div class="menu-title">Reports</div>
                   </a>
                   <ul>
                       <li class="<?php echo e(request()->is('campaigns') ? 'active' : ''); ?>">
                           <a href="<?php echo e(route('campaigns')); ?>"><i class="bi bi-circle"></i>Campaign / Blast</a>
                       </li>
                       <li class="<?php echo e(request()->is('messages.history') ? 'active' : ''); ?>">
                           <a href="<?php echo e(route('messages.history')); ?>"><i class="bi bi-circle"></i>Messages History</a>
                       </li>

                   </ul>
               </li>

               <?php if (isset($component)) { $__componentOriginal5d9952b5be3538308e32d3fe1eb0eea98123b88c = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\SelectDevice::class, []); ?>
<?php $component->withName('select-device'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes([]); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5d9952b5be3538308e32d3fe1eb0eea98123b88c)): ?>
<?php $component = $__componentOriginal5d9952b5be3538308e32d3fe1eb0eea98123b88c; ?>
<?php unset($__componentOriginal5d9952b5be3538308e32d3fe1eb0eea98123b88c); ?>
<?php endif; ?>

               
               <?php if(Session::has('selectedDevice')): ?>
                   <li class="<?php echo e(request()->is('autoreply') ? 'active' : ''); ?>">
                       <a href="<?php echo e(route('autoreply')); ?>">
                           <div class="parent-icon"><i class="bi bi-chat-left-dots-fill"></i>
                           </div>
                           <div class="menu-title">Auto Reply</div>
                       </a>
                   </li>
                   
                   <li class=" <?php echo e(url()->current() == route('campaign.create') ? 'mm-active' : ''); ?>">
                       <a class="" href="<?php echo e(route('campaign.create')); ?>">
                           <div class="parent-icon"><i class="bi bi-plus-circle-fill"></i>
                           </div>
                           <div class="menu-title">Create Campaign</div>
                       </a>
                   </li>
                   
                   
                   <li class=" <?php echo e(url()->current() == route('messagetest') ? 'mm-active' : ''); ?>">
                       <a class="" href="<?php echo e(route('messagetest')); ?>">
                           <div class="parent-icon"><i class="bi bi-chat-left-dots-fill"></i>
                           </div>
                           <div class="menu-title">Test Message</div>
                       </a>
                   </li>
                   
               <?php endif; ?>


               
               <?php if(Auth::user()->level == 'admin'): ?>
                   <li>
                       <a href="javascript:;" class="has-arrow">
                           <div class="parent-icon">
                               
                               <i class="bi bi-person-lines-fill"></i>
                           </div>
                           <div class="menu-title">Admin</div>
                       </a>
                       <ul>
                           <li class="<?php echo e(request()->is('admin.manage-users') ? 'active' : ''); ?>">
                               <a href="<?php echo e(route('admin.manage-users')); ?>">
                                <i class="bi bi-circle"></i>
                               Manage User</a>
                           </li>
                          
                       </ul>
                   </li>
               <?php endif; ?>


               



           </ul>
           <!--end navigation-->
       </aside>
       <!--end sidebar -->
<?php /**PATH /Users/iwanbudihalim/Documents/code/docker/wa-gateway/extended-whatsapp-gateway-v-550-multi-device/Code/resources/views/components/aside.blade.php ENDPATH**/ ?>