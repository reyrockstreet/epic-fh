    <?php echo e(Form::model($plan, array('route' => array('plans.update', $plan->id), 'method' => 'PUT', 'enctype' => "multipart/form-data"))); ?>

    <div class="modal-body">
        
        <?php
            $settings = \App\Models\Utility::settings();
        ?>
        <?php if(!empty($settings['chat_gpt_key'])): ?>
        <div class="text-end">
            <a href="#" data-size="md" class="btn  btn-primary btn-icon btn-sm" data-ajax-popup-over="true" data-url="<?php echo e(route('generate',['plan'])); ?>"
               data-bs-placement="top" data-title="<?php echo e(__('Generate content with AI')); ?>">
                <i class="fas fa-robot"></i> <span><?php echo e(__('Generate with AI')); ?></span>
            </a>
        </div>
        <?php endif; ?>
        

    <div class="row">
        <div class="form-group col-md-6">
            <?php echo e(Form::label('name',__('Name'),['class'=>'form-label'])); ?>

            <?php echo e(Form::text('name',null,array('class'=>'form-control font-style','placeholder'=>__('Enter Plan Name'),'required'=>'required'))); ?>

        </div>
        <?php if($plan->price >0): ?>
            <div class="form-group col-md-6">
                <?php echo e(Form::label('price',__('Price'),['class'=>'form-label'])); ?>

                <?php echo e(Form::number('price',null,array('class'=>'form-control','placeholder'=>__('Enter Plan Price'),'required'=>'required'))); ?>

            </div>
        <?php endif; ?>
        <div class="form-group col-md-6">
            <?php echo e(Form::label('duration', __('Duration'),['class'=>'form-label'])); ?>

            <?php echo Form::select('duration', $arrDuration, null,array('class' => 'form-control select','required'=>'required')); ?>

        </div>
        <div class="form-group col-md-6">
            <?php echo e(Form::label('max_users',__('Maximum Users'),['class'=>'form-label'])); ?>

            <?php echo e(Form::number('max_users',null,array('class'=>'form-control','required'=>'required'))); ?>

            <span class="small"><?php echo e(__('Note: "-1" for Unlimited')); ?></span>
        </div>
        <div class="form-group col-md-6">
            <?php echo e(Form::label('max_customers',__('Maximum Customers'),['class'=>'form-label'])); ?>

            <?php echo e(Form::number('max_customers',null,array('class'=>'form-control','required'=>'required'))); ?>

            <span class="small"><?php echo e(__('Note: "-1" for Unlimited')); ?></span>
        </div>
        <div class="form-group col-md-6">
            <?php echo e(Form::label('max_venders',__('Maximum Venders'),['class'=>'form-label'])); ?>

            <?php echo e(Form::number('max_venders',null,array('class'=>'form-control','required'=>'required'))); ?>

            <span class="small"><?php echo e(__('Note: "-1" for Unlimited')); ?></span>
        </div>
        <div class="form-group col-md-6">
            <?php echo e(Form::label('max_clients',__('Maximum Clients'),['class'=>'form-label'])); ?>

            <?php echo e(Form::number('max_clients',null,array('class'=>'form-control','required'=>'required'))); ?>

            <span class="small"><?php echo e(__('Note: "-1" for Unlimited')); ?></span>
        </div>
        <div class="form-group col-md-6">
            <?php echo e(Form::label('storage_limit', __('Storage limit'), ['class' => 'form-label'])); ?>

            <div class="input-group">
                <?php echo e(Form::number('storage_limit', null,array('class'=>'form-control','required'=>'required'))); ?>

                <div class="input-group-append">
                <span class="input-group-text"
                      id="basic-addon2"><?php echo e(__('MB')); ?></span>
                </div>
            </div>
            <span class="small"><?php echo e(__('Note: upload size ( In MB)')); ?></span>
        </div>


        <div class="form-group col-md-12">
            <?php echo e(Form::label('description', __('Description'),['class'=>'form-label'])); ?>

            <?php echo Form::textarea('description', null, ['class'=>'form-control','rows'=>'2']); ?>

        </div>
        <div class="form-group col-md-3">
            <div class="form-check form-switch ">
                <input type="checkbox" class="form-check-input" name="enable_crm" id="enable_crm" <?php echo e($plan['crm'] == 1 ? 'checked="checked"' : ''); ?>>
                <label class="custom-control-label form-label" for="enable_crm"><?php echo e(__('CRM')); ?></label>
            </div>
        </div>
        <div class="form-group col-md-3">
            <div class="form-check form-switch">
                <input type="checkbox" class="form-check-input" name="enable_project" id="enable_project" <?php echo e($plan['project'] == 1 ? 'checked="checked"' : ''); ?>>
                <label class="custom-control-label form-label" for="enable_project"><?php echo e(__('Project')); ?></label>
            </div>
        </div>
        <div class="form-group col-md-3">
            <div class="form-check form-switch">
                <input type="checkbox" class="form-check-input" name="enable_hrm" id="enable_hrm" <?php echo e($plan['hrm'] == 1 ? 'checked="checked"' : ''); ?>>
                <label class="custom-control-label form-label" for="enable_hrm"><?php echo e(__('HRM')); ?></label>
            </div>
        </div>
        <div class="form-group col-md-3">
            <div class="form-check form-switch">
                <input type="checkbox" class="form-check-input" name="enable_account" id="enable_account" <?php echo e($plan['account'] == 1 ? 'checked="checked"' : ''); ?>>
                <label class="custom-control-label form-label" for="enable_account"><?php echo e(__('Account')); ?></label>
            </div>
        </div>
        <div class="form-group col-md-3">
            <div class="form-check form-switch">
                <input type="checkbox" class="form-check-input" name="enable_pos" id="enable_pos" <?php echo e($plan['pos'] == 1 ? 'checked="checked"' : ''); ?>>
                <label class="custom-control-label form-label" for="enable_pos"><?php echo e(__('POS')); ?></label>
            </div>
        </div>
        <div class="form-group col-md-3">
            <div class="form-check form-switch">
                <input type="checkbox" class="form-check-input" name="enable_chatgpt" id="enable_chatgpt" <?php echo e($plan['chatgpt'] == 1 ? 'checked="checked"' : ''); ?>>
                <label class="custom-control-label form-label" for="enable_chatgpt"><?php echo e(__('Chat GPT')); ?></label>
            </div>
        </div>

    </div>
    </div>

    <div class="modal-footer">
    <input type="button" value="<?php echo e(__('Cancel')); ?>" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="<?php echo e(__('Update')); ?>" class="btn  btn-primary">
</div>
    <?php echo e(Form::close()); ?>


<?php /**PATH /Users/reyrockstreet/Sites/epic-fh/resources/views/plan/edit.blade.php ENDPATH**/ ?>