<?php $__env->startSection('page-title'); ?>
    Manage Gold
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb'); ?>
    
    <li class="breadcrumb-item"><a href="/"><?php echo e(__('Dashboard')); ?></a></li>
    <li class="breadcrumb-item">Report Gold</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('action-btn'); ?>
    <div class="float-end">
        
        
        

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create revenue')): ?>
            <a href="#" data-url="<?php echo e(route('reportgold.create')); ?>" data-size="lg" data-ajax-popup="true" data-title="<?php echo e(__('Report New Gold')); ?>" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" title="<?php echo e(__('Create')); ?>">
                <i class="ti ti-plus"></i>
            </a>
        <?php endif; ?>

    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-sm-12">
            <div class="mt-2 " id="multiCollapseExample1">
                <div class="card">
                    <div class="card-body">
                        <?php echo e(Form::open(array('route' => array('reportgold.index'),'method' => 'GET','id'=>'reportgold_form'))); ?>

                        <div class="row align-items-center justify-content-end">
                            <div class="col-xl-10">
                                <div class="row">

                                    <div class="col-3">
                                        <?php echo e(Form::label('date',__('Date'),['class'=>'form-label'])); ?>

                                        <?php echo e(Form::text('date', isset($_GET['date'])?$_GET['date']:null, array('class' => 'form-control month-btn','id'=>'pc-daterangepicker-1','readonly'))); ?>


                                    </div>

                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 month">
                                        <div class="btn-box">
                                            <?php echo e(Form::label('account',__('Account'),['class'=>'form-label'])); ?>

                                            <?php echo e(Form::select('account',$account,isset($_GET['account'])?$_GET['account']:'', array('class' => 'form-control select'))); ?>

                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 date">
                                        <div class="btn-box">
                                            <?php echo e(Form::label('farmer', __('Farmer'),['class'=>'form-label'])); ?>

                                            <?php echo e(Form::select('farmer',$farmer,isset($_GET['farmer'])?$_GET['farmer']:'', array('class' => 'form-control select'))); ?>

                                        </div>
                                    </div>

                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                        <div class="btn-box">
                                            <?php echo e(Form::label('games', __('Games'),['class'=>'form-label'])); ?>

                                            <?php echo e(Form::select('games',$games,isset($_GET['games'])?$_GET['games']:'', array('class' => 'form-control select'))); ?>

                                        </div>
                                    </div>


                                </div>
                            </div>
                            <div class="col-auto mt-4">
                                <div class="row">
                                    <div class="col-auto">

                                        <a href="#" class="btn btn-sm btn-primary" onclick="document.getElementById('reportgold_form').submit(); return false;" data-bs-toggle="tooltip" title="<?php echo e(__('Apply')); ?>" data-original-title="<?php echo e(__('apply')); ?>">
                                            <span class="btn-inner--icon"><i class="ti ti-search"></i></span>
                                        </a>

                                        <a href="<?php echo e(route('reportgold.index')); ?>" class="btn btn-sm btn-danger " data-bs-toggle="tooltip"  title="<?php echo e(__('Reset')); ?>" data-original-title="<?php echo e(__('Reset')); ?>">
                                            <span class="btn-inner--icon"><i class="ti ti-trash-off text-white-off "></i></span>
                                        </a>


                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <?php echo e(Form::close()); ?>

                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body table-border-style mt-2">
                    <h5></h5>
                    <div class="table-responsive">
                        <table class="table datatable">
                            <thead>
                            <tr>
                                <th> <?php echo e(__('Date')); ?></th>
                                <th> <?php echo e(__('Amount')); ?></th>
                                <th> <?php echo e(__('Account')); ?></th>
                                <th> <?php echo e(__('Games')); ?></th>
                                <th> <?php echo e(__('Server')); ?></th>
                                
                                <th> <?php echo e(__('Description')); ?></th>
                                <th><?php echo e(__('Proof')); ?></th>
                                <th><?php echo e(__('Farmer')); ?>


                                
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                                $goldincomepath=\App\Models\Utility::get_file('uploads/goldincome');
                            ?>
                            <?php $__currentLoopData = $goldincomes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $goldincome): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                <tr class="font-style">
                                    <td><?php echo e(Auth::user()->dateFormat($goldincome->date)); ?></td>
                                    <td><?php echo e(Auth::user()->priceFormat($goldincome->amount)); ?></td>
                                    <td><?php echo e((!empty($goldincome->accountGames)?$goldincome->accountGames->account_name:'-')); ?></td>
                                    <td><?php echo e((!empty($goldincome->games)?$goldincome->games->name:'-')); ?></td>
                                    <td><?php echo e(!empty($goldincome->server)?$goldincome->server->name:'-'); ?></td>
                                    <td><?php echo e(!empty($goldincome->description)?$goldincome->description:'-'); ?></td>



                                    

                                    <td>












                                        <?php if(!empty($goldincome->proof)): ?>
                                            <a  class="action-btn bg-primary ms-2 btn btn-sm align-items-center" href="<?php echo e($goldincomepath . '/' . $goldincome->proof); ?>" download="">
                                                <i class="ti ti-download text-white"></i>
                                            </a>
                                            <a href="<?php echo e($goldincomepath . '/' . $goldincome->proof); ?>"  class="action-btn bg-secondary ms-2 mx-3 btn btn-sm align-items-center" data-bs-toggle="tooltip" title="<?php echo e(__('Download')); ?>" target="_blank"><span class="btn-inner--icon"><i class="ti ti-crosshair text-white" ></i></span></a>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>

                                    </td>

                                    <td><?php echo e((!empty($goldincome->farmer)?$goldincome->farmer->name:'-')); ?></td>

                                    
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

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/reyrockstreet/Sites/epic-fh/resources/views/reportgold/index.blade.php ENDPATH**/ ?>