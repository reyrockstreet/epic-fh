<?php $__env->startSection('title'); ?>
    <?php echo e(__('Dashboard')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
    <li class="breadcrumb-item"><?php echo e(__('Client')); ?></li>

<?php $__env->stopSection(); ?>


<?php $__env->startPush('theme-script'); ?>
    <script src="<?php echo e(asset('assets/js/plugins/apexcharts.min.js')); ?>"></script>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('script-page'); ?>


    <script>
            <?php if($calenderTasks): ?>
            (function () {
                var etitle;
                var etype;
                var etypeclass;
                var calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay'
                    },
                    themeSystem: 'bootstrap',
                    initialDate: '<?php echo e($transdate); ?>',
                    slotDuration: '00:10:00',
                    navLinks: true,
                    droppable: true,
                    selectable: true,
                    selectMirror: true,
                    editable: true,
                    dayMaxEvents: true,
                    handleWindowResize: true,
                    events:<?php echo json_encode($calenderTasks); ?>,

                });
                calendar.render();
            })();
        <?php endif; ?>

        $(document).on('click', '.fc-day-grid-event', function (e) {
            if (!$(this).hasClass('deal')) {
                e.preventDefault();
                var event = $(this);
                var title = $(this).find('.fc-content .fc-title').html();
                var size = 'md';
                var url = $(this).attr('href');
                $("#commonModal .modal-title").html(title);
                $("#commonModal .modal-dialog").addClass('modal-' + size);

                $.ajax({
                    url: url,
                    success: function (data) {
                        $('#commonModal .modal-body').html(data);
                        $("#commonModal").modal('show');
                    },
                    error: function (data) {
                        data = data.responseJSON;
                        show_toastr('error', data.error, 'error')
                    }
                });
            }
        });



    </script>
    <script>


        (function () {
            var chartBarOptions = {
                series: <?php echo json_encode($taskData['dataset']); ?>,


                chart: {
                    height: 250,
                    type: 'area',
                    // type: 'line',
                    dropShadow: {
                        enabled: true,
                        color: '#000',
                        top: 18,
                        left: 7,
                        blur: 10,
                        opacity: 0.2
                    },
                    toolbar: {
                        show: false
                    }
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    width: 2,
                    curve: 'smooth'
                },
                title: {
                    text: '',
                    align: 'left'
                },
                xaxis: {
                    categories:<?php echo json_encode($taskData['label']); ?>,
                    title: {
                        text: "<?php echo e(__('Days')); ?>"
                    }
                },
                colors: ['#6fd944', '#883617','#4e37b9','#8f841b'],

                grid: {
                    strokeDashArray: 4,
                },
                legend: {
                    show: false,
                },
                // markers: {
                //     size: 4,
                //     colors: ['#3b6b1d', '#be7713' ,'#2037dc','#cbbb27'],
                //     opacity: 0.9,
                //     strokeWidth: 2,
                //     hover: {
                //         size: 7,
                //     }
                // },
                yaxis: {
                    title: {
                        text: "<?php echo e(__('Amount')); ?>"
                    },

                }

            };
            var arChart = new ApexCharts(document.querySelector("#chart-sales"), chartBarOptions);
            arChart.render();
        })();



        (function () {
            var options = {
                chart: {
                    height: 140,
                    type: 'donut',
                },
                dataLabels: {
                    enabled: false,
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '70%',
                        }
                    }
                },
                series: <?php echo json_encode(array_values($projectData)); ?>,
                colors:["#bd9925", "#2f71bd", "#720d3a","#ef4917"],
                labels:   <?php echo json_encode($project_status); ?>,
                legend: {
                    show: true
                }
            };
            var chart = new ApexCharts(document.querySelector("#chart-doughnut"), options);
            chart.render();
        })();
    </script>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<?php

  $project_task_percentage = $project['project_task_percentage'];
  $label='';
        if($project_task_percentage<=15){
            $label='bg-danger';
        }else if ($project_task_percentage > 15 && $project_task_percentage <= 33) {
            $label='bg-warning';
        } else if ($project_task_percentage > 33 && $project_task_percentage <= 70) {
            $label='bg-primary';
        } else {
            $label='bg-primary';
        }


  $project_percentage = $project['project_percentage'];
  $label1='';
        if($project_percentage<=15){
            $label1='bg-danger';
        }else if ($project_percentage > 15 && $project_percentage <= 33) {
            $label1='bg-warning';
        } else if ($project_percentage > 33 && $project_percentage <= 70) {
            $label1='bg-primary';
        } else {
            $label1='bg-primary';
        }

  $project_bug_percentage = $project['project_bug_percentage'];
  $label2='';
      if($project_bug_percentage<=15){
        $label2='bg-danger';
      }else if ($project_bug_percentage > 15 && $project_bug_percentage <= 33) {
        $label2='bg-warning';
      } else if ($project_bug_percentage > 33 && $project_bug_percentage <= 70) {
        $label2='bg-primary';
      } else {
        $label2='bg-primary';
      }
?>

    <div class="row">
        <?php if(!empty($arrErr)): ?>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <?php if(!empty($arrErr['system'])): ?>
                    <div class="alert alert-danger text-xs">
                         <?php echo e(__('are required in')); ?> <a href="<?php echo e(route('settings')); ?>" class=""><u> <?php echo e(__('System Setting')); ?></u></a>
                    </div>
                <?php endif; ?>
                <?php if(!empty($arrErr['user'])): ?>
                    <div class="alert alert-danger text-xs">
                         <a href="<?php echo e(route('users')); ?>" class=""><u><?php echo e(__('here')); ?></u></a>
                    </div>
                <?php endif; ?>
                <?php if(!empty($arrErr['role'])): ?>
                    <div class="alert alert-danger text-xs">
                         <a href="<?php echo e(route('roles.index')); ?>" class=""><u><?php echo e(__('here')); ?></u></a>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

<div class="col-sm-12">
    <div class="row">
        <div class="col-xxl-6">
            <div class="row">
                <?php if(isset($arrCount['deal'])): ?>
                    <div class="col-lg-6 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="row align-items-center justify-content-between">
                                    <div class="col-auto mb-3 mb-sm-0">
                                        <div class="d-flex align-items-center">
                                            <div class="theme-avtar bg-primary">
                                                <i class="ti ti-cast"></i>
                                            </div>
                                            <div class="ms-3">
                                                <small class="text-muted"><?php echo e(__('Total')); ?></small>
                                                <h6 class="m-0"><?php echo e(__('Deal')); ?></h6>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-auto text-end">
                                        <h5 class="m-0"><?php echo e($arrCount['deal']); ?></h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <?php if(isset($arrCount['task'])): ?>
                        <div class="col-lg-6 col-md-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row align-items-center justify-content-between">
                                                    <div class="col-auto mb-3 mb-sm-0">
                                                        <div class="d-flex align-items-center">
                                                            <div class="theme-avtar bg-primary">
                                                                <i class="ti ti-list"></i>
                                                            </div>
                                                            <div class="ms-3">
                                                                <small class="text-muted"><?php echo e(__('Total')); ?></small>
                                                                <h6 class="m-0"><?php echo e(__('Deal Task')); ?></h6>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-auto text-end">
                                                        <h5 class="m-0"><?php echo e($arrCount['task']); ?></h5>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                    <?php endif; ?>

                <div class="col-xxl-12">
                    <div class="card">
                        <div class="card-header">
                            <h5><?php echo e(__('Calendar')); ?></h5>
                        </div>
                        <div class="card-body">
                            <div id='calendar' class='calendar'></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-6">
            <div class="row">
                <div class="col--xxl-12">
                    <div class="card">
                            <div class="card-body">
                                <div class="row ">
                                    <div class="col-md-4 col-sm-6">
                                        <div class="align-items-start">
                                            <div class="ms-2">
                                                <p class="text-muted text-sm mb-0"><?php echo e(__('Total Project')); ?></p>
                                                <h3 class="mb-0 text-warning"><?php echo e($project['project_percentage']); ?>%</h3>
                                                <div class="progress mb-0">
                                                    <div class="progress-bar bg-<?php echo e($label1); ?>" style="width: <?php echo e($project['project_percentage']); ?>%;"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-6">
                                        <div class="align-items-start">
                                            <div class="ms-2">
                                                <p class="text-muted text-sm mb-0"><?php echo e(__('Total Project Tasks')); ?></p>
                                                <h3 class="mb-0 text-info"><?php echo e($project['projects_tasks_count']); ?>%</h3>
                                                <div class="progress mb-0">
                                                    <div class="progress-bar bg-<?php echo e($label1); ?>" style="width: <?php echo e($project['project_task_percentage']); ?>%;"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-6">
                                        <div class="align-items-start">

                                            <div class="ms-2">

                                                <p class="text-muted text-sm mb-0"><?php echo e(__('Total Bugs')); ?></p>
                                                <h3 class="mb-0 text-danger"><?php echo e($project['projects_bugs_count']); ?>%</h3>
                                                <div class="progress mb-0">
                                                    <div class="progress-bar bg-<?php echo e($label1); ?>" style="width: <?php echo e($project['project_bug_percentage']); ?>%;"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
                <div class="col-xxl-12">
                    <div class="card">
                        <div class="card-header">
                            <h5><?php echo e(__('Tasks Overview')); ?></h5>
                            <h6 class="last-day-text"><?php echo e(__('Last 7 Days')); ?></h6>
                        </div>
                        <div class="card-body">
                            <div id="chart-sales" height="200" class="p-3"></div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-12">
                    <div class="card">
                        <div class="card-header">
                            <h5><?php echo e(__('Project Status')); ?>

                                <span class="float-end text-muted"><?php echo e(__('Year').' - '.$currentYear); ?></span>
                            </h5>

                        </div>
                        <div class="card-body">
                            <div id="chart-doughnut"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="<?php echo e((Auth::user()->type =='company' || Auth::user()->type =='client') ? 'col-xl-6 col-lg-6 col-md-6' : 'col-xl-8 col-lg-8 col-md-8'); ?> col-sm-12">
            <div class="card bg-none min-410 mx-410">
                <div class="card-header">
                    <h5><?php echo e(__('Top Due Project')); ?></h5>
                </div>
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                    <table class="table align-items-center mb-0">
                        <thead>
                        <tr>
                            <th><?php echo e(__('Task Name')); ?></th>
                            <th><?php echo e(__('Remain Task')); ?></th>
                            <th><?php echo e(__('Due Date')); ?></th>
                            <th><?php echo e(__('Action')); ?></th>
                        </tr>
                        </thead>
                        <tbody class="list">
                        <?php $__empty_1 = true; $__currentLoopData = $project['projects']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php
                                $datetime1 = new DateTime($project->due_date);
                                $datetime2 = new DateTime(date('Y-m-d'));
                                $interval = $datetime1->diff($datetime2);
                                $days = $interval->format('%a');

                                 $project_last_stage = ($project->project_last_stage($project->id))?$project->project_last_stage($project->id)->id:'';
                                $total_task = $project->project_total_task($project->id);
                                $completed_task=$project->project_complete_task($project->id,$project_last_stage);
                                $remain_task=$total_task-$completed_task;
                            ?>
                            <tr>
                                <td class="id-web">
                                    <?php echo e($project->project_name); ?>

                                </td>
                                <td><?php echo e($remain_task); ?></td>
                                <td><?php echo e(Auth::user()->dateFormat($project->end_date)); ?></td>
                                <td>
                                    <div class="action-btn bg-primary ms-2">
                                        <a href="<?php echo e(route('projects.show',$project->id)); ?>" class="mx-3 btn btn-sm align-items-center"><i class="ti ti-eye text-white"></i></a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr class="text-center">
                                <td colspan="4"><?php echo e(__('No Data Found.!')); ?></td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-6">
            <div class="card bg-none min-410 mx-410">
                <div class="card-header">
                    <h5><?php echo e(__('Top Due Task')); ?></h5>
                </div>
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                    <table class="table align-items-center mb-0">
                        <thead>
                        <tr>
                            <th><?php echo e(__('Task Name')); ?></th>
                            <th><?php echo e(__('Assign To')); ?></th>
                            <th><?php echo e(__('Task Stage')); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $top_tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $top_task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td class="id-web">
                                    <?php echo e($top_task->name); ?>

                                </td>
                                <td>
                                    <div class="avatar-group">
                                        <?php if($top_task->users()->count() > 0): ?>
                                            <?php if($users = $top_task->users()): ?>
                                                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php if($key<3): ?>
                                                        <a href="#" class="avatar rounded-circle avatar-sm">
                                                            <img data-original-title="<?php echo e((!empty($user)?$user->name:'')); ?>" <?php if($user->avatar): ?> src="<?php echo e(asset('/storage/uploads/avatar/'.$user->avatar)); ?>" <?php else: ?> src="<?php echo e(asset('assets/img/avatar/avatar-1.png')); ?>" <?php endif; ?> title="<?php echo e($user->name); ?>" class="hweb">
                                                        </a>
                                                    <?php else: ?>
                                                        <?php break; ?>
                                                    <?php endif; ?>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php endif; ?>
                                            <?php if(count($users) > 3): ?>
                                                <a href="#" class="avatar rounded-circle avatar-sm">
                                                    <img  data-original-title="<?php echo e((!empty($user)?$user->name:'')); ?>" <?php if($user->avatar): ?> src="<?php echo e(asset('/storage/uploads/avatar/'.$user->avatar)); ?>" <?php else: ?> src="<?php echo e(asset('assets/img/avatar/avatar-1.png')); ?>" <?php endif; ?> class="hweb">
                                                </a>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <?php echo e(__('-')); ?>

                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td><span class="p-2 px-3 rounded badge bg-"><?php echo e($top_task->stage->name); ?></span></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr class="text-center">
                                <td colspan="4"><?php echo e(__('No Data Found.!')); ?></td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                </div>
            </div>
        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/reyrockstreet/Sites/epic-fh/resources/views/dashboard/clientView.blade.php ENDPATH**/ ?>