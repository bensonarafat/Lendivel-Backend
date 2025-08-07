@extends('include.app')
@section('header')
    <script src="{{ asset('asset/script/userWalletRecharge.js') }}"></script>
@endsection

@section('content')
    <div class="card mt-3">
        <div class="card-header">
            <h4>{{ __('Recharge Logs (User)') }}</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive col-12">
                <table class="table table-striped w-100 word-wrap" id="walletRechargeTable">
                    <thead>
                        <tr>
                            <th>{{ __('User') }}</th>
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
    </div>
@endsection
