@extends('include.app')
@section('header')
    <script src="{{ asset('asset/script/appointments.js') }}"></script>
@endsection

@section('content')
    <div class="card mt-3">
        <div class="card-header">
            <h4>{{ __('Appointments') }}</h4>
        </div>
        <div class="card-body">

            <ul class="nav nav-pills border-b mb-3  ml-0">

                <li role="presentation" class="nav-item"><a class="nav-link pointer active" href="#allSection"
                        aria-controls="home" role="tab" data-toggle="tab">{{ __('All Appointments') }}<span
                            class="badge badge-transparent "></span></a>
                </li>

                <li role="presentation" class="nav-item"><a class="nav-link pointer" href="#pendingSection" role="tab"
                        data-toggle="tab">{{ __('Pending') }}
                        <span class="badge badge-transparent "></span></a>
                </li>

                <li role="presentation" class="nav-item"><a class="nav-link pointer" href="#acceptedSection" role="tab"
                        data-toggle="tab">{{ __('Accepted') }}
                        <span class="badge badge-transparent "></span></a>
                </li>

                <li role="presentation" class="nav-item"><a class="nav-link pointer" href="#completedSection" role="tab"
                        data-toggle="tab">{{ __('Completed') }}
                        <span class="badge badge-transparent "></span></a>
                </li>
                <li role="presentation" class="nav-item"><a class="nav-link pointer" href="#cancelledSection" role="tab"
                        data-toggle="tab">{{ __('Cancelled') }}
                        <span class="badge badge-transparent "></span></a>
                </li>
                <li role="presentation" class="nav-item"><a class="nav-link pointer" href="#declinedSection" role="tab"
                        data-toggle="tab">{{ __('Declined') }}
                        <span class="badge badge-transparent "></span></a>
                </li>
            </ul>

            <div class="tab-content tabs" id="home">
                {{-- All --}}
                <div role="tabpanel" class="row tab-pane active" id="allSection">
                    <div class="table-responsive col-12">
                        <table class="table table-striped w-100 word-wrap" id="allAppointmentTable">
                            <thead>
                                <tr>
                                    <th>{{ __('Appointments Number') }}</th>
                                    <th>{{ __('User') }}</th>
                                    <th>{{ __('Doctor') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Date & Time') }}</th>
                                    <th>{{ __('Service Amount') }}</th>
                                    <th>{{ __('Discount Amount') }}</th>
                                    <th>{{ __('Subtotal') }}</th>
                                    <th>{{ __('Total Tax Amount') }}</th>
                                    <th>{{ __('Payable Amount') }}</th>
                                    <th>{{ __('Order Date') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                {{-- Pending --}}
                <div role="tabpanel" class="row tab-pane" id="pendingSection">
                    <div class="table-responsive col-12">
                        <table class="table table-striped w-100" id="pendingAppointmentTable">
                            <thead>
                                <tr>
                                    <th>{{ __('Appointments Number') }}</th>
                                    <th>{{ __('User') }}</th>
                                    <th>{{ __('Doctor') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Date & Time') }}</th>
                                    <th>{{ __('Service Amount') }}</th>
                                    <th>{{ __('Discount Amount') }}</th>
                                    <th>{{ __('Subtotal') }}</th>
                                    <th>{{ __('Total Tax Amount') }}</th>
                                    <th>{{ __('Payable Amount') }}</th>
                                    <th>{{ __('Order Date') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
                {{-- Accepted --}}
                <div role="tabpanel" class="row tab-pane" id="acceptedSection">
                    <div class="table-responsive col-12">
                        <table class="table table-striped w-100" id="acceptedAppointmentTable">
                            <thead>
                                <tr>
                                    <th>{{ __('Appointments Number') }}</th>
                                    <th>{{ __('User') }}</th>
                                    <th>{{ __('Doctor') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Date & Time') }}</th>
                                    <th>{{ __('Service Amount') }}</th>
                                    <th>{{ __('Discount Amount') }}</th>
                                    <th>{{ __('Subtotal') }}</th>
                                    <th>{{ __('Total Tax Amount') }}</th>
                                    <th>{{ __('Payable Amount') }}</th>
                                    <th>{{ __('Order Date') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                {{-- Completed --}}
                <div role="tabpanel" class="row tab-pane" id="completedSection">
                    <div class="table-responsive col-12">
                        <table class="table table-striped w-100" id="completedAppointmentTable">
                            <thead>
                                <tr>
                                    <th>{{ __('Appointments Number') }}</th>
                                    <th>{{ __('User') }}</th>
                                    <th>{{ __('Doctor') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Date & Time') }}</th>
                                    <th>{{ __('Service Amount') }}</th>
                                    <th>{{ __('Discount Amount') }}</th>
                                    <th>{{ __('Subtotal') }}</th>
                                    <th>{{ __('Total Tax Amount') }}</th>
                                    <th>{{ __('Payable Amount') }}</th>
                                    <th>{{ __('Order Date') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                {{-- Cancelled --}}
                <div role="tabpanel" class="row tab-pane" id="cancelledSection">
                    <div class="table-responsive col-12">
                        <table class="table table-striped w-100" id="cancelledAppointmentTable">
                            <thead>
                                <tr>
                                    <th>{{ __('Appointments Number') }}</th>
                                    <th>{{ __('User') }}</th>
                                    <th>{{ __('Doctor') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Date & Time') }}</th>
                                    <th>{{ __('Service Amount') }}</th>
                                    <th>{{ __('Discount Amount') }}</th>
                                    <th>{{ __('Subtotal') }}</th>
                                    <th>{{ __('Total Tax Amount') }}</th>
                                    <th>{{ __('Payable Amount') }}</th>
                                    <th>{{ __('Order Date') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                {{-- declined --}}
                <div role="tabpanel" class="row tab-pane" id="declinedSection">
                    <div class="table-responsive col-12">
                        <table class="table table-striped w-100" id="declinedAppointmentTable">
                            <thead>
                                <tr>
                                    <th>{{ __('Appointments Number') }}</th>
                                    <th>{{ __('User') }}</th>
                                    <th>{{ __('Doctor') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Date & Time') }}</th>
                                    <th>{{ __('Service Amount') }}</th>
                                    <th>{{ __('Discount Amount') }}</th>
                                    <th>{{ __('Subtotal') }}</th>
                                    <th>{{ __('Total Tax Amount') }}</th>
                                    <th>{{ __('Payable Amount') }}</th>
                                    <th>{{ __('Order Date') }}</th>
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



    </div>
    </div>
@endsection
