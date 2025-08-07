@extends('include.app')
@section('header')
    <script src="{{ asset('asset/script/viewAppointment.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('asset/style/viewAppointment.css') }}">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"
        integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@endsection

@php
    use App\Models\Constants as Constants;
    use App\Models\GlobalFunction as GlobalFunction;
@endphp

<style>
    .medicine-item {
        background-color: whitesmoke;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 8px 20px;
        border-bottom: 1px solid #c2c2c2;
    }

    .invoice-item {
        background-color: whitesmoke;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 8px 20px;
        border-bottom: 1px solid #c2c2c2;
    }

    .medicine-item:last-child {
        border-bottom: none;
    }

    .pres-medi-note {
        font-size: 12px;
    }

    .invoice-item:last-child {
        border-bottom: none;
        background-color: #363636;
        color: white;
    }

    .invoice-item p,
    .medicine-item p {
        margin: 0;
    }

    .coupon-text {
        padding: 0px 5px;
        border-radius: 5px;
    }
</style>

@section('content')
    <input type="hidden" value="{{ $appointment->id }}" id="appointmentId">
    <input type="hidden" value="{{ $appointment->appointment_number }}" id="appointmentNumber">

    <div class="row">

        <div class="card col-12">
            <div class="card-header">
                <h4 class="d-inline">
                    {{ $appointment->appointment_number }}
                </h4>

                {{--  Status --}}
                @if ($appointment->status == Constants::orderPlacedPending)
                    <span class="badge bg-warning text-white ">{{ __('Waiting For Confirmation') }} </span>
                @elseif($appointment->status == Constants::orderAccepted)
                    <span class="badge bg-info text-white ">{{ __('Accepted') }} </span>
                @elseif($appointment->status == Constants::orderCompleted)
                    <span class="badge bg-success text-white ">{{ __('Completed') }} </span>
                @elseif($appointment->status == Constants::orderDeclined)
                    <span class="badge bg-danger text-white ">{{ __('Declined') }} </span>
                @elseif($appointment->status == Constants::orderCancelled)
                    <span class="badge bg-danger text-white ">{{ __('Cancelled') }} </span>
                @endif


            </div>
            <div class="card-body">
                <div class="form-row">
                    {{-- Doctor --}}
                    <div class="col-md-4">
                        <label class="mb-1 text-grey d-block " for="">{{ __('Doctor') }}</label>
                        <div class="d-flex align-items-center card-profile">
                            <img class="rounded-circle owner-img-border mr-2" width="80" height="80"
                                src="{{ env('FILES_BASE_URL') }}{{ $appointment->doctor->image }}" alt="">
                            <div>
                                <p class="mt-0 mb-0 p-data">{{ $appointment->doctor->name }}</p>
                                <p class="mt-0 mb-0">{{ $appointment->doctor->designation }}</p>
                                <span class="mt-0 mb-0">{{ $appointment->doctor->degrees }}</span>
                            </div>
                        </div>
                    </div>
                    {{-- User --}}
                    <div class="col-md-4">
                        <label class="mb-1 text-grey d-block" for="">{{ __('User') }}</label>
                        <div class="d-flex align-items-center card-profile">
                            <img class="rounded-circle owner-img-border mr-2" width="80" height="80"
                                src="{{ env('FILES_BASE_URL') }}{{ $appointment->user->profile_image }}" alt="">
                            <div>
                                <p class="mt-0 mb-0 p-data">{{ $appointment->user->fullname }}</p>
                                <span class="mt-0 mb-0">{{ $appointment->user->gender == 1 ? __('Male') : __('Female') }} :
                                    {{ $appointment->user->age() }}{{ __(' Years') }}</span>
                            </div>
                        </div>


                    </div>

                    {{-- Patient --}}
                    <div class="col-md-4">
                        <label class="mb-1 text-grey d-block" for="">{{ __('Patient') }}</label>
                        <div class="card-profile align-items-center">
                            <div style="height: 80px ">
                                @if ($appointment->patient == null)
                                    <p class="mt-0 mb-0 p-data">{{ __('Self') }}</p>
                                @else
                                    <p class="mt-0 mb-0 p-data">{{ $appointment->patient->fullname }}</p>
                                    <span
                                        class="mt-0 mb-0">{{ $appointment->patient->gender == 1 ? __('Male') : __('Female') }}
                                        :
                                        {{ $appointment->patient->age }}{{ __(' Years') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row flex-column flex-xl-row mt-2">

        <div class="card col mr-2">
            <div class="card-header">
                <h4 class="d-inline">
                    {{ __('Details') }}
                </h4>
            </div>
            <div class="card-body">
                {{-- Appointment Time/Date/Type --}}
                <div>
                    <h6 class="mb-1 text-dark d-block" for="">{{ __('Date/Time/Type') }}</h6>
                    <div class="card-profile align-items-center">
                        <div>
                            <span class="mt-0 mb-0">{{ __('Date') }}: {{ $appointment->date }}</span><br>
                            <span class="mt-0 mb-0">{{ __('Time') }}:
                                {{ GlobalFunction::formateTimeString($appointment->time) }}</span><br>
                            @if ($appointment->type == 0)
                                <span class="mt-0 mb-0">{{ __('Type') }}: {{ __('Online') }}</span>
                            @else
                                <span class="mt-0 mb-0">{{ __('Type') }}: {{ __('Offline') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                {{-- Problem --}}
                <div class="mt-3">
                    <h6 class="mb-1 text-dark d-block" for="">{{ __('Problem') }}</h6>
                    <div class="card-profile align-items-center">
                        <div>
                            <span class="mt-0 mb-0">{{ $appointment->problem }}</span><br>
                        </div>
                    </div>
                </div>
                {{-- Attachments --}}
                <div class="mt-3">
                    <h6 class="mb-1 text-dark d-block" for="">{{ __('Attachments') }}</h6>
                    <div class="card-profile align-items-center">
                        <div>
                            @if ($appointment->documents->count() > 0)
                                @foreach ($appointment->documents as $document)
                                    <img class="rounded shadow border-grey mr-2 appointment-doc" width="80"
                                        height="80" src="{{ env('FILES_BASE_URL') }}{{ $document->image }}"
                                        alt="">
                                @endforeach
                            @else
                                <span class="text-grey p-1">{{ __('No Attachments') }}</span>
                            @endif
                        </div>
                    </div>

                </div>
                {{-- Diagnosed with --}}
                <div class="mt-3">
                    <h6 class="mb-1 text-dark d-block" for="">{{ __('Diagnosed With') }}</h6>
                    <div class="card-profile align-items-center">
                        <div>
                            <span class="mt-0 mb-0">{{ $appointment->diagnosed_with }}</span><br>
                        </div>
                    </div>
                </div>
                {{-- Feedback --}}
                <div class="mt-3">
                    <h6 class="mb-1 text-dark d-block" for="">{{ __('Feedback') }}</h6>
                    <div class="card-profile align-items-center">
                        <div>
                            @if ($appointment->rating != null)
                                {!! $ratingBar !!}
                                <br>
                                <span class="mt-0 mb-0">{{ $appointment->rating->comment }}</span><br>
                            @else
                                <span class="mt-0 mb-0">{{ __('No Feedback') }}</span><br>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card col ml-2">
            <div class="card-header">
                <h4 class="d-inline">
                    {{ __('Payment Details') }}
                </h4>

                <a class="ml-auto" id="print-payment" href="">
                    <span class="badge bg-warning text-white ">{{ __('Print') }} </span>
                </a>
                <a class="ml-2" id="download-pdf" href="">
                    <span class="badge bg-warning text-white ">{{ __('Save PDF') }} </span>
                </a>

            </div>
            <div id="payment-details-body" class="card-body">


                <div class="col-md-4 mb-3">
                    <label class="text-grey d-block mb-0" for="">{{ __('Appointment') }}</label>
                    <div class="card-profile align-items-center">
                        <div>
                            <p class="mt-0 mb-0 p-data">{{ $appointment->appointment_number }}</p>
                        </div>
                    </div>
                </div>

                <div class="invoice-item ">
                    <div class="d-flex">
                        <p>{{ __('Consultation Charge') }}</p>
                    </div>
                    <p>{{ $settings->currency }}{{ $orderSummary['service_amount'] }}</p>
                </div>
                @if ($orderSummary['coupon_apply'] == 1)
                    <div class="invoice-item ">
                        <div class="d-flex">
                            <p>{{ __('Discount Amount') }}</p>
                            <p class="ml-2 bg-dark text-white coupon-text">{{ $orderSummary['coupon']['coupon'] }}</p>
                        </div>
                        <p>{{ $settings->currency }}{{ $orderSummary['discount_amount'] }}</p>
                    </div>
                @endif
                <div class="invoice-item ">
                    <div class="d-flex">
                        <p>{{ __('Subtotal') }}</p>
                    </div>
                    <p>{{ $settings->currency }}{{ $orderSummary['subtotal'] }}</p>
                </div>

                @foreach ($orderSummary['taxes'] as $item)
                    <div class="invoice-item">
                        <div class="d-flex">
                            @if ($item['type'] == Constants::taxPercent)
                                <p>{{ $item['tax_title'] }} : {{ $item['value'] }}%</p>
                            @else
                                <p>{{ $item['tax_title'] }}</p>
                            @endif
                        </div>
                        <p>{{ $settings->currency }}{{ $item['tax_amount'] }}</p>
                    </div>
                @endforeach

                <div class="invoice-item ">
                    <div class="d-flex">
                        <p class="text-white">{{ __('Payable Amount') }}</p>
                    </div>
                    <p class="text-white">{{ $settings->currency }}{{ $orderSummary['payable_amount'] }}</p>
                </div>

            </div>
        </div>
    </div>

    <div class="row flex-column flex-xl-row mt-2">
        @if ($prescription != null)
            <div class="card col-6 mr-2">
                <div class="card-header">
                    <h4 class="d-inline">
                        {{ __('Prescription') }}
                    </h4>
                </div>
                <div class="card-body">
                    @foreach ($prescription['addMedicine'] as $medicine)
                        <div class="medicine-item">
                            <div class="">
                                <span class="font-weight-bold text-dark">{{ $medicine['title'] }} -</span>
                                <span>{{ $medicine['mealTime'] == 0 ? __('Before Meal') : __('After Meal') }}</span><br>
                                <span>{{ $medicine['dosage'] }}</span><br>
                                <span class="pres-medi-note">{{ $medicine['notes'] }}</span>
                            </div>
                            <h5 class="text-dark">{{ $medicine['quantity'] }}</h5>
                        </div>
                    @endforeach

                </div>
            </div>
        @endif

    </div>
@endsection
