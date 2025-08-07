@extends('include.app')
@section('header')
    <script src="{{ asset('asset/script/reviews.js') }}"></script>
@endsection

@section('content')
    <style>
        .starDisabled {
            color: rgb(184, 184, 184) !important;
        }

        .starActive {
            color: orangered !important;
        }

        .reviewsTable table.dataTable th,
        .reviewsTable table.dataTable td {
            white-space: unset !important;
        }


        .w-30 {
            width: 30% !important;
        }
    </style>
    <div class="card mt-3">
        <div class="card-header">
            <h4>{{ __('Reviews') }}</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive col-12">
                <table class="table table-striped w-100 word-wrap reviewsTable" id="reviewsTable">
                    <thead>
                        <tr>
                            <th>{{ __('Rating') }}</th>
                            <th class="w-30">{{ __('Comment') }}</th>
                            <th>{{ __('Appointment') }}</th>
                            <th>{{ __('Doctor') }}</th>
                            <th>{{ __('Date&Time') }}</th>
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
