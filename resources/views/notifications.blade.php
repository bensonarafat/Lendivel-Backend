@extends('include.app')
@section('header')
    <script src="{{ asset('asset/script/notifications.js') }}"></script>
@endsection

@section('content')
    <style>
        #Section1 table.dataTable td {
            white-space: normal !important;
        }

        #Section2 table.dataTable td {
            white-space: normal !important;
        }

        .w-70 {
            width: 70% !important;
        }
    </style>
    <div class="card mt-3">
        <div class="card-header">
            <h4>{{ __('Notifications') }}</h4>

            <a data-toggle="modal" data-target="#addUserNotiModal" href=""
                class="ml-auto btn btn-primary text-white">{{ __('Notify Users') }}</a>

            <a data-toggle="modal" data-target="#addDoctorNotiModal" href=""
                class="ml-2 btn btn-primary text-white">{{ __('Notify Doctors') }}</a>
        </div>
        <div class="card-body">
            <ul class="nav nav-pills border-b mb-3  ml-0">

                <li role="presentation" class="nav-item"><a class="nav-link pointer active" href="#Section1"
                        aria-controls="home" role="tab" data-toggle="tab">{{ __('Users') }}<span
                            class="badge badge-transparent "></span></a>
                </li>

                <li role="presentation" class="nav-item"><a class="nav-link pointer" href="#Section2" role="tab"
                        data-toggle="tab">{{ __('Doctors') }}
                        <span class="badge badge-transparent "></span></a>
                </li>
            </ul>

            <div class="tab-content tabs" id="home">
                {{-- Section 1 --}}
                <div role="tabpanel" class="row tab-pane active" id="Section1">
                    <div class="table-responsive col-12">
                        <table class="table table-striped w-100" id="usersTable">
                            <thead>
                                <tr>
                                    <th class="w-70">{{ __('Notification') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                {{-- Section 2 --}}
                <div role="tabpanel" class="row tab-pane" id="Section2">
                    <div class="table-responsive col-12">
                        <table class="table table-striped w-100" id="doctorTable">
                            <thead>
                                <tr>
                                    <th class="w-70">{{ __('Notification') }}</th>
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
    {{-- Add Doctor Noti Modal --}}
    <div class="modal fade" id="addDoctorNotiModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>{{ __('Notify Doctors') }}</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form action="" method="post" enctype="multipart/form-data" id="addDoctorNotiForm"
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

    {{-- Edit Doctor Noti Modal --}}
    <div class="modal fade" id="editDoctorNotiModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>{{ __('Notify Doctors') }}</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form action="" method="post" enctype="multipart/form-data" id="editDoctorNotiForm"
                        autocomplete="off">
                        @csrf

                        <input type="hidden" name="id" id="editDoctorNotiId">

                        <div class="form-group">
                            <label> {{ __('Title') }}</label>
                            <input type="text" id="editDoctorNotiTitle" name="title" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label> {{ __('Description') }}</label>
                            <textarea id="editDoctorNotiDesc" rows="10" style="height:200px !important;" type="text" name="description"
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
@endsection
