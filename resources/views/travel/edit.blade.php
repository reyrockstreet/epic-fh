{{Form::model($travel,array('route' => array('travel.update', $travel->id), 'method' => 'PUT')) }}
 <div class="modal-body">
     {{-- start for ai module--}}
     @php
         $plan = \App\Models\Plan::find(\Auth::user()->plan);
     @endphp
     @if($plan->chatgpt == 1)
     <div class="text-end">
         <a href="#" data-size="md" class="btn  btn-primary btn-icon btn-sm" data-ajax-popup-over="true" data-url="{{ route('generate',['travel']) }}"
            data-bs-placement="top" data-title="{{ __('Generate content with AI') }}">
             <i class="fas fa-robot"></i> <span>{{__('Generate with AI')}}</span>
         </a>
     </div>
     @endif
     {{-- end for ai module--}}
    <div class="row">
        <div class="form-group col-md-12">
            {{ Form::label('employee_id', __('Employee'),['class'=>'form-label'])}}
            {{ Form::select('employee_id', $employees,null, array('class' => 'form-control select','required'=>'required')) }}
        </div>
        <div class="form-group col-lg-6 col-md-6">
            {{Form::label('start_date',__('Start Date'),['class'=>'form-label'])}}
            {{Form::date('start_date',null,array('class'=>'form-control'))}}
        </div>
        <div class="form-group col-lg-6 col-md-6">
            {{Form::label('end_date',__('End Date'),['class'=>'form-label'])}}
            {{Form::date('end_date',null,array('class'=>'form-control'))}}
        </div>
        <div class="form-group col-lg-6 col-md-6">
            {{Form::label('purpose_of_visit',__('Purpose of Trip'),['class'=>'form-label'])}}
            {{Form::text('purpose_of_visit',null,array('class'=>'form-control'))}}
        </div>
        <div class="form-group col-lg-6 col-md-6">
            {{Form::label('place_of_visit',__('Country'),['class'=>'form-label'])}}
            {{Form::text('place_of_visit',null,array('class'=>'form-control'))}}
        </div>
        <div class="form-group col-md-12">
            {{Form::label('description',__('Description'),['class'=>'form-label'])}}
            {{Form::textarea('description',null,array('class'=>'form-control','placeholder'=>__('Enter Description')))}}
        </div>

    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Update')}}" class="btn  btn-primary">
</div>
{{Form::close()}}
