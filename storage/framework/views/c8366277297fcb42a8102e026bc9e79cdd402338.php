<?php $__env->startSection('page-title'); ?>
    <?php echo e($notification_template->name); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
    <li class="breadcrumb-item active" aria-current="page"><?php echo e(__('Notification Template')); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('pre-purpose-css-page'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('css/summernote/summernote-bs4.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('action-btn'); ?>
    <div class="row">
        <div class="text-end mb-3">
            <div class="text-end">
                <div class="d-flex justify-content-end drp-languages">
                    <ul class="list-unstyled mb-0 m-2">
                        <li class="dropdown dash-h-item drp-language">
                            <a class="email-color dash-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown"
                               href="#" role="button" aria-haspopup="false" aria-expanded="false"
                               id="dropdownLanguage">
                            <span
                                class="drp-text hide-mob text-primary"><?php echo e(Str::upper($curr_noti_tempLang->lang)); ?></span>
                                <i class="ti ti-chevron-down drp-arrow nocolor"></i>
                            </a>
                            <div class="dropdown-menu dash-h-dropdown dropdown-menu-end"
                                 aria-labelledby="dropdownLanguage">
                                <?php $__currentLoopData = $languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <a href="<?php echo e(route('notification_templates.index', [$notification_template->id, $lang])); ?>"
                                       class="dropdown-item <?php echo e($curr_noti_tempLang->lang == $lang ? 'text-primary' : ''); ?>"><?php echo e(Str::upper($lang)); ?></a>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </li>
                    </ul>
                    <ul class="list-unstyled mb-0 m-2">
                        <li class="dropdown dash-h-item drp-language">
                            <a class="email-color dash-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown"
                               href="#" role="button" aria-haspopup="false" aria-expanded="false"
                               id="dropdownLanguage">
                            <span
                                class="drp-text hide-mob text-primary"><?php echo e(__('Template: ')); ?><?php echo e($notification_template->name); ?></span>
                                <i class="ti ti-chevron-down drp-arrow nocolor"></i>
                            </a>
                            <div class="dropdown-menu dash-h-dropdown dropdown-menu-end email_temp" aria-labelledby="dropdownLanguage">
                                <?php $__currentLoopData = $notification_templates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <a href="<?php echo e(route('notification_templates.index', [$notification->id,(Request::segment(3)?Request::segment(3):\Auth::user()->lang)])); ?>"
                                       class="dropdown-item <?php echo e($notification->name == $notification_template->name ? 'text-primary' : ''); ?>"><?php echo e($notification->name); ?>

                                    </a>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </li>
                    </ul>


                
                    <?php
                        $plan = \App\Models\Plan::find(\Auth::user()->plan);
                    ?>
                    <?php if($plan->chatgpt == 1): ?>
                    <div class="mb-0 m-2">
                        <a href="#" data-size="md" class="btn  btn-primary btn-sm" data-ajax-popup-over="true" data-url="<?php echo e(route('generate',['notification template'])); ?>"
                           data-bs-placement="top" data-title="<?php echo e(__('Generate content with AI')); ?>">
                            <i class="fas fa-robot"></i> <span><?php echo e(__('Generate with AI')); ?></span>
                        </a>
                    </div>
                    <?php endif; ?>

                </div>

                
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body ">
                    <h5 class= "font-weight-bold pb-3"><?php echo e(__('Placeholders')); ?></h5>
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="card">
                            <div class="card-header card-body">
                                <div class="row text-xs">
                                    <h6 class="font-weight-bold mb-4"><?php echo e(__('Variables')); ?></h6>

                                    <?php
                                        $variables = json_decode($curr_noti_tempLang->variables);
                                    ?>
                                    <?php if(!empty($variables) > 0): ?>
                                        <?php $__currentLoopData = $variables; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $var): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="col-6 pb-1">
                                                <p class="mb-1"><?php echo e(__($key)); ?> : <span class="pull-right text-primary"><?php echo e('{'.$var.'}'); ?></span></p>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php echo e(Form::model($curr_noti_tempLang,array('route' => array('notification-templates.update', $curr_noti_tempLang->parent_id), 'method' => 'PUT'))); ?>

                    <div class="row">
                        <div class="form-group col-12">
                            <?php echo e(Form::label('content',__('Notification Message'),['class'=>'form-label text-dark'])); ?>

                            <?php echo e(Form::textarea('content',$curr_noti_tempLang->content,array('class'=>'form-control','required'=>'required','rows'=>'04','placeholder'=>'EX. Hello, {company_name}'))); ?>

                            <small><?php echo e(__('A variable is to be used in such a way.')); ?> <span class="text-primary"><?php echo e(__('Ex. Hello, {user_name}')); ?></span></small>
                        </div>
                    </div>
                    <hr>
                    <div class="col-md-12 text-end">
                        <?php echo e(Form::hidden('lang',null)); ?>

                        <input type="submit" value="<?php echo e(__('Save Changes')); ?>" class="btn btn-print-invoice  btn-primary m-r-10">
                    </div>
                    <?php echo e(Form::close()); ?>

                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/reyrockstreet/Sites/epic-fh/resources/views/notification_templates/index.blade.php ENDPATH**/ ?>