@extends('include.app')
@section('header')
    <script src="{{ asset('asset/script/viewDoctorProfile.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('asset/style/viewDoctorProfile.css') }}">
@endsection

@section('content')
    <input type="hidden" value="{{ $doctor->id }}" id="doctorId">

    <div class="card">
        <div class="card-header">
            <img class="rounded-circle img-border mr-2" width="40" height="40"
                src="{{ env('FILES_BASE_URL') }}{{ $doctor->image }}" alt="">
            <h4>
                {{ $doctor->name }} / {{ $doctor->doctor_number }}
            </h4>


            {{-- Doctor Status --}}
            @if ($doctor->status == $doctorStatus['statusDoctorPending'])
                <span class="badge bg-warning text-white ">{{ __('Pending Review') }} </span>
            @elseif($doctor->status == $doctorStatus['statusDoctorApproved'])
                <span class="badge bg-success text-white ">{{ __('Active') }} </span>
            @elseif($doctor->status == $doctorStatus['statusDoctorBanned'])
                <span class="badge bg-danger text-white ">{{ __('Banned') }} </span>
            @endif


            {{-- Action Buttons --}}
            @if ($doctor->status == $doctorStatus['statusDoctorPending'])
                <a href="" id="approveDoctor"
                    class="ml-auto btn btn-success activateDoctor">{{ __('Approve Doctor') }}</a>
            @elseif($doctor->status == $doctorStatus['statusDoctorApproved'])
                <a href="" id="banDoctor" class="ml-auto btn btn-danger text-white">{{ __('Ban Doctor') }}</a>
            @elseif($doctor->status == $doctorStatus['statusDoctorBanned'])
                <a href="" id="activateDoctor"
                    class="ml-auto btn btn-success activateDoctor">{{ __('Activate Doctor') }}</a>
            @endif

        </div>
        <div class="card-body">

            <div class="form-row">

                <div class="col-md-2">
                    <label class="mb-0 text-grey" for="">{{ __('Mobile Number') }}</label>
                    <p class="mt-0 p-data">{{ $doctor->visible_mobile_number }}</p>
                </div>
                <div class="col-md-2">
                    <label class="mb-0 text-grey d-block" for="">{{ __('Gender') }}</label>
                    @if ($doctor->gender == 1)
                        <span class="badge bg-primary text-white ">{{ __('Male') }}</span>
                    @else
                        <span class="badge bg-info text-white ">{{ __('Female') }}</span>
                    @endif
                </div>
                <div class="col-md-2">
                    <label class="mb-0 text-grey d-block" for="">{{ __('Category') }}</label>
                    <span class="badge bg-primary text-white mr-2">{{ $doctor->category->title }}</span>
                </div>

                {{-- Online consultation --}}
                <div class="col-md-2">
                    <label class="mb-0 text-grey" for="">{{ __('Online Consultation') }}</label>
                    @if ($doctor->online_consultation == 0)
                        <p class="mt-0 p-data">{{ __('No') }}</p>
                    @else
                        <p class="mt-0 p-data">{{ __('Yes') }}</p>
                    @endif
                </div>
                {{-- Clinic consultation --}}
                <div class="col-md-2">
                    <label class="mb-0 text-grey" for="">{{ __('Clinic Consultation') }}</label>
                    @if ($doctor->clinic_consultation == 0)
                        <p class="mt-0 p-data">{{ __('No') }}</p>
                    @else
                        <p class="mt-0 p-data">{{ __('Yes') }}</p>
                    @endif
                </div>

            </div>

            <div class="form-row mt-3">
                <div class="col-md-2">
                    <label class="mb-0 text-grey" for="">{{ __('Wallet') }}</label>
                    <p class="mt-0 p-data">{{ $settings->currency }}{{ $doctor->wallet }}</p>
                </div>
                <div class="col-md-2">
                    <label class="mb-0 text-grey" for="">{{ __('Lifetime Earnings') }}</label>
                    <p class="mt-0 p-data">{{ $settings->currency }}{{ $doctor->lifetime_earnings }}</p>
                </div>
                <div class="col-md-2">
                    <label class="mb-0 text-grey" for="">{{ __('Total Patients Cured') }}</label>
                    <p class="mt-0 p-data">{{ $doctor->total_patients_cured }}</p>
                </div>

                <div class="col-md-2">
                    <label class="mb-0 text-grey" for="">{{ __('Overall Rating') }}</label>
                    <p class="mt-0 p-data">{{ $doctor->rating }}</p>
                </div>

                {{-- On Vacation Status --}}
                <div class="col-md-2">
                    <label class="mb-0 text-grey" for="">{{ __('On Vacation') }}</label>
                    @if ($doctor->on_vacation == 0)
                        <p class="mt-0 p-data">{{ __('No') }}</p>
                    @else
                        <p class="mt-0 p-data">{{ __('Yes') }}</p>
                    @endif
                </div>
            </div>
            <div class="form-row mt-2">
                <div class="col-md-2">
                    <div class="bank-details">
                        <span>{{ __('Bank Details :') }}</span>
                        @if ($doctor->bankAccount != null)
                            <span class="text-dark font-14">{{ __('Holder : ') }}{{ $doctor->bankAccount->holder }}</span>
                            <span
                                class="text-dark font-14">{{ __('Bank : ') }}{{ $doctor->bankAccount->bank_name }}</span>
                            <span
                                class="text-dark font-14">{{ __('Account : ') }}{{ $doctor->bankAccount->account_number }}</span>
                            <span
                                class="text-dark font-14">{{ __('Swift Code : ') }}{{ $doctor->bankAccount->swift_code }}</span>
                            <a id="chequePhoto" data-cheque="{{ env('FILES_BASE_URL') . $doctor->bankAccount->cheque_photo }}" target="_blank" class="badge bg-primary text-white mt-1"
                            href="">{{ __('Cheque Photo') }}</a>
                        @endif
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="bank-details">
                        <span>{{ __('Clinic Details :') }}</span>

                        <span class="text-dark font-14">{{ __('Clinic Name : ') }}{{ $doctor->clinic_name }}</span>
                        <span class="text-dark font-14">{{ __('Clinic Address : ') }}{{ $doctor->clinic_address }}</span>

                        <a target="_blank" class="badge bg-primary text-white mt-1"
                            href="https://www.google.com/maps/?q={{ $doctor->clinic_lat }},{{ $doctor->clinic_long }}">{{ __('Click To Locate') }}</a>

                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <ul class="nav nav-pills border-b  ml-0">

                <li role="presentation" class="nav-item"><a class="nav-link pointer active" href="#tabDetails"
                        aria-controls="home" role="tab" data-toggle="tab">{{ __('Details') }}<span
                            class="badge badge-transparent "></span></a>
                </li>

                <li role="presentation" class="nav-item"><a class="nav-link pointer" href="#tabAppointments" role="tab"
                        aria-controls="tabAppointments" data-toggle="tab">{{ __('Appointments') }}
                        <span class="badge badge-transparent "></span></a>
                </li>

                <li role="presentation" class="nav-item"><a class="nav-link pointer" href="#tabReviews" role="tab"
                        aria-controls="tabReviews" data-toggle="tab">{{ __('Reviews') }}
                        <span class="badge badge-transparent "></span></a>
                </li>

                <li role="presentation" class="nav-item"><a class="nav-link pointer" href="#tabReels" role="tab"
                        data-toggle="tab">{{ __('Reels') }}
                        <span class="badge badge-transparent "></span></a>
                </li>

                <li role="presentation" class="nav-item"><a class="nav-link pointer" href="#tabWallet" role="tab"
                        data-toggle="tab">{{ __('Wallet') }}
                        <span class="badge badge-transparent "></span></a>
                </li>
                <li role="presentation" class="nav-item"><a class="nav-link pointer" href="#tabPayOuts" role="tab"
                        data-toggle="tab">{{ __('Payouts') }}
                        <span class="badge badge-transparent "></span></a>
                </li>
                <li role="presentation" class="nav-item"><a class="nav-link pointer" href="#tabEarningHistory"
                        role="tab" data-toggle="tab">{{ __('Earnings') }}
                        <span class="badge badge-transparent "></span></a>
                </li>

                <li role="presentation" class="nav-item"><a class="nav-link pointer" href="#tabServices" role="tab"
                        data-toggle="tab">{{ __('Services') }}
                        <span class="badge badge-transparent "></span></a>
                </li>
                <li role="presentation" class="nav-item"><a class="nav-link pointer" href="#tabExpertise"
                        role="tab" data-toggle="tab">{{ __('Expertise') }}
                        <span class="badge badge-transparent "></span></a>
                </li>
                <li role="presentation" class="nav-item"><a class="nav-link pointer" href="#tabExperience"
                        role="tab" data-toggle="tab">{{ __('Experience') }}
                        <span class="badge badge-transparent "></span></a>
                </li>
                <li role="presentation" class="nav-item"><a class="nav-link pointer" href="#tabServiceLocations"
                        role="tab" data-toggle="tab">{{ __('Service Locations') }}
                        <span class="badge badge-transparent "></span></a>
                </li>

                <li role="presentation" class="nav-item"><a class="nav-link pointer" href="#tabAwards" role="tab"
                        data-toggle="tab">{{ __('Awards') }}
                        <span class="badge badge-transparent "></span></a>
                </li>
                <li role="presentation" class="nav-item"><a class="nav-link pointer" href="#tabHolidays" role="tab"
                        data-toggle="tab">{{ __('Holidays') }}
                        <span class="badge badge-transparent "></span></a>
                </li>
                <li role="presentation" class="nav-item"><a class="nav-link pointer" href="#tabSlots" role="tabSlots"
                        data-toggle="tab">{{ __('Slots') }}
                        <span class="badge badge-transparent "></span></a>
                </li>



            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content tabs" id="home">

                {{-- Details --}}
                <div role="tabpanel" class="tab-pane active" id="tabDetails">

                    <div class="form-group mt-0">
                        <div class="d-flex mb-2">
                            <div class="salon_image">
                                <img width="100" class="rounded" height="100"
                                    src="{{ env('FILES_BASE_URL') }}{{ $doctor->image }}" alt="">
                            </div>
                        </div>
                    </div>


                    <form action="" method="post" enctype="multipart/form-data" class=""
                        id="doctorDetailsForm" autocomplete="off">
                        @csrf

                        <input type="hidden" name="id" value="{{ $doctor->id }}">

                        <div class="form-row ">
                            <div class="form-group col-md-3">
                                <label for="">{{ __('Designation') }}</label>
                                <input type="text" class="form-control" name="designation"
                                    value="{{ $doctor->designation }}">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="">{{ __('Languages Spoken') }}</label>
                                <input type="text" class="form-control" name="languages_spoken"
                                    value="{{ $doctor->languages_spoken }}">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="">{{ __('Experience Years') }}</label>
                                <input type="number" class="form-control" name="experience_year"
                                    value="{{ $doctor->experience_year }}">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="">{{ __('Consultation Fees') }}</label>
                                <input type="number" class="form-control" name="consultation_fee"
                                    value="{{ $doctor->consultation_fee }}">
                            </div>
                        </div>

                        <div class="form-row ">
                            <div class="form-group col-md-4">
                                <label for="">{{ __('Degrees') }}</label>
                                <textarea style="height:100px !important;" type="text" class="form-control" name="degrees">{{ $doctor->degrees }}</textarea>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="">{{ __('About') }}</label>
                                <textarea style="height:100px !important;" type="text" class="form-control" name="about_youself">{{ $doctor->about_youself }}</textarea>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="">{{ __('Educational Journey') }}</label>
                                <textarea style="height:100px !important;" type="text" class="form-control" name="educational_journey">{{ $doctor->educational_journey }}</textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <input class="btn btn-primary mr-1" type="submit" value=" {{ __('Submit') }}">
                        </div>

                    </form>


                </div>
                {{-- Appointments --}}
                <div role="tabpanel" class="tab-pane" id="tabAppointments">
                    <div class="table-responsive col-12">
                        <table class="table table-striped w-100 word-wrap" id="appointmentsTable">
                            <thead>
                                <tr>
                                    <th>{{ __('Appointments Number') }}</th>
                                    <th>{{ __('User') }}</th>
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
                {{-- Reviews --}}
                <div role="tabpanel" class="tab-pane" id="tabReviews">
                    <div class="table-responsive col-12">
                        <table class="table table-striped w-100 word-wrap" id="reviewsTable">
                            <thead>
                                <tr>
                                    <th>{{ __('Rating') }}</th>
                                    <th class="w-30">{{ __('Comment') }}</th>
                                    <th>{{ __('Appointment') }}</th>
                                    <th>{{ __('Date&Time') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Reels --}}
                <div role="tabpanel" class="tab-pane" id="tabReels">
                    <div class="table-responsive col-12">
                        <table class="table table-striped w-100 word-wrap" id="reelsTable">
                            <thead>
                                <tr>
                                    <th>{{ __('Thumb') }}</th>
                                    <th>{{ __('Description') }}</th>
                                    <th>{{ __('Stats') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Wallet --}}
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
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Payouts --}}
                <div role="tabpanel" class="tab-pane" id="tabPayOuts">
                    <div class="table-responsive col-12">
                        <table class="table table-striped w-100 word-wrap" id="doctorPayOutsTable">
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

                {{-- Earnings List --}}
                <div role="tabpanel" class="tab-pane" id="tabEarningHistory">
                    <div class="table-responsive col-12">
                        <table class="table table-striped w-100 word-wrap" id="earningsTable">
                            <thead>
                                <tr>
                                    <th>{{ __('Earning Number') }}</th>
                                    <th>{{ __('Appointment Number') }}</th>
                                    <th>{{ __('Amount') }}</th>
                                    <th>{{ __('Date') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Services --}}
                <div role="tabpanel" class="tab-pane" id="tabServices">
                    <div class="table-responsive col-12">
                        <table class="table table-striped w-100 word-wrap" id="servicesTable">
                            <thead>
                                <tr>
                                    <th>{{ __('Title') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                {{-- Experitise --}}
                <div role="tabpanel" class="tab-pane" id="tabExpertise">
                    <div class="table-responsive col-12">
                        <table class="table table-striped w-100 word-wrap" id="expertiseTable">
                            <thead>
                                <tr>
                                    <th>{{ __('Title') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Service Locations --}}
                <div role="tabpanel" class="tab-pane" id="tabServiceLocations">
                    <div class="table-responsive col-12">
                        <table class="table table-striped w-100 word-wrap" id="serviceLocationsTable">
                            <thead>
                                <tr>
                                    <th>{{ __('Hospital Name') }}</th>
                                    <th>{{ __('Hospital Address') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Experience --}}
                <div role="tabpanel" class="tab-pane" id="tabExperience">
                    <div class="table-responsive col-12">
                        <table class="table table-striped w-100 word-wrap" id="experienceTable">
                            <thead>
                                <tr>
                                    <th>{{ __('Title') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                {{-- Experience --}}
                <div role="tabpanel" class="tab-pane" id="tabAwards">
                    <div class="table-responsive col-12">
                        <table class="table table-striped w-100 word-wrap" id="awardsTable">
                            <thead>
                                <tr>
                                    <th>{{ __('Title') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                {{-- Experience --}}
                <div role="tabpanel" class="tab-pane" id="tabHolidays">
                    <div class="table-responsive col-12">
                        <table class="table table-striped w-100 word-wrap" id="holidaysTable">
                            <thead>
                                <tr>
                                    <th>{{ __('Date') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                {{-- Slots --}}
                <div role="tabpanel" class="tab-pane" id="tabSlots">
                    <div class="table-responsive col-12">
                        <div class="mt-2">
                            <label class="mb-0 text-grey" for="">{{ __('Monday') }}</label>
                            @foreach ($slots['mondaySlots'] as $item)
                                <span class="badge bg-info text-white ">{{ $item['time'] }}</span>
                            @endforeach
                        </div>
                        <div class="mt-2">
                            <label class="mb-0 text-grey" for="">{{ __('Tuesday') }}</label>
                            @foreach ($slots['tuesdaySlots'] as $item)
                                <span class="badge bg-info text-white ">{{ $item['time'] }}</span>
                            @endforeach
                        </div>
                        <div class="mt-2">
                            <label class="mb-0 text-grey" for="">{{ __('Wednesday') }}</label>
                            @foreach ($slots['wednesdaySlots'] as $item)
                                <span class="badge bg-info text-white ">{{ $item['time'] }}</span>
                            @endforeach
                        </div>
                        <div class="mt-2">
                            <label class="mb-0 text-grey" for="">{{ __('Thursday') }}</label>
                            @foreach ($slots['thursdaySlots'] as $item)
                                <span class="badge bg-info text-white ">{{ $item['time'] }}</span>
                            @endforeach
                        </div>
                        <div class="mt-2">
                            <label class="mb-0 text-grey" for="">{{ __('Friday') }}</label>
                            @foreach ($slots['thursdaySlots'] as $item)
                                <span class="badge bg-info text-white ">{{ $item['time'] }}</span>
                            @endforeach
                        </div>
                        <div class="mt-2">
                            <label class="mb-0 text-grey" for="">{{ __('Saturday') }}</label>
                            @foreach ($slots['saturdaySlots'] as $item)
                                <span class="badge bg-info text-white ">{{ $item['time'] }}</span>
                            @endforeach
                        </div>
                        <div class="mt-2">
                            <label class="mb-0 text-grey" for="">{{ __('Sunday') }}</label>
                            @foreach ($slots['sundaySlots'] as $item)
                                <span class="badge bg-info text-white ">{{ $item['time'] }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>

      {{-- View cheque photo Modal --}}
      <div class="modal fade" id="chequePhotoModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
      aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
              <div class="modal-header">
                  <h5>{{ __('Cheque Photo') }}</h5>

                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body">
                <img id="chequePhotoImg" width="100%" src="" alt="">
              </div>

          </div>
      </div>
  </div>


    {{-- Preview Gallery Modal --}}
    <div class="modal fade" id="previewGalleryModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>{{ __('Preview Gallery Post') }}</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p id="descGalleryPreview"></p>
                    <img class="rounded" width="100%" id="imggalleryPreview" src="" alt="">
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
      {{-- Video Modal --}}
      <div class="modal fade" id="video_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
      aria-hidden="true">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                  <h5>{{ __('View Reel') }}</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body">
                  <p id="videoDesc"></p>
                  <video rel="" id="video" width="450" height="450" controls>
                      <source src="" type="video/mp4">
                      Your browser does not support the video tag.
                  </video>
              </div>

          </div>
      </div>
  </div>
@endsection
