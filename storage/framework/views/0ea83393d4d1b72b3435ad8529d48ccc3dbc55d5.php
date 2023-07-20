<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Manage Account')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('breadcrumb'); ?>
    
    <li class="breadcrumb-item"><a href="/"><?php echo e(__('Dashboard')); ?></a></li>
    <li class="breadcrumb-item"><?php echo e(__('Account')); ?></li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('action-btn'); ?>
    <div class="float-end">
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create bank account')): ?>
            
            <a href="#">
                <i class="ti ti-plus"></i>
            </a>

        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body table-border-style table-border-style">
                    <h5></h5>
                    <div class="table-responsive">
                        <table class="table datatable">
                            <thead>
                            <tr>
                                <th> <?php echo e(__('Account Name')); ?></th>
                                <th> <?php echo e(__('Account Email')); ?></th>
                                <th> <?php echo e(__('Account Pasword')); ?></th>
                                <th> <?php echo e(__('Game')); ?></th>
                                <th> <?php echo e(__('Current Gold')); ?></th>
                                <th> <?php echo e(__('Farmer')); ?></th>

                                    <th width="10%"> <?php echo e(__('Action')); ?></th>

                            </tr>
                            </thead>

                            <tbody>
                            <?php $__currentLoopData = $accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="font-style">
                                    <td><?php echo e($account->account_name); ?></td>
                                    <td><?php echo e($account->account_email); ?></td>
                                    <td><?php echo e($account->account_password); ?></td>
                                    
                                    <td><?php echo e(('World of Warcraft')); ?></td>
                                    <td><?php echo e(\Auth::user()->priceFormat($account->account_gold)); ?></td>
                                    
                                    <td><?php echo e(('Rey')); ?></td>
                                    <?php if(Gate::check('edit bank account') || Gate::check('delete bank account')): ?>
                                        <td class="Action">
                                            <span>
                                                
                                            </span>
                                        </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/reyrockstreet/Sites/epic-fh/resources/views/accountGames/index.blade.php ENDPATH**/ ?>