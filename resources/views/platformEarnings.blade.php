@extends('include.app')
@section('header')
    <script src="{{ asset('asset/script/platformEarnings.js') }}"></script>
@endsection

@section('content')
    <div class="card mt-3">
        <div class="card-header">
            <h4>{{ __('Platform Earnings') }}</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive col-12">
                <table class="table table-striped w-100 word-wrap" id="platformEarningsTable">
                    <thead>
                        <tr>
                            <th>{{ __('Earning Number') }}</th>
                            <th>{{ __('Earning Amount') }}</th>
                            <th>{{ __('Order Amount') }}</th>
                            <th>{{ __('Percentage') }}</th>
                            <th>{{ __('Booking Number') }}</th>
                            <th>{{ __('Doctor') }}</th>
                            <th>{{ __('Date & Time') }}</th>
                            <th>{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
@endsection
