@extends('include.app')
@section('header')
    <script src="{{ asset('asset/script/viewUserProfile.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('asset/style/viewUserProfile.css') }}">
@endsection

@section('content')
    <input type="hidden" value="{{ $user->id }}" id="userId">

    <div class="card">
        <div class="card-header">
            <img class="rounded-circle owner-img-border mr-2" width="40" height="40"
                src="{{ env('FILES_BASE_URL') }}{{ $user->profile_image }}" alt="">
            <h4 class="d-inline">
                <span>{{ $user->fullname }}</span>
            </h4>
            <span>- {{ $user->identity }}</span>

            {{-- Add Money To Wallet --}}
            <a href="" id="rechargeWallet" class="ml-auto btn btn-primary text-white">{{ __('Recharge Wallet') }}</a>

            {{-- Block/Unblock --}}
            @if ($user->is_block == 1)
                <a href="" id="unblockUser" class="ml-2 btn btn-success text-white">{{ __('Unblock') }}</a>
            @else
                <a href="" id="blockUser" class="ml-2 btn btn-danger text-white">{{ __('Block') }}</a>
            @endif

        </div>
        <div class="card-body">
            <div class="form-row">
                <div class="col-md-2">
                    <label class="mb-0 text-grey" for="">{{ __('Wallet') }}</label>
                    <p class="mt-0 p-data">{{ $settings->currency }}{{ $user->wallet }}</p>
                </div>
                <div class="col-md-2">
                    <label class="mb-0 text-grey" for="">{{ __('Total Appointments') }}</label>
                    <p class="mt-0 p-data">{{ $totalAppointments }}</p>
                </div>
            </div>


        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <ul class="nav nav-pills border-b  ml-0">

                <li role="presentation" class="nav-item "><a class="nav-link pointer active" href="#tabBookings"
                        role="tab" aria-controls="tabBookings" data-toggle="tab">{{ __('Appointments') }}
                        <span class="badge badge-transparent "></span></a>
                </li>
                <li role="presentation" class="nav-item"><a class="nav-link pointer" href="#tabPatients" role="tab"
                        data-toggle="tab">{{ __('Patients') }}
                        <span class="badge badge-transparent "></span></a>
                </li>

                <li role="presentation" class="nav-item"><a class="nav-link pointer" href="#tabWallet" role="tab"
                        data-toggle="tab">{{ __('Wallet') }}
                        <span class="badge badge-transparent "></span></a>
                </li>
                <li role="presentation" class="nav-item"><a class="nav-link pointer" href="#tabWithdrawRequests"
                        role="tab" data-toggle="tab">{{ __('Payouts') }}
                        <span class="badge badge-transparent "></span></a>
                </li>
                <li role="presentation" class="nav-item"><a class="nav-link pointer" href="#tabWalletRechargeLogs"
                        role="tab" data-toggle="tab">{{ __('Wallet Recharges') }}
                        <span class="badge badge-transparent "></span></a>
                </li>


            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content tabs" id="home">
                {{-- Bookings --}}
                <div role="tabpanel" class="tab-pane active" id="tabBookings">
                    <div class="table-responsive col-12">
                        <table class="table table-striped w-100 word-wrap" id="appointmentsTable">
                            <thead>
                                <tr>
                                    <th>{{ __('Appointments Number') }}</th>
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

                {{-- wallet --}}
                <div role="tabpanel" class="tab-pane" id="tabWallet">
                    <div class="table-responsive col-12">
                        <table class="table table-striped w-100 word-wrap" id="walletStatementTable">
                            <thead>
                                <tr>
                                    <th>{{ __('Transaction ID') }}</th>
                                    <th>{{ __('Summary') }}</th>
                                    <th>{{ __('Amount') }}</th>
                                    <th>{{ __('Credit/Debit') }}</th>
                                    <th>{{ __('Date & Time') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                {{-- Payouts --}}
                <div role="tabpanel" class="tab-pane" id="tabWithdrawRequests">
                    <div class="table-responsive col-12">
                        <table class="table table-striped w-100 word-wrap" id="tabWithdrawRequestsTable">
                            <thead>
                                <tr>
                                    <th>{{ __('Request Number') }}</th>
                                    <th>{{ __('Bank Details') }}</th>
                                    <th>{{ __('Amount & Status') }}</th>
                                    <th>{{ __('Placed On') }}</th>
                                    <th>{{ __('Summary') }}</th>
                                    <th>{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                {{-- Wallet Recharges --}}
                <div role="tabpanel" class="tab-pane" id="tabWalletRechargeLogs">
                    <div class="table-responsive col-12">
                        <table class="table table-striped w-100 word-wrap" id="walletRechargeLogsTable">
                            <thead>
                                <tr>
                                    <th>{{ __('Amount') }}</th>
                                    <th>{{ __('Gateway') }}</th>
                                    <th>{{ __('Transaction ID') }}</th>
                                    <th>{{ __('Transaction Summary') }}</th>
                                    <th>{{ __('Date') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                {{-- Patients --}}
                <div role="tabpanel" class="tab-pane" id="tabPatients">
                    <div class="table-responsive col-12">
                        <table class="table table-striped w-100 word-wrap" id="patientsTable">
                            <thead>
                                <tr>
                                    <th>{{ __('Image') }}</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Age') }}</th>
                                    <th>{{ __('Gender') }}</th>
                                    <th>{{ __('Relation') }}</th>
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

    {{-- Reject Modal --}}
    <div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>{{ __('Reject Withdrawal') }}</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form action="" method="post" enctype="multipart/form-data" id="rejectForm"
                        autocomplete="off">
                        @csrf
                        <input type="hidden" id="rejectId" name="id">
                        <div class="form-group">
                            <label> {{ __('Summary') }}</label>
                            <textarea rows="10" style="height:200px !important;" type="text" name="summary" class="form-control"></textarea>
                        </div>
                        <div class="form-group">
                            <input class="btn btn-primary mr-1" type="submit" value=" {{ __('Submit') }}">
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>
    {{-- Complete Modal --}}
    <div class="modal fade" id="completeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>{{ __('Complete Withdrawal') }}</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form action="" method="post" enctype="multipart/form-data" id="completeForm"
                        autocomplete="off">
                        @csrf
                        <input type="hidden" id="completeId" name="id">
                        <div class="form-group">
                            <label> {{ __('Summary') }}</label>
                            <textarea rows="10" style="height:200px !important;" type="text" name="summary" class="form-control"></textarea>
                        </div>
                        <div class="form-group">
                            <input class="btn btn-primary mr-1" type="submit" value=" {{ __('Submit') }}">
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>


    {{-- Add Coupon Modal --}}
    <div class="modal fade" id="rechargeWalletModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>{{ __('Recharge Wallet') }}</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form action="" method="post" enctype="multipart/form-data" id="rechargeWalletForm"
                        autocomplete="off">
                        @csrf

                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                        <input type="hidden" name="transaction_id" value="{{ __('ADDED_BY_ADMIN') }}">
                        <input type="hidden" name="gateway" value="2"> {{-- Added by admin --}}


                        <div class="form-group">
                            <label> {{ __('Amount') }}</label>
                            <input type="number" name="amount" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label> {{ __('Transaction Summary') }}</label>
                            <textarea name="transaction_summary" class="form-control" required></textarea>
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
