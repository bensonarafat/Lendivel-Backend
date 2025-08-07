@extends('include.app')
@section('header')
    <script src="{{ asset('asset/script/coupons.js') }}"></script>
@endsection

@section('content')
    <div class="card mt-3">
        <div class="card-header">
            <h4>{{ __('Coupons') }}</h4>

            <a data-toggle="modal" data-target="#addCouponModal" href=""
                class="ml-auto btn btn-primary text-white">{{ __('Add Coupon') }}</a>
        </div>
        <div class="card-body">
            <div class="table-responsive col-12">
                <table class="table table-striped w-100 word-wrap" id="couponsTable">
                    <thead>
                        <tr>
                            <th>{{ __('Coupon') }}</th>
                            <th>{{ __('Percentage') }}</th>
                            <th>{{ __('Max. Discount Amount') }}</th>
                            <th>{{ __('Heading') }}</th>
                            <th>{{ __('Description') }}</th>
                            <th>{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    {{-- Edit Coupon Modal --}}
    <div class="modal fade" id="editCouponModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>{{ __('Edit Coupon') }}</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form action="" method="post" enctype="multipart/form-data" id="editCouponForm"
                        autocomplete="off">
                        @csrf

                        <input type="hidden" name="id" id="editCouponId">

                        <div class="form-group">
                            <label> {{ __('Coupon') }}</label>
                            <input id="editCoupon" type="text" name="coupon" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label> {{ __('Heading') }}</label>
                            <input id="editHeading" type="text" name="heading" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label> {{ __('Description') }}</label>
                            <input id="editDescription" type="text" name="description" class="form-control" required>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-6">
                                <label> {{ __('Percentage') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            %
                                        </div>
                                    </div>
                                    <input id="editPercentage" name="percentage" type="number"
                                        class="form-control currency" required>
                                </div>
                            </div>

                            <div class="form-group col-6">
                                <label>{{ __('Max. Discount Amount') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            {{ $settings->currency }}
                                        </div>
                                    </div>
                                    <input id="editMaxDiscAmount" name="max_discount_amount" type="number"
                                        class="form-control currency" required>
                                </div>
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
    {{-- Add Coupon Modal --}}
    <div class="modal fade" id="addCouponModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>{{ __('Add Coupon') }}</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form action="" method="post" enctype="multipart/form-data" id="addCouponForm" autocomplete="off">
                        @csrf

                        <div class="form-group">
                            <label> {{ __('Coupon') }}</label>
                            <input type="text" name="coupon" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label> {{ __('Heading') }}</label>
                            <input type="text" name="heading" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label> {{ __('Description') }}</label>
                            <input type="text" name="description" class="form-control" required>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-6">
                                <label> {{ __('Percentage') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            %
                                        </div>
                                    </div>
                                    <input name="percentage" type="number" class="form-control currency" required>
                                </div>
                            </div>
                            <div class="form-group col-6">
                                <label>{{ __('Max. Discount Amount') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            {{ $settings->currency }}
                                        </div>
                                    </div>
                                    <input name="max_discount_amount" type="number" class="form-control currency"
                                        required>
                                </div>
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
@endsection
