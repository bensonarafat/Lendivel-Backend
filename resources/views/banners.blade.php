@extends('include.app')
@section('header')
    <script src="{{ asset('asset/script/banners.js') }}"></script>
@endsection

@section('content')
    <div class="card mt-3">
        <div class="card-header">
            <h4>{{ __('Banners') }}</h4>
            <a data-toggle="modal" data-target="#addBannerModal" href=""
                class="ml-auto btn btn-primary text-white">{{ __('Add Banner') }}</a>
        </div>
        <div class="card-body">
            <div class="table-responsive col-12">
                <table class="table table-striped w-100 word-wrap" id="bannersTable">
                    <thead>
                        <tr>
                            <th>{{ __('Image') }}</th>
                            <th>{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
    {{-- Add Banner Modal --}}
    <div class="modal fade" id="addBannerModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>{{ __('Add Banner') }}</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form action="" method="post" enctype="multipart/form-data" id="addBannerForm" autocomplete="off">
                        @csrf

                        <div class="form-group">
                            <label>{{ __('Banner') }}</label>
                            <input class="form-control" type="file" name="image" required>
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
