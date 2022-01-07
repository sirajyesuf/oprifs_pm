@extends('layouts.admin')

@section('page-title', __('Settings'))

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <section class="nav-tabs border-bottom-0">
                <div class="col-lg-12 our-system">
                    <div class="row">
                        <ul class="nav nav-tabs my-4">
                            <li>
                                <a data-toggle="tab" href="#site-settings" class="active">{{__('Site Setting')}}</a>
                            </li>
                            <li class="annual-billing">
                                <a data-toggle="tab" href="#task-stages-settings" class="">{{__('Task Stages')}} </a>
                            </li>
                            <li class="annual-billing">
                                <a data-toggle="tab" href="#bug-stages-settings" class="">{{__('Bug Stages')}} </a>
                            </li>
                            <li class="annual-billing">
                                <a data-toggle="tab" href="#taxes-settings" class="">{{__('Taxes')}} </a>
                            </li>
                            <li class="annual-billing">
                                <a data-toggle="tab" href="#billing-settings" class="">{{__('Billing')}} </a>
                            </li>
                            <li class="annual-billing">
                                <a data-toggle="tab" href="#payment-settings" class="">{{__('Payment')}} </a>
                            </li>
                            <li class="annual-billing">
                                <a data-toggle="tab" href="#invoices-settings" class="">{{__('Invoices')}} </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="tab-content">
                    <div id="site-settings" class="tab-pane active  ">
                        {{Form::open(array('route'=>['workspace.settings.store', $currentWorkspace->slug],'method'=>'post', 'enctype' => 'multipart/form-data'))}}
                        <div class="row justify-content-center">
                            <div class="col-md-12 col-sm-12">
                                <h4 class="h4 font-weight-400 float-left pb-2">{{__('Site settings')}}</h4>
                            </div>
                            <div class="col-lg-4 col-sm-4 col-md-4">
                                <h4 class="small-title">{{__('Logo')}}</h4>
                                <div class="card setting-card setting-logo-box">
                                    <div class="logo-content">
                                        <img src="@if($currentWorkspace->logo){{asset(Storage::url('logo/'.$currentWorkspace->logo))}}@else{{asset(Storage::url('logo/logo-blue.png'))}}@endif" class="big-logo"/>
                                    </div>
                                    <div class="choose-file mt-5">
                                        <label for="logo">
                                            <div>{{__('Choose file here')}}</div>
                                            <input type="file" class="form-control" name="logo" id="logo" data-filename="edit-logo">
                                        </label>
                                        <p class="edit-logo"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-4 col-md-4">
                                <h4 class="small-title">{{__('Workspace Settings')}}</h4>
                                <div class="card setting-card">
                                    <div class="form-group">
                                        {{Form::label('name',__('Name'),array('class'=>'form-control-label')) }}
                                        {{Form::text('name',$currentWorkspace->name,array('class'=>'form-control', 'required' => 'required','placeholder'=>__('Enter Name')))}}
                                        @error('name')
                                        <span class="invalid-name" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="offset-2 col-lg-12">
                                <input type="submit" value="{{__('Save Changes')}}" class="btn-submit">
                            </div>
                        </div>
                        {{Form::close()}}
                    </div>
                    <div id="task-stages-settings" class="tab-pane">
                        <div class="row justify-content-center">
                            <div class="col-md-6">
                                <div class="card task-stages" data-value="{{json_encode($stages)}}">
                                    <div class="card-header">
                                        <h6 class="float-left">
                                            {{ __('Task Stages') }}
                                            <small class="d-block mt-2">{{__('System will consider last stage as a completed / done task for get progress on project.')}}</small>
                                        </h6>
                                        <button data-repeater-create type="button" class="btn-submit float-right">
                                            <i class="fas fa-plus mr-1"></i>{{__('Create')}}
                                        </button>
                                    </div>
                                    <div class="card-body">
                                        <form method="post" action="{{route('stages.store',$currentWorkspace->slug)}}">
                                            @csrf
                                            <table class="table table-hover" data-repeater-list="stages">
                                                <thead>
                                                <th>
                                                    <div data-toggle="tooltip" data-placement="left" data-title="{{ __('Drag Stage to Change Order') }}" data-original-title="" title="">
                                                        <i class="fas fa-crosshairs"></i>
                                                    </div>
                                                </th>
                                                <th>{{__('Color')}}</th>
                                                <th>{{__('Name')}}</th>
                                                <th class="text-right">{{__('Delete')}}</th>
                                                </thead>
                                                <tbody>
                                                <tr data-repeater-item>
                                                    <td><i class="fas fa-crosshairs sort-handler"></i></td>
                                                    <td>
                                                        <input type="color" name="color">
                                                    </td>
                                                    <td>
                                                        <input type="hidden" name="id" id="id"/>
                                                        <input type="text" name="name" class="form-control mb-0" required/>
                                                    </td>
                                                    <td class="text-right">
                                                        <a data-repeater-delete class="delete-icon"><i class="fas fa-trash"></i></a>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                            <div class="text-right p-4">
                                                <button class="btn-submit" type="submit">{{__('Save')}}</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="bug-stages-settings" class="tab-pane">
                        <div class="row justify-content-center">
                            <div class="col-md-6">
                                <div class="card bug-stages" data-value="{{json_encode($bugStages)}}">
                                    <div class="card-header">
                                        <h6 class="float-left">
                                            {{ __('Bug Stages') }}
                                            <small class="d-block mt-2">{{__('System will consider last stage as a completed / done task for get progress on project.')}}</small>
                                        </h6>
                                        <button data-repeater-create type="button" class="btn-submit float-right">
                                            <i class="fas fa-plus mr-1"></i>{{__('Create')}}
                                        </button>
                                    </div>
                                    <div class="card-body">
                                        <form method="post" action="{{route('bug.stages.store',$currentWorkspace->slug)}}">
                                            @csrf
                                            <table class="table table-hover" data-repeater-list="stages">
                                                <thead>
                                                <th>
                                                    <div data-toggle="tooltip" data-placement="left" data-title="{{ __('Drag Stage to Change Order') }}" data-original-title="" title="">
                                                        <i class="fas fa-crosshairs"></i>
                                                    </div>
                                                </th>
                                                <th>{{__('Color')}}</th>
                                                <th>{{__('Name')}}</th>
                                                <th class="text-right">{{__('Delete')}}</th>
                                                </thead>
                                                <tbody>
                                                <tr data-repeater-item>
                                                    <td><i class="fas fa-crosshairs sort-handler"></i></td>
                                                    <td>
                                                        <input type="color" name="color">
                                                    </td>
                                                    <td>
                                                        <input type="hidden" name="id" id="id"/>
                                                        <input type="text" name="name" class="form-control mb-0" required/>
                                                    </td>
                                                    <td class="text-right">
                                                        <a data-repeater-delete class="delete-icon"><i class="fas fa-trash"></i></a>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                            <div class="text-right p-4">
                                                <button class="btn-submit" type="submit">{{__('Save')}}</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="taxes-settings" class="tab-pane">
                        <div class="row justify-content-center">
                            <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="float-left">
                                            {{ __('Taxes') }}
                                        </h6>
                                        <button class="btn-submit float-right" type="button" data-ajax-popup="true" data-title="{{ __('Add Tax') }}" data-url="{{route('tax.create',$currentWorkspace->slug)}}">
                                            <i class="fas fa-plus mr-1"></i>{{__('Create')}}
                                        </button>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">

                                            <table id="selection-datatable" class="table table-bordered">
                                                <thead>
                                                <tr>
                                                    <th>{{__('Name')}}</th>
                                                    <th>{{__('Rate')}}</th>
                                                    <th width="200px" class="text-right">{{__('Action')}}</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($taxes as $tax)
                                                    <tr>
                                                        <td>{{$tax->name}}</td>
                                                        <td>{{$tax->rate}}%</td>
                                                        <td class="text-right">
                                                            <a href="#" class="edit-icon" data-ajax-popup="true" data-title="{{ __('Edit Tax') }}" data-url="{{route('tax.edit',[$currentWorkspace->slug,$tax->id])}}">
                                                                <i class="fas fa-pencil-alt"></i>
                                                            </a>
                                                            <a href="#" class="delete-icon" data-confirm="{{ __('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?') }}" data-confirm-yes="document.getElementById('delete-form-{{ $tax->id }}').submit();">
                                                                <i class="fas fa-trash"></i>
                                                            </a>
                                                            <form id="delete-form-{{$tax->id}}" action="{{ route('tax.destroy',[$currentWorkspace->slug,$tax->id]) }}" method="POST" style="display: none;">
                                                                @csrf
                                                                @method('DELETE')
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="billing-settings" class="tab-pane">
                        <div class="row justify-content-center">
                            <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="float-left">
                                            {{ __('Billing Details') }}
                                        </h6>
                                    </div>
                                    <div class="card-body p-4">
                                        <form method="post" action="{{route('workspace.settings.store',$currentWorkspace->slug)}}" class="payment-form">
                                            @csrf
                                            <div class="row mt-3">
                                                <div class="form-group col-md-6">
                                                    <label for="company" class="form-control-label">{{ __('Name') }}</label>
                                                    <input type="text" name="company" id="company" class="form-control" value="{{ $currentWorkspace->company }}" required="required"/>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="address" class="form-control-label">{{ __('Address') }}</label>
                                                    <input type="text" name="address" id="address" class="form-control" value="{{$currentWorkspace->address}}" required="required"/>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="city" class="form-control-label">{{__('City')}}</label>
                                                    <input class="form-control" name="city" type="text" value="{{ $currentWorkspace->city }}" id="city">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="state" class="form-control-label">{{__('State')}}</label>
                                                    <input class="form-control" name="state" type="text" value="{{ $currentWorkspace->state }}" id="state">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="zipcode" class="form-control-label">{{__('Zip/Post Code')}}</label>
                                                    <input class="form-control" name="zipcode" type="text" value="{{ $currentWorkspace->zipcode }}" id="zipcode">
                                                </div>
                                                <div class="form-group  col-md-6">
                                                    <label for="country" class="form-control-label">{{__('Country')}}</label>
                                                    <input class="form-control" name="country" type="text" value="{{ $currentWorkspace->country }}" id="country">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="telephone" class="form-control-label">{{__('Telephone')}}</label>
                                                    <input class="form-control" name="telephone" type="text" value="{{ $currentWorkspace->telephone }}" id="telephone">
                                                </div>
                                            </div>

                                            <button type="submit" class="btn-submit">{{ __('Update')}}</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div id="payment-settings" class="tab-pane">
                        <div class="row justify-content-center">
                            <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="float-left">
                                            {{ __('Payment Details') }}
                                            <small class="d-block mt-2">{{__("This detail will use for get payment of invoice from workspace's client")}}</small>
                                        </h6>
                                    </div>
                                    <div class="card-body p-4">
                                        <form method="post" action="{{route('workspace.settings.store',$currentWorkspace->slug)}}" class="payment-form">
                                            @csrf
                                            <div class="row mt-3">
                                                <div class="form-group col-md-6">
                                                    <label for="currency" class="form-control-label">{{ __('Currency') }}</label>
                                                    <input type="text" name="currency" id="currency" class="form-control" value="{{$currentWorkspace->currency}}" required="required"/>
                                                    @if ($errors->has('currency'))
                                                        <span class="invalid-feedback d-block">
                                                            {{ $errors->first('currency') }}
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="currency_code" class="form-control-label">{{ __('Currency Code') }}</label>
                                                    <input type="text" name="currency_code" id="currency_code" class="form-control" value="{{$currentWorkspace->currency_code}}" required="required"/>
                                                    @if ($errors->has('currency_code'))
                                                        <span class="invalid-feedback d-block">
                                                            {{ $errors->first('currency_code') }}
                                                        </span>
                                                    @endif
                                                    <small> {{ __('Note: Add currency code as per three-letter ISO code.') }} <a href="https://stripe.com/docs/currencies" target="_new">{{ __('you can find out here.') }}</a>.</small>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <div id="payment-gateways" class="accordion accordion-spaced">
                                                        <!-- Stripe -->
                                                        <div class="card">
                                                            <div class="card-header py-4" id="stripe-payment" data-toggle="collapse" role="button" data-target="#collapse-stripe" aria-expanded="false" aria-controls="collapse-stripe">
                                                                <h6 class="mb-0"><i class="far fa-credit-card mr-3"></i>{{__('Stripe')}}</h6>
                                                            </div>
                                                            <div id="collapse-stripe" class="collapse" aria-labelledby="stripe-payment" data-parent="#payment-gateways">
                                                                <div class="card-body">
                                                                    <div class="row">
                                                                        <div class="col-6 py-2">
                                                                            <h5 class="h5">{{__('Stripe')}}</h5>
                                                                            <small>{{__('Note: This detail will use for make checkout of plan.')}}</small>
                                                                        </div>
                                                                        <div class="col-6 py-2 text-right">
                                                                            <div class="custom-control custom-switch">
                                                                                <input type="checkbox" class="custom-control-input" name="is_stripe_enabled" id="is_stripe_enabled" {{(isset($currentWorkspace->is_stripe_enabled) && $currentWorkspace->is_stripe_enabled == '1') ? 'checked' : ''}}>
                                                                                <label class="custom-control-label form-control-label" for="is_stripe_enabled">{{__('Enable Stripe')}}</label>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                                                            <div class="form-group">
                                                                                {{ Form::label('stripe_key', __('Stripe Key'),['class' => 'form-control-label']) }}
                                                                                {{ Form::text('stripe_key', (isset($currentWorkspace->stripe_key) && !empty($currentWorkspace->stripe_key)) ? $currentWorkspace->stripe_key :'', ['class' => 'form-control','placeholder' => __('Stripe Key')]) }}
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                                                            <div class="form-group">
                                                                                {{ Form::label('stripe_secret', __('Stripe Secret'),['class' => 'form-control-label']) }}
                                                                                {{ Form::text('stripe_secret', (isset($currentWorkspace->stripe_secret) && !empty($currentWorkspace->stripe_secret)) ? $currentWorkspace->stripe_secret:'', ['class' => 'form-control','placeholder' => __('Stripe Secret')]) }}
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Paypal -->
                                                        <div class="card">
                                                            <div class="card-header py-4" id="paypal-payment" data-toggle="collapse" role="button" data-target="#collapse-paypal" aria-expanded="false" aria-controls="collapse-paypal">
                                                                <h6 class="mb-0"><i class="far fa-credit-card mr-3"></i>{{__('Paypal')}}</h6>
                                                            </div>
                                                            <div id="collapse-paypal" class="collapse" aria-labelledby="paypal-payment" data-parent="#payment-gateways">
                                                                <div class="card-body">
                                                                    <div class="row">
                                                                        <div class="col-6 py-2">
                                                                            <h5 class="h5">{{__('PayPal')}}</h5>
                                                                            <small>{{__('Note: This detail will use for make checkout of plan.')}}</small>
                                                                        </div>
                                                                        <div class="col-6 py-2 text-right">
                                                                            <div class="custom-control custom-switch">
                                                                                <input type="checkbox" class="custom-control-input" name="is_paypal_enabled" id="is_paypal_enabled" {{(isset($currentWorkspace->is_paypal_enabled) && $currentWorkspace->is_paypal_enabled == '1') ? 'checked' : ''}}>
                                                                                <label class="custom-control-label form-control-label" for="is_paypal_enabled">{{__('Enable Paypal')}}</label>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 pb-4">
                                                                            <label class="paypal-label form-control-label" for="paypal_mode">{{__('Paypal Mode')}}</label> <br>
                                                                            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                                                                <label class="btn btn-primary btn-sm {{ (!isset($currentWorkspace->paypal_mode) || empty($currentWorkspace->paypal_mode) || $currentWorkspace->paypal_mode == 'sandbox') ? 'active' : ''}}">
                                                                                    <input type="radio" name="paypal_mode" value="sandbox" {{ (!isset($currentWorkspace->paypal_mode) || empty($currentWorkspace->paypal_mode) || $currentWorkspace->paypal_mode == 'sandbox') ? 'checked' : ''}}>{{ __('Sandbox') }}
                                                                                </label>
                                                                                <label class="btn btn-primary btn-sm {{ (isset($currentWorkspace->paypal_mode) && $currentWorkspace->paypal_mode == 'live') ? 'active' : ''}}">
                                                                                    <input type="radio" name="paypal_mode" value="live" {{ (isset($currentWorkspace->paypal_mode) && $currentWorkspace->paypal_mode == 'live') ? 'checked' : ''}}>{{ __('Live') }}
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                                                            <div class="form-group">
                                                                                {{ Form::label('paypal_client_id', __('Client ID'),['class' => 'form-control-label']) }}
                                                                                {{ Form::text('paypal_client_id', (isset($currentWorkspace->paypal_client_id)) ? $currentWorkspace->paypal_client_id : '', ['class' => 'form-control','placeholder' => __('Client ID')]) }}
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                                                            <div class="form-group">
                                                                                {{ Form::label('paypal_secret_key', __('Secret Key'),['class' => 'form-control-label']) }}
                                                                                {{ Form::text('paypal_secret_key', isset($currentWorkspace->paypal_secret_key) ? $currentWorkspace->paypal_secret_key : '', ['class' => 'form-control','placeholder' => __('Secret Key')]) }}
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Paystack -->
                                                        <div class="card">
                                                            <div class="card-header py-4" id="paystack-payment" data-toggle="collapse" role="button" data-target="#collapse-paystack" aria-expanded="false" aria-controls="collapse-paystack">
                                                                <h6 class="mb-0"><i class="far fa-credit-card mr-3"></i>{{__('Paystack')}}</h6>
                                                            </div>
                                                            <div id="collapse-paystack" class="collapse" aria-labelledby="paystack-payment" data-parent="#payment-gateways">
                                                                <div class="card-body">
                                                                    <div class="row">
                                                                        <div class="col-6 py-2">
                                                                            <h5 class="h5">{{__('Paystack')}}</h5>
                                                                            <small> {{__('Note: This detail will use for make checkout of plan.')}}</small>
                                                                        </div>
                                                                        <div class="col-6 py-2 text-right">
                                                                            <div class="custom-control custom-switch">
                                                                                <input type="checkbox" class="custom-control-input" name="is_paystack_enabled" id="is_paystack_enabled" {{ isset($payment_detail['is_paystack_enabled']) && $payment_detail['is_paystack_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                                <label class="custom-control-label form-control-label" for="is_paystack_enabled">{{__('Enable Paystack')}}</label>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                                                            <div class="form-group">
                                                                                <label class="form-control-label" for="paystack_public_key">{{ __('Public Key') }}</label>
                                                                                <input type="text" name="paystack_public_key" id="paystack_public_key" class="form-control" value="{{isset($payment_detail['paystack_public_key']) ? $payment_detail['paystack_public_key']:''}}" placeholder="{{ __('Public Key') }}"/>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                                                            <div class="form-group">
                                                                                <label class="form-control-label" for="paystack_secret_key">{{ __('Secret Key') }}</label>
                                                                                <input type="text" name="paystack_secret_key" id="paystack_secret_key" class="form-control" value="{{isset($payment_detail['paystack_secret_key']) ? $payment_detail['paystack_secret_key']:''}}" placeholder="{{ __('Secret Key') }}"/>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- FLUTTERWAVE -->
                                                        <div class="card">
                                                            <div class="card-header py-4" id="flutterwave-payment" data-toggle="collapse" role="button" data-target="#collapse-flutterwave" aria-expanded="false" aria-controls="collapse-flutterwave">
                                                                <h6 class="mb-0"><i class="far fa-credit-card mr-3"></i>{{__('Flutterwave')}}</h6>
                                                            </div>
                                                            <div id="collapse-flutterwave" class="collapse" aria-labelledby="flutterwave-payment" data-parent="#payment-gateways">
                                                                <div class="card-body">
                                                                    <div class="row">
                                                                        <div class="col-6 py-2">
                                                                            <h5 class="h5">{{__('Flutterwave')}}</h5>
                                                                            <small> {{__('Note: This detail will use for make checkout of plan.')}}</small>
                                                                        </div>
                                                                        <div class="col-6 py-2 text-right">
                                                                            <div class="custom-control custom-switch">
                                                                                <input type="checkbox" class="custom-control-input" name="is_flutterwave_enabled" id="is_flutterwave_enabled" {{ isset($payment_detail['is_flutterwave_enabled'])  && $payment_detail['is_flutterwave_enabled']== 'on' ? 'checked="checked"' : '' }}>
                                                                                <label class="custom-control-label form-control-label" for="is_flutterwave_enabled">{{__('Enable Flutterwave')}}</label>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                                                            <div class="form-group">
                                                                                <label class="form-control-label" for="flutterwave_public_key">{{ __('Public Key') }}</label>
                                                                                <input type="text" name="flutterwave_public_key" id="flutterwave_public_key" class="form-control" value="{{isset($payment_detail['flutterwave_public_key'])?$payment_detail['flutterwave_public_key']:''}}" placeholder="{{ __('Public Key') }}"/>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                                                            <div class="form-group">
                                                                                <label class="form-control-label" for="paystack_secret_key">{{ __('Secret Key') }}</label>
                                                                                <input type="text" name="flutterwave_secret_key" id="flutterwave_secret_key" class="form-control" value="{{isset($payment_detail['flutterwave_secret_key'])?$payment_detail['flutterwave_secret_key']:''}}" placeholder="{{ __('Secret Key') }}"/>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Razorpay -->
                                                        <div class="card">
                                                            <div class="card-header py-4" id="razorpay-payment" data-toggle="collapse" role="button" data-target="#collapse-razorpay" aria-expanded="false" aria-controls="collapse-razorpay">
                                                                <h6 class="mb-0"><i class="far fa-credit-card mr-3"></i>{{__('Razorpay')}}</h6>
                                                            </div>
                                                            <div id="collapse-razorpay" class="collapse" aria-labelledby="razorpay-payment" data-parent="#payment-gateways">
                                                                <div class="card-body">
                                                                    <div class="row">
                                                                        <div class="col-6 py-2">
                                                                            <h5 class="h5">{{__('Razorpay')}}</h5>
                                                                            <small> {{__('Note: This detail will use for make checkout of plan.')}}</small>
                                                                        </div>
                                                                        <div class="col-6 py-2 text-right">
                                                                            <div class="custom-control custom-switch">
                                                                                <input type="checkbox" class="custom-control-input" name="is_razorpay_enabled" id="is_razorpay_enabled" {{ isset($payment_detail['is_razorpay_enabled']) && $payment_detail['is_razorpay_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                                <label class="custom-control-label form-control-label" for="is_razorpay_enabled">{{__('Enable Razorpay')}}</label>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                                                            <div class="form-group">
                                                                                <label class="form-control-label" for="razorpay_public_key">{{ __('Public Key') }}</label>
                                                                                <input type="text" name="razorpay_public_key" id="razorpay_public_key" class="form-control" value="{{ isset($payment_detail['razorpay_public_key'])?$payment_detail['razorpay_public_key']:''}}" placeholder="{{ __('Public Key') }}"/>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                                                            <div class="form-group">
                                                                                <label class="form-control-label" for="paystack_secret_key">{{ __('Secret Key') }}</label>
                                                                                <input type="text" name="razorpay_secret_key" id="razorpay_secret_key" class="form-control" value="{{ isset($payment_detail['razorpay_secret_key'])?$payment_detail['razorpay_secret_key']:''}}" placeholder="{{ __('Secret Key') }}"/>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Mercado Pago-->
                                                        <div class="card">
                                                            <div class="card-header py-4" id="mercado_pago-payment" data-toggle="collapse" role="button" data-target="#collapse-mercado_pago" aria-expanded="false" aria-controls="collapse-mercado_pago">
                                                                <h6 class="mb-0"><i class="far fa-credit-card mr-3"></i>{{__('Mercado Pago')}}</h6>
                                                            </div>
                                                            <div id="collapse-mercado_pago" class="collapse" aria-labelledby="mercado_pago-payment" data-parent="#payment-gateways">
                                                                <div class="card-body">
                                                                    <div class="row">
                                                                        <div class="col-6 py-2">
                                                                            <h5 class="h5">{{__('Mercado Pago')}}</h5>
                                                                            <small> {{__('Note: This detail will use for make checkout of plan.')}}</small>
                                                                        </div>
                                                                        <div class="col-6 py-2 text-right">
                                                                            <div class="custom-control custom-switch">
                                                                                <input type="checkbox" class="custom-control-input" name="is_mercado_enabled" id="is_mercado_enabled" {{isset($payment_detail['is_mercado_enabled']) &&  $payment_detail['is_mercado_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                                <label class="custom-control-label form-control-label" for="is_mercado_enabled">{{__('Enable Mercado Pago')}}</label>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                                                            <div class="form-group">
                                                                                <label class="form-control-label" for="mercado_app_id">{{ __('App ID') }}</label>
                                                                                <input type="text" name="mercado_app_id" id="mercado_app_id" class="form-control" value="{{isset($payment_detail['mercado_app_id']) ?  $payment_detail['mercado_app_id']:''}}" placeholder="{{ __('App ID') }}"/>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                                                            <div class="form-group">
                                                                                <label class="form-control-label" for="mercado_secret_key">{{ __('App Secret KEY') }}</label>
                                                                                <input type="text" name="mercado_secret_key" id="mercado_secret_key" class="form-control" value="{{isset($payment_detail['mercado_secret_key']) ? $payment_detail['mercado_secret_key']:''}}" placeholder="{{ __('App Secret Key') }}"/>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Paytm -->
                                                        <div class="card">
                                                            <div class="card-header py-4" id="paytm-payment" data-toggle="collapse" role="button" data-target="#collapse-paytm" aria-expanded="false" aria-controls="collapse-paytm">
                                                                <h6 class="mb-0"><i class="far fa-credit-card mr-3"></i>{{__('Paytm')}}</h6>
                                                            </div>
                                                            <div id="collapse-paytm" class="collapse" aria-labelledby="paytm-payment" data-parent="#payment-gateways">
                                                                <div class="card-body">
                                                                    <div class="row">
                                                                        <div class="col-6 py-2">
                                                                            <h5 class="h5">{{__('Paytm')}}</h5>
                                                                            <small> {{__('Note: This detail will use for make checkout of plan.')}}</small>
                                                                        </div>
                                                                        <div class="col-6 py-2 text-right">
                                                                            <div class="custom-control custom-switch">
                                                                                <input type="checkbox" class="custom-control-input" name="is_paytm_enabled" id="is_paytm_enabled" {{ isset($payment_detail['is_paytm_enabled']) && $payment_detail['is_paytm_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                                <label class="custom-control-label form-control-label" for="is_paytm_enabled">{{__('Enable Paytm')}}</label>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-12 pb-4">
                                                                            <label class="paypal-label form-control-label" for="paypal_mode">{{__('Paytm Environment')}}</label> <br>
                                                                            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                                                                <label class="btn btn-primary btn-sm {{isset($payment_detail['paytm_mode']) && $payment_detail['paytm_mode'] == 'local' ? 'active' : ''}}">
                                                                                    <input type="radio" name="paytm_mode" value="local" {{ isset($payment_detail['paytm_mode']) && $payment_detail['paytm_mode'] == '' || isset($payment_detail['paytm_mode']) && $payment_detail['paytm_mode'] == 'local' ? 'checked="checked"' : '' }}>{{__('Local')}}
                                                                                </label>
                                                                                <label class="btn btn-primary btn-sm {{isset($payment_detail['paytm_mode']) && $payment_detail['paytm_mode'] == 'live' ? 'active' : ''}}">
                                                                                    <input type="radio" name="paytm_mode" value="production" {{ isset($payment_detail['paytm_mode']) && $payment_detail['paytm_mode'] == 'production' ? 'checked="checked"' : '' }}>{{__('Production')}}
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                                                                            <div class="form-group">
                                                                                <label class="form-control-label" for="paytm_public_key">{{ __('Merchant ID') }}</label>
                                                                                <input type="text" name="paytm_merchant_id" id="paytm_merchant_id" class="form-control" value="{{isset($payment_detail['paytm_merchant_id'])? $payment_detail['paytm_merchant_id']:''}}" placeholder="{{ __('Merchant ID') }}"/>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                                                                            <div class="form-group">
                                                                                <label class="form-control-label" for="paytm_secret_key">{{ __('Merchant Key') }}</label>
                                                                                <input type="text" name="paytm_merchant_key" id="paytm_merchant_key" class="form-control" value="{{ isset($payment_detail['paytm_merchant_key']) ? $payment_detail['paytm_merchant_key']:''}}" placeholder="{{ __('Merchant Key') }}"/>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                                                                            <div class="form-group">
                                                                                <label class="form-control-label" for="paytm_industry_type">{{ __('Industry Type') }}</label>
                                                                                <input type="text" name="paytm_industry_type" id="paytm_industry_type" class="form-control" value="{{isset($payment_detail['paytm_industry_type']) ?$payment_detail['paytm_industry_type']:''}}" placeholder="{{ __('Industry Type') }}"/>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Mollie -->
                                                        <div class="card">
                                                            <div class="card-header py-4" id="mollie-payment" data-toggle="collapse" role="button" data-target="#collapse-mollie" aria-expanded="false" aria-controls="collapse-mollie">
                                                                <h6 class="mb-0"><i class="far fa-credit-card mr-3"></i>{{__('Mollie')}}</h6>
                                                            </div>
                                                            <div id="collapse-mollie" class="collapse" aria-labelledby="mollie-payment" data-parent="#payment-gateways">
                                                                <div class="card-body">
                                                                    <div class="row">
                                                                        <div class="col-6 py-2">
                                                                            <h5 class="h5">{{__('Mollie')}}</h5>
                                                                            <small> {{__('Note: This detail will use for make checkout of plan.')}}</small>
                                                                        </div>
                                                                        <div class="col-6 py-2 text-right">
                                                                            <div class="custom-control custom-switch">
                                                                                <input type="checkbox" class="custom-control-input" name="is_mollie_enabled" id="is_mollie_enabled" {{ isset($payment_detail['is_mollie_enabled']) && $payment_detail['is_mollie_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                                <label class="custom-control-label form-control-label" for="is_mollie_enabled">{{__('Enable Mollie')}}</label>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                                                                            <div class="form-group">
                                                                                <label class="form-control-label" for="mollie_api_key">{{ __('Mollie Api Key') }}</label>
                                                                                <input type="text" name="mollie_api_key" id="mollie_api_key" class="form-control" value="{{ isset($payment_detail['mollie_api_key'])?$payment_detail['mollie_api_key']:''}}" placeholder="{{ __('Mollie Api Key') }}"/>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                                                                            <div class="form-group">
                                                                                <label class="form-control-label" for="mollie_profile_id">{{ __('Mollie Profile Id') }}</label>
                                                                                <input type="text" name="mollie_profile_id" id="mollie_profile_id" class="form-control" value="{{ isset($payment_detail['mollie_profile_id'])?$payment_detail['mollie_profile_id']:''}}" placeholder="{{ __('Mollie Profile Id') }}"/>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                                                                            <div class="form-group">
                                                                                <label class="form-control-label" for="mollie_partner_id">{{ __('Mollie Partner Id') }}</label>
                                                                                <input type="text" name="mollie_partner_id" id="mollie_partner_id" class="form-control" value="{{ isset($payment_detail['mollie_partner_id'])?$payment_detail['mollie_partner_id']:''}}" placeholder="{{ __('Mollie Partner Id') }}"/>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Skrill -->
                                                        <div class="card">
                                                            <div class="card-header py-4" id="skrill-payment" data-toggle="collapse" role="button" data-target="#collapse-skrill" aria-expanded="false" aria-controls="collapse-skrill">
                                                                <h6 class="mb-0"><i class="far fa-credit-card mr-3"></i>{{__('Skrill')}}</h6>
                                                            </div>
                                                            <div id="collapse-skrill" class="collapse" aria-labelledby="skrill-payment" data-parent="#payment-gateways">
                                                                <div class="card-body">
                                                                    <div class="row">
                                                                        <div class="col-6 py-2">
                                                                            <h5 class="h5">{{__('Skrill')}}</h5>
                                                                            <small> {{__('Note: This detail will use for make checkout of plan.')}}</small>
                                                                        </div>
                                                                        <div class="col-6 py-2 text-right">
                                                                            <div class="custom-control custom-switch">
                                                                                <input type="checkbox" class="custom-control-input" name="is_skrill_enabled" id="is_skrill_enabled" {{ isset($payment_detail['is_skrill_enabled']) && $payment_detail['is_skrill_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                                <label class="custom-control-label form-control-label" for="is_skrill_enabled">{{__('Enable Skrill')}}</label>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                                                            <div class="form-group">
                                                                                <label class="form-control-label" for="mollie_api_key">{{ __('Skrill Email') }}</label>
                                                                                <input type="email" name="skrill_email" id="skrill_email" class="form-control" value="{{ isset($payment_detail['skrill_email'])?$payment_detail['skrill_email']:''}}" placeholder="{{ __('Skrill Email') }}"/>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- CoinGate -->
                                                        <div class="card">
                                                            <div class="card-header py-4" id="coingate-payment" data-toggle="collapse" role="button" data-target="#collapse-coingate" aria-expanded="false" aria-controls="collapse-coingate">
                                                                <h6 class="mb-0"><i class="far fa-credit-card mr-3"></i>{{__('CoinGate')}}</h6>
                                                            </div>
                                                            <div id="collapse-coingate" class="collapse" aria-labelledby="coingate-payment" data-parent="#payment-gateways">
                                                                <div class="card-body">
                                                                    <div class="row">
                                                                        <div class="col-6 py-2">
                                                                            <h5 class="h5">{{__('CoinGate')}}</h5>
                                                                            <small> {{__('Note: This detail will use for make checkout of plan.')}}</small>
                                                                        </div>
                                                                        <div class="col-6 py-2 text-right">
                                                                            <div class="custom-control custom-switch">
                                                                                <input type="checkbox" class="custom-control-input" name="is_coingate_enabled" id="is_coingate_enabled" {{ isset($payment_detail['is_coingate_enabled']) && $payment_detail['is_coingate_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                                <label class="custom-control-label form-control-label" for="is_coingate_enabled">{{__('Enable CoinGate')}}</label>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-12 pb-4">
                                                                            <label class="coingate-label form-control-label" for="coingate_mode">{{__('CoinGate Mode')}}</label> <br>
                                                                            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                                                                <label class="btn btn-primary btn-sm {{isset($payment_detail['coingate_mode']) && $payment_detail['coingate_mode'] == 'sandbox' ? 'active' : ''}}">
                                                                                    <input type="radio" name="coingate_mode" value="sandbox" {{ isset($payment_detail['coingate_mode']) && $payment_detail['coingate_mode'] == '' || isset($payment_detail['coingate_mode']) && $payment_detail['coingate_mode'] == 'sandbox' ? 'checked="checked"' : '' }}>{{__('Sandbox')}}
                                                                                </label>
                                                                                <label class="btn btn-primary btn-sm {{isset($payment_detail['coingate_mode']) && $payment_detail['coingate_mode'] == 'live' ? 'active' : ''}}">
                                                                                    <input type="radio" name="coingate_mode" value="live" {{ isset($payment_detail['coingate_mode']) && $payment_detail['coingate_mode'] == 'live' ? 'checked="checked"' : '' }}>{{__('Live')}}
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                                                            <div class="form-group">
                                                                                <label class="form-control-label" for="coingate_auth_token">{{ __('CoinGate Auth Token') }}</label>
                                                                                <input type="text" name="coingate_auth_token" id="coingate_auth_token" class="form-control" value="{{ isset($payment_detail['coingate_auth_token'])?$payment_detail['coingate_auth_token']:''}}" placeholder="{{ __('CoinGate Auth Token') }}"/>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <button type="submit" class="btn-submit">{{ __('Update')}}</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div id="invoices-settings" class="tab-pane">

                        <div class="row justify-content-center">
                            <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="float-left">
                                            {{ __('Invoice Footer Details') }}
                                            <small class="d-block mt-2">{{__('This detail will be displayed into invoice footer.')}}</small>
                                        </h6>
                                    </div>
                                    <div class="card-body p-4">
                                        <form method="post" action="{{route('workspace.settings.store',$currentWorkspace->slug)}}">
                                            @csrf
                                            <div class="row">
                                                <div class="form-group col-md-12">
                                                    <label for="invoice_footer_title" class="form-control-label">{{ __('Invoice Footer Title') }}</label>
                                                    <input class="form-control" name="invoice_footer_title" type="text" value="{{ $currentWorkspace->invoice_footer_title }}" id="invoice_footer_title">
                                                </div>
                                                <div class="form-group col-md-12">
                                                    <label for="invoice_footer_notes" class="form-control-label">{{ __('Invoice Footer Notes') }}</label>
                                                    <textarea class="form-control" name="invoice_footer_notes">{{ $currentWorkspace->invoice_footer_notes }}</textarea>
                                                </div>
                                                <div class="form-group col-md-12">
                                                    <button type="submit" class="btn-submit">
                                                        {{ __('Update') }}
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="float-left">
                                            {{ __('Invoice') }}
                                        </h6>
                                    </div>
                                    <div class="card-body p-3">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <form action="{{route('workspace.settings.store',$currentWorkspace->slug)}}" method="post">
                                                    @csrf
                                                    <div class="form-group">
                                                        <label for="address" class="form-control-label">{{__('Invoice Template')}}</label>
                                                        <select class="form-control select2" name="invoice_template">
                                                            <option value="template1" @if($currentWorkspace->invoice_template == 'template1') selected @endif>New York</option>
                                                            <option value="template2" @if($currentWorkspace->invoice_template == 'template2') selected @endif>Toronto</option>
                                                            <option value="template3" @if($currentWorkspace->invoice_template == 'template3') selected @endif>Rio</option>
                                                            <option value="template4" @if($currentWorkspace->invoice_template == 'template4') selected @endif>London</option>
                                                            <option value="template5" @if($currentWorkspace->invoice_template == 'template5') selected @endif>Istanbul</option>
                                                            <option value="template6" @if($currentWorkspace->invoice_template == 'template6') selected @endif>Mumbai</option>
                                                            <option value="template7" @if($currentWorkspace->invoice_template == 'template7') selected @endif>Hong Kong</option>
                                                            <option value="template8" @if($currentWorkspace->invoice_template == 'template8') selected @endif>Tokyo</option>
                                                            <option value="template9" @if($currentWorkspace->invoice_template == 'template9') selected @endif>Sydney</option>
                                                            <option value="template10" @if($currentWorkspace->invoice_template == 'template10') selected @endif>Paris</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="form-control-label">{{__('Color')}}</label>
                                                        <div class="row gutters-xs">
                                                            @foreach($colors as $key => $color)
                                                                <div class="col-auto">
                                                                    <label class="colorinput">
                                                                        <input name="invoice_color" type="radio" value="{{$color}}" class="colorinput-input" @if($currentWorkspace->invoice_color == $color) checked @endif>
                                                                        <span class="colorinput-color" style="background: #{{$color}}"></span>
                                                                    </label>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>

                                                    <button class="btn-submit" type="submit">
                                                        {{__('Save')}}
                                                    </button>
                                                </form>
                                            </div>
                                            <div class="col-md-10">
                                                <iframe frameborder="0" width="100%" height="1080px" src="{{route('invoice.preview',[$currentWorkspace->slug,($currentWorkspace->invoice_template)?$currentWorkspace->invoice_template:'template1',($currentWorkspace->invoice_color)?$currentWorkspace->invoice_color:'fff'])}}"></iframe>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{asset('assets/js/jquery-ui.min.js')}}"></script>
    <script src="{{asset('assets/js/repeater.js')}}"></script>
    <script src="{{ asset('assets/js/colorPick.js') }}"></script>

    <script>

        $(document).on("change", "select[name='invoice_template'], input[name='invoice_color']", function () {
            var template = $("select[name='invoice_template']").val();
            var color = $("input[name='invoice_color']:checked").val();
            $('iframe').attr('src', '{{url($currentWorkspace->slug.'/invoices/preview')}}/' + template + '/' + color);
        });

        $(document).ready(function () {

            var $dragAndDrop = $("body .task-stages tbody").sortable({
                handle: '.sort-handler'
            });

            var $repeater = $('.task-stages').repeater({
                initEmpty: true,
                defaultValues: {},
                show: function () {
                    $(this).slideDown();
                },
                hide: function (deleteElement) {
                    if (confirm('{{__('Are you sure ?')}}')) {
                        $(this).slideUp(deleteElement);
                    }
                },
                ready: function (setIndexes) {
                    $dragAndDrop.on('drop', setIndexes);
                },
                isFirstItemUndeletable: true
            });


            var value = $(".task-stages").attr('data-value');
            if (typeof value != 'undefined' && value.length != 0) {
                value = JSON.parse(value);
                $repeater.setList(value);
            }

            var $dragAndDropBug = $("body .bug-stages tbody").sortable({
                handle: '.sort-handler'
            });

            var $repeaterBug = $('.bug-stages').repeater({
                initEmpty: true,
                defaultValues: {},
                show: function () {
                    $(this).slideDown();
                },
                hide: function (deleteElement) {
                    if (confirm('{{__('Are you sure ?')}}')) {
                        $(this).slideUp(deleteElement);
                    }
                },
                ready: function (setIndexes) {
                    $dragAndDropBug.on('drop', setIndexes);
                },
                isFirstItemUndeletable: true
            });


            var valuebug = $(".bug-stages").attr('data-value');
            if (typeof valuebug != 'undefined' && valuebug.length != 0) {
                valuebug = JSON.parse(valuebug);
                $repeaterBug.setList(valuebug);
            }
        });
    </script>
@endpush
