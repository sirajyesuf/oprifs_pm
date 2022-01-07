@extends('layouts.admin')

@section('page-title') {{__('Invoices')}} @endsection

@section('multiple-action-button')
    @auth('client')
        @if($invoice->getDueAmount() > 0 && $currentWorkspace->is_stripe_enabled == 1 || $currentWorkspace->is_paypal_enabled == 1  || (isset($paymentSetting['is_paypal_enabled']) && $paymentSetting['is_paypal_enabled'] == 'on') || (isset($paymentSetting['is_paystack_enabled']) && $paymentSetting['is_paystack_enabled'] == 'on') || (isset($paymentSetting['is_flutterwave_enabled']) && $paymentSetting['is_flutterwave_enabled'] == 'on') || (isset($paymentSetting['is_razorpay_enabled']) &&
    $paymentSetting['is_razorpay_enabled'] == 'on') || (isset($paymentSetting['is_mercado_enabled']) && $paymentSetting['is_mercado_enabled'] == 'on') || (isset($paymentSetting['is_paytm_enabled']) && $paymentSetting['is_paytm_enabled'] == 'on') || (isset($paymentSetting['is_mollie_enabled']) && $paymentSetting['is_mollie_enabled'] == 'on') || (isset($paymentSetting['is_skrill_enabled']) && $paymentSetting['is_skrill_enabled'] == 'on') || (isset($paymentSetting['is_coingate_enabled']) && $paymentSetting['is_coingate_enabled'] == 'on'))
            <div class="text-sm-right">
                <a href="#" data-toggle="modal" data-target="#paymentModal" class="btn btn-xs btn-white btn-icon-only width-auto" type="button">
                    <i class="fas fa-credit-card mr-1"></i> {{__('Pay Now')}}
                </a>
            </div>
        @endif
    @endauth

    @auth('web')
        @if($currentWorkspace->creater->id == Auth::user()->id)
            <div class="text-sm-right">
                <a href="#" data-toggle="modal" data-target="#addPaymentModal" class="btn btn-xs btn-white btn-icon-only width-auto" type="button">
                    <i class="fas fa-credit-card mr-1"></i> {{__('Add Payment')}}
                </a>
                <a href="#" class="btn btn-xs btn-white btn-icon-only width-auto" data-size="lg" data-ajax-popup="true" data-title="{{ __('Edit Invoice') }}" data-url="{{route('invoices.edit',[$currentWorkspace->slug,$invoice->id])}}">
                    <i class="fas fa-pencil-alt mr-1"></i>{{__('Edit Invoice')}}
                </a>
                <a href="#" data-ajax-popup="true" data-title="{{ __('Add Item') }}" data-url="{{route('invoice.item.create',[$currentWorkspace->slug,$invoice->id])}}" class="btn btn-xs btn-white btn-icon-only width-auto" type="button">
                    <i class="fas fa-plus mr-1"></i> {{__('Add Item')}}
                </a>
            </div>
        @endif
    @endauth
@endsection

@section('content')

    <section class="section">
        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <div class="text-right">
                        <div class="h5">{{App\Models\Utility::invoiceNumberFormat($invoice->invoice_id)}}</div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-6">
                            <address class="text-sm">
                                <h6>{{__('From')}}:</h6>
                                @if($currentWorkspace->company){{$currentWorkspace->company}}@endif
                                @if($currentWorkspace->address) <br>{{$currentWorkspace->address}}@endif
                                @if($currentWorkspace->city) <br> {{$currentWorkspace->city}}, @endif @if($currentWorkspace->state){{$currentWorkspace->state}}@endif @if($currentWorkspace->zipcode) - {{$currentWorkspace->zipcode}}@endif
                                @if($currentWorkspace->country) <br>{{$currentWorkspace->country}}@endif
                                @if($currentWorkspace->telephone) <br>{{$currentWorkspace->telephone}}@endif
                            </address>
                            <address class="text-sm">
                                <h6>{{__('To')}}:</h6>
                                @if($invoice->client)
                                    {{$invoice->client->name}}
                                    @if($invoice->client->address) <br>{{$invoice->client->address}}@endif
                                    @if($invoice->client->city) <br> {{$invoice->client->city}}, @endif @if($invoice->client->state){{$invoice->client->state}}@endif @if($invoice->client->zipcode) - {{$invoice->client->zipcode}}@endif
                                    @if($invoice->client->country) <br>{{$invoice->client->country}}@endif
                                    <br>{{$invoice->client->email}}
                                    @if($invoice->client->telephone) <br>{{$invoice->client->telephone}}@endif
                                @endif
                            </address>
                        </div>
                        <div class="col-md-6 text-md-right">
                            <address>
                                <h6>{{ __('Project') }}:</h6>
                                {{$invoice->project->name}}
                            </address>
                            <address>
                                <h6>{{ __('Status') }}:</h6>
                                <div class="font-weight-bold font-style">
                                    @if($invoice->status == 'sent')
                                        <span class="badge badge-warning">{{__('Sent')}}</span>
                                    @elseif($invoice->status == 'paid')
                                        <span class="badge badge-success">{{__('Paid')}}</span>
                                    @elseif($invoice->status == 'canceled')
                                        <span class="badge badge-danger">{{__('Canceled')}}</span>
                                    @endif
                                </div>
                            </address>
                            <address>
                                <h6>{{__('Issue Date')}}:</h6>
                                {{App\Models\Utility::dateFormat($invoice->issue_date)}}
                            </address>
                            <address>
                                <h6>{{__('Due Date')}}:</h6>
                                {{App\Models\Utility::dateFormat($invoice->due_date)}}
                            </address>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="h6">{{__('Order Summary')}}</div>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-md">
                                    <tbody>
                                    <tr>
                                        <th class="form-control-label">#</th>
                                        <th class="form-control-label">{{__('Item')}}</th>
                                        <th class="form-control-label text-right">{{__('Totals')}}</th>
                                        @auth('web')
                                            <th class="form-control-label text-right">{{__('Action')}}</th>
                                        @endauth
                                    </tr>
                                    @foreach($invoice->items as $key => $item)
                                        <tr>
                                            <td>{{$key+1}}</td>
                                            <td>{{$item->task->title}} - <b>{{$item->task->project->name}}</b></td>
                                            <td>{{$currentWorkspace->priceFormat($item->price * $item->qty)}}</td>
                                            @auth('web')
                                                <td class="text-right">
                                                    <a href="#" class="delete-icon" data-confirm="{{ __('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?') }}" data-confirm-yes="document.getElementById('delete-form-{{$item->id}}').submit();">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </a>
                                                    <form id="delete-form-{{$item->id}}" action="{{ route('invoice.item.destroy',[$currentWorkspace->slug,$invoice->id,$item->id]) }}" method="POST" style="display: none;">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                </td>
                                            @endauth
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="row mt-4">
                                <div class="offset-md-8 col-md-4 text-right">
                                    <div class="invoice-detail-item">
                                        <span>{{__('Subtotal')}}</span>
                                        <div class="h6">{{$currentWorkspace->priceFormat($invoice->getSubTotal())}}</div>
                                    </div>
                                    @if($invoice->discount)
                                        <div class="invoice-detail-item">
                                            <span>{{__('Discount')}}</span>
                                            <div class="h6">{{$currentWorkspace->priceFormat($invoice->discount)}}</div>
                                        </div>
                                    @endif
                                    @if($invoice->tax)
                                        <div class="invoice-detail-item">
                                            <span>{{__('Tax')}} {{$invoice->tax->name}} ({{$invoice->tax->rate}}%)</span>
                                            <div class="h6">{{$currentWorkspace->priceFormat($invoice->getTaxTotal())}}</div>
                                        </div>
                                    @endif
                                    <hr class="mt-2 mb-2">
                                    <div class="invoice-detail-item">
                                        <span>{{__('Total')}}</span>
                                        <div class="h6">{{$currentWorkspace->priceFormat($invoice->getTotal())}}</div>
                                    </div>
                                    <div class="invoice-detail-item">
                                        <span>{{__('Due Amount')}}</span>
                                        <div class="h6 text-danger">{{$currentWorkspace->priceFormat($invoice->getDueAmount())}}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 text-right">
                            <a href="@auth('web'){{route('invoice.print',[$currentWorkspace->slug,\Illuminate\Support\Facades\Crypt::encryptString($invoice->id)])}}@elseauth{{route('client.invoice.print',[$currentWorkspace->slug,\Illuminate\Support\Facades\Crypt::encryptString($invoice->id)])}}@endauth" class="btn-submit">
                                <i class="fas fa-print"></i> {{ __('Print') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @if($payments = $invoice->payments)
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h6>{{__('Payments')}}</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-md">
                                <tbody>
                                <tr>
                                    <th class="form-control-label">{{__('Id')}}</th>
                                    <th class="form-control-label">{{__('Amount')}}</th>
                                    <th class="form-control-label">{{__('Currency')}}</th>
                                    <th class="form-control-label">{{__('Status')}}</th>
                                    <th class="form-control-label">{{__('Payment Type')}}</th>
                                    <th class="form-control-label">{{__('Date')}}</th>
                                    <th class="form-control-label">{{__('Receipt')}}</th>
                                </tr>
                                @foreach($payments as $key => $payment)
                                    <tr>
                                        <td>{{$payment->order_id}}</td>
                                        <td>{{$currentWorkspace->priceFormat($payment->amount)}}</td>
                                        <td>{{strtoupper($payment->currency)}}</td>
                                        <td>
                                            @if($payment->payment_status == 'succeeded' || $payment->payment_status == 'approved')
                                                <i class="fas fa-circle text-success"></i> {{__(ucfirst($payment->payment_status))}}
                                            @else
                                                <i class="fas fa-circle text-danger"></i> {{__(ucfirst($payment->payment_status))}}
                                            @endif
                                        </td>
                                        <td>{{ __($payment->payment_type) }}</td>
                                        <td>{{App\Models\Utility::dateFormat($payment->created_at)}}</td>
                                        <td>
                                            @if(!empty($payment->receipt))
                                                <a href="{{$payment->receipt}}" target="_blank" class="btn-submit"><i class="fas fa-print mr-1"></i> {{__('Receipt')}}</a>
                                            @endif
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
    @endif

    @if(auth('web') && $invoice->getDueAmount() > 0)
        <!-- Modal -->
        <div class="modal fade" id="addPaymentModal" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="paymentModalLabel">{{ __('Add Manual Payment') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="card card-box">
                            <form method="post" action="{{ route('manual.invoice.payment',[$currentWorkspace->slug,$invoice->id]) }}" class="require-validation">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="amount" class="form-control-label">{{ __('Amount') }}</label>
                                        <div class="form-icon-user">
                                            <span class="currency-icon">{{ (!empty($currentWorkspace->currency)) ? $currentWorkspace->currency : '$' }}</span>
                                            <input class="form-control" type="number" id="amount" name="amount" value="{{$invoice->getDueAmount()}}" min="0" step="0.01" max="{{$invoice->getDueAmount()}}" placeholder="{{ __('Enter Monthly Price') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <input type="submit" class="btn-create badge-blue" value="{{ __('Make Payment') }}">
                                        <input type="button" class="btn-create bg-gray" data-dismiss="modal" value="{{ __('Cancel') }}">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @auth('client')
        @if($invoice->getDueAmount() > 0)
            <div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-md" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="paymentModalLabel">{{ __('Add Payment') }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="card-box">
                                @if((isset($currentWorkspace->is_stripe_enabled) && $currentWorkspace->is_stripe_enabled == 1) || (isset($currentWorkspace->is_paypal_enabled) && $currentWorkspace->is_paypal_enabled == 1) || (isset($paymentSetting['is_paystack_enabled']) && $paymentSetting['is_paystack_enabled'] == 'on') || (isset($paymentSetting['is_flutterwave_enabled']) && $paymentSetting['is_flutterwave_enabled'] == 'on') || (isset($paymentSetting['is_razorpay_enabled']) &&
            $paymentSetting['is_razorpay_enabled'] == 'on') || (isset($paymentSetting['is_mercado_enabled']) && $paymentSetting['is_mercado_enabled'] == 'on') || (isset($paymentSetting['is_paytm_enabled']) && $paymentSetting['is_paytm_enabled'] == 'on') || (isset($paymentSetting['is_mollie_enabled']) && $paymentSetting['is_mollie_enabled'] == 'on') || (isset($paymentSetting['is_skrill_enabled']) && $paymentSetting['is_skrill_enabled'] == 'on') || (isset($paymentSetting['is_coingate_enabled']) && $paymentSetting['is_coingate_enabled'] == 'on'))
                                    <ul class="nav nav-tabs">
                                        @if($currentWorkspace->is_stripe_enabled == 1)
                                            <li>
                                                <a data-toggle="tab" href="#stripe-payment" class="active">{{__('Stripe')}}</a>
                                            </li>
                                        @endif
                                        @if($currentWorkspace->is_paypal_enabled == 1)
                                            <li>
                                                <a data-toggle="tab" href="#paypal-payment" class="">{{__('Paypal')}} </a>
                                            </li>
                                        @endif
                                        @if(isset($paymentSetting['is_paystack_enabled']) && $paymentSetting['is_paystack_enabled'] == 'on')
                                            <li>
                                                <a data-toggle="tab" href="#paystack-payment" role="tab" aria-controls="paystack" aria-selected="false">{{ __('Paystack') }}</a>
                                            </li>
                                        @endif
                                        @if(isset($paymentSetting['is_flutterwave_enabled']) && $paymentSetting['is_flutterwave_enabled'] == 'on')
                                            <li>
                                                <a data-toggle="tab" href="#flutterwave-payment" role="tab" aria-controls="flutterwave" aria-selected="false">{{ __('Flutterwave') }}</a>
                                            </li>
                                        @endif
                                        @if(isset($paymentSetting['is_razorpay_enabled']) && $paymentSetting['is_razorpay_enabled'] == 'on')
                                            <li>
                                                <a data-toggle="tab" href="#razorpay-payment" role="tab" aria-controls="razorpay" aria-selected="false">{{ __('Razorpay') }}</a>
                                            </li>
                                        @endif
                                        @if(isset($paymentSetting['is_mercado_enabled']) && $paymentSetting['is_mercado_enabled'] == 'on')
                                            <li>
                                                <a data-toggle="tab" href="#mercado-payment" role="tab" aria-controls="mercado" aria-selected="false">{{ __('Mercado Pago') }}</a>
                                            </li>
                                        @endif
                                        @if(isset($paymentSetting['is_paytm_enabled']) && $paymentSetting['is_paytm_enabled'] == 'on')
                                            <li>
                                                <a data-toggle="tab" href="#paytm-payment" role="tab" aria-controls="paytm" aria-selected="false">{{ __('Paytm') }}</a>
                                            </li>
                                        @endif
                                        @if(isset($paymentSetting['is_mollie_enabled']) && $paymentSetting['is_mollie_enabled'] == 'on')
                                            <li>
                                                <a data-toggle="tab" href="#mollie-payment" role="tab" aria-controls="mollie" aria-selected="false">{{ __('Mollie') }}</a>
                                            </li>
                                        @endif
                                        @if(isset($paymentSetting['is_skrill_enabled']) && $paymentSetting['is_skrill_enabled'] == 'on')
                                            <li>
                                                <a data-toggle="tab" href="#skrill-payment" role="tab" aria-controls="skrill" aria-selected="false">{{ __('Skrill') }}</a>
                                            </li>
                                        @endif
                                        @if(isset($paymentSetting['is_coingate_enabled']) && $paymentSetting['is_coingate_enabled'] == 'on')
                                            <li>
                                                <a data-toggle="tab" href="#coingate-payment" role="tab" aria-controls="coingate" aria-selected="false">{{ __('CoinGate') }}</a>
                                            </li>
                                        @endif
                                    </ul>
                                @endif

                                <div class="tab-content mt-3">
                                    @if($currentWorkspace->is_stripe_enabled == 1)
                                        <div class="tab-pane fade {{ (($currentWorkspace->is_stripe_enabled == 1 && $currentWorkspace->is_paypal_enabled == 1) || $currentWorkspace->is_stripe_enabled == 1) ? "show active" : "" }}" id="stripe-payment" role="tabpanel" aria-labelledby="stripe-payment">
                                            <form method="post" action="{{ route('client.invoice.payment',[$currentWorkspace->slug,$invoice->id]) }}" class="require-validation" id="payment-form">
                                                @csrf
                                                <div class="row">
                                                    <div class="col-sm-8">
                                                        <div class="custom-radio">
                                                            <label class="font-16 form-control-label">{{__('Credit / Debit Card')}}</label>
                                                        </div>
                                                        <p class="mb-0 pt-1 text-sm">{{__('Safe money transfer using your bank account. We support Mastercard, Visa, Discover and American express.')}}</p>
                                                    </div>
                                                    <div class="col-sm-4 text-sm-right mt-3 mt-sm-0">
                                                        <img src="{{asset('assets/img/payments/master.png')}}" height="24" alt="master-card-img">
                                                        <img src="{{asset('assets/img/payments/discover.png')}}" height="24" alt="discover-card-img">
                                                        <img src="{{asset('assets/img/payments/visa.png')}}" height="24" alt="visa-card-img">
                                                        <img src="{{asset('assets/img/payments/american express.png')}}" height="24" alt="american-express-card-img">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="card-name-on" class="form-control-label">{{__('Name on card')}}</label>
                                                            <input type="text" name="name" id="card-name-on" class="form-control required" placeholder="{{\Auth::user()->name}}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div id="card-element">
                                                        </div>
                                                        <div id="card-errors" role="alert"></div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group col-md-12">
                                                        <label for="amount" class="form-control-label">{{ __('Amount') }}</label>
                                                        <div class="form-icon-user">
                                                            <span class="currency-icon">{{ (!empty($currentWorkspace->currency)) ? $currentWorkspace->currency : '$'}}</span>
                                                            <input class="form-control" required="required" min="0" name="amount" type="number" value="{{$invoice->getDueAmount()}}" min="0" step="0.01" max="{{$invoice->getDueAmount()}}" id="amount">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="error" style="display: none;">
                                                            <div class='alert-danger alert'>{{__('Please correct the errors and try again.')}}</div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <input type="submit" class="btn-create badge-blue" value="{{ __('Make Payment') }}">
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    @endif
                                    @if($currentWorkspace->is_paypal_enabled == 1)
                                        <div class="tab-pane fade {{ ($currentWorkspace->is_stripe_enabled == 0 && $currentWorkspace->is_paypal_enabled == 1) ? "show active" : "" }}" id="paypal-payment" role="tabpanel" aria-labelledby="paypal-payment">
                                            <form class="w3-container w3-display-middle w3-card-4 " method="POST" id="payment-form" action="{{ route('client.pay.with.paypal', [$currentWorkspace->slug, $invoice->id]) }}">
                                                @csrf
                                                <div class="row">
                                                    <div class="form-group col-md-12">
                                                        <label for="amount" class="form-control-label">{{ __('Amount') }}</label>
                                                        <div class="form-icon-user">
                                                            <span class="currency-icon">{{ (!empty($currentWorkspace->currency)) ? $currentWorkspace->currency : '$'}}</span>
                                                            <input class="form-control" required="required" min="0" name="amount" type="number" value="{{$invoice->getDueAmount()}}" min="0" step="0.01" max="{{$invoice->getDueAmount()}}" id="amount">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group mt-3">
                                                    <input type="submit" class="btn-create badge-blue" value="{{ __('Make Payment') }}">
                                                </div>
                                            </form>
                                        </div>
                                    @endif
                                    @if($paymentSetting['is_paystack_enabled'] == 'on')
                                        <div class="tab-pane fade" id="paystack-payment" role="tabpanel" aria-labelledby="paystack-payment">
                                            <form method="post" action="{{route('client.invoice.pay.with.paystack',[$currentWorkspace->slug, $invoice->id])}}" class="require-validation" id="paystack-payment-form">
                                                @csrf
                                                <div class="row">
                                                    <div class="form-group col-md-12">
                                                        <label for="amount" class="form-control-label">{{ __('Amount') }}</label>
                                                        <div class="form-icon-user">
                                                            <span class="currency-icon">{{ (!empty($currentWorkspace->currency)) ? $currentWorkspace->currency : '$'}}</span>
                                                            <input class="form-control" required="required" min="0" name="amount" type="number" value="{{$invoice->getDueAmount()}}" min="0" step="0.01" max="{{$invoice->getDueAmount()}}" id="amount">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group mt-3">
                                                    <button class="btn-create badge-blue" type="button" id="pay_with_paystack">{{ __('Make Payment') }}</button>
                                                </div>
                                            </form>
                                        </div>
                                    @endif
                                    @if(isset($paymentSetting['is_flutterwave_enabled']) && $paymentSetting['is_flutterwave_enabled'] == 'on')
                                        <div class="tab-pane fade" id="flutterwave-payment" role="tabpanel" aria-labelledby="flutterwave-payment">
                                            <form method="post" action="{{route('client.invoice.pay.with.flaterwave',[$currentWorkspace->slug, $invoice->id])}}" class="require-validation" id="flaterwave-payment-form">
                                                @csrf
                                                <div class="row">
                                                    <div class="form-group col-md-12">
                                                        <label class="form-control-label" for="amount">{{ __('Amount') }}</label>
                                                        <div class="form-icon-user">
                                                            <span class="currency-icon">{{ (!empty($currentWorkspace->currency)) ? $currentWorkspace->currency : '$'}}</span>
                                                            <input class="form-control" required="required" min="0" name="amount" type="number" value="{{$invoice->getDueAmount()}}" min="0" step="0.01" max="{{$invoice->getDueAmount()}}" id="amount">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group mt-3">
                                                    <button class="btn-create badge-blue" type="button" id="pay_with_flaterwave">{{ __('Make Payment') }}</button>
                                                </div>
                                            </form>
                                        </div>
                                    @endif
                                    @if(isset($paymentSetting['is_razorpay_enabled']) && $paymentSetting['is_razorpay_enabled'] == 'on')
                                        <div class="tab-pane fade" id="razorpay-payment" role="tabpanel" aria-labelledby="razorpay-payment">
                                            <form method="post" action="{{route('client.invoice.pay.with.razorpay',[$currentWorkspace->slug, $invoice->id])}}" class="require-validation" id="razorpay-payment-form">
                                                @csrf
                                                <div class="row">
                                                    <div class="form-group col-md-12">
                                                        <label class="form-control-label" for="amount">{{ __('Amount') }}</label>
                                                        <div class="form-icon-user">
                                                            <span class="currency-icon">{{ (!empty($currentWorkspace->currency)) ? $currentWorkspace->currency : '$'}}</span>
                                                            <input class="form-control" required="required" min="0" name="amount" type="number" value="{{$invoice->getDueAmount()}}" min="0" step="0.01" max="{{$invoice->getDueAmount()}}" id="amount">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group mt-3">
                                                    <button class="btn-create badge-blue" type="button" id="pay_with_razerpay">{{ __('Make Payment') }}</button>
                                                </div>
                                            </form>
                                        </div>
                                    @endif
                                    @if(isset($paymentSetting['is_mercado_enabled']) && $paymentSetting['is_mercado_enabled'] == 'on')
                                        <div class="tab-pane fade" id="mercado-payment" role="tabpanel" aria-labelledby="mercado-payment">
                                            <form method="post" action="{{route('client.invoice.pay.with.mercado',[$currentWorkspace->slug, $invoice->id])}}" class="require-validation" id="mercado-form">
                                                @csrf
                                                <div class="row">
                                                    <div class="form-group col-md-12">
                                                        <label class="form-control-label" for="amount">{{ __('Amount') }}</label>
                                                        <div class="form-icon-user">
                                                            <span class="currency-icon">{{ (!empty($currentWorkspace->currency)) ? $currentWorkspace->currency : '$'}}</span>
                                                            <input class="form-control" required="required" min="0" name="amount" type="number" value="{{$invoice->getDueAmount()}}" min="0" step="0.01" max="{{$invoice->getDueAmount()}}" id="amount">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group mt-3">
                                                    <button class="btn-create badge-blue" type="submit">{{ __('Make Payment') }}</button>
                                                </div>
                                            </form>
                                        </div>
                                    @endif
                                    @if(isset($paymentSetting['is_paytm_enabled']) && $paymentSetting['is_paytm_enabled'] == 'on')
                                        <div class="tab-pane fade" id="paytm-payment" role="tabpanel" aria-labelledby="paytm-payment">
                                            <form method="post" action="{{route('client.invoice.pay.with.paytm',[$currentWorkspace->slug, $invoice->id])}}" class="require-validation" id="paytm-form">
                                                @csrf
                                                <div class="row">
                                                    <div class="form-group col-md-12">
                                                        <label class="form-control-label" for="amount">{{ __('Amount') }}</label>
                                                        <div class="form-icon-user">
                                                            <span class="currency-icon">{{ (!empty($currentWorkspace->currency)) ? $currentWorkspace->currency : '$'}}</span>
                                                            <input class="form-control" required="required" min="0" name="amount" type="number" value="{{$invoice->getDueAmount()}}" min="0" step="0.01" max="{{$invoice->getDueAmount()}}" id="amount">
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="mobile" class="form-control-label text-dark">{{__('Mobile Number')}}</label>
                                                            <input type="text" id="mobile" name="mobile" class="form-control mobile" data-from="mobile" placeholder="Enter Mobile Number" required="">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group mt-3">
                                                    <button class="btn-create badge-blue" type="submit">{{ __('Make Payment') }}</button>
                                                </div>
                                            </form>
                                        </div>
                                    @endif
                                    @if(isset($paymentSetting['is_mollie_enabled']) && $paymentSetting['is_mollie_enabled'] == 'on')
                                        <div class="tab-pane fade" id="mollie-payment" role="tabpanel" aria-labelledby="mollie-payment">
                                            <form method="post" action="{{route('client.invoice.pay.with.mollie',[$currentWorkspace->slug, $invoice->id])}}" class="require-validation" id="mollie-form">
                                                @csrf
                                                <div class="row">
                                                    <div class="form-group col-md-12">
                                                        <label class="form-control-label" for="amount">{{ __('Amount') }}</label>
                                                        <div class="form-icon-user">
                                                            <span class="currency-icon">{{ (!empty($currentWorkspace->currency)) ? $currentWorkspace->currency : '$'}}</span>
                                                            <input class="form-control" required="required" min="0" name="amount" type="number" value="{{$invoice->getDueAmount()}}" min="0" step="0.01" max="{{$invoice->getDueAmount()}}" id="amount">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group mt-3">
                                                    <button class="btn-create badge-blue" type="submit">{{ __('Make Payment') }}</button>
                                                </div>
                                            </form>
                                        </div>
                                    @endif
                                    @if(isset($paymentSetting['is_skrill_enabled']) && $paymentSetting['is_skrill_enabled'] == 'on')
                                        <div class="tab-pane fade" id="skrill-payment" role="tabpanel" aria-labelledby="skrill-payment">
                                            <form method="post" action="{{route('client.invoice.pay.with.skrill',[$currentWorkspace->slug, $invoice->id])}}" class="require-validation" id="skrill-form">
                                                @csrf
                                                <div class="row">
                                                    <div class="form-group col-md-12">
                                                        <label class="form-control-label" for="amount">{{ __('Amount') }}</label>
                                                        <div class="form-icon-user">
                                                            <span class="currency-icon">{{ (!empty($currentWorkspace->currency)) ? $currentWorkspace->currency : '$'}}</span>
                                                            <input class="form-control" required="required" min="0" name="amount" type="number" value="{{$invoice->getDueAmount()}}" min="0" step="0.01" max="{{$invoice->getDueAmount()}}" id="amount">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group mt-3">
                                                    <button class="btn-create badge-blue" type="submit">{{ __('Make Payment') }}</button>
                                                </div>
                                            </form>
                                        </div>
                                    @endif
                                    @if(isset($paymentSetting['is_coingate_enabled']) && $paymentSetting['is_coingate_enabled'] == 'on')
                                        <div class="tab-pane fade" id="coingate-payment" role="tabpanel" aria-labelledby="coingate-payment">
                                            <form method="post" action="{{route('client.invoice.pay.with.coingate',[$currentWorkspace->slug, $invoice->id])}}" class="require-validation" id="coingate-form">
                                                @csrf
                                                <div class="row">
                                                    <div class="form-group col-md-12">
                                                        <label class="form-control-label" for="amount">{{ __('Amount') }}</label>
                                                        <div class="form-icon-user">
                                                            <span class="currency-icon">{{ (!empty($currentWorkspace->currency)) ? $currentWorkspace->currency : '$'}}</span>
                                                            <input class="form-control" required="required" min="0" name="amount" type="number" value="{{$invoice->getDueAmount()}}" min="0" step="0.01" max="{{$invoice->getDueAmount()}}" id="amount">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group mt-3">
                                                    <button class="btn-create badge-blue" type="submit">{{ __('Make Payment') }}</button>
                                                </div>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endauth
@endsection

@auth('client')
    @if($invoice->getDueAmount() > 0 && $currentWorkspace->is_stripe_enabled == 1 || $currentWorkspace->is_paypal_enabled == 1  || (isset($paymentSetting['is_paypal_enabled']) && $paymentSetting['is_paypal_enabled'] == 'on') || (isset($paymentSetting['is_paystack_enabled']) && $paymentSetting['is_paystack_enabled'] == 'on') || (isset($paymentSetting['is_flutterwave_enabled']) && $paymentSetting['is_flutterwave_enabled'] == 'on') || (isset($paymentSetting['is_razorpay_enabled']) &&
    $paymentSetting['is_razorpay_enabled'] == 'on') || (isset($paymentSetting['is_mercado_enabled']) && $paymentSetting['is_mercado_enabled'] == 'on') || (isset($paymentSetting['is_paytm_enabled']) && $paymentSetting['is_paytm_enabled'] == 'on') || (isset($paymentSetting['is_mollie_enabled']) && $paymentSetting['is_mollie_enabled'] == 'on') || (isset($paymentSetting['is_skrill_enabled']) && $paymentSetting['is_skrill_enabled'] == 'on') || (isset($paymentSetting['is_coingate_enabled']) && $paymentSetting['is_coingate_enabled'] == 'on'))
        @push('css-page')
            <style>
                #card-element {
                    border: 1px solid #e4e6fc;
                    border-radius: 5px;
                    padding: 10px;
                }
            </style>
        @endpush
        @push('scripts')
            <script src="https://js.stripe.com/v3/"></script>

            <script type="text/javascript">

                var stripe = Stripe('{{ $currentWorkspace->stripe_key }}');
                var elements = stripe.elements();

                // Custom styling can be passed to options when creating an Element.
                var style = {
                    base: {
                        // Add your base input styles here. For example:
                        fontSize: '14px',
                        color: '#32325d',
                    },
                };

                // Create an instance of the card Element.
                var card = elements.create('card', {style: style});

                // Add an instance of the card Element into the `card-element` <div>.
                card.mount('#card-element');

                // Create a token or display an error when the form is submitted.
                var form = document.getElementById('payment-form');
                form.addEventListener('submit', function (event) {
                    event.preventDefault();

                    stripe.createToken(card).then(function (result) {
                        if (result.error) {
                            show_toastr('Error', result.error.message, 'error');
                        } else {
                            // Send the token to your server.
                            stripeTokenHandler(result.token);
                        }
                    });
                });

                function stripeTokenHandler(token) {
                    // Insert the token ID into the form so it gets submitted to the server
                    var form = document.getElementById('payment-form');
                    var hiddenInput = document.createElement('input');
                    hiddenInput.setAttribute('type', 'hidden');
                    hiddenInput.setAttribute('name', 'stripeToken');
                    hiddenInput.setAttribute('value', token.id);
                    form.appendChild(hiddenInput);

                    // Submit the form
                    form.submit();
                }
            </script>

            <script src="{{url('assets/js/jquery.form.js')}}"></script>

            @if(isset($paymentSetting['is_paystack_enabled']) && $paymentSetting['is_paystack_enabled'] == 'on')
                <script src="https://js.paystack.co/v1/inline.js"></script>
                <script>
                    //    Paystack Payment
                    $(document).on("click", "#pay_with_paystack", function () {

                        $('#paystack-payment-form').ajaxForm(function (res) {
                            if (res.flag == 1) {
                                var coupon_id = res.coupon;
                                var paystack_callback = "{{ url('client/'.$currentWorkspace->slug.'/invoice/paystack') }}";
                                var order_id = '{{time()}}';
                                var handler = PaystackPop.setup({
                                    key: '{{ $paymentSetting['paystack_public_key']  }}',
                                    email: res.email,
                                    amount: res.total_price * 100,
                                    currency: res.currency,
                                    ref: 'pay_ref_id' + Math.floor((Math.random() * 1000000000) +
                                        1
                                    ), // generates a pseudo-unique reference. Please replace with a reference you generated. Or remove the line entirely so our API will generate one for you
                                    metadata: {
                                        custom_fields: [{
                                            display_name: "Email",
                                            variable_name: "email",
                                            value: res.email,
                                        }]
                                    },

                                    callback: function (response) {
                                        console.log(response.reference, order_id);
                                        window.location.href = paystack_callback + '/' + response.reference + '/' + '{{encrypt($invoice->id)}}';
                                        {{--window.location.href = paystack_callback + '/' + '{{$invoice->id}}';--}}
                                    },
                                    onClose: function () {
                                        alert('window closed');
                                    }
                                });
                                handler.openIframe();
                            } else {
                                show_toastr('Error', data.message, 'msg');
                            }

                        }).submit();
                    });
                </script>
            @endif

            @if(isset($paymentSetting['is_flutterwave_enabled']) && $paymentSetting['is_flutterwave_enabled'] == 'on')
                <script src="https://api.ravepay.co/flwv3-pug/getpaidx/api/flwpbf-inline.js"></script>
                <script>
                    //    Flaterwave Payment
                    $(document).on("click", "#pay_with_flaterwave", function () {
                        $('#flaterwave-payment-form').ajaxForm(function (res) {
                            if (res.flag == 1) {
                                var coupon_id = res.coupon;

                                var API_publicKey = '{{ $paymentSetting['flutterwave_public_key']  }}';
                                var nowTim = "{{ date('d-m-Y-h-i-a') }}";
                                var flutter_callback = "{{ url('client/'.$currentWorkspace->slug.'/invoice/flaterwave') }}";
                                var x = getpaidSetup({
                                    PBFPubKey: API_publicKey,
                                    customer_email: '{{Auth::user()->email}}',
                                    amount: res.total_price,
                                    currency: res.currency,
                                    txref: nowTim + '__' + Math.floor((Math.random() * 1000000000)) + 'fluttpay_online-' +
                                        {{ date('Y-m-d') }},
                                    meta: [{
                                        metaname: "payment_id",
                                        metavalue: "id"
                                    }],
                                    onclose: function () {
                                    },
                                    callback: function (response) {
                                        var txref = response.tx.txRef;
                                        if (
                                            response.tx.chargeResponseCode == "00" ||
                                            response.tx.chargeResponseCode == "0"
                                        ) {
                                            window.location.href = flutter_callback + '/' + txref + '/' + '{{\Illuminate\Support\Facades\Crypt::encrypt($invoice->id)}}';
                                        } else {
                                            // redirect to a failure page.
                                        }
                                        x.close(); // use this to close the modal immediately after payment.
                                    }
                                });
                            }
                        }).submit();
                    });
                </script>
            @endif

            @if(isset($paymentSetting['is_razorpay_enabled']) && $paymentSetting['is_razorpay_enabled'] == 'on')
                <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
                <script>
                    // Razorpay Payment
                    $(document).on("click", "#pay_with_razerpay", function () {
                        $('#razorpay-payment-form').ajaxForm(function (res) {
                            if (res.flag == 1) {
                                var razorPay_callback = '{{url('client/'.$currentWorkspace->slug.'/invoice/razorpay')}}';
                                var totalAmount = res.total_price * 100;
                                var coupon_id = res.coupon;
                                var options = {
                                    "key": "{{ $paymentSetting['razorpay_public_key']  }}", // your Razorpay Key Id
                                    "amount": totalAmount,
                                    "name": 'Plan',
                                    "currency": res.currency,
                                    "description": "",
                                    "handler": function (response) {
                                        window.location.href = razorPay_callback + '/' + response.razorpay_payment_id + '/' + '{{\Illuminate\Support\Facades\Crypt::encrypt($invoice->id)}}?coupon_id=' + coupon_id + '&payment_frequency=' + res.payment_frequency;
                                    },
                                    "theme": {
                                        "color": "#528FF0"
                                    }
                                };
                                var rzp1 = new Razorpay(options);
                                rzp1.open();
                            } else {
                                show_toastr('Error', res.msg, 'msg');
                            }

                        }).submit();
                    });
                </script>
            @endif
        @endpush
    @endif
@endauth
