@extends('include.app')
@section('header')
    <script src="{{ asset('asset/script/settings.js') }}"></script>
@endsection

<style>
    .payment-gateway-card {
        background-color: rgb(245, 245, 245);
        border-radius: 10px;
    }
</style>

@section('content')
    <div>
        <ul class="nav nav-pills border-b mb-3  ml-0">

            <li role="presentation" class="nav-item"><a class="nav-link pointer active" href="#Section1" aria-controls="home"
                    role="tab" data-toggle="tab">{{ __('Settings') }}<span class="badge badge-transparent "></span></a>
            </li>

            <li role="presentation" class="nav-item"><a class="nav-link pointer" href="#Section5" role="tab"
                data-toggle="tab">{{ __('Appointment Reminders') }}
                <span class="badge badge-transparent "></span></a>
        </li>

            <li role="presentation" class="nav-item"><a class="nav-link pointer" href="#Section2" role="tab"
                    data-toggle="tab">{{ __('Taxes') }}
                    <span class="badge badge-transparent "></span></a>
            </li>

            <li role="presentation" class="nav-item"><a class="nav-link pointer" href="#Section3" role="tab"
                    data-toggle="tab">{{ __('Payment Gateways') }}
                    <span class="badge badge-transparent "></span></a>
            </li>

            <li role="presentation" class="nav-item"><a class="nav-link pointer" href="#Section4" role="tab"
                    data-toggle="tab">{{ __('Admin Password') }}
                    <span class="badge badge-transparent "></span></a>
            </li>
        </ul>
    </div>

    <div class="tab-content tabs" id="home">
        {{-- Section 1 --}}
        <div role="tabpanel" class="card tab-pane active" id="Section1">
            <div class="card-header">
                <h6 class="text-dark">{{ __('Settings') }}</h6>
            </div>
            <div class="card-body">

                <form Autocomplete="off" class="form-group form-border" id="globalSettingsForm" action=""
                    method="post">

                    @csrf
                    <div class="form-row ">
                        <div class="form-group col-md-3">
                            <label for="">{{ __('Currency Symbol (Displayed before price)') }}</label>
                            <input value="{{ $data->currency }}" type="text" class="form-control" name="currency"
                                required>
                        </div>

                        <div class="form-group col-md-3">
                            <label for="">{{ __('Platform Commission (%)') }}</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-percent"></i>
                                    </div>
                                </div>
                                <input value="{{ $data->comission }}" type="number" class="form-control" name="comission">
                            </div>
                        </div>

                        <div class="form-group col-md-3">
                            <label for="">{{ __('Min. amount required to payout (Doctor)') }}</label>
                            <input value="{{ $data->min_amount_payout_doctor }}" type="text" class="form-control"
                                name="min_amount_payout_doctor" required>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="">{{ __('Number of bookings users can have at a time') }}</label>
                            <input value="{{ $data->max_order_at_once }}" type="text" class="form-control"
                                name="max_order_at_once" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label for="">{{ __('Support Email') }}</label>
                            <input value="{{ $data->support_email }}" type="text" class="form-control" name="support_email"
                                required>
                        </div>
                    </div>

                    <h6 class="text-dark">{{__('ChatBot')}}</h6>
                    <div class="form-row">
                        {{-- Switch --}}
                        <div class="form-group col-md-3">
                            <label class="" for="">{{ __('Enable ChatBot') }}</label>
                            <br>
                            <label class="switch ml-1">
                                <input type="checkbox" name="enable_chatbot" id="chatBotSwitch"
                                    {{ $data->enable_chatbot == 1 ? 'checked' : '' }}>
                                <span class="slider round"></span>
                            </label>
                        </div>
                        {{-- Chatbot name --}}
                        <div class="form-group col-md-3">
                            <label> {{ __('ChatBot Thumb') }}</label>
                            <input accept="image/png, image/jpeg" type="file" name="chatbot_thumb" class="form-control">
                            @if ($data->chatbot_thumb != null)
                                <img class="mt-2 rounded shadow border" src="{{ env('FILES_BASE_URL')}}{{$data->chatbot_thumb }}" alt="" height="60" width="60">
                            @endif
                        </div>
                        {{-- Chatbot Name --}}
                        <div class="form-group col-md-3">
                            <label for="">{{ __('Chatbot Name') }}</label>
                            <input value="{{ $data->chatbot_name }}" type="text" class="form-control" name="chatbot_name">
                        </div>
                        {{-- ChatGPT Token --}}
                        <div class="form-group col-md-3">
                            <label for="">{{ __('ChatGPT Token') }}</label>
                            <input id="chatGPTToken" value="{{ $data->chatgpt_token }}" type="text" class="form-control" name="chatgpt_token">
                        </div>
                    </div>

                    <div class="form-group-submit">
                        <button class="btn btn-primary " type="submit">{{ __('Save') }}</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Section 2 --}}
        <div role="tabpanel" class="card tab-pane" id="Section2">
            <div class="card-header">
                <h6 class="text-dark">{{ __('Taxes') }}</h6>

                <a data-toggle="modal" data-target="#addTaxModal" href=""
                    class="ml-auto btn btn-primary text-white">{{ __('Add Tax') }}</a>
            </div>
            <div class="card-body">
                <div class="table-responsive col-12">
                    <table class="table table-striped w-100 word-wrap" id="taxesTable">
                        <thead>
                            <tr>
                                <th>{{ __('Tax Title') }}</th>
                                <th>{{ __('Type') }}</th>
                                <th>{{ __('Value') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

        {{-- Section 3 --}}
        <div role="tabpanel" class="card tab-pane" id="Section3">
            <div class="card-header">
                <h6 class="text-dark">{{ __('Payment Gateways') }}</h6>
            </div>
            <div class="card-body">
                <form Autocomplete="off" class="form-group form-border" id="paymentGatewayForm" action=""
                    method="post">

                    @csrf
                    <div class="">
                        <span>- The platform supports one payment gateway only at a time. So users can recharge the wallet
                            with
                            the selected gateway only.</span><br>
                        <span>- Make sure to add the <strong>Currency Code</strong> from the list of supported currencies by
                            the
                            payment gateways. Links are provided below each of them.</span><br>
                        <span>- Make sure that the <strong>Currency Symbol</strong> matches with the selected
                            <strong>Currency
                                Code</strong> to avoid confusions to user.</span><br>
                        <span>- Select the one gateway to use and then save it. Make sure to set required credentials for
                            that
                            gateways.</span>
                    </div>
                    <div class="form-row mt-3">
                        <div class="form-group col-md-4">
                            <label for="exampleFormControlSelect1">{{ __('Payment Gateway') }}</label>

                            <select name="payment_gateway" class="form-control">
                                <option {{ $data->payment_gateway == 1 ? 'selected' : '' }} value="1">
                                    {{ __('Stripe') }}
                                </option>
                                <option {{ $data->payment_gateway == 3 ? 'selected' : '' }} value="3">
                                    {{ __('Razorpay') }}</option>
                                <option {{ $data->payment_gateway == 4 ? 'selected' : '' }} value="4">
                                    {{ __('Paystack') }}</option>
                                <option {{ $data->payment_gateway == 5 ? 'selected' : '' }} value="5">
                                    {{ __('PayPal') }}
                                </option>
                                <option {{ $data->payment_gateway == 6 ? 'selected' : '' }} value="6">
                                    {{ __('Flutterwave') }}</option>

                                <option {{ $data->payment_gateway == 7 ? 'selected' : '' }} value="7">
                                    {{ __('SSLCommerze') }}</option>
                            </select>
                        </div>
                    </div>

                    {{-- Stripe --}}
                    <h5 class="text-dark d-block">{{ __('Stripe') }}</h5>
                    <p class="text-muted">{{ __('Supported Currencies :') }} <a href="https://stripe.com/docs/currencies"
                            target="_blank">https://stripe.com/docs/currencies</a> </p>

                    <div class="form-row payment-gateway-card p-2">
                        <div class="form-group col-md-4">
                            <label for="">{{ __('Public Key') }}</label>
                            <input value="{{ $data->stripe_publishable_key }}" type="text" class="form-control"
                                name="stripe_publishable_key">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="">{{ __('Secret Key') }}</label>
                            <input value="{{ $data->stripe_secret }}" type="text" class="form-control"
                                name="stripe_secret">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="">{{ __('Currency Code (***)') }}</label>
                            <input value="{{ $data->stripe_currency_code }}" type="text" class="form-control"
                                name="stripe_currency_code">
                        </div>
                    </div>
                    {{-- Razorpay --}}
                    <h5 class="text-dark d-block mt-2">{{ __('Razorpay') }}</h5>
                    <p class="text-muted">{{ __('Supported Currencies :') }} <a
                            href="https://knowledgebase.razorpay.com/support/solutions/articles/82000533827-what-currencies-does-razorpay-support-"
                            target="_blank">https://knowledgebase.razorpay.com/support/solutions/articles/82000533827-what-currencies-does-razorpay-support-</a>
                    </p>
                    <div class="form-row payment-gateway-card p-2">
                        <div class="form-group col-md-4">
                            <label for="">{{ __('Razorpay Key') }}</label>
                            <input value="{{ $data->razorpay_key }}" type="text" class="form-control"
                                name="razorpay_key">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="">{{ __('Currency Code (***)') }}</label>
                            <input value="{{ $data->razorpay_currency_code }}" type="text" class="form-control"
                                name="razorpay_currency_code">
                        </div>
                    </div>

                    {{-- Paystack --}}
                    <h5 class="text-dark d-block mt-2">{{ __('Paystack') }}</h5>
                    <p class="text-muted">{{ __('Supported Currencies :') }} <a
                            href="https://support.paystack.com/hc/en-us/articles/360009973779-What-currency-is-available-to-my-business-"
                            target="_blank">https://support.paystack.com/hc/en-us/articles/360009973779-What-currency-is-available-to-my-business-</a>
                    </p>

                    <div class="form-row payment-gateway-card p-2">
                        <div class="form-group col-md-4">
                            <label for="">{{ __('Public Key') }}</label>
                            <input value="{{ $data->paystack_public_key }}" type="text" class="form-control"
                                name="paystack_public_key">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="">{{ __('Secret Key') }}</label>
                            <input value="{{ $data->paystack_secret_key }}" type="text" class="form-control"
                                name="paystack_secret_key">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="">{{ __('Currency Code (***)') }}</label>
                            <input value="{{ $data->paystack_currency_code }}" type="text" class="form-control"
                                name="paystack_currency_code">
                        </div>
                    </div>
                    {{-- Paypal --}}
                    <h5 class="text-dark d-block mt-2">{{ __('PayPal') }}</h5>
                    <p class="text-muted">{{ __('Supported Currencies :') }} <a
                            href="https://developer.paypal.com/docs/reports/reference/paypal-supported-currencies/"
                            target="_blank">https://developer.paypal.com/docs/reports/reference/paypal-supported-currencies/</a>
                    </p>

                    <div class="form-row payment-gateway-card p-2">
                        <div class="form-group col-md-4">
                            <label for="">{{ __('Client Id') }}</label>
                            <input value="{{ $data->paypal_client_id }}" type="text" class="form-control"
                                name="paypal_client_id">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="">{{ __('Secret Key') }}</label>
                            <input value="{{ $data->paypal_secret_key }}" type="text" class="form-control"
                                name="paypal_secret_key">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="">{{ __('Currency Code (***)') }}</label>
                            <input value="{{ $data->paypal_currency_code }}" type="text" class="form-control"
                                name="paypal_currency_code">
                        </div>
                    </div>
                    {{-- Flutterwave --}}
                    <h5 class="text-dark d-block mt-2">{{ __('Flutterwave') }}</h5>
                    <p class="text-muted">{{ __('Supported Currencies :') }} <a
                            href="https://flutterwave.com/tz/support/general/what-are-the-currencies-accepted-on-flutterwave"
                            target="_blank">https://flutterwave.com/tz/support/general/what-are-the-currencies-accepted-on-flutterwave</a>
                    </p>
                    <div class="form-row payment-gateway-card p-2">
                        <div class="form-group col-md-3">
                            <label for="">{{ __('Public Key') }}</label>
                            <input value="{{ $data->flutterwave_public_key }}" type="text" class="form-control"
                                name="flutterwave_public_key">
                        </div>

                        <div class="form-group col-md-3">
                            <label for="">{{ __('Secret Key') }}</label>
                            <input value="{{ $data->flutterwave_secret_key }}" type="text" class="form-control"
                                name="flutterwave_secret_key">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="">{{ __('Encryption Key') }}</label>
                            <input value="{{ $data->flutterwave_encryption_key }}" type="text" class="form-control"
                                name="flutterwave_encryption_key">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="">{{ __('Currency Code (***)') }}</label>
                            <input value="{{ $data->flutterwave_currency_code }}" type="text" class="form-control"
                                name="flutterwave_currency_code">
                        </div>

                    </div>
                    {{-- SSL Commerze --}}
                    <h5 class="text-dark d-block mt-2">{{ __('SSLCommerze') }}</h5>
                    <p class="text-muted">{{ __('Supported Currencies :') }} USD,BDT Only
                    </p>
                    <div class="form-row payment-gateway-card p-2">
                        <div class="form-group col-md-3">
                            <label for="">{{ __('Store Id') }}</label>
                            <input value="{{ $data->sslcommerz_store_id }}" type="text" class="form-control"
                                name="sslcommerz_store_id">
                        </div>

                        <div class="form-group col-md-3">
                            <label for="">{{ __('Store Password') }}</label>
                            <input value="{{ $data->sslcommerz_store_passwd }}" type="text" class="form-control"
                                name="sslcommerz_store_passwd">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="">{{ __('Currency Code (***)') }} USD & BDT only</label>
                            <input value="{{ $data->sslcommerz_currency_code }}" type="text" class="form-control"
                                name="sslcommerz_currency_code">
                        </div>

                    </div>

                    <div class="form-group-submit mt-3">
                        <button class="btn btn-primary " type="submit">{{ __('Save') }}</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Section 4 --}}
        <div role="tabpanel" class="card tab-pane" id="Section4">
            <div class="card-header">
                <h6 class="text-dark">{{ __('Admin Password') }}</h6>
            </div>
            <div class="card-body">

                <form Autocomplete="off" class="form-group form-border" id="passwordForm" action=""
                    method="post">

                    @csrf
                    <div class="">
                        <span>To change the password: Enter the password below and click on save.</span>
                    </div>
                    <div class="form-row mt-3">
                        <div class="form-group col-md-4">
                            <label for="">{{ __('Old Password') }}</label>
                            <input type="text" class="form-control" name="old_password" value="" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="">{{ __('New Password') }}</label>
                            <input type="text" class="form-control" name="new_password" value="" required>
                        </div>

                    </div>
                    <div class="form-group-submit">
                        <button class="btn btn-primary " type="submit">{{ __('Save') }}</button>
                    </div>
                </form>
            </div>
        </div>
          {{-- Section 5 --}}
          <div role="tabpanel" class="card tab-pane" id="Section5">
            <div class="card-header">
                <h4>{{ __('Appointment Reminders') }}</h4>

                <a data-toggle="modal" data-target="#addReminderModal" href=""
                class="ml-auto btn btn-primary text-white">{{ __('Add New') }}</a>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <span>Add how many reminders you want to have users before appointment time for each appointments.<br> These will be activated only once the appointment is accepted by doctor. <br> And make sure that you have configured the cron job correctly.<br> New settings applies for new appointments only.</span>
                </div>
                <div class="table-responsive col-12">
                    <table class="table table-striped w-100 word-wrap" id="remindersTable">
                        <thead>
                            <tr>
                                <th>{{ __('Day') }}</th>
                                <th>{{ __('Hour') }}</th>
                                <th>{{ __('Minute') }}</th>
                                <th>{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

     {{-- Add Reminder Modal --}}
     <div class="modal fade" id="addReminderModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
     <div class="modal-dialog modal-dialog-centered" role="document">
         <div class="modal-content">
             <div class="modal-header">
                 <h6>{{ __('Add Reminder') }}</h6>

                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                 </button>
             </div>
             <div class="modal-body">

                 <form action="" method="post" enctype="multipart/form-data" id="addReminderForm"
                     autocomplete="off">
                     @csrf

                     <div class="form-row">

                         <div class="form-group col-md-4">
                             <label> {{ __('Day') }}</label>
                             <select name="day" class="form-control">
                                 @for ($i = 0; $i <= 10; $i++)
                                 <option value="{{ $i }}">{{ $i }}</option>
                                 @endfor
                             </select>
                         </div>
                         <div class="form-group col-md-4">
                             <label> {{ __('Hour') }}</label>
                             <select name="hr" class="form-control">
                                 @for ($i = 0; $i <= 24; $i++)
                                 <option value="{{ $i }}">{{ $i }}</option>
                                 @endfor
                             </select>
                         </div>
                         <div class="form-group col-md-4">
                             <label> {{ __('Minutes') }}</label>
                             <select name="min" class="form-control">
                                 @for ($i = 0; $i <= 60; $i++)
                                 <option value="{{ $i }}">{{ $i }}</option>
                                 @endfor
                             </select>
                         </div>
                     </div>

                     <div class="form-group">
                         <input class="btn btn-primary mr-1" type="submit" value=" {{ __('Submit') }}">
                     </div>

                 </form>
             </div>

         </div>
     </div>
 </div>

    {{-- Add tax Modal --}}
    <div class="modal fade" id="addTaxModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6>{{ __('Add Tax') }}</h6>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form action="" method="post" enctype="multipart/form-data" id="addTaxForm"
                        autocomplete="off">
                        @csrf

                        <div class="form-group">
                            <label> {{ __('Tax Title') }}</label>
                            <input type="text" name="tax_title" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label> {{ __('Type') }}</label>
                            <select name="type" class="form-control">
                                <option value="0">{{ __('Percent') }}</option>
                                <option value="1">{{ __('Fixed') }}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label> {{ __('Value') }}</label>
                            <input type="number" name="value" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <input class="btn btn-primary mr-1" type="submit" value=" {{ __('Submit') }}">
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>

    {{-- Edit tax Modal --}}
    <div class="modal fade" id="editTaxModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6>{{ __('Edit Tax') }}</h6>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form action="" method="post" enctype="multipart/form-data" id="editTaxForm"
                        autocomplete="off">
                        @csrf

                        <input type="hidden" name="id" id="editTaxId">

                        <div class="form-group">
                            <label> {{ __('Tax Title') }}</label>
                            <input id="edit_tax_title" type="text" name="tax_title" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label> {{ __('Type') }}</label>
                            <select id="edit_tax_type" name="type" class="form-control">

                            </select>
                        </div>
                        <div class="form-group">
                            <label> {{ __('Value') }}</label>
                            <input id="edit_tax_value" type="number" name="value" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <input class="btn btn-primary mr-1" type="submit" value=" {{ __('Submit') }}">
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>
@endsection
