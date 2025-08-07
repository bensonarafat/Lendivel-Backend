@extends('include.app')
@section('header')
    <script src="{{ asset('asset/script/users.js') }}"></script>
@endsection

@section('content')
    <style>
    </style>
    <div class="card mt-3">
        <div class="card-header">
            <h4>{{ __('Users') }}</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive col-12">
                <table class="table table-striped w-100" id="usersTable">
                    <thead>
                        <tr>
                            <th>{{ __('Image') }}</th>
                            <th>{{ __('Identity') }}</th>
                            <th>{{ __('Fullname') }}</th>
                            <th>{{ __('Total Appointments') }}</th>
                            <th>{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>



        </div>
    </div>

    {{-- Edit user Noti Modal --}}
    <div class="modal fade" id="editUserNotiModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>{{ __('Notify Users') }}</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form action="" method="post" enctype="multipart/form-data" id="editUserNotiForm"
                        autocomplete="off">
                        @csrf

                        <input type="hidden" name="id" id="editUserNotiId">

                        <div class="form-group">
                            <label> {{ __('Title') }}</label>
                            <input type="text" id="editUserNotiTitle" name="title" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label> {{ __('Description') }}</label>
                            <textarea id="editUserNotiDesc" rows="10" style="height:200px !important;" type="text" name="description"
                                class="form-control" required></textarea>
                        </div>
                        <div class="form-group">
                            <input class="btn btn-primary mr-1" type="submit" value=" {{ __('Submit') }}">
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>
    {{-- Add user Noti Modal --}}
    <div class="modal fade" id="addUserNotiModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>{{ __('Notify Users') }}</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form action="" method="post" enctype="multipart/form-data" id="addUserNotiForm"
                        autocomplete="off">
                        @csrf

                        <div class="form-group">
                            <label> {{ __('Title') }}</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label> {{ __('Description') }}</label>
                            <textarea rows="10" style="height:200px !important;" type="text" name="description" class="form-control"
                                required></textarea>
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
