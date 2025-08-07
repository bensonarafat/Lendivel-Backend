<?php

namespace App\Http\Controllers;

use App\Models\Appointments;
use App\Models\Constants;
use App\Models\DoctorAppointmentSlots;
use App\Models\DoctorAwards;
use App\Models\DoctorBankAccount;
use App\Models\DoctorCategories;
use App\Models\DoctorCatSuggestions;
use App\Models\DoctorEarningHistory;
use App\Models\DoctorExperience;
use App\Models\DoctorExpertise;
use App\Models\DoctorHolidays;
use App\Models\DoctorNotifications;
use App\Models\DoctorPayoutHistory;
use App\Models\DoctorReviews;
use App\Models\Doctors;
use App\Models\DoctorServiceLocations;
use App\Models\DoctorServices;
use App\Models\DoctorWalletStatements;
use App\Models\FaqCats;
use App\Models\GlobalFunction;
use App\Models\GlobalSettings;
use App\Models\Prescriptions;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class DoctorController extends Controller
{
    //

    function fetchUserDetails(Request $request)
    {
        $rules = [
            'user_id' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }

        $user = Users::find($request->user_id);
        if ($user == null) {
            return response()->json(['status' => false, 'message' => "User doesn't exists!"]);
        }

        return Globalfunction::sendDataResponse(true, 'details fetched successfully', $user);
    }

    function deleteExperience($id)
    {
        $item = DoctorExperience::find($id);
        $item->delete();

        return Globalfunction::sendSimpleResponse(true, 'deleted successfully');
    }
    function deleteAwards($id)
    {
        $item = DoctorAwards::find($id);
        $item->delete();

        return Globalfunction::sendSimpleResponse(true, 'deleted successfully');
    }
    function deleteExpertise($id)
    {
        $item = DoctorExpertise::find($id);
        $item->delete();

        return Globalfunction::sendSimpleResponse(true, 'deleted successfully');
    }
    function deleteService($id)
    {
        $item = DoctorServices::find($id);
        $item->delete();

        return Globalfunction::sendSimpleResponse(true, 'deleted successfully');
    }
    function deleteServiceLocation($id)
    {
        $item = DoctorServiceLocations::find($id);
        $item->delete();

        return Globalfunction::sendSimpleResponse(true, 'item deleted successfully');
    }
    function deleteDoctorHoliday($id)
    {
        $item = DoctorHolidays::find($id);
        $item->delete();

        return Globalfunction::sendSimpleResponse(true, 'item deleted successfully');
    }

    function fetchDoctorServiceLocationList(Request $request)
    {
        $totalData =  DoctorServiceLocations::where('doctor_id', $request->doctorId)->count();
        $rows = DoctorServiceLocations::where('doctor_id', $request->doctorId)->orderBy('id', 'DESC')->get();

        $result = $rows;

        $columns = array(
            0 => 'id',
            1 => 'fullname',
            2 => 'identity',
            3 => 'username',
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $totalFiltered = $totalData;
        if (empty($request->input('search.value'))) {
            $result = DoctorServiceLocations::where('doctor_id', $request->doctorId)
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
            $result =  DoctorServiceLocations::where('doctor_id', $request->doctorId)
                ->where(function ($query) use ($search) {
                    $query->Where('hospital_title', 'LIKE', "%{$search}%")
                        ->orWhere('hospital_address', 'LIKE', "%{$search}%");
                })
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = DoctorServiceLocations::where('doctor_id', $request->doctorId)
                ->where(function ($query) use ($search) {
                    $query->Where('hospital_title', 'LIKE', "%{$search}%")
                        ->orWhere('hospital_address', 'LIKE', "%{$search}%");
                })
                ->count();
        }
        $data = array();
        foreach ($result as $item) {

            $delete = '<a href="" class="mr-2 btn btn-danger text-white delete" rel=' . $item->id . ' >' . __("Delete") . '</a>';
            $data[] = array(
                $item->hospital_title,
                $item->hospital_address,
                $delete,
            );
        }
        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => $totalFiltered,
            "data"            => $data
        );
        echo json_encode($json_data);
        exit();
    }
    function fetchDoctorHolidaysList(Request $request)
    {
        $totalData =  DoctorHolidays::where('doctor_id', $request->doctorId)->count();
        $rows = DoctorHolidays::where('doctor_id', $request->doctorId)->orderBy('id', 'DESC')->get();

        $result = $rows;

        $columns = array(
            0 => 'id',
            1 => 'fullname',
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $totalFiltered = $totalData;
        if (empty($request->input('search.value'))) {
            $result = DoctorHolidays::where('doctor_id', $request->doctorId)
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
            $result =  DoctorHolidays::where('doctor_id', $request->doctorId)
                ->where(function ($query) use ($search) {
                    $query->Where('date', 'LIKE', "%{$search}%");
                })
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = DoctorHolidays::where('doctor_id', $request->doctorId)
                ->where(function ($query) use ($search) {
                    $query->Where('date', 'LIKE', "%{$search}%");
                })
                ->count();
        }
        $data = array();
        foreach ($result as $item) {

            $delete = '<a href="" class="mr-2 btn btn-danger text-white delete" rel=' . $item->id . ' >' . __("Delete") . '</a>';
            $data[] = array(
                date('d-m-Y', strtotime($item->date)),
                $delete,
            );
        }
        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => $totalFiltered,
            "data"            => $data
        );
        echo json_encode($json_data);
        exit();
    }
    function fetchDoctorAwardsList(Request $request)
    {
        $totalData =  DoctorAwards::where('doctor_id', $request->doctorId)->count();
        $rows = DoctorAwards::where('doctor_id', $request->doctorId)->orderBy('id', 'DESC')->get();

        $result = $rows;

        $columns = array(
            0 => 'id',
            1 => 'fullname',
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $totalFiltered = $totalData;
        if (empty($request->input('search.value'))) {
            $result = DoctorAwards::where('doctor_id', $request->doctorId)
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
            $result =  DoctorAwards::where('doctor_id', $request->doctorId)
                ->where(function ($query) use ($search) {
                    $query->Where('title', 'LIKE', "%{$search}%");
                })
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = DoctorAwards::where('doctor_id', $request->doctorId)
                ->where(function ($query) use ($search) {
                    $query->Where('title', 'LIKE', "%{$search}%");
                })
                ->count();
        }
        $data = array();
        foreach ($result as $item) {

            $delete = '<a href="" class="mr-2 btn btn-danger text-white delete" rel=' . $item->id . ' >' . __("Delete") . '</a>';
            $data[] = array(
                $item->title,
                $delete,
            );
        }
        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => $totalFiltered,
            "data"            => $data
        );
        echo json_encode($json_data);
        exit();
    }
    function fetchDoctorExperienceList(Request $request)
    {
        $totalData =  DoctorExperience::where('doctor_id', $request->doctorId)->count();
        $rows = DoctorExperience::where('doctor_id', $request->doctorId)->orderBy('id', 'DESC')->get();
        $settings = GlobalSettings::first();

        $result = $rows;

        $columns = array(
            0 => 'id',
            1 => 'fullname',
            2 => 'identity',
            3 => 'username',
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $totalFiltered = $totalData;
        if (empty($request->input('search.value'))) {
            $result = DoctorExperience::where('doctor_id', $request->doctorId)
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
            $result =  DoctorExperience::where('doctor_id', $request->doctorId)
                ->where(function ($query) use ($search) {
                    $query->Where('title', 'LIKE', "%{$search}%");
                })
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = DoctorExperience::where('doctor_id', $request->doctorId)
                ->where(function ($query) use ($search) {
                    $query->Where('title', 'LIKE', "%{$search}%");
                })
                ->count();
        }
        $data = array();
        foreach ($result as $item) {

            $delete = '<a href="" class="mr-2 btn btn-danger text-white delete" rel=' . $item->id . ' >' . __("Delete") . '</a>';
            $data[] = array(
                $item->title,
                $delete,
            );
        }
        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => $totalFiltered,
            "data"            => $data
        );
        echo json_encode($json_data);
        exit();
    }
    function fetchDoctorExpertiseList(Request $request)
    {
        $totalData =  DoctorExpertise::where('doctor_id', $request->doctorId)->count();
        $rows = DoctorExpertise::where('doctor_id', $request->doctorId)->orderBy('id', 'DESC')->get();
        $settings = GlobalSettings::first();

        $result = $rows;

        $columns = array(
            0 => 'id',
            1 => 'fullname',
            2 => 'identity',
            3 => 'username',
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $totalFiltered = $totalData;
        if (empty($request->input('search.value'))) {
            $result = DoctorExpertise::where('doctor_id', $request->doctorId)
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
            $result =  DoctorExpertise::where('doctor_id', $request->doctorId)
                ->where(function ($query) use ($search) {
                    $query->Where('title', 'LIKE', "%{$search}%");
                })
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = DoctorExpertise::where('doctor_id', $request->doctorId)
                ->where(function ($query) use ($search) {
                    $query->Where('title', 'LIKE', "%{$search}%");
                })
                ->count();
        }
        $data = array();
        foreach ($result as $item) {

            $delete = '<a href="" class="mr-2 btn btn-danger text-white delete" rel=' . $item->id . ' >' . __("Delete") . '</a>';
            $data[] = array(
                $item->title,
                $delete,
            );
        }
        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => $totalFiltered,
            "data"            => $data
        );
        echo json_encode($json_data);
        exit();
    }
    function fetchDoctorServicesList(Request $request)
    {
        $totalData =  DoctorServices::where('doctor_id', $request->doctorId)->count();
        $rows = DoctorServices::where('doctor_id', $request->doctorId)->orderBy('id', 'DESC')->get();
        $settings = GlobalSettings::first();

        $result = $rows;

        $columns = array(
            0 => 'id',
            1 => 'fullname',
            2 => 'identity',
            3 => 'username',
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $totalFiltered = $totalData;
        if (empty($request->input('search.value'))) {
            $result = DoctorServices::where('doctor_id', $request->doctorId)
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
            $result =  DoctorServices::where('doctor_id', $request->doctorId)
                ->where(function ($query) use ($search) {
                    $query->Where('title', 'LIKE', "%{$search}%");
                })
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = DoctorServices::where('doctor_id', $request->doctorId)
                ->where(function ($query) use ($search) {
                    $query->Where('title', 'LIKE', "%{$search}%");
                })
                ->count();
        }
        $data = array();
        foreach ($result as $item) {

            $delete = '<a href="" class="mr-2 btn btn-danger text-white delete" rel=' . $item->id . ' >' . __("Delete") . '</a>';
            $data[] = array(
                $item->title,
                $delete,
            );
        }
        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => $totalFiltered,
            "data"            => $data
        );
        echo json_encode($json_data);
        exit();
    }
    function fetchDoctorEarningsList(Request $request)
    {
        $totalData =  DoctorEarningHistory::where('doctor_id', $request->doctorId)->with('appointment')->count();
        $rows = DoctorEarningHistory::where('doctor_id', $request->doctorId)->with('appointment')->orderBy('id', 'DESC')->get();
        $settings = GlobalSettings::first();

        $result = $rows;

        $columns = array(
            0 => 'id',
            1 => 'fullname',
            2 => 'identity',
            3 => 'username',
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $totalFiltered = $totalData;
        if (empty($request->input('search.value'))) {
            $result = DoctorEarningHistory::where('doctor_id', $request->doctorId)->with('appointment')
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
            $result =  DoctorEarningHistory::where('doctor_id', $request->doctorId)
                ->with('appointment')
                ->where(function ($query) use ($search) {
                    $query->Where('earning_number', 'LIKE', "%{$search}%")
                        ->orWhere('amount', 'LIKE', "%{$search}%");
                })
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = DoctorEarningHistory::where('doctor_id', $request->doctorId)
                ->with('appointment')
                ->where(function ($query) use ($search) {
                    $query->Where('earning_number', 'LIKE', "%{$search}%")
                        ->orWhere('amount', 'LIKE', "%{$search}%");
                })
                ->orWhere('amount', 'LIKE', "%{$search}%")
                ->count();
        }
        $data = array();
        foreach ($result as $item) {

            $data[] = array(
                $item->earning_number,
                $item->appointment->appointment_number,
                $settings->currency . $item->amount,
                GlobalFunction::formateTimeString($item->created_at),
            );
        }
        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => $totalFiltered,
            "data"            => $data
        );
        echo json_encode($json_data);
        exit();
    }

    function fetchDoctorPayoutRequestsList(Request $request)
    {
        $totalData =  DoctorPayoutHistory::where('doctor_id', $request->doctorId)->with('doctor')->count();
        $rows = DoctorPayoutHistory::where('doctor_id', $request->doctorId)->with('doctor')->orderBy('id', 'DESC')->get();
        $settings = GlobalSettings::first();
        $result = $rows;

        $columns = array(
            0 => 'id',
            1 => 'fullname',
            2 => 'identity',
            3 => 'username',
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $totalFiltered = $totalData;
        if (empty($request->input('search.value'))) {
            $result = DoctorPayoutHistory::where('doctor_id', $request->doctorId)->with('doctor')
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
            $result =  DoctorPayoutHistory::where('doctor_id', $request->doctorId)
                ->where(function ($query) use ($search) {
                    $query->where('request_number', 'LIKE', "%{$search}%")
                        ->orWhere('amount', 'LIKE', "%{$search}%")
                        ->orWhere('summary', 'LIKE', "%{$search}%")
                        ->orWhereHas('doctor', function ($query) use ($search) {
                            $query->Where('name', 'LIKE', "%{$search}%");
                        });
                })
                ->with('doctor')
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = DoctorPayoutHistory::where('doctor_id', $request->doctorId)->with('doctor')
                ->where(function ($query) use ($search) {
                    $query->where('request_number', 'LIKE', "%{$search}%")
                        ->orWhere('amount', 'LIKE', "%{$search}%")
                        ->orWhere('summary', 'LIKE', "%{$search}%")
                        ->orWhereHas('doctor', function ($query) use ($search) {
                            $query->Where('name', 'LIKE', "%{$search}%");
                        });
                })
                ->count();
        }
        $data = array();
        foreach ($result as $item) {

            $bankAccount = $item->doctor->bankAccount;

            $bankDetails = "";

            if ($bankAccount != null) {
                $holder = '<span class="text-dark font-weight-bold font-14">' . $bankAccount->holder . '</span>';
                $bank_title = '<div class="bank-details"><span>' . $bankAccount->bank_name . '</span>';
                $account_number = '<span>' . __('Account : ') .  $bankAccount->account_number . '</span>';
                $swift_code = '<span>' . __('Swift Code : ') . $bankAccount->swift_code . '</span></div>';
                $bankDetails = $holder . $bank_title . $account_number . $swift_code;
            }

            $complete = '<a href="" class="mr-2 btn btn-success text-white complete" rel=' . $item->id . ' >' . __("Complete") . '</a>';
            $reject = '<a href="" class="mr-2 btn btn-danger text-white reject" rel=' . $item->id . ' >' . __("Reject") . '</a>';
            $action = '';

            if ($item->status == Constants::statusWithdrawalPending) {
                $status = '<span class="badge bg-warning text-white"rel="' . $item->id . '">' . __('Pending') . '</span>';
                $action =  $complete . $reject;
            }
            if ($item->status == Constants::statusWithdrawalCompleted) {
                $status = '<span class="badge bg-success text-white"rel="' . $item->id . '">' . __('Completed') . '</span>';
            }
            if ($item->status == Constants::statusWithdrawalRejected) {
                $status = '<span class="badge bg-danger text-white"rel="' . $item->id . '">' . __('Rejected') . '</span>';
            }

            // Amount & Status
            $amount = '<span class="text-dark font-weight-bold font-16">' . $settings->currency . $item->amount . '</span><br>';
            $amountData = $amount . $status;

            $data[] = array(
                $item->request_number,
                $bankDetails,
                $amountData,
                GlobalFunction::formateTimeString($item->created_at),
                $item->summary,
                $action
            );
        }
        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => $totalFiltered,
            "data"            => $data
        );
        echo json_encode($json_data);
        exit();
    }

    function fetchDoctorWalletStatement(Request $request)
    {
        $totalData =  DoctorWalletStatements::where('doctor_id', $request->doctorId)->count();
        $rows = DoctorWalletStatements::where('doctor_id', $request->doctorId)->orderBy('id', 'DESC')->get();
        $settings = GlobalSettings::first();

        $result = $rows;

        $columns = array(
            0 => 'id',
            1 => 'fullname',
            2 => 'identity',
            3 => 'username',
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $totalFiltered = $totalData;
        if (empty($request->input('search.value'))) {
            $result = DoctorWalletStatements::where('doctor_id', $request->doctorId)
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
            $result =  DoctorWalletStatements::where('doctor_id', $request->doctorId)
                ->where(function ($query) use ($search) {
                    $query->Where('appointment_number', 'LIKE', "%{$search}%")
                        ->orWhere('transaction_id', 'LIKE', "%{$search}%")
                        ->orWhere('summary', 'LIKE', "%{$search}%")
                        ->orWhere('created_at', 'LIKE', "%{$search}%")
                        ->orWhere('amount', 'LIKE', "%{$search}%");
                })
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = DoctorWalletStatements::where('doctor_id', $request->doctorId)
                ->where(function ($query) use ($search) {
                    $query->Where('appointment_number', 'LIKE', "%{$search}%")
                        ->orWhere('transaction_id', 'LIKE', "%{$search}%")
                        ->orWhere('summary', 'LIKE', "%{$search}%")
                        ->orWhere('created_at', 'LIKE', "%{$search}%")
                        ->orWhere('amount', 'LIKE', "%{$search}%");
                })
                ->count();
        }
        $data = array();
        foreach ($result as $item) {

            $cr_dr = $item->cr_or_dr;
            $icon = '';
            $textClass = '';
            $crDrBadge = '';

            if ($cr_dr == Constants::credit) {
                $icon =  '<i class="fas fa-plus-circle m-1 ic-credit"></i>';
                $textClass = 'text-credit';
                $crDrBadge = '<span  class="badge bg-success text-white ">' . __("Credit") . '</span>';
            } else {
                $icon =  '<i class="fas fa-minus-circle m-1 ic-debit"></i>';
                $textClass = 'text-debit';
                $crDrBadge = '<span  class="badge bg-danger text-white ">' . __("Debit") . '</span>';
            }
            $transaction = $icon . '<span class=' . $textClass . '>' . $item->transaction_id . '</span>';




            $data[] = array(
                $transaction,
                $item->summary,
                $settings->currency . $item->amount,
                $crDrBadge,
                GlobalFunction::formateTimeString($item->created_at),
            );
        }
        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => $totalFiltered,
            "data"            => $data
        );
        echo json_encode($json_data);
        exit();
    }
    function fetchDoctorReviewsList(Request $request)
    {
        $totalData =  DoctorReviews::where('doctor_id', $request->doctorId)->with(['doctor', 'appointment'])->count();
        $rows = DoctorReviews::where('doctor_id', $request->doctorId)->with(['doctor', 'appointment'])->orderBy('id', 'DESC')->get();

        $result = $rows;

        $columns = array(
            0 => 'id',
            1 => 'fullname',
            2 => 'identity',
            3 => 'username',
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $totalFiltered = $totalData;
        if (empty($request->input('search.value'))) {
            $result = DoctorReviews::where('doctor_id', $request->doctorId)->with(['doctor', 'appointment'])

                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
            $result =  DoctorReviews::where('doctor_id', $request->doctorId)->with(['doctor', 'appointment'])
                ->whereHas('appointment', function ($q) use ($search) {
                    $q->where('appointment_number', 'LIKE', "%{$search}%");
                })
                ->orWhere('comment', 'LIKE', "%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = DoctorReviews::where('doctor_id', $request->doctorId)->with(['doctor', 'appointment'])
                ->whereHas('appointment', function ($q) use ($search) {
                    $q->where('appointment_number', 'LIKE', "%{$search}%");
                })
                ->orWhere('comment', 'LIKE', "%{$search}%")
                ->count();
        }
        $data = array();
        foreach ($result as $item) {
            $delete = '<a href="" class="mr-2 btn btn-danger text-white delete" rel=' . $item->id . ' >' . __("Delete") . '</a>';

            $starDisabled = '<i class="fas fa-star starDisabled"></i>';
            $starActive = '<i class="fas fa-star starActive"></i>';

            $ratingBar = '';
            for ($i = 0; $i < 5; $i++) {
                if ($item->rating > $i) {
                    $ratingBar = $ratingBar . $starActive;
                } else {
                    $ratingBar = $ratingBar . $starDisabled;
                }
            }

            $doctor = '<a href="' . route('viewDoctorProfile', $item->doctor->id) . '"><span class="badge bg-primary text-white">
                        ' . $item->doctor->name . '</span></a>';

            $action = $delete;
            $data[] = array(
                $ratingBar,
                $item->comment,
                $item->appointment != null ? $item->appointment->appointment_number : '',
                $doctor,
                $action,
            );
        }
        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => $totalFiltered,
            "data"            => $data
        );
        echo json_encode($json_data);
        exit();
    }

    function fetchDoctorAppointmentsList(Request $request)
    {
        $totalData =  Appointments::where('doctor_id', $request->doctorId)->count();
        $rows = Appointments::where('doctor_id', $request->doctorId)->orderBy('id', 'DESC')->get();
        $settings = GlobalSettings::first();

        $result = $rows;

        $columns = array(
            0 => 'id',
            1 => 'fullname',
            2 => 'identity',
            3 => 'username',
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $totalFiltered = $totalData;
        if (empty($request->input('search.value'))) {
            $result = Appointments::where('doctor_id', $request->doctorId)->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
            $result =  Appointments::where('doctor_id', $request->doctorId)->where(function ($query) use ($search) {
                $query->Where('appointment_number', 'LIKE', "%{$search}%")
                    ->orWhere('payable_amount', 'LIKE', "%{$search}%");
            })->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = Appointments::where('doctor_id', $request->doctorId)->where(function ($query) use ($search) {
                $query->Where('appointment_number', 'LIKE', "%{$search}%")
                    ->orWhere('payable_amount', 'LIKE', "%{$search}%");
            })->count();
        }
        $data = array();
        foreach ($result as $item) {

            $user = "";
            if ($item->user != null) {
                $user = '<a href="' . route('viewUserProfile', $item->user->id) . '"><span class="badge bg-primary text-white">' . $item->user->fullname . '</span></a>';
            }

            $view = '<a href="' . route('viewAppointment', $item->id) . '" class="mr-2 btn btn-info text-white " rel=' . $item->id . ' >' . __("View") . '</a>';

            $status = GlobalFunction::returnAppointmentStatus($item->status);

            $action = $view;

            $dateTime =  $item->date . '<br>' . GlobalFunction::formateTimeString($item->time);
            $payableAmount = $settings->currency . $item->payable_amount;

            $data[] = array(
                $item->appointment_number,
                $user,
                $status,
                $dateTime,
                $settings->currency . $item->service_amount,
                $settings->currency . $item->discount_amount,
                $settings->currency . $item->subtotal,
                $settings->currency . $item->total_tax_amount,
                $payableAmount,
                GlobalFunction::formateTimeString($item->created_at),
                $action,
            );
        }
        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => $totalFiltered,
            "data"            => $data
        );
        echo json_encode($json_data);
        exit();
    }
    function updateDoctorDetails_Admin(Request $request)
    {
        $item = Doctors::find($request->id);
        $item->designation = $request->designation;
        $item->languages_spoken = $request->languages_spoken;
        $item->consultation_fee = $request->consultation_fee;
        $item->experience_year = $request->experience_year;

        $item->degrees = $request->degrees;
        $item->about_youself = $request->about_youself;
        $item->educational_journey = $request->educational_journey;
        $item->save();

        return Globalfunction::sendSimpleResponse(true, 'Details Updated successfully');
    }
    function banDoctor($id)
    {
        $item = Doctors::find($id);
        $item->status = Constants::statusDoctorBanned;
        $item->save();
        return GlobalFunction::sendSimpleResponse(true, 'Doctor banned successfully!');
    }
    function activateDoctor($id)
    {
        $item = Doctors::find($id);
        $item->status = Constants::statusDoctorApproved;
        $item->save();
        return GlobalFunction::sendSimpleResponse(true, 'Doctor activated successfully!');
    }

    function viewDoctorProfile($doctorId)
    {
        $doctor = Doctors::with(['bankAccount', 'category'])->find($doctorId);
        $settings = GlobalSettings::first();

        $slots = DoctorAppointmentSlots::where('doctor_id', $doctorId)->get();
        foreach ($slots as $slot) {
            $slot->time = GlobalFunction::formateTimeString($slot->time);
        }

        $doctor->visible_mobile_number = GlobalFunction::decodeDoctorsMobileNumber($doctor);

        $mondaySlots = array_filter($slots->toArray(), function ($slot) {
            return $slot['weekday'] === 1;
        });
        $tuesdaySlots = array_filter($slots->toArray(), function ($slot) {
            return $slot['weekday'] === 2;
        });
        $wednesdaySlots = array_filter($slots->toArray(), function ($slot) {
            return $slot['weekday'] === 3;
        });
        $thursdaySlots = array_filter($slots->toArray(), function ($slot) {
            return $slot['weekday'] === 4;
        });
        $fridaySlots = array_filter($slots->toArray(), function ($slot) {
            return $slot['weekday'] === 5;
        });
        $saturdaySlots = array_filter($slots->toArray(), function ($slot) {
            return $slot['weekday'] === 6;
        });
        $sundaySlots = array_filter($slots->toArray(), function ($slot) {
            return $slot['weekday'] === 7;
        });

        return view('viewDoctorProfile', [
            'doctor' => $doctor,
            'settings' => $settings,
            'doctorStatus' => array(
                'statusDoctorPending' => Constants::statusDoctorPending,
                'statusDoctorApproved' => Constants::statusDoctorApproved,
                'statusDoctorBanned' => Constants::statusDoctorBanned,
            ),
            'slots' => array(
                'mondaySlots' => $mondaySlots,
                'tuesdaySlots' => $tuesdaySlots,
                'wednesdaySlots' => $wednesdaySlots,
                'thursdaySlots' => $thursdaySlots,
                'fridaySlots' => $fridaySlots,
                'saturdaySlots' => $saturdaySlots,
                'sundaySlots' => $sundaySlots,
            )
        ]);
    }

    function rejectDoctorWithdrawal(Request $request)
    {
        $item = DoctorPayoutHistory::find($request->id);
        if ($request->has('summary')) {
            $item->summary = $request->summary;
        }
        $item->status = Constants::statusWithdrawalRejected;
        $item->save();

        $summary = '(Rejected) Withdraw request :' . $item->request_number;
        // Adding wallet statement
        GlobalFunction::addDoctorStatementEntry(
            $item->doctor->id,
            null,
            $item->amount,
            Constants::credit,
            Constants::doctorWalletPayoutReject,
            $summary
        );

        //adding money to user wallet
        $item->doctor->wallet = $item->doctor->wallet + $item->amount;
        $item->doctor->save();

        return GlobalFunction::sendSimpleResponse(true, 'request rejected successfully');
    }

    function completeDoctorWithdrawal(Request $request)
    {
        $item = DoctorPayoutHistory::find($request->id);
        if ($request->has('summary')) {
            $item->summary = $request->summary;
        }
        $item->status = Constants::statusWithdrawalCompleted;
        $item->save();

        return GlobalFunction::sendSimpleResponse(true, 'request completed successfully');
    }
    function fetchDoctorRejectedWithdrawalsList(Request $request)
    {
        $totalData =  DoctorPayoutHistory::where('status', Constants::statusWithdrawalRejected)->with('doctor')->count();
        $rows = DoctorPayoutHistory::where('status', Constants::statusWithdrawalRejected)->with('doctor')->orderBy('id', 'DESC')->get();
        $settings = GlobalSettings::first();
        $result = $rows;

        $columns = array(
            0 => 'id',
            1 => 'fullname',
            2 => 'identity',
            3 => 'username',
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $totalFiltered = $totalData;
        if (empty($request->input('search.value'))) {
            $result = DoctorPayoutHistory::where('status', Constants::statusWithdrawalRejected)
                ->with('doctor')
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
            $result =  DoctorPayoutHistory::where('status', Constants::statusWithdrawalRejected)
                ->where(function ($query) use ($search) {
                    $query->where('request_number', 'LIKE', "%{$search}%")
                        ->orWhere('amount', 'LIKE', "%{$search}%")
                        ->orWhere('summary', 'LIKE', "%{$search}%")
                        ->orWhereHas('doctor', function ($query) use ($search) {
                            $query->Where('name', 'LIKE', "%{$search}%");
                        });
                })
                ->with('doctor')
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = DoctorPayoutHistory::where('status', Constants::statusWithdrawalRejected)
                ->with('doctor')
                ->where(function ($query) use ($search) {
                    $query->where('request_number', 'LIKE', "%{$search}%")
                        ->orWhere('amount', 'LIKE', "%{$search}%")
                        ->orWhere('summary', 'LIKE', "%{$search}%")
                        ->orWhereHas('doctor', function ($query) use ($search) {
                            $query->Where('name', 'LIKE', "%{$search}%");
                        });
                })
                ->count();
        }
        $data = array();
        foreach ($result as $item) {

            $bankAccount = $item->doctor->bankAccount;

            $bankDetails = "";

            if ($bankAccount != null) {
                $holder = '<span class="text-dark font-weight-bold font-14">' . $bankAccount->holder . '</span>';
                $bank_title = '<div class="bank-details"><span>' . $bankAccount->bank_title . '</span>';
                $account_number = '<span>' . __('Account : ') .  $bankAccount->account_number . '</span>';
                $swift_code = '<span>' . __('Swift Code : ') . $bankAccount->swift_code . '</span></div>';
                $bankDetails = $holder . $bank_title . $account_number . $swift_code;
            }

            // Amount & Status
            $amount = '<span class="text-dark font-weight-bold font-16">' . $settings->currency . $item->amount . '</span><br>';
            $status = '<span class="badge bg-danger text-white"rel="' . $item->id . '">' . __('Rejected') . '</span>';
            $amountData = $amount . $status;

            $doctor = "";
            if ($item->doctor != null) {
                $doctor = '<a href="' . route('viewDoctorProfile', $item->doctor->id) . '"><span class="badge bg-primary text-white">' . $item->doctor->name . '</span></a>';
            }



            $data[] = array(
                $item->request_number,
                $bankDetails,
                $amountData,
                $doctor,
                $item->summary
            );
        }
        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => $totalFiltered,
            "data"            => $data
        );
        echo json_encode($json_data);
        exit();
    }
    function fetchDoctorCompletedWithdrawalsList(Request $request)
    {
        $totalData =  DoctorPayoutHistory::where('status', Constants::statusWithdrawalCompleted)->with('doctor')->count();
        $rows = DoctorPayoutHistory::where('status', Constants::statusWithdrawalCompleted)->with('doctor')->orderBy('id', 'DESC')->get();
        $settings = GlobalSettings::first();
        $result = $rows;

        $columns = array(
            0 => 'id',
            1 => 'fullname',
            2 => 'identity',
            3 => 'username',
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $totalFiltered = $totalData;
        if (empty($request->input('search.value'))) {
            $result = DoctorPayoutHistory::where('status', Constants::statusWithdrawalCompleted)
                ->with('doctor')
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
            $result =  DoctorPayoutHistory::where('status', Constants::statusWithdrawalCompleted)
                ->where(function ($query) use ($search) {
                    $query->where('request_number', 'LIKE', "%{$search}%")
                        ->orWhere('amount', 'LIKE', "%{$search}%")
                        ->orWhere('summary', 'LIKE', "%{$search}%")
                        ->orWhereHas('doctor', function ($query) use ($search) {
                            $query->Where('name', 'LIKE', "%{$search}%");
                        });
                })
                ->with('doctor')
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = DoctorPayoutHistory::where('status', Constants::statusWithdrawalCompleted)
                ->with('doctor')
                ->where(function ($query) use ($search) {
                    $query->where('request_number', 'LIKE', "%{$search}%")
                        ->orWhere('amount', 'LIKE', "%{$search}%")
                        ->orWhere('summary', 'LIKE', "%{$search}%")
                        ->orWhereHas('doctor', function ($query) use ($search) {
                            $query->Where('name', 'LIKE', "%{$search}%");
                        });
                })
                ->count();
        }
        $data = array();
        foreach ($result as $item) {

            $bankAccount = $item->doctor->bankAccount;

            $bankDetails = "";

            if ($bankAccount != null) {
                $holder = '<span class="text-dark font-weight-bold font-14">' . $bankAccount->holder . '</span>';
                $bank_title = '<div class="bank-details"><span>' . $bankAccount->bank_title . '</span>';
                $account_number = '<span>' . __('Account : ') .  $bankAccount->account_number . '</span>';
                $swift_code = '<span>' . __('Swift Code : ') . $bankAccount->swift_code . '</span></div>';
                $bankDetails = $holder . $bank_title . $account_number . $swift_code;
            }

            // Amount & Status
            $amount = '<span class="text-dark font-weight-bold font-16">' . $settings->currency . $item->amount . '</span><br>';
            $status = '<span class="badge bg-success text-white"rel="' . $item->id . '">' . __('Completed') . '</span>';
            $amountData = $amount . $status;

            $doctor = "";
            if ($item->doctor != null) {
                $doctor = '<a href="' . route('viewDoctorProfile', $item->doctor->id) . '"><span class="badge bg-primary text-white">' . $item->doctor->name . '</span></a>';
            }

            $data[] = array(
                $item->request_number,
                $bankDetails,
                $amountData,
                $doctor,
                $item->summary
            );
        }
        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => $totalFiltered,
            "data"            => $data
        );
        echo json_encode($json_data);
        exit();
    }
    function fetchDoctorPendingWithdrawalsList(Request $request)
    {
        $totalData =  DoctorPayoutHistory::with('doctor')->count();
        $rows = DoctorPayoutHistory::where('status', Constants::statusWithdrawalPending)->with('doctor')->orderBy('id', 'DESC')->get();
        $settings = GlobalSettings::first();
        $result = $rows;

        $columns = array(
            0 => 'id',
            1 => 'fullname',
            2 => 'identity',
            3 => 'username',
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $totalFiltered = $totalData;
        if (empty($request->input('search.value'))) {
            $result = DoctorPayoutHistory::where('status', Constants::statusWithdrawalPending)
                ->with('doctor')
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
            $result =  DoctorPayoutHistory::where('status', Constants::statusWithdrawalPending)
                ->where(function ($query) use ($search) {
                    $query->where('request_number', 'LIKE', "%{$search}%")
                        ->orWhere('amount', 'LIKE', "%{$search}%")
                        ->orWhere('summary', 'LIKE', "%{$search}%")
                        ->orWhereHas('doctor', function ($query) use ($search) {
                            $query->Where('name', 'LIKE', "%{$search}%");
                        });
                })
                ->with('doctor')
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = DoctorPayoutHistory::where('status', Constants::statusWithdrawalPending)
                ->with('doctor')
                ->where(function ($query) use ($search) {
                    $query->where('request_number', 'LIKE', "%{$search}%")
                        ->orWhere('amount', 'LIKE', "%{$search}%")
                        ->orWhere('summary', 'LIKE', "%{$search}%")
                        ->orWhereHas('doctor', function ($query) use ($search) {
                            $query->Where('name', 'LIKE', "%{$search}%");
                        });
                })
                ->count();
        }
        $data = array();
        foreach ($result as $item) {

            $bankAccount = $item->doctor->bankAccount;

            $bankDetails = "";

            if ($bankAccount != null) {
                $holder = '<span class="text-dark font-weight-bold font-14">' . $bankAccount->holder . '</span>';
                $bank_title = '<div class="bank-details"><span>' . $bankAccount->bank_title . '</span>';
                $account_number = '<span>' . __('Account : ') .  $bankAccount->account_number . '</span>';
                $swift_code = '<span>' . __('Swift Code : ') . $bankAccount->swift_code . '</span></div>';
                $bankDetails = $holder . $bank_title . $account_number . $swift_code;
            }

            // Amount & Status
            $amount = '<span class="text-dark font-weight-bold font-16">' . $settings->currency . $item->amount . '</span><br>';
            $status = '<span class="badge bg-warning text-white"rel="' . $item->id . '">' . __('Pending') . '</span>';
            $amountData = $amount . $status;

            $complete = '<a href="" class="mr-2 btn btn-success text-white complete" rel=' . $item->id . ' >' . __("Complete") . '</a>';
            $reject = '<a href="" class="mr-2 btn btn-danger text-white reject" rel=' . $item->id . ' >' . __("Reject") . '</a>';
            // $delete = '<a href="" class="mr-2 btn btn-danger text-white delete" rel=' . $item->id . ' >' . __("Delete") . '</a>';
            $action =  $complete . $reject;

            $doctor = "";
            if ($item->doctor != null) {
                $doctor = '<a href="' . route('viewDoctorProfile', $item->doctor->id) . '"><span class="badge bg-primary text-white">' . $item->doctor->name . '</span></a>';
            }


            $data[] = array(
                $item->request_number,
                $bankDetails,
                $amountData,
                $doctor,
                $item->created_at->format('d M, Y'),
                $action
            );
        }
        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => $totalFiltered,
            "data"            => $data
        );
        echo json_encode($json_data);
        exit();
    }

    function doctorWithdraws()
    {
        return view('doctorWithdraws');
    }
    function doctors()
    {
        return view('doctors');
    }

    function fetchBannedDoctorsList(Request $request)
    {
        $totalData =  Doctors::where('status', Constants::statusDoctorBanned)->count();
        $rows = Doctors::where('status', Constants::statusDoctorBanned)->orderBy('id', 'DESC')->get();
        $settings = GlobalSettings::first();

        $result = $rows;

        $columns = array(
            0 => 'id',
            1 => 'fullname',
            2 => 'identity',
            3 => 'username',
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $totalFiltered = $totalData;
        if (empty($request->input('search.value'))) {
            $result = Doctors::where('status', Constants::statusDoctorBanned)->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
            $result =  Doctors::where('status', Constants::statusDoctorBanned)->where(function ($query) use ($search) {
                $query->Where('name', 'LIKE', "%{$search}%")
                    ->orWhere('mobile_number', 'LIKE', "%{$search}%")
                    ->orWhere('doctor_number', 'LIKE', "%{$search}%");
            })->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = Doctors::where('status', Constants::statusDoctorBanned)->where(function ($query) use ($search) {
                $query->Where('name', 'LIKE', "%{$search}%")
                    ->orWhere('mobile_number', 'LIKE', "%{$search}%")
                    ->orWhere('doctor_number', 'LIKE', "%{$search}%");
            })->count();
        }
        $data = array();
        foreach ($result as $item) {

            if ($item->image == null) {
                $image = '<img src="http://placehold.jp/150x150.png" width="50" height="50">';
            } else {
                $imgUrl = GlobalFunction::createMediaUrl($item->image);
                $image = '<img src="' . $imgUrl . '" width="50" height="50">';
            }

            $view = '<a href="' . route('viewDoctorProfile', $item->id) . '" class="mr-2 btn btn-info text-white " rel=' . $item->id . ' >' . __("View") . '</a>';

            $status = "";
            if ($item->status == Constants::statusDoctorPending) {
                $status = '<span  class="badge bg-warning text-white ">' . __("Pending") . '</span>';
            }
            if ($item->status == Constants::statusDoctorApproved) {
                $status = '<span  class="badge bg-success text-white ">' . __("Approved") . '</span>';
            }
            if ($item->status == Constants::statusDoctorBanned) {
                $status = '<span  class="badge bg-danger text-white ">' . __("Banned") . '</span>';
            }

            $gender = '';
            if ($item->gender == Constants::genderMale) {
                $gender = '<span  class="badge bg-primary text-white ">' . __("Male") . '</span>';
            }
            if ($item->gender == Constants::genderFemale) {
                $gender = '<span  class="badge bg-info text-white ">' . __("Female") . '</span>';
            }


            $action = $view;

            $category = $item->category == null ? '' : $item->category->title;

            $data[] = array(
                $image,
                $item->name,
                $item->doctor_number,
                $status,
                $gender,
                $category,
                $item->experience_year,
                $item->total_patients_cured,
                $settings->currency . $item->lifetime_earnings,
                GlobalFunction::decodeDoctorsMobileNumber($item),
                $action,
            );
        }
        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => $totalFiltered,
            "data"            => $data
        );
        echo json_encode($json_data);
        exit();
    }
    function fetchPendingDoctorsList(Request $request)
    {
        $totalData =  Doctors::where('status', Constants::statusDoctorPending)->count();
        $rows = Doctors::where('status', Constants::statusDoctorPending)->orderBy('id', 'DESC')->get();
        $settings = GlobalSettings::first();

        $result = $rows;

        $columns = array(
            0 => 'id',
            1 => 'fullname',
            2 => 'identity',
            3 => 'username',
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $totalFiltered = $totalData;
        if (empty($request->input('search.value'))) {
            $result = Doctors::where('status', Constants::statusDoctorPending)->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
            $result =  Doctors::where('status', Constants::statusDoctorPending)->where(function ($query) use ($search) {
                $query->Where('name', 'LIKE', "%{$search}%")
                    ->orWhere('mobile_number', 'LIKE', "%{$search}%")
                    ->orWhere('doctor_number', 'LIKE', "%{$search}%");
            })->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = Doctors::where('status', Constants::statusDoctorPending)->where(function ($query) use ($search) {
                $query->Where('name', 'LIKE', "%{$search}%")
                    ->orWhere('mobile_number', 'LIKE', "%{$search}%")
                    ->orWhere('doctor_number', 'LIKE', "%{$search}%");
            })->count();
        }
        $data = array();
        foreach ($result as $item) {

            if ($item->image == null) {
                $image = '<img src="http://placehold.jp/150x150.png" width="50" height="50">';
            } else {
                $imgUrl = GlobalFunction::createMediaUrl($item->image);
                $image = '<img src="' . $imgUrl . '" width="50" height="50">';
            }

            $view = '<a href="' . route('viewDoctorProfile', $item->id) . '" class="mr-2 btn btn-info text-white " rel=' . $item->id . ' >' . __("View") . '</a>';

            $status = "";
            if ($item->status == Constants::statusDoctorPending) {
                $status = '<span  class="badge bg-warning text-white ">' . __("Pending") . '</span>';
            }
            if ($item->status == Constants::statusDoctorApproved) {
                $status = '<span  class="badge bg-success text-white ">' . __("Approved") . '</span>';
            }
            if ($item->status == Constants::statusDoctorBanned) {
                $status = '<span  class="badge bg-danger text-white ">' . __("Banned") . '</span>';
            }

            $gender = '';
            if ($item->gender == Constants::genderMale) {
                $gender = '<span  class="badge bg-primary text-white ">' . __("Male") . '</span>';
            }
            if ($item->gender == Constants::genderFemale) {
                $gender = '<span  class="badge bg-info text-white ">' . __("Female") . '</span>';
            }


            $action = $view;

            $category = $item->category == null ? '' : $item->category->title;

            $data[] = array(
                $image,
                $item->name,
                $item->doctor_number,
                $status,
                $gender,
                $category,
                $item->experience_year,
                $item->total_patients_cured,
                $settings->currency . $item->lifetime_earnings,
                GlobalFunction::decodeDoctorsMobileNumber($item),
                $action,
            );
        }
        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => $totalFiltered,
            "data"            => $data
        );
        echo json_encode($json_data);
        exit();
    }
    function fetchApprovedDoctorsList(Request $request)
    {
        $totalData =  Doctors::where('status', Constants::statusDoctorApproved)->count();
        $rows = Doctors::where('status', Constants::statusDoctorApproved)->orderBy('id', 'DESC')->get();
        $settings = GlobalSettings::first();

        $result = $rows;

        $columns = array(
            0 => 'id',
            1 => 'fullname',
            2 => 'identity',
            3 => 'username',
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $totalFiltered = $totalData;
        if (empty($request->input('search.value'))) {
            $result = Doctors::where('status', Constants::statusDoctorApproved)->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
            $result =  Doctors::where('status', Constants::statusDoctorApproved)->where(function ($query) use ($search) {
                $query->Where('name', 'LIKE', "%{$search}%")
                    ->orWhere('mobile_number', 'LIKE', "%{$search}%")
                    ->orWhere('doctor_number', 'LIKE', "%{$search}%");
            })->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = Doctors::where('status', Constants::statusDoctorApproved)->where(function ($query) use ($search) {
                $query->Where('name', 'LIKE', "%{$search}%")
                    ->orWhere('mobile_number', 'LIKE', "%{$search}%")
                    ->orWhere('doctor_number', 'LIKE', "%{$search}%");
            })->count();
        }
        $data = array();
        foreach ($result as $item) {

            if ($item->image == null) {
                $image = '<img src="http://placehold.jp/150x150.png" width="50" height="50">';
            } else {
                $imgUrl = GlobalFunction::createMediaUrl($item->image);
                $image = '<img src="' . $imgUrl . '" width="50" height="50">';
            }

            $view = '<a href="' . route('viewDoctorProfile', $item->id) . '" class="mr-2 btn btn-info text-white " rel=' . $item->id . ' >' . __("View") . '</a>';

            $status = "";
            if ($item->status == Constants::statusDoctorPending) {
                $status = '<span  class="badge bg-warning text-white ">' . __("Pending") . '</span>';
            }
            if ($item->status == Constants::statusDoctorApproved) {
                $status = '<span  class="badge bg-success text-white ">' . __("Approved") . '</span>';
            }
            if ($item->status == Constants::statusDoctorBanned) {
                $status = '<span  class="badge bg-danger text-white ">' . __("Banned") . '</span>';
            }

            $gender = '';
            if ($item->gender == Constants::genderMale) {
                $gender = '<span  class="badge bg-primary text-white ">' . __("Male") . '</span>';
            }
            if ($item->gender == Constants::genderFemale) {
                $gender = '<span  class="badge bg-info text-white ">' . __("Female") . '</span>';
            }


            $action = $view;

            $category = $item->category == null ? '' : $item->category->title;

            $data[] = array(
                $image,
                $item->name,
                $item->doctor_number,
                $status,
                $gender,
                $category,
                $item->experience_year,
                $item->total_patients_cured,
                $settings->currency . $item->lifetime_earnings,
                GlobalFunction::decodeDoctorsMobileNumber($item),
                $action,
            );
        }
        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => $totalFiltered,
            "data"            => $data
        );
        echo json_encode($json_data);
        exit();
    }
    function fetchAllDoctorsList(Request $request)
    {
        $totalData =  Doctors::count();
        $rows = Doctors::orderBy('id', 'DESC')->get();
        $settings = GlobalSettings::first();

        $result = $rows;

        $columns = array(
            0 => 'id',
            1 => 'fullname',
            2 => 'identity',
            3 => 'username',
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $totalFiltered = $totalData;
        if (empty($request->input('search.value'))) {
            $result = Doctors::offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
            $result =  Doctors::where(function ($query) use ($search) {
                $query->Where('name', 'LIKE', "%{$search}%")
                    ->orWhere('mobile_number', 'LIKE', "%{$search}%")
                    ->orWhere('doctor_number', 'LIKE', "%{$search}%");
            })->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = Doctors::where(function ($query) use ($search) {
                $query->Where('name', 'LIKE', "%{$search}%")
                    ->orWhere('mobile_number', 'LIKE', "%{$search}%")
                    ->orWhere('doctor_number', 'LIKE', "%{$search}%");
            })->count();
        }
        $data = array();
        foreach ($result as $item) {

            if ($item->image == null) {
                $image = '<img src="http://placehold.jp/150x150.png" width="50" height="50">';
            } else {
                $imgUrl = GlobalFunction::createMediaUrl($item->image);
                $image = '<img src="' . $imgUrl . '" width="50" height="50">';
            }

            $view = '<a href="' . route('viewDoctorProfile', $item->id) . '" class="mr-2 btn btn-info text-white " rel=' . $item->id . ' >' . __("View") . '</a>';

            $status = "";
            if ($item->status == Constants::statusDoctorPending) {
                $status = '<span  class="badge bg-warning text-white ">' . __("Pending") . '</span>';
            }
            if ($item->status == Constants::statusDoctorApproved) {
                $status = '<span  class="badge bg-success text-white ">' . __("Approved") . '</span>';
            }
            if ($item->status == Constants::statusDoctorBanned) {
                $status = '<span  class="badge bg-danger text-white ">' . __("Banned") . '</span>';
            }

            $gender = '';
            if ($item->gender == Constants::genderMale) {
                $gender = '<span  class="badge bg-primary text-white ">' . __("Male") . '</span>';
            }
            if ($item->gender == Constants::genderFemale) {
                $gender = '<span  class="badge bg-info text-white ">' . __("Female") . '</span>';
            }


            $action = $view;

            $category = $item->category == null ? '' : $item->category->title;

            $data[] = array(
                $image,
                $item->name,
                $item->doctor_number,
                $status,
                $gender,
                $category,
                $item->experience_year,
                $item->total_patients_cured,
                $settings->currency . $item->lifetime_earnings,
                GlobalFunction::decodeDoctorsMobileNumber($item),
                $action,
            );
        }
        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => $totalFiltered,
            "data"            => $data
        );
        echo json_encode($json_data);
        exit();
    }

    function checkMobileNumberExists(Request $request)
    {
        $rules = [
            'mobile_number' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }

        $doctor = Doctors::where('mobile_number', $request->mobile_number)->first();

        if ($doctor == null) {
            return GlobalFunction::sendSimpleResponse(true, 'number available to use');
        } else {
            return GlobalFunction::sendSimpleResponse(false, 'mobile number in use already!');
        }
    }

    function fetchDoctorReviews(Request $request)
    {
        $rules = [
            'start' => 'required',
            'count' => 'required',
            'doctor_id' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }
        $doctor = Doctors::where('id', $request->doctor_id)->first();
        if ($doctor == null) {
            return GlobalFunction::sendSimpleResponse(false, 'Doctor does not exists!');
        }
        $result =  DoctorReviews::with(['user'])
            ->Where('doctor_id', $request->doctor_id)
            ->whereHas('user')
            ->whereHas('doctor')
            ->orderBy('id', 'DESC')
            ->offset($request->start)
            ->limit($request->count)
            ->get();

        return GlobalFunction::sendDataResponse(true, 'data fetched successfully', $result);
    }


    function fetchDoctorProfile(Request $request)
    {
        $rules = [
            'doctor_id' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }
        $doctor = Doctors::where('id', $request->doctor_id)->first();
        if ($doctor == null) {
            return GlobalFunction::sendSimpleResponse(false, 'Doctor does not exists!');
        }

        $doctor = GlobalFunction::generateDoctorFullData($doctor->id);

        return GlobalFunction::sendDataResponse(true, 'data fetched successfully', $doctor);
    }
    function searchDoctor(Request $request)
    {
        $rules = [
            'start' => 'required',
            'count' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }
        $query = Doctors::query();

        if ($request->has('gender')) {
            $query->where('gender', $request->gender);
        }
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->has('sort_type')) {
            if ($request->sort_type == Constants::sortTypePriceLow) {
                $query->orderBy('consultation_fee', 'ASC');
            }
            if ($request->sort_type == Constants::sortTypePriceHigh) {
                $query->orderBy('consultation_fee', 'DESC');
            }
            if ($request->sort_type == Constants::sortTypeRating) {
                $query->orderBy('rating', 'DESC');
            }
        }

        $doctors = $query
            ->where('name', 'LIKE', "%{$request->keyword}%")
            ->where('status', Constants::statusDoctorApproved)
            ->where('on_vacation', Constants::doctorNotOnVacation)
            ->offset($request->start)
            ->limit($request->count)
            ->get();

        return GlobalFunction::sendDataResponse(true, 'Data fetched successfully', $doctors);
    }
    function manageDrBankAccount(Request $request)
    {
        $rules = [
            'doctor_id' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }
        $doctor = Doctors::where('id', $request->doctor_id)->first();
        if ($doctor == null) {
            return GlobalFunction::sendSimpleResponse(false, 'Doctor does not exists!');
        }
        $bankAcc = $doctor->bankAccount;
        if ($bankAcc == null) {
            $rules = [
                'bank_name' => 'required',
                'account_number' => 'required',
                'holder' => 'required',
                'swift_code' => 'required',
                'cheque_photo' => 'required',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $messages = $validator->errors()->all();
                $msg = $messages[0];
                return response()->json(['status' => false, 'message' => $msg]);
            }
            $bankAcc = new DoctorBankAccount();
            $bankAcc->bank_name = GlobalFunction::cleanString($request->bank_name);
            $bankAcc->account_number = GlobalFunction::cleanString($request->account_number);
            $bankAcc->holder = GlobalFunction::cleanString($request->holder);
            $bankAcc->swift_code = GlobalFunction::cleanString($request->swift_code);
            $bankAcc->doctor_id = $request->doctor_id;
            $bankAcc->cheque_photo = GlobalFunction::saveFileAndGivePath($request->cheque_photo);
            $bankAcc->save();
        } else {
            if ($request->has('bank_name')) {
                $bankAcc->bank_name =
                    GlobalFunction::cleanString($request->bank_name);
            }
            if ($request->has('account_number')) {
                $bankAcc->account_number =
                    GlobalFunction::cleanString($request->account_number);
            }
            if ($request->has('holder')) {
                $bankAcc->holder =
                    GlobalFunction::cleanString($request->holder);
            }
            if ($request->has('swift_code')) {
                $bankAcc->swift_code =
                    GlobalFunction::cleanString($request->swift_code);
            }
            if ($request->has('cheque_photo')) {
                $bankAcc->cheque_photo = GlobalFunction::saveFileAndGivePath($request->cheque_photo);
            }
            $bankAcc->save();
        }

        $doctor = Globalfunction::generateDoctorFullData($request->doctor_id);

        return GlobalFunction::sendDataResponse(true, 'bank details updated successfully', $doctor);
    }
    function fetchFaqCats(Request $request)
    {
        $faqCats = FaqCats::with('faqs')->get();

        return GlobalFunction::sendDataResponse(true, 'Data fetch successfully', $faqCats);
    }

    function deleteHoliday(Request $request)
    {
        $rules = [
            'holiday_id' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }
        $item = DoctorHolidays::find($request->holiday_id);
        if ($item == null) {
            return GlobalFunction::sendSimpleResponse(false, 'Holiday does not Exists');
        }
        $item->delete();
        return GlobalFunction::sendSimpleResponse(false, 'Holiday deleted successfully!');
    }
    function addHoliday(Request $request)
    {
        $rules = [
            'date' => 'required',
            'doctor_id' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }
        $doctor = Doctors::where('id', $request->doctor_id)->first();
        if ($doctor == null) {
            return GlobalFunction::sendSimpleResponse(false, 'Doctor does not exists!');
        }
        $holiday = DoctorHolidays::where('date', $request->date)
            ->where('doctor_id', $request->doctor_id)
            ->first();
        if ($holiday == null) {
            $holiday = new DoctorHolidays();
            $holiday->date = $request->date;
            $holiday->doctor_id = $request->doctor_id;
            $holiday->save();
            return GlobalFunction::sendSimpleResponse(true, 'Holiday added successfully');
        }
        return GlobalFunction::sendSimpleResponse(false, 'Holiday exists already!');
    }
    function deleteAppointmentSlot(Request $request)
    {
        $rules = [
            'slot_id' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }
        $slot = DoctorAppointmentSlots::find($request->slot_id);
        if ($slot == null) {
            return GlobalFunction::sendSimpleResponse(false, 'Slot does not Exists');
        }
        $slot->delete();
        return GlobalFunction::sendSimpleResponse(true, 'This Slot deleted successfully!');
    }
    function addAppointmentSlots(Request $request)
    {
        $rules = [
            'time' => 'required',
            'weekday' => 'required',
            'doctor_id' => 'required',
            'booking_limit' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }
        $doctor = Doctors::where('id', $request->doctor_id)->first();
        if ($doctor == null) {
            return GlobalFunction::sendSimpleResponse(false, 'Doctor does not exists!');
        }

        $slot = DoctorAppointmentSlots::where('time', $request->time)
            ->where('weekday', $request->weekday)
            ->where('doctor_id', $doctor->id)
            ->first();

        if ($slot == null) {
            $slot = new DoctorAppointmentSlots();
            $slot->time = $request->time;
            $slot->weekday = $request->weekday;
            $slot->doctor_id = $request->doctor_id;
            $slot->booking_limit = $request->booking_limit;
            $slot->save();

            $slots = DoctorAppointmentSlots::where('doctor_id', $request->doctor_id)->get();
            return GlobalFunction::sendDataResponse(true, 'Slot added successfully', $slots);
        } else {
            return GlobalFunction::sendSimpleResponse(false, 'This Slot is available already!');
        }
    }
    function addEditServiceLocations(Request $request)
    {
        $rules = [
            'type' => 'required',
            'doctor_id' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }

        // 1= Add Service
        if ($request->type == 1) {
            $rules = [
                'doctor_id' => 'required',
                'hospital_title' => 'required',
                'hospital_address' => 'required',
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $messages = $validator->errors()->all();
                $msg = $messages[0];
                return response()->json(['status' => false, 'message' => $msg]);
            }

            $doctor = Doctors::where('id', $request->doctor_id)->first();
            if ($doctor == null) {
                return GlobalFunction::sendSimpleResponse(false, 'Doctor does not exists!');
            }

            $item = new DoctorServiceLocations();
            $item->hospital_title = GlobalFunction::cleanString($request->hospital_title);
            $item->hospital_address = $request->hospital_address;
            $item->doctor_id = $doctor->id;

            if ($request->has('hospital_long')) {
                $item->hospital_long = $request->hospital_long;
            }
            if ($request->has('hospital_lat')) {
                $item->hospital_lat = $request->hospital_lat;
            }
            $item->save();

            $doctor = GlobalFunction::generateDoctorFullData($request->doctor_id);

            return response()->json(['status' => true, 'message' => 'Service location added successfully !', 'data' => $doctor]);
        }
        if ($request->type == 2) {
            // 2 = edit
            $rules = [
                'doctor_id' => 'required',
                'serviceLocation_id' => 'required',
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $messages = $validator->errors()->all();
                $msg = $messages[0];
                return response()->json(['status' => false, 'message' => $msg]);
            }

            $doctor = Doctors::where('id', $request->doctor_id)->first();
            if ($doctor == null) {
                return GlobalFunction::sendSimpleResponse(false, 'Doctor does not exists!');
            }
            $item = DoctorServiceLocations::where('id', $request->serviceLocation_id)->first();
            if ($item == null) {
                return GlobalFunction::sendSimpleResponse(false, 'Service location does not exists!');
            }
            if ($request->has('hospital_title')) {
                $item->hospital_title = GlobalFunction::cleanString($request->hospital_title);
            }
            if ($request->has('hospital_address')) {
                $item->hospital_address = $request->hospital_address;
            }
            if ($request->has('hospital_long')) {
                $item->hospital_long = $request->hospital_long;
            }
            if ($request->has('hospital_lat')) {
                $item->hospital_lat = $request->hospital_lat;
            }

            $item->save();

            $doctor = GlobalFunction::generateDoctorFullData($request->doctor_id);

            return response()->json(['status' => true, 'message' => 'Service location edited successfully !', 'data' => $doctor]);
        }

        if ($request->type == 3) {
            $rules = [
                'serviceLocation_id' => 'required',
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $messages = $validator->errors()->all();
                $msg = $messages[0];
                return response()->json(['status' => false, 'message' => $msg]);
            }
            $item = DoctorServiceLocations::where('id', $request->serviceLocation_id)->first();
            if ($item == null) {
                return GlobalFunction::sendSimpleResponse(false, 'service Location does not exists!');
            }
            $item->delete();
            $doctor = GlobalFunction::generateDoctorFullData($request->doctor_id);
            return response()->json(['status' => true, 'message' => 'service Location deleted successfully !', 'data' => $doctor]);
        }
    }
    function addEditExperience(Request $request)
    {
        $rules = [
            'type' => 'required',
            'doctor_id' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }

        // 1= Add Service
        if ($request->type == 1) {
            $rules = [
                'doctor_id' => 'required',
                'title' => 'required'
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $messages = $validator->errors()->all();
                $msg = $messages[0];
                return response()->json(['status' => false, 'message' => $msg]);
            }

            $doctor = Doctors::where('id', $request->doctor_id)->first();
            if ($doctor == null) {
                return GlobalFunction::sendSimpleResponse(false, 'Doctor does not exists!');
            }

            $item = new DoctorExperience();
            $item->title =
                GlobalFunction::cleanString($request->title);
            $item->doctor_id = $doctor->id;
            $item->save();

            $doctor = GlobalFunction::generateDoctorFullData($request->doctor_id);

            return response()->json(['status' => true, 'message' => 'Experience added successfully !', 'data' => $doctor]);
        }
        if ($request->type == 2) {
            // 2 = edit
            $rules = [
                'doctor_id' => 'required',
                'experience_id' => 'required',
                'title' => 'required'
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $messages = $validator->errors()->all();
                $msg = $messages[0];
                return response()->json(['status' => false, 'message' => $msg]);
            }

            $doctor = Doctors::where('id', $request->doctor_id)->first();
            if ($doctor == null) {
                return GlobalFunction::sendSimpleResponse(false, 'Doctor does not exists!');
            }
            $item = DoctorExperience::where('id', $request->experience_id)->first();
            if ($item == null) {
                return GlobalFunction::sendSimpleResponse(false, 'Experience does not exists!');
            }

            $item->title =
                GlobalFunction::cleanString($request->title);
            $item->save();

            $doctor = GlobalFunction::generateDoctorFullData($request->doctor_id);

            return response()->json(['status' => true, 'message' => 'Experience edited successfully !', 'data' => $doctor]);
        }
        if ($request->type == 3) {
            $rules = [
                'experience_id' => 'required',
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $messages = $validator->errors()->all();
                $msg = $messages[0];
                return response()->json(['status' => false, 'message' => $msg]);
            }
            $item = DoctorExperience::where('id', $request->experience_id)->first();
            if ($item == null) {
                return GlobalFunction::sendSimpleResponse(false, 'Experience does not exists!');
            }
            $item->delete();

            $doctor = GlobalFunction::generateDoctorFullData($request->doctor_id);
            return response()->json(['status' => true, 'message' => 'Experience deleted successfully !', 'data' => $doctor]);
        }
    }
    function addEditAwards(Request $request)
    {
        $rules = [
            'type' => 'required',
            'doctor_id' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }

        // 1= Add Service
        if ($request->type == 1) {
            $rules = [
                'doctor_id' => 'required',
                'title' => 'required'
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $messages = $validator->errors()->all();
                $msg = $messages[0];
                return response()->json(['status' => false, 'message' => $msg]);
            }

            $doctor = Doctors::where('id', $request->doctor_id)->first();
            if ($doctor == null) {
                return GlobalFunction::sendSimpleResponse(false, 'Doctor does not exists!');
            }

            $item = new DoctorAwards();
            $item->title = GlobalFunction::cleanString($request->title);
            $item->doctor_id = $doctor->id;
            $item->save();

            $doctor = GlobalFunction::generateDoctorFullData($request->doctor_id);

            return response()->json(['status' => true, 'message' => 'Award added successfully !', 'data' => $doctor]);
        }
        if ($request->type == 2) {
            // 2 = edit
            $rules = [
                'doctor_id' => 'required',
                'award_id' => 'required',
                'title' => 'required'
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $messages = $validator->errors()->all();
                $msg = $messages[0];
                return response()->json(['status' => false, 'message' => $msg]);
            }

            $doctor = Doctors::where('id', $request->doctor_id)->first();
            if ($doctor == null) {
                return GlobalFunction::sendSimpleResponse(false, 'Doctor does not exists!');
            }
            $item = DoctorAwards::where('id', $request->award_id)->first();
            if ($item == null) {
                return GlobalFunction::sendSimpleResponse(false, 'Award does not exists!');
            }

            $item->title =
                GlobalFunction::cleanString($request->title);
            $item->save();

            $doctor = GlobalFunction::generateDoctorFullData($request->doctor_id);

            return response()->json(['status' => true, 'message' => 'Award edited successfully !', 'data' => $doctor]);
        }
        if ($request->type == 3) {
            $rules = [
                'award_id' => 'required',
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $messages = $validator->errors()->all();
                $msg = $messages[0];
                return response()->json(['status' => false, 'message' => $msg]);
            }
            $item = DoctorAwards::where('id', $request->award_id)->first();
            if ($item == null) {
                return GlobalFunction::sendSimpleResponse(false, 'Award does not exists!');
            }
            $item->delete();

            $doctor = GlobalFunction::generateDoctorFullData($request->doctor_id);

            return response()->json(['status' => true, 'message' => 'Award deleted successfully !', 'data' => $doctor]);
        }
    }
    function addEditExpertise(Request $request)
    {
        $rules = [
            'type' => 'required',
            'doctor_id' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }

        // 1= Add Service
        if ($request->type == 1) {
            $rules = [
                'doctor_id' => 'required',
                'title' => 'required'
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $messages = $validator->errors()->all();
                $msg = $messages[0];
                return response()->json(['status' => false, 'message' => $msg]);
            }

            $doctor = Doctors::where('id', $request->doctor_id)->first();
            if ($doctor == null) {
                return GlobalFunction::sendSimpleResponse(false, 'Doctor does not exists!');
            }

            $item = new DoctorExpertise();
            $item->title =
                GlobalFunction::cleanString($request->title);
            $item->doctor_id = $doctor->id;
            $item->save();

            $doctor = GlobalFunction::generateDoctorFullData($request->doctor_id);

            return response()->json(['status' => true, 'message' => 'Expertise added successfully !', 'data' => $doctor]);
        }
        if ($request->type == 2) {
            // 2 = edit
            $rules = [
                'doctor_id' => 'required',
                'expertise_id' => 'required',
                'title' => 'required'
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $messages = $validator->errors()->all();
                $msg = $messages[0];
                return response()->json(['status' => false, 'message' => $msg]);
            }

            $doctor = Doctors::where('id', $request->doctor_id)->first();
            if ($doctor == null) {
                return GlobalFunction::sendSimpleResponse(false, 'Doctor does not exists!');
            }
            $item = DoctorExpertise::where('id', $request->expertise_id)->first();
            if ($item == null) {
                return GlobalFunction::sendSimpleResponse(false, 'Expertise does not exists!');
            }

            $item->title =
                GlobalFunction::cleanString($request->title);
            $item->save();

            $doctor = GlobalFunction::generateDoctorFullData($request->doctor_id);

            return response()->json(['status' => true, 'message' => 'Expertise edited successfully !', 'data' => $doctor]);
        }
        if ($request->type == 3) {
            $rules = [
                'expertise_id' => 'required',
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $messages = $validator->errors()->all();
                $msg = $messages[0];
                return response()->json(['status' => false, 'message' => $msg]);
            }
            $item = DoctorExpertise::where('id', $request->expertise_id)->first();
            if ($item == null) {
                return GlobalFunction::sendSimpleResponse(false, 'Expertise does not exists!');
            }
            $item->delete();

            $doctor = GlobalFunction::generateDoctorFullData($request->doctor_id);

            return response()->json(['status' => true, 'message' => 'Expertise deleted successfully !', 'data' => $doctor]);
        }
    }
    function addEditService(Request $request)
    {
        $rules = [
            'type' => 'required',
            'doctor_id' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }

        // 1= Add Service
        if ($request->type == 1) {
            $rules = [
                'doctor_id' => 'required',
                'title' => 'required'
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $messages = $validator->errors()->all();
                $msg = $messages[0];
                return response()->json(['status' => false, 'message' => $msg]);
            }

            $doctor = Doctors::where('id', $request->doctor_id)->first();
            if ($doctor == null) {
                return GlobalFunction::sendSimpleResponse(false, 'Doctor does not exists!');
            }

            $service = new DoctorServices();
            $service->title = GlobalFunction::cleanString($request->title);
            $service->doctor_id = $doctor->id;
            $service->save();

            $doctor = GlobalFunction::generateDoctorFullData($request->doctor_id);

            return response()->json(['status' => true, 'message' => 'Service added successfully !', 'data' => $doctor]);
        }
        if ($request->type == 2) {
            // 2 = edit
            $rules = [
                'doctor_id' => 'required',
                'service_id' => 'required',
                'title' => 'required'
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $messages = $validator->errors()->all();
                $msg = $messages[0];
                return response()->json(['status' => false, 'message' => $msg]);
            }

            $doctor = Doctors::where('id', $request->doctor_id)->first();
            if ($doctor == null) {
                return GlobalFunction::sendSimpleResponse(false, 'Doctor does not exists!');
            }
            $service = DoctorServices::where('id', $request->service_id)->first();
            if ($service == null) {
                return GlobalFunction::sendSimpleResponse(false, 'Service does not exists!');
            }

            $service->title =  GlobalFunction::cleanString($request->title);
            $service->save();

            $doctor = GlobalFunction::generateDoctorFullData($request->doctor_id);

            return response()->json(['status' => true, 'message' => 'Service edited successfully !', 'data' => $doctor]);
        }
        if ($request->type == 3) {
            $rules = [
                'service_id' => 'required',
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $messages = $validator->errors()->all();
                $msg = $messages[0];
                return response()->json(['status' => false, 'message' => $msg]);
            }
            $service = DoctorServices::where('id', $request->service_id)->first();
            if ($service == null) {
                return GlobalFunction::sendSimpleResponse(false, 'Service does not exists!');
            }
            $service->delete();

            $doctor = GlobalFunction::generateDoctorFullData($request->doctor_id);

            return response()->json(['status' => true, 'message' => 'Service deleted successfully !', 'data' => $doctor]);
        }
    }
    function fetchMyDoctorProfile(Request $request)
    {

        $rules = [
            'doctor_id' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }

        $doctor = Doctors::where('id', $request->doctor_id)->first();
        if ($doctor == null) {
            return GlobalFunction::sendSimpleResponse(false, 'Doctor does not exists!');
        }

        $doctor = GlobalFunction::generateDoctorFullData($doctor->id);

        return response()->json(['status' => true, 'message' => 'Data fetched successfully !', 'data' => $doctor]);
    }
    function fetchDoctorNotifications(Request $request)
    {
        $rules = [
            'start' => 'required',
            'count' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }

        $doctorNotifications = DoctorNotifications::offset($request->start)
            ->limit($request->count)
            ->orderBy('id', 'DESC')
            ->get();

        return response()->json(['status' => true, 'message' => 'Data fetched successfully !', 'data' => $doctorNotifications]);
    }
    function fetchDoctorCategories(Request $request)
    {
        $cats = DoctorCategories::where('is_deleted', 0)->get();

        return GlobalFunction::sendDataResponse(true, 'data fetched successfully', $cats);
    }
    function doctorRegistration(Request $request)
    {
        $rules = [
            'identity' => 'required',
            'device_token' => 'required',
            'device_type' => 'required',
            'is_login' => [Rule::in(0,1)] //1=login 0=register
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }

        $doctor = Doctors::where('identity', $request->identity)->first();
        if($request->is_login == 1 && $doctor == null){
               return GlobalFunction::sendSimpleResponse(false, 'user not found');
        }

        if ($doctor == null) {
            $doctor = new Doctors();
            $doctor->identity = $request->identity;
            $doctor->name = $request->name;
            $doctor->device_token = $request->device_token;
            $doctor->device_type = $request->device_type;
            $doctor->doctor_number = GlobalFunction::generateDoctorNumber();
            $doctor->save();

            $doctor = Doctors::find($doctor->id);

            return GlobalFunction::sendDataResponse(true, 'Doctor Data fetched successfully', $doctor);
        } else {
            $doctor->device_token = $request->device_token;
            $doctor->device_type = $request->device_type;
            $doctor->save();
            return GlobalFunction::sendDataResponse(true, 'Doctor Data fetched successfully', $doctor);
        }
    }
    function suggestDoctorCategory(Request $request)
    {
        $rules = [
            'title' => 'required',
            'about' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }

        $item = new DoctorCatSuggestions();
        $item->title = GlobalFunction::cleanString($request->title);
        $item->about = GlobalFunction::cleanString($request->about);
        $item->save();

        return GlobalFunction::sendSimpleResponse(true, 'suggestion stored successfully');
    }
    function deleteDoctorAccount(Request $request)
    {
        $rules = [
            'doctor_id' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }

        $doctor = Doctors::where('id', $request->doctor_id)->first();
        if ($doctor == null) {
            return GlobalFunction::sendSimpleResponse(false, 'Doctor does not exists!');
        }

        if (in_array($doctor->mobile_number, ["+91 8160530517", "+91 7096404485"])) {
            return GlobalFunction::sendSimpleResponse(false, 'This account can not be deleted! Please log out.');
        }

        DoctorBankAccount::where('doctor_id', $doctor->id)->delete();
        DoctorHolidays::where('doctor_id', $doctor->id)->delete();
        DoctorAppointmentSlots::where('doctor_id', $doctor->id)->delete();
        DoctorAwards::where('doctor_id', $doctor->id)->delete();
        DoctorServices::where('doctor_id', $doctor->id)->delete();
        DoctorExpertise::where('doctor_id', $doctor->id)->delete();
        DoctorExperience::where('doctor_id', $doctor->id)->delete();
        DoctorServiceLocations::where('doctor_id', $doctor->id)->delete();
        DoctorEarningHistory::where('doctor_id', $doctor->id)->delete();
        DoctorPayoutHistory::where('doctor_id', $doctor->id)->delete();
        DoctorWalletStatements::where('doctor_id', $doctor->id)->delete();

        $doctor->delete();

        return GlobalFunction::sendSimpleResponse(true, 'Doctor account deleted successfully');
    }
    function updateDoctorDetails(Request $request)
    {
        $rules = [
            'doctor_id' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }

        $doctor = Doctors::where('id', $request->doctor_id)->first();
        if ($doctor == null) {
            return GlobalFunction::sendSimpleResponse(false, 'Doctor does not exists!');
        }

        if ($request->has('name')) {
            $doctor->name = GlobalFunction::cleanString($request->name);
        }
        if ($request->has('saved_reels')) {
            $doctor->saved_reels = $request->saved_reels;
        }
        if ($request->has('country_code')) {
            $doctor->country_code = $request->country_code;
        }
        if ($request->has('mobile_number')) {
            $doctor->mobile_number = $request->mobile_number;
        }
        if ($request->has('image')) {
            $doctor->image = GlobalFunction::saveFileAndGivePath($request->image);
        }
        if ($request->has('gender')) {
            $doctor->gender = $request->gender;
        }
        if ($request->has('category_id')) {
            $doctor->category_id = $request->category_id;
        }
        if ($request->has('designation')) {
            $doctor->designation = GlobalFunction::cleanString($request->designation);
        }
        if ($request->has('degrees')) {
            $doctor->degrees = GlobalFunction::cleanString($request->degrees);
        }
        if ($request->has('languages_spoken')) {
            $doctor->languages_spoken = GlobalFunction::cleanString($request->languages_spoken);
        }
        if ($request->has('experience_year')) {
            $doctor->experience_year = $request->experience_year;
        }
        if ($request->has('consultation_fee')) {
            $doctor->consultation_fee = $request->consultation_fee;
        }
        if ($request->has('about_youself')) {
            $doctor->about_youself = GlobalFunction::cleanString($request->about_youself);
        }
        if ($request->has('educational_journey')) {
            $doctor->educational_journey = GlobalFunction::cleanString($request->educational_journey);
        }
        if ($request->has('online_consultation')) {
            $doctor->online_consultation = $request->online_consultation;
        }
        if ($request->has('clinic_consultation')) {
            $doctor->clinic_consultation = $request->clinic_consultation;
        }
        if ($request->has('clinic_name')) {
            $doctor->clinic_name = GlobalFunction::cleanString($request->clinic_name);
        }
        if ($request->has('clinic_address')) {
            $doctor->clinic_address = $request->clinic_address;
        }
        if ($request->has('clinic_lat')) {
            $doctor->clinic_lat = $request->clinic_lat;
        }
        if ($request->has('clinic_long')) {
            $doctor->clinic_long = $request->clinic_long;
        }
        if ($request->has('is_notification')) {
            $doctor->is_notification = $request->is_notification;
        }
        if ($request->has('on_vacation')) {
            $doctor->on_vacation = $request->on_vacation;
        }
        $doctor->save();

        $doctor = GlobalFunction::generateDoctorFullData($doctor->id);

        return GlobalFunction::sendDataResponse(true, 'Doctor details updated successfully', $doctor);
    }
    function logOutDoctor(Request $request)
    {
        $rules = [
            'doctor_id' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }

        $doctor = Doctors::where('id', $request->doctor_id)->first();
        if ($doctor == null) {
            return GlobalFunction::sendSimpleResponse(false, 'Doctor does not exists!');
        }
        $doctor->device_token = null;
        $doctor->save();

        $doctor = GlobalFunction::generateDoctorFullData($doctor->id);

        return GlobalFunction::sendSimpleResponse(true, 'Doctor log out successfully');
    }
}
