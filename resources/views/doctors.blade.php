@extends('include.app')
@section('header')
    <script src="{{ asset('asset/script/doctors.js') }}"></script>
@endsection

@section('content')
    <div class="card mt-3">
        <div class="card-header">
            <h4>{{ __('Doctors') }}</h4>
        </div>
        <div class="card-body">
            <ul class="nav nav-pills border-b mb-3  ml-0">

                <li role="presentation" class="nav-item"><a class="nav-link pointer active" href="#Section1" aria-controls="home"
                        role="tab" data-toggle="tab">{{ __('All Doctors') }}<span
                            class="badge badge-transparent "></span></a>
                </li>

                <li role="presentation" class="nav-item"><a class="nav-link pointer" href="#Section2" aria-controls="home"
                        role="tab" data-toggle="tab">{{ __('Approved') }}<span
                            class="badge badge-transparent "></span></a>
                </li>

                <li role="presentation" class="nav-item"><a class="nav-link pointer" href="#Section3" aria-controls="home"
                        role="tab" data-toggle="tab">{{ __('Pending') }}<span
                            class="badge badge-transparent "></span></a>
                </li>
                <li role="presentation" class="nav-item"><a class="nav-link pointer" href="#Section4" aria-controls="home"
                        role="tab" data-toggle="tab">{{ __('Banned') }}<span
                            class="badge badge-transparent "></span></a>
                </li>

            </ul>

            <div class="tab-content tabs" id="home">
                {{-- Section 1 --}}
                <div role="tabpanel" class="row tab-pane active" id="Section1">
                    <div class="table-responsive col-12">
                        <table class="table table-striped w-100" id="allDoctorsTable">
                            <thead>
                                <tr>
                                    <th>{{ __('Image') }}</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Number') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Gender') }}</th>
                                    <th>{{ __('Category') }}</th>
                                    <th>{{ __('Experience') }}</th>
                                    <th>{{ __('Total Patients Cured') }}</th>
                                    <th>{{ __('Lifetime Earnings') }}</th>
                                    <th>{{ __('Contact') }}</th>
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
                        <table class="table table-striped w-100" id="approvedDoctorsTable">
                            <thead>
                                <tr>
                                    <th>{{ __('Image') }}</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Number') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Gender') }}</th>
                                    <th>{{ __('Category') }}</th>
                                    <th>{{ __('Experience') }}</th>
                                    <th>{{ __('Total Patients Cured') }}</th>
                                    <th>{{ __('Lifetime Earnings') }}</th>
                                    <th>{{ __('Contact') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                {{-- Section 3 --}}
                <div role="tabpanel" class="row tab-pane" id="Section3">
                    <div class="table-responsive col-12">
                        <table class="table table-striped w-100" id="pendingDoctorsTable">
                            <thead>
                                <tr>
                                    <th>{{ __('Image') }}</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Number') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Gender') }}</th>
                                    <th>{{ __('Category') }}</th>
                                    <th>{{ __('Experience') }}</th>
                                    <th>{{ __('Total Patients Cured') }}</th>
                                    <th>{{ __('Lifetime Earnings') }}</th>
                                    <th>{{ __('Contact') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                {{-- Section 4 --}}
                <div role="tabpanel" class="row tab-pane" id="Section4">
                    <div class="table-responsive col-12">
                        <table class="table table-striped w-100" id="bannedDoctorsTable">
                            <thead>
                                <tr>
                                    <th>{{ __('Image') }}</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Number') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Gender') }}</th>
                                    <th>{{ __('Category') }}</th>
                                    <th>{{ __('Experience') }}</th>
                                    <th>{{ __('Total Patients Cured') }}</th>
                                    <th>{{ __('Lifetime Earnings') }}</th>
                                    <th>{{ __('Contact') }}</th>
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
@endsection
