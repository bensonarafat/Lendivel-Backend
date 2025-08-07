@extends('include.app')
@section('header')
    <script src="{{ asset('asset/script/viewBookingDetails.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('asset/style/viewBookingDetails.css') }}">
@endsection

@section('content')
    <input type="hidden" value="{{ $booking->id }}" id="bookingId">

    <div class="card">
        <div class="card-header">
            <h4>

            </h4>


        </div>
        <div class="card-body">


        </div>
    </div>

    <div class="card">
        <div class="card-header">
        </div>
        <div class="card-body">

        </div>
    </div>
@endsection
