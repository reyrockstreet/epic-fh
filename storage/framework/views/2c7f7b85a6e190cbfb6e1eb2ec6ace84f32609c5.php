<?php
    use App\Models\Utility;
      //  $logo=asset(Storage::url('uploads/logo/'));
        $logo=\App\Models\Utility::get_file('uploads/logo/');
        $company_logo=Utility::getValByName('company_logo_dark');
        $company_logos=Utility::getValByName('company_logo_light');
        $company_small_logo=Utility::getValByName('company_small_logo');
        $setting = \App\Models\Utility::colorset();
        $mode_setting = \App\Models\Utility::mode_layout();
        $emailTemplate     = \App\Models\EmailTemplate::first();
        $lang= Auth::user()->lang;


?>

<?php if(isset($setting['cust_theme_bg']) && $setting['cust_theme_bg'] == 'on'): ?>
    <nav class="dash-sidebar light-sidebar transprent-bg">
<?php else: ?>
    <nav class="dash-sidebar light-sidebar ">
<?php endif; ?>
    <div class="navbar-wrapper">
        <div class="m-header main-logo">
            <a href="#" class="b-brand">


                <?php if($mode_setting['cust_darklayout'] && $mode_setting['cust_darklayout'] == 'on' ): ?>
                    <img src="<?php echo e($logo . '/' . (isset($company_logos) && !empty($company_logos) ? $company_logos : 'logo-dark.png')); ?>"
                         alt="<?php echo e(config('app.name', 'FH-Management-System')); ?>" class="logo logo-lg">
                <?php else: ?>
                    <img src="<?php echo e($logo . '/' . (isset($company_logo) && !empty($company_logo) ? $company_logo : 'logo-dark.png')); ?>"
                         alt="<?php echo e(config('app.name', 'FH-Management-System')); ?>" class="logo logo-lg">
                <?php endif; ?>

            </a>
        </div>
        <div class="navbar-content">
            <?php if(\Auth::user()->type != 'client'): ?>
                <ul class="dash-navbar">
                    <!--------------------- Start Dashboard ----------------------------------->
                    <?php if( Gate::check('show hrm dashboard') || Gate::check('show project dashboard') || Gate::check('show account dashboard') || Gate::check('show crm dashboard') || Gate::check('show pos dashboard')): ?>
                        <li class="dash-item dash-hasmenu
                                <?php echo e(( Request::segment(1) == null ||Request::segment(1) == 'account-dashboard' || Request::segment(1) == 'income report'
                                   || Request::segment(1) == 'report' || Request::segment(1) == 'reports-payroll' || Request::segment(1) == 'reports-leave'
                                   || Request::segment(1) == 'reports-monthly-attendance' || Request::segment(1) == 'reports-lead' || Request::segment(1) == 'reports-deal'
                                   || Request::segment(1) == 'pos-dashboard'|| Request::segment(1) == 'reports-warehouse' || Request::segment(1) == 'reports-daily-purchase'
                                   || Request::segment(1) == 'reports-monthly-purchase' || Request::segment(1) == 'reports-daily-pos' ||Request::segment(1) == 'reports-monthly-pos') ?'active dash-trigger':''); ?>">
                                <a href="#!" class="dash-link ">
                                    <span class="dash-micon">
                                        <i class="ti ti-home"></i>
                                    </span>
                                    <span class="dash-mtext"><?php echo e(__('Dashboard')); ?></span>
                                    <span class="dash-arrow"><i data-feather="chevron-right"></i></span></a>
                                    
                        </li>
                    <?php endif; ?>
                    <!--------------------- End Dashboard ----------------------------------->

                    <!--------------------- Start GOLD System ----------------------------------->

                    <?php if(\Auth::user()->type!='super admin' && ( Gate::check('manage user') || Gate::check('manage role') || Gate::check('manage client'))): ?>
                        <li class="dash-item dash-hasmenu <?php echo e((Request::segment(1) == 'users' || Request::segment(1) == 'roles'
                            || Request::segment(1) == 'clients'  || Request::segment(1) == 'userlogs')?' active dash-trigger':''); ?>">

                            <a href="#!" class="dash-link ">
                                <span class="dash-micon">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-currency-guarani" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M16.007 7.54a5.965 5.965 0 0 0 -4.008 -1.54a6 6 0 0 0 -5.992 6c0 3.314 2.682 6 5.992 6a5.965 5.965 0 0 0 4 -1.536c.732 -.66 1.064 -2.148 1 -4.464h-5" />
                                    <path d="M12 20v-16" />
                                    </svg>
                                </span>
                                    <span class="dash-mtext">Gold Management</span>
                                <span class="dash-arrow"><i data-feather="chevron-right"></i></span>
                            </a>
                            <ul class="dash-submenu">
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage user')): ?>
                                    <li class="dash-item <?php echo e((Request::route()->getName() == 'reportgold.index' || Request::route()->getName() == 'reportgold.create' || Request::route()->getName() == 'reportgold.edit' || Request::route()->getName() == 'user.userlog') ? ' active' : ''); ?>">
                                        <a class="dash-link" href="<?php echo e(route('reportgold.index')); ?>">Report Gold</a>
                                    </li>
                                <?php endif; ?>

                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage user')): ?>
                                    <li class="dash-item <?php echo e((Request::route()->getName() == 'users.index' || Request::route()->getName() == 'users.create' || Request::route()->getName() == 'users.edit' || Request::route()->getName() == 'user.userlog') ? ' active' : ''); ?>">
                                        <a class="dash-link" href="#">Expense Gold</a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </li>
                    <?php endif; ?>

                    <!--------------------- End Gold Management System----------------------------------->


                    <!--------------------- Start Account Management ----------------------------------->

                    <?php if(\Auth::user()->show_project() == 1): ?>
                        <?php if( Gate::check('manage project')): ?>
                            <li class="dash-item dash-hasmenu
                                            <?php echo e(( Request::segment(1) == 'project' || Request::segment(1) == 'bugs-report' || Request::segment(1) == 'bugstatus' ||
                                                 Request::segment(1) == 'project-task-stages' || Request::segment(1) == 'calendar' || Request::segment(1) == 'timesheet-list' ||
                                                 Request::segment(1) == 'taskboard' || Request::segment(1) == 'timesheet-list' || Request::segment(1) == 'taskboard' ||
                                                 Request::segment(1) == 'project' || Request::segment(1) == 'projects' || Request::segment(1) == 'project_report') ? 'active dash-trigger' : ''); ?>">
                                <a href="#!" class="dash-link"><span class="dash-micon"><i class="ti ti-share"></i></span><span class="dash-mtext">Account Management</span><span class="dash-arrow"><i data-feather="chevron-right"></i></span></a>
                                <ul class="dash-submenu">
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage project')): ?>
                                        <li class="dash-item  <?php echo e(Request::segment(1) == 'project' || Request::route()->getName() == 'projects.list' || Request::route()->getName() == 'projects.list' ||Request::route()->getName() == 'projects.index' || Request::route()->getName() == 'projects.show' || request()->is('projects/*') ? 'active' : ''); ?>">
                                            <a class="dash-link" href="#">Games</a>
                                        </li>
                                    <?php endif; ?>
                                    <?php if( Gate::check('manage bank account') ||  Gate::check('manage bank transfer')): ?>
                                        <li class="dash-item <?php echo e((Request::route()->getName() == 'account-games.index' || Request::route()->getName() == 'account-games.create' || Request::route()->getName() == 'account-games.edit') ? ' active' : ''); ?>">
                                            <a class="dash-link" href="<?php echo e(route('account-games.index')); ?>">Account</a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </li>
                        <?php endif; ?>
                    <?php endif; ?>

                    <!--------------------- End Account ----------------------------------->



                    <!--------------------- Start HRM ----------------------------------->
                    

                    <!--------------------- End HRM ----------------------------------->

                    <!--------------------- Start Account ----------------------------------->
                    
                    <!--------------------- End Account ----------------------------------->

                    <!--------------------- Start CRM ----------------------------------->
                            
                    <!--------------------- End Project ----------------------------------->



                    <!--------------------- Start User Management System ----------------------------------->

                    <?php if(\Auth::user()->type!='super admin' && ( Gate::check('manage user') || Gate::check('manage role') || Gate::check('manage client'))): ?>
                        <li class="dash-item dash-hasmenu <?php echo e((Request::segment(1) == 'users' || Request::segment(1) == 'roles'
                            || Request::segment(1) == 'clients'  || Request::segment(1) == 'userlogs')?' active dash-trigger':''); ?>">

                            <a href="#!" class="dash-link "
                            ><span class="dash-micon"><i class="ti ti-users"></i></span
                                ><span class="dash-mtext"><?php echo e(__('User Management')); ?></span
                                ><span class="dash-arrow"><i data-feather="chevron-right"></i></span
                                ></a>
                            <ul class="dash-submenu">
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage user')): ?>
                                    <li class="dash-item <?php echo e((Request::route()->getName() == 'users.index' || Request::route()->getName() == 'users.create' || Request::route()->getName() == 'users.edit' || Request::route()->getName() == 'user.userlog') ? ' active' : ''); ?>">
                                        <a class="dash-link" href="<?php echo e(route('users.index')); ?>"><?php echo e(__('User')); ?></a>
                                    </li>
                                <?php endif; ?>
                                





                            </ul>
                        </li>
                    <?php endif; ?>

                    <!--------------------- End User Managaement System----------------------------------->


                    <!--------------------- Start Products System ----------------------------------->
                        
                    





                    <!--------------------- End System Setup ----------------------------------->
                </ul>
                <?php endif; ?>
                
            <?php if((\Auth::user()->type == 'super admin')): ?>
                <ul class="dash-navbar">
                    <?php if(Gate::check('manage super admin dashboard')): ?>
                        <li class="dash-item dash-hasmenu <?php echo e((Request::segment(1) == 'dashboard') ? ' active' : ''); ?>">
                            <a href="<?php echo e(route('client.dashboard.view')); ?>" class="dash-link">
                                <span class="dash-micon"><i class="ti ti-home"></i></span><span class="dash-mtext"><?php echo e(__('Dashboard')); ?></span>
                            </a>
                        </li>

                    <?php endif; ?>


                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage user')): ?>
                        <li class="dash-item dash-hasmenu <?php echo e((Request::route()->getName() == 'users.index' || Request::route()->getName() == 'users.create' || Request::route()->getName() == 'users.edit') ? ' active' : ''); ?>">
                            <a href="<?php echo e(route('users.index')); ?>" class="dash-link">
                                <span class="dash-micon"><i class="ti ti-users"></i></span><span class="dash-mtext"><?php echo e(__('User')); ?></span>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if(Gate::check('manage plan')): ?>
                        <li class="dash-item dash-hasmenu  <?php echo e((Request::segment(1) == 'plans')?'active':''); ?>">
                            <a href="<?php echo e(route('plans.index')); ?>" class="dash-link">
                                <span class="dash-micon"><i class="ti ti-trophy"></i></span><span class="dash-mtext"><?php echo e(__('Plan')); ?></span>
                            </a>
                        </li>

                    <?php endif; ?>
                    <?php if(\Auth::user()->type=='super admin'): ?>
                        <li class="dash-item dash-hasmenu <?php echo e(request()->is('plan_request*') ? 'active' : ''); ?>">
                            <a href="<?php echo e(route('plan_request.index')); ?>" class="dash-link">
                                <span class="dash-micon"><i class="ti ti-arrow-up-right-circle"></i></span><span class="dash-mtext"><?php echo e(__('Plan Request')); ?></span>
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php if(Gate::check('manage coupon')): ?>
                    <!--
                        <li class="dash-item dash-hasmenu <?php echo e((Request::segment(1) == 'coupons')?'active':''); ?>">
                            <a href="<?php echo e(route('coupons.index')); ?>" class="dash-link">
                                <span class="dash-micon"><i class="ti ti-gift"></i></span><span class="dash-mtext"><?php echo e(__('Coupon')); ?></span>
                            </a>
                        </li>
                    -->
                    <?php endif; ?>
                    <?php if(Gate::check('manage order')): ?>
                        <li class="dash-item dash-hasmenu  <?php echo e((Request::segment(1) == 'orders')?'active':''); ?>">
                            <a href="<?php echo e(route('order.index')); ?>" class="dash-link">
                                <span class="dash-micon"><i class="ti ti-shopping-cart-plus"></i></span><span class="dash-mtext"><?php echo e(__('Order')); ?></span>
                            </a>
                        </li>
                    <?php endif; ?>
                    <!--
                        <li class="dash-item dash-hasmenu <?php echo e(Request::segment(1) == 'email_template' || Request::route()->getName() == 'manage.email.language' ? ' active dash-trigger' : 'collapsed'); ?>">
                            <a href="<?php echo e(route('manage.email.language',[$emailTemplate ->id,\Auth::user()->lang])); ?>" class="dash-link">
                                <span class="dash-micon"><i class="ti ti-template"></i></span>
                                <span class="dash-mtext"><?php echo e(__('Email Template')); ?></span></a>
                        </li>
                    -->

                    <?php if(Gate::check('manage system settings')): ?>
                        <li class="dash-item dash-hasmenu <?php echo e((Request::route()->getName() == 'systems.index') ? ' active' : ''); ?>">
                            <a href="<?php echo e(route('systems.index')); ?>" class="dash-link">
                                <span class="dash-micon"><i class="ti ti-settings"></i></span><span class="dash-mtext"><?php echo e(__('Settings')); ?></span>
                            </a>
                        </li>
                    <?php endif; ?>

                </ul>
            <?php endif; ?>

                <div class="navbar-footer border-top ">
                    <div class="d-flex align-items-center py-3 px-3 border-bottom">
                        <div class="me-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="29" height="30" viewBox="0 0 29 30" fill="none">
                                <circle cx="14.5" cy="15.1846" r="14.5" fill="#6FD943"></circle>
                                <path opacity="0.4" d="M22.08 8.66459C21.75 8.28459 21.4 7.92459 21.02 7.60459C19.28 6.09459 17 5.18461 14.5 5.18461C12.01 5.18461 9.73999 6.09459 7.98999 7.60459C7.60999 7.92459 7.24999 8.28459 6.92999 8.66459C5.40999 10.4146 4.5 12.6946 4.5 15.1846C4.5 17.6746 5.40999 19.9546 6.92999 21.7046C7.24999 22.0846 7.60999 22.4446 7.98999 22.7646C9.73999 24.2746 12.01 25.1846 14.5 25.1846C17 25.1846 19.28 24.2746 21.02 22.7646C21.4 22.4446 21.75 22.0846 22.08 21.7046C23.59 19.9546 24.5 17.6746 24.5 15.1846C24.5 12.6946 23.59 10.4146 22.08 8.66459ZM14.5 19.6246C13.54 19.6246 12.65 19.3146 11.93 18.7946C11.52 18.5146 11.17 18.1646 10.88 17.7546C10.37 17.0346 10.06 16.1346 10.06 15.1846C10.06 14.2346 10.37 13.3346 10.88 12.6146C11.17 12.2046 11.52 11.8546 11.93 11.5746C12.65 11.0546 13.54 10.7446 14.5 10.7446C15.46 10.7446 16.35 11.0546 17.08 11.5646C17.49 11.8546 17.84 12.2046 18.13 12.6146C18.64 13.3346 18.95 14.2346 18.95 15.1846C18.95 16.1346 18.64 17.0346 18.13 17.7546C17.84 18.1646 17.49 18.5146 17.08 18.8046C16.35 19.3146 15.46 19.6246 14.5 19.6246Z" fill="#162C4E"></path>
                                <path d="M22.08 8.66459L18.18 12.5746C18.16 12.5846 18.15 12.6046 18.13 12.6146C17.84 12.2046 17.49 11.8546 17.08 11.5646C17.09 11.5446 17.1 11.5346 17.12 11.5146L21.02 7.60459C21.4 7.92459 21.75 8.28459 22.08 8.66459Z" fill="#162C4E"></path>
                                <path d="M11.9297 18.7947C11.9197 18.8147 11.9097 18.8347 11.8897 18.8547L7.98969 22.7647C7.60969 22.4447 7.24969 22.0847 6.92969 21.7047L10.8297 17.7947C10.8397 17.7747 10.8597 17.7647 10.8797 17.7547C11.1697 18.1647 11.5197 18.5147 11.9297 18.7947Z" fill="#162C4E"></path>
                                <path d="M11.9297 11.5746C11.5197 11.8546 11.1697 12.2045 10.8797 12.6145C10.8597 12.6045 10.8497 12.5846 10.8297 12.5746L6.92969 8.66453C7.24969 8.28453 7.60969 7.92453 7.98969 7.60453L11.8897 11.5146C11.9097 11.5346 11.9197 11.5546 11.9297 11.5746Z" fill="#162C4E"></path>
                                <path d="M22.08 21.7046C21.75 22.0846 21.4 22.4446 21.02 22.7646L17.12 18.8546C17.1 18.8346 17.09 18.8246 17.08 18.8046C17.49 18.5146 17.84 18.1646 18.13 17.7546C18.15 17.7646 18.16 17.7746 18.18 17.7946L22.08 21.7046Z" fill="#162C4E"></path>
                            </svg>
                        </div>
                        <div>
                            <b class="d-block f-w-700"><?php echo e(__('Epic FH System')); ?></b>
                            <span><?php echo e(__('Check out our repository')); ?> </span>
                        </div>
                    </div>
                </div>
        </div>
    </div>
</nav>
<?php /**PATH /Users/reyrockstreet/Sites/epic-fh/resources/views/partials/admin/menu.blade.php ENDPATH**/ ?>