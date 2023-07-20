@extends('layouts.admin')
@section('page-title')
    {{__('Manage Account')}}
@endsection
@section('breadcrumb')
    {{--<li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>--}}
    <li class="breadcrumb-item"><a href="/">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Account')}}</li>
@endsection

@section('action-btn')
    <div class="float-end">
        @can('create bank account')
            {{--<a href="#" data-url="{{ route('bank-account.create') }}" data-ajax-popup="true" data-size="lg" data-bs-toggle="tooltip" title="{{__('Create')}}" data-title="{{__('Create New Bank Account')}}" class="btn btn-sm btn-primary">--}}
            <a href="#">
                <i class="ti ti-plus"></i>
            </a>

        @endcan
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body table-border-style table-border-style">
                    <h5></h5>
                    <div class="table-responsive">
                        <table class="table datatable">
                            <thead>
                            <tr>
                                <th> {{__('Account Name')}}</th>
                                <th> {{__('Account Email')}}</th>
                                <th> {{__('Account Pasword')}}</th>
                                <th> {{__('Game')}}</th>
                                <th> {{__('Current Gold')}}</th>
                                <th> {{__('Farmer')}}</th>

                                    <th width="10%"> {{__('Action')}}</th>

                            </tr>
                            </thead>

                            <tbody>
                            @foreach ($accounts as $account)
                                <tr class="font-style">
                                    <td>{{  $account->account_name}}</td>
                                    <td>{{  $account->account_email}}</td>
                                    <td>{{  $account->account_password}}</td>
                                    {{--<td>{{  $account->account_games_id}}</td>--}}
                                    <td>{{  ('World of Warcraft')}}</td>
                                    <td>{{  \Auth::user()->priceFormat($account->account_gold)}}</td>
                                    {{--<td>{{  $account->created_by}}</td>--}}
                                    <td>{{  ('Rey')}}</td>
                                    @if(Gate::check('edit bank account') || Gate::check('delete bank account'))
                                        <td class="Action">
                                            <span>
                                                {{--
                                            @if($account->holder_name!='Cash')
                                                    @can('edit bank account')
                                                        <div class="action-btn bg-primary ms-2">
                                                            <a href="#" class="mx-3 btn btn-sm align-items-center" data-url="{{ route('bank-account.edit',$account->id) }}" data-ajax-popup="true" title="{{__('Edit')}}" data-title="{{__('Edit Bank Account')}}"data-bs-toggle="tooltip"  data-size="lg"  data-original-title="{{__('Edit')}}">
                                                                <i class="ti ti-pencil text-white"></i>
                                                            </a>
                                                        </div>
                                                    @endcan
                                                    @can('delete bank account')
                                                            <div class="action-btn bg-danger ms-2">
                                                                {!! Form::open(['method' => 'DELETE', 'route' => ['bank-account.destroy', $account->id],'id'=>'delete-form-'.$account->id]) !!}
                                                                <a href="#" class="mx-3 btn btn-sm align-items-center bs-pass-para" data-bs-toggle="tooltip" title="{{__('Delete')}}" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$account->id}}').submit();">
                                                                    <i class="ti ti-trash text-white text-white"></i>
                                                                </a>
                                                                {!! Form::close() !!}
                                                            </div>
                                                    @endcan
                                                @else
                                                    -
                                                @endif
                                                --}}
                                            </span>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
