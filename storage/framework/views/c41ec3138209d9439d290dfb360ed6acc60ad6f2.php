<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Orders')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
    <li class="breadcrumb-item"><?php echo e(__('Order')); ?></li>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table datatable">
                            <thead>
                            <tr>
                                <th><?php echo e(__('Order Id')); ?></th>
                                <th><?php echo e(__('Name')); ?></th>
                                <th><?php echo e(__('Plan Name')); ?></th>
                                <th><?php echo e(__('Price')); ?></th>
                                <th><?php echo e(__('Status')); ?></th>
                                <th><?php echo e(__('Payment Type')); ?></th>
                                <th><?php echo e(__('Date')); ?></th>
                                <th><?php echo e(__('Coupon')); ?></th>
                                <th><?php echo e(__('Invoice')); ?></th>
                                <?php if(\Auth::user()->type == 'super admin'): ?>
                                <th><?php echo e(__('Action')); ?></th>
                                <?php endif; ?>
                            </tr>
                            </thead>

                            <tbody>
                            <?php
                                $path =\App\Models\Utility::get_file('uploads/order');
                            ?>
                            <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($order->order_id); ?></td>
                                    <td><?php echo e($order->user_name); ?></td>
                                    <td><?php echo e($order->plan_name); ?></td>
                                    <td><?php echo e(env('CURRENCY_SYMBOL')); ?><?php echo e(number_format($order->price)); ?></td>

                                    <td>
                                        <?php if($order->payment_status == 'success' || $order->payment_status == 'Approved'): ?>
                                            <span class="status_badge badge bg-primary p-2 px-3 rounded"><?php echo e(ucfirst($order->payment_status)); ?></span>
                                        <?php elseif($order->payment_status == 'succeeded'): ?>
                                            <span class="status_badge badge bg-primary p-2 px-3 rounded"><?php echo e(__('Success')); ?></span>
                                        <?php elseif($order->payment_status == 'Pending'): ?>
                                            <span class="status_badge badge bg-warning p-2 px-3 rounded"><?php echo e(__('Pending')); ?></span>
                                        <?php else: ?>
                                            <span class="status_badge badge bg-danger p-2 px-3 rounded"><?php echo e(ucfirst($order->payment_status)); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo e($order->payment_type); ?></td>
                                    <td><?php echo e($order->created_at->format('d M Y')); ?></td>
                                    <td class="text-center">
                                        <?php echo e(!empty($order->total_coupon_used)? !empty($order->total_coupon_used->coupon_detail)?$order->total_coupon_used->coupon_detail->code:'-':'-'); ?>

                                    </td>
                                    <td class="Id">
                                        <?php if($order->payment_type=='Manually'): ?>
                                            <p><?php echo e(__('Manually plan upgraded by Super Admin')); ?></p>
                                        <?php elseif($order->receipt =='free coupon'): ?>
                                            <p><?php echo e(__('Used 100 % discount coupon code.')); ?></p>
                                        <?php elseif($order->payment_type=='STRIPE'): ?>
                                            <a href="<?php echo e($order->receipt); ?>" target="_blank">
                                                <i class="ti ti-file-invoice"></i> <?php echo e(__('Receipt')); ?>

                                            </a>
                                        <?php elseif(!empty( $order->receipt) && $order->payment_type=='Bank Transfer'): ?>
                                            <a href="<?php echo e($path . '/' . $order->receipt); ?>" target="_blank">
                                                <i class="ti ti-file-invoice"></i> <?php echo e(__('Receipt')); ?>

                                            </a>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                    <?php if(\Auth::user()->type == 'super admin'): ?>
                                        <td class="Action">
                                            <?php if($order->payment_type =='Bank Transfer' && $order->payment_status == 'Pending'): ?>
                                            <span>
                                                 <div class="action-btn bg-warning">
                                                    <a href="#" data-url="<?php echo e(URL::to('order/'.$order->id.'/action')); ?>" data-size="lg"
                                                       data-ajax-popup="true" data-title="<?php echo e(__('Payment Status')); ?>" class="mx-3 btn btn-sm align-items-center"
                                                       data-bs-toggle="tooltip" title="<?php echo e(__('Payment Status')); ?>" data-original-title="<?php echo e(__('Payment Status')); ?>">
                                                        <i class="ti ti-caret-right text-white"></i>
                                                    </a>
                                                 </div>
                                            </span>
                                            <?php endif; ?>
                                            <span>
                                                <div class="action-btn bg-danger ms-2">
                                                    <?php echo Form::open(['method' => 'DELETE', 'route' => ['order.destroy', $order->id],'id'=>'delete-form-'.$order->id]); ?>

                                                         <a href="#" class="mx-3 btn btn-sm align-items-center bs-pass-para" data-bs-toggle="tooltip"
                                                            title="<?php echo e(__('Delete')); ?>" data-original-title="<?php echo e(__('Delete')); ?>"
                                                            data-confirm="<?php echo e(__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')); ?>"
                                                            data-confirm-yes="document.getElementById('delete-form-<?php echo e($order->id); ?>').submit();">
                                                             <i class="ti ti-trash text-white"></i>
                                                         </a>
                                                    <?php echo Form::close(); ?>

                                                </div>
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

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/reyrockstreet/Sites/epic-fh/resources/views/order/index.blade.php ENDPATH**/ ?>