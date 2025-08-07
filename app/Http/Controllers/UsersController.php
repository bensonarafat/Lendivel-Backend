<?php

namespace App\Http\Controllers;

use App\Models\AddedPatients;
use App\Models\AppointmentDocs;
use App\Models\Appointments;
use App\Models\Constants;
use App\Models\Coupons;
use App\Models\DoctorCategories;
use App\Models\Doctors;
use App\Models\GlobalFunction;
use App\Models\GlobalSettings;
use App\Models\UserNotification;
use App\Models\Users;
use App\Models\UserWalletRechargeLogs;
use App\Models\UserWalletStatements;
use App\Models\UserWithdrawRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UsersController extends Controller
{
    function TEST_sendNotificationToUser()
    {
        $user = Users::find(12);
        return GlobalFunction::sendPushToUser('Title', 'Message', $user);
    }

    function fetchUserAppointmentsList(Request $request)
    {
        $totalData =  Appointments::where('user_id', $request->userId)->count();
        $rows = Appointments::where('user_id', $request->userId)->orderBy('id', 'DESC')->get();
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
            $result = Appointments::where('user_id', $request->userId)->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
            $result =  Appointments::where('user_id', $request->userId)->where(function ($query) use ($search) {
                $query->Where('appointment_number', 'LIKE', "%{$search}%")
                    ->orWhere('payable_amount', 'LIKE', "%{$search}%");
            })->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = Appointments::where('user_id', $request->userId)->where(function ($query) use ($search) {
                $query->Where('appointment_number', 'LIKE', "%{$search}%")
                    ->orWhere('payable_amount', 'LIKE', "%{$search}%");
            })->count();
        }
        $data = array();
        foreach ($result as $item) {

            $doctor = "";
            if ($item->doctor != null) {
                $doctor = '<a href="' . route('viewDoctorProfile', $item->doctor->id) . '"><span class="badge bg-primary text-white">' . $item->doctor->name . '</span></a>';
            }

            $view = '<a href="' . route('viewAppointment', $item->id) . '" class="mr-2 btn btn-info text-white " rel=' . $item->id . ' >' . __("View") . '</a>';

            $status = GlobalFunction::returnAppointmentStatus($item->status);

            $action = $view;

            $dateTime =  $item->date . '<br>' . GlobalFunction::formateTimeString($item->time);
            $payableAmount = $settings->currency . $item->payable_amount;

            $data[] = array(
                $item->appointment_number,
                $doctor,
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
    function viewUserProfile($id)
    {
        $user = Users::find($id);
        $settings = GlobalSettings::first();
        $totalAppointments = Appointments::where('user_id', $id)->count();
        return view('viewUserProfile', [
            'user' => $user,
            'settings' => $settings,
            'totalAppointments' => $totalAppointments,
        ]);
    }

    function blockUserFromAdmin($id)
    {
        $user = Users::find($id);
        $user->is_block = 1;
        $user->save();

        return GlobalFunction::sendSimpleResponse(true, 'User blocked successfully!');
    }
    function unblockUserFromAdmin($id)
    {
        $user = Users::find($id);
        $user->is_block = 0;
        $user->save();

        return GlobalFunction::sendSimpleResponse(true, 'User unblocked successfully!');
    }

    function rejectUserWithdrawal(Request $request)
    {
        $item = UserWithdrawRequest::find($request->id);
        if ($request->has('summary')) {
            $item->summary = $request->summary;
        }
        $item->status = Constants::statusWithdrawalRejected;
        $item->save();

        $summary = '(Rejected) Withdraw request :' . $item->request_number;
        // Adding wallet statement
        GlobalFunction::addUserStatementEntry(
            $item->user->id,
            null,
            $item->amount,
            Constants::credit,
            Constants::deposit,
            $summary
        );

        //adding money to user wallet
        $item->user->wallet = $item->user->wallet + $item->amount;
        $item->user->save();

        return GlobalFunction::sendSimpleResponse(true, 'request rejected successfully');
    }
    function completeUserWithdrawal(Request $request)
    {
        $item = UserWithdrawRequest::find($request->id);
        if ($request->has('summary')) {
            $item->summary = $request->summary;
        }
        $item->status = Constants::statusWithdrawalCompleted;
        $item->save();

        return GlobalFunction::sendSimpleResponse(true, 'request completed successfully');
    }

    function fetchUserCompletedWithdrawalsList(Request $request)
    {
        $totalData =  UserWithdrawRequest::where('status', Constants::statusWithdrawalCompleted)->with('user')->count();
        $rows = UserWithdrawRequest::where('status', Constants::statusWithdrawalCompleted)->with('user')->orderBy('id', 'DESC')->get();
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
            $result = UserWithdrawRequest::where('status', Constants::statusWithdrawalCompleted)
                ->with('user')
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
            $result =  UserWithdrawRequest::where('status', Constants::statusWithdrawalCompleted)
                ->where(function ($query) use ($search) {
                    $query->where('request_number', 'LIKE', "%{$search}%")
                        ->orWhere('amount', 'LIKE', "%{$search}%")
                        ->orWhere('holder', 'LIKE', "%{$search}%")
                        ->orWhere('summary', 'LIKE', "%{$search}%")
                        ->orWhereHas('user', function ($query) use ($search) {
                            $query->Where('fullname', 'LIKE', "%{$search}%");
                        });
                })
                ->with('user')
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = UserWithdrawRequest::where('status', Constants::statusWithdrawalCompleted)
                ->with('user')
                ->where(function ($query) use ($search) {
                    $query->where('request_number', 'LIKE', "%{$search}%")
                        ->orWhere('amount', 'LIKE', "%{$search}%")
                        ->orWhere('holder', 'LIKE', "%{$search}%")
                        ->orWhere('summary', 'LIKE', "%{$search}%")
                        ->orWhereHas('user', function ($query) use ($search) {
                            $query->Where('fullname', 'LIKE', "%{$search}%");
                        });
                })
                ->count();
        }
        $data = array();
        foreach ($result as $item) {

            $holder = '<span class="text-dark font-weight-bold font-14">' . $item->holder . '</span>';
            $bank_title = '<div class="bank-details"><span>' . $item->bank_title . '</span>';
            $account_number = '<span>' . __('Account : ') .  $item->account_number . '</span>';
            $swift_code = '<span>' . __('Swift Code : ') . $item->swift_code . '</span></div>';
            $bankDetails = $holder . $bank_title . $account_number . $swift_code;

            // Amount & Status
            $amount = '<span class="text-dark font-weight-bold font-16">' . $settings->currency . $item->amount . '</span><br>';
            $status = '<span class="badge bg-success text-white"rel="' . $item->id . '">' . __('Completed') . '</span>';
            $amountData = $amount . $status;



            $user = "";
            if ($item->user != null) {
                $user = '<a href="' . route('viewUserProfile', $item->user->id) . '"><span class="badge bg-primary text-white">' . $item->user->fullname . '</span></a>';
            }


            $data[] = array(
                $item->request_number,
                $bankDetails,
                $amountData,
                $user,
                $item->summary,
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
    function fetchUserRejectedWithdrawalsList(Request $request)
    {
        $totalData =  UserWithdrawRequest::where('status', Constants::statusWithdrawalRejected)->with('user')->count();
        $rows = UserWithdrawRequest::where('status', Constants::statusWithdrawalRejected)->with('user')->orderBy('id', 'DESC')->get();
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
            $result = UserWithdrawRequest::where('status', Constants::statusWithdrawalRejected)
                ->with('user')
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
            $result =  UserWithdrawRequest::where('status', Constants::statusWithdrawalRejected)
                ->where(function ($query) use ($search) {
                    $query->where('request_number', 'LIKE', "%{$search}%")
                        ->orWhere('amount', 'LIKE', "%{$search}%")
                        ->orWhere('holder', 'LIKE', "%{$search}%")
                        ->orWhere('summary', 'LIKE', "%{$search}%")
                        ->orWhereHas('user', function ($query) use ($search) {
                            $query->Where('fullname', 'LIKE', "%{$search}%");
                        });
                })
                ->with('user')
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = UserWithdrawRequest::where('status', Constants::statusWithdrawalRejected)
                ->with('user')
                ->where(function ($query) use ($search) {
                    $query->where('request_number', 'LIKE', "%{$search}%")
                        ->orWhere('amount', 'LIKE', "%{$search}%")
                        ->orWhere('holder', 'LIKE', "%{$search}%")
                        ->orWhere('summary', 'LIKE', "%{$search}%")
                        ->orWhereHas('user', function ($query) use ($search) {
                            $query->Where('fullname', 'LIKE', "%{$search}%");
                        });
                })
                ->count();
        }
        $data = array();
        foreach ($result as $item) {

            $holder = '<span class="text-dark font-weight-bold font-14">' . $item->holder . '</span>';
            $bank_title = '<div class="bank-details"><span>' . $item->bank_title . '</span>';
            $account_number = '<span>' . __('Account : ') .  $item->account_number . '</span>';
            $swift_code = '<span>' . __('Swift Code : ') . $item->swift_code . '</span></div>';
            $bankDetails = $holder . $bank_title . $account_number . $swift_code;

            // Amount & Status
            $amount = '<span class="text-dark font-weight-bold font-16">' . $settings->currency . $item->amount . '</span><br>';
            $status = '<span class="badge bg-danger text-white"rel="' . $item->id . '">' . __('Rejected') . '</span>';
            $amountData = $amount . $status;

            $user = "";
            if ($item->user != null) {
                $user = '<a href="' . route('viewUserProfile', $item->user->id) . '"><span class="badge bg-primary text-white">' . $item->user->fullname . '</span></a>';
            }


            $data[] = array(
                $item->request_number,
                $bankDetails,
                $amountData,
                $user,
                $item->summary,
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
    function fetchUserPendingWithdrawalsList(Request $request)
    {
        $totalData =  UserWithdrawRequest::where('status', Constants::statusWithdrawalPending)->with('user')->count();
        $rows = UserWithdrawRequest::where('status', Constants::statusWithdrawalPending)->with('user')->orderBy('id', 'DESC')->get();
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
            $result = UserWithdrawRequest::where('status', Constants::statusWithdrawalPending)
                ->with('user')
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
            $result =  UserWithdrawRequest::where('status', Constants::statusWithdrawalPending)
                ->where(function ($query) use ($search) {
                    $query->where('request_number', 'LIKE', "%{$search}%")
                        ->orWhere('amount', 'LIKE', "%{$search}%")
                        ->orWhere('holder', 'LIKE', "%{$search}%")
                        ->orWhereHas('user', function ($query) use ($search) {
                            $query->Where('fullname', 'LIKE', "%{$search}%");
                        });
                })
                ->with('user')
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = UserWithdrawRequest::where('status', Constants::statusWithdrawalPending)
                ->with('user')
                ->where(function ($query) use ($search) {
                    $query->where('request_number', 'LIKE', "%{$search}%")
                        ->orWhere('amount', 'LIKE', "%{$search}%")
                        ->orWhere('holder', 'LIKE', "%{$search}%")
                        ->orWhereHas('user', function ($query) use ($search) {
                            $query->Where('fullname', 'LIKE', "%{$search}%");
                        });
                })
                ->count();
        }
        $data = array();
        foreach ($result as $item) {

            $holder = '<span class="text-dark font-weight-bold font-14">' . $item->holder . '</span>';
            $bank_title = '<div class="bank-details"><span>' . $item->bank_title . '</span>';
            $account_number = '<span>' . __('Account : ') .  $item->account_number . '</span>';
            $swift_code = '<span>' . __('Swift Code : ') . $item->swift_code . '</span></div>';
            $bankDetails = $holder . $bank_title . $account_number . $swift_code;



            $user = "";
            if ($item->user != null) {
                $user = '<a href="' . route('viewUserProfile', $item->user->id) . '"><span class="badge bg-primary text-white">' . $item->user->fullname . '</span></a>';
            }

            // Amount & Status
            $amount = '<span class="text-dark font-weight-bold font-16">' . $settings->currency . $item->amount . '</span><br>';
            $status = '<span class="badge bg-warning text-white"rel="' . $item->id . '">' . __('Pending') . '</span>';
            $amountData = $amount . $status;

            $complete = '<a href="" class="mr-2 btn btn-success text-white complete" rel=' . $item->id . ' >' . __("Complete") . '</a>';
            $reject = '<a href="" class="mr-2 btn btn-danger text-white reject" rel=' . $item->id . ' >' . __("Reject") . '</a>';
            // $delete = '<a href="" class="mr-2 btn btn-danger text-white delete" rel=' . $item->id . ' >' . __("Delete") . '</a>';
            $action =  $complete . $reject;


            $data[] = array(
                $item->request_number,
                $bankDetails,
                $amountData,
                $user,
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

    function userWithdraws()
    {
        return view('userWithdraws');
    }

    function users()
    {
        return view('users');
    }


    function fetchUsersList(Request $request)
    {
        $totalData =  Users::count();
        $rows = Users::orderBy('id', 'DESC')->get();

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
            $result = Users::offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
            $result =  Users::where(function ($query) use ($search) {
                $query->Where('identity', 'LIKE', "%{$search}%")
                    ->orWhere('fullname', 'LIKE', "%{$search}%");
            })->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = Users::where(function ($query) use ($search) {
                $query->Where('identity', 'LIKE', "%{$search}%")
                    ->orWhere('fullname', 'LIKE', "%{$search}%");
            })->count();
        }
        $data = array();
        foreach ($result as $item) {

            if ($item->profile_image == null) {
                $image = '<img src="http://placehold.jp/150x150.png" width="50" height="50">';
            } else {
                $imgUrl = GlobalFunction::createMediaUrl($item->profile_image);
                $image = '<img src="' . $imgUrl . '" width="50" height="50">';
            }

            $appointmentCount = Appointments::where('user_id', $item->id)->count();

            $view = '<a href="' . route('viewUserProfile', $item->id) . '" class="mr-2 btn btn-info text-white " rel=' . $item->id . ' >' . __("View") . '</a>';

            $block = "";
            if ($item->is_block == 0) {
                $block = '<a href="" class="mr-2 btn btn-danger text-white block" rel=' . $item->id . ' >' . __("Block") . '</a>';
            } else {
                $block = '<a href="" class="mr-2 btn btn-success text-white unblock" rel=' . $item->id . ' >' . __("Unblock") . '</a>';
            }

            $action = $view  . $block;

            $data[] = array(
                $image,
                $item->identity,
                $item->fullname,
                $appointmentCount,
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


    function fetchUserWithdrawRequests(Request $request)
    {
        $rules = [
            'user_id' => 'required',
            'start' => 'required',
            'count' => 'required',
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

        $withdraws = UserWithdrawRequest::where('user_id', $user->id)
            ->offset($request->start)
            ->limit($request->count)
            ->orderBy('id', 'DESC')
            ->get();

        return GlobalFunction::sendDataResponse(true, 'withdraw requests fetched successfully!', $withdraws);
    }

    function submitUserWithdrawRequest(Request $request)
    {
        $rules = [
            'user_id' => 'required',
            'bank_title' => 'required',
            'account_number' => 'required',
            'holder' => 'required',
            'swift_code' => 'required',
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
        if ($user->wallet < 1) {
            return response()->json(['status' => false, 'message' => "Not enough balance to withdraw!"]);
        }

        $withdraw = new UserWithdrawRequest();
        $withdraw->user_id = $user->id;
        $withdraw->request_number = GlobalFunction::generateUserWithdrawRequestNumber();
        $withdraw->bank_title = GlobalFunction::cleanString($request->bank_title);
        $withdraw->amount = $user->wallet;
        $withdraw->account_number = GlobalFunction::cleanString($request->account_number);
        $withdraw->holder = GlobalFunction::cleanString($request->holder);
        $withdraw->swift_code = GlobalFunction::cleanString($request->swift_code);
        $withdraw->save();

        $summary = 'Withdraw request :' . $withdraw->request_number;
        // Adding wallet statement
        GlobalFunction::addUserStatementEntry(
            $user->id,
            null,
            $user->wallet,
            Constants::debit,
            Constants::withdraw,
            $summary
        );

        //resetting users wallet
        $user->wallet = 0;
        $user->save();

        return GlobalFunction::sendSimpleResponse(true, 'withdraw request submitted successfully!');
    }
    function fetchMyUserDetails(Request $request)
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

        $user = GlobalFunction::generateUserFullData($user->id);

        return GlobalFunction::sendDataResponse(true, 'user data fetched successfully', $user);
    }
    function fetchPatients(Request $request)
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

        $patients = AddedPatients::where('user_id', $user->id)->get();

        return GlobalFunction::sendDataResponse(true, 'patients data fetched successfully', $patients);
    }


    function addMoneyToUserWallet(Request $request)
    {
        $rules = [
            'user_id' => 'required',
            'amount' => 'required',
            'transaction_id' => 'required',
            'transaction_summary' => 'required',
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
        $user->wallet = $user->wallet + $request->amount;
        $user->save();
        // Adding Statement entry
        GlobalFunction::addUserStatementEntry(
            $user->id,
            null,
            $request->amount,
            Constants::credit,
            Constants::deposit,
            $request->transaction_summary
        );
        // Recharge Wallet History
        $rechargeLog = new UserWalletRechargeLogs();
        $rechargeLog->user_id = $user->id;
        $rechargeLog->amount = $request->amount;
        $rechargeLog->gateway = $request->gateway;
        $rechargeLog->transaction_id = $request->transaction_id;
        $rechargeLog->transaction_summary = $request->transaction_summary;
        $rechargeLog->save();

        return GlobalFunction::sendSimpleResponse(true, 'Money added to wallet successfully!');
    }

    function fetchUserWalletRechargeLogsList(Request $request)
    {
        $userId = $request->userId;
        $totalData =  UserWalletRechargeLogs::where('user_id', $userId)->count();
        $rows = UserWalletRechargeLogs::where('user_id', $userId)->orderBy('id', 'DESC')->get();
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
            $result = UserWalletRechargeLogs::where('user_id', $userId)
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
            $result =  UserWalletRechargeLogs::where('user_id', $userId)
                ->where(function ($query) use ($search) {
                    $query->Where('amount', 'LIKE', "%{$search}%")
                        ->orWhere('transaction_summary', 'LIKE', "%{$search}%")
                        ->orWhere('transaction_id', 'LIKE', "%{$search}%");
                })
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = UserWalletRechargeLogs::where('user_id', $userId)
                ->where(function ($query) use ($search) {
                    $query->Where('amount', 'LIKE', "%{$search}%")
                        ->orWhere('transaction_summary', 'LIKE', "%{$search}%")
                        ->orWhere('transaction_id', 'LIKE', "%{$search}%");
                })
                ->count();
        }
        $data = array();
        foreach ($result as $item) {

            $gateway = GlobalFunction::detectPaymentGateway($item->gateway);

            $data[] = array(
                $settings->currency . $item->amount,
                $gateway,
                $item->transaction_id,
                $item->transaction_summary,
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
    function fetchUserPatientsList(Request $request)
    {
        $userId = $request->userId;
        $totalData =  AddedPatients::where('user_id', $userId)->count();
        $rows = AddedPatients::where('user_id', $userId)->orderBy('id', 'DESC')->get();
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
            $result = AddedPatients::where('user_id', $userId)
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
            $result =  AddedPatients::where('user_id', $userId)
                ->where(function ($query) use ($search) {
                    $query->Where('fullname', 'LIKE', "%{$search}%")
                        ->orWhere('relation', 'LIKE', "%{$search}%");
                })
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = AddedPatients::where('user_id', $userId)
                ->where(function ($query) use ($search) {
                    $query->Where('fullname', 'LIKE', "%{$search}%")
                        ->orWhere('relation', 'LIKE', "%{$search}%");
                })
                ->count();
        }
        $data = array();
        foreach ($result as $item) {

            $imgUrl = "http://placehold.jp/150x150.png";
            if ($item->image == null) {
                $img = '<img src="http://placehold.jp/150x150.png" width="50" height="50">';
            } else {
                $imgUrl = GlobalFunction::createMediaUrl($item->image);
                $img = '<img src="' . $imgUrl . '" width="50" height="50">';
            }

            $gender = '';
            if ($item->gender == Constants::genderMale) {
                $gender = '<span  class="badge bg-primary text-white ">' . __("Male") . '</span>';
            }
            if ($item->gender == Constants::genderFemale) {
                $gender = '<span  class="badge bg-info text-white ">' . __("Female") . '</span>';
            }

            $data[] = array(
                $img,
                $item->fullname,
                $item->age,
                $gender,
                $item->relation,
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


    function fetchUserWithdrawRequestsList(Request $request)
    {
        $userId = $request->userId;
        $totalData =  UserWithdrawRequest::where('user_id', $userId)->with(['user'])->count();
        $rows = UserWithdrawRequest::where('user_id', $userId)->with(['user'])->orderBy('id', 'DESC')->get();
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
            $result = UserWithdrawRequest::where('user_id', $userId)->with(['user'])
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
            $result = UserWithdrawRequest::where('user_id', $userId)->with(['user'])
                ->where(function ($query) use ($search) {
                    $query->where('request_number', 'LIKE', "%{$search}%")
                        ->orWhere('amount', 'LIKE', "%{$search}%")
                        ->orWhere('summary', 'LIKE', "%{$search}%");
                })
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = UserWithdrawRequest::where('user_id', $userId)->with(['user'])
                ->where(function ($query) use ($search) {
                    $query->where('request_number', 'LIKE', "%{$search}%")
                        ->orWhere('amount', 'LIKE', "%{$search}%")
                        ->orWhere('summary', 'LIKE', "%{$search}%");
                })
                ->count();
        }
        $data = array();
        foreach ($result as $item) {

            $holder = '<span class="text-dark font-weight-bold font-14">' . $item->holder . '</span>';
            $bank_title = '<div class="bank-details"><span>' . $item->bank_title . '</span>';
            $account_number = '<span>' . __('Account : ') .  $item->account_number . '</span>';
            $swift_code = '<span>' . __('Swift Code : ') . $item->swift_code . '</span></div>';
            $bankDetails = $holder . $bank_title . $account_number . $swift_code;

            $complete = '<a href="" class="mr-2 btn btn-success text-white complete" rel=' . $item->id . ' >' . __("Complete") . '</a>';
            $reject = '<a href="" class="mr-2 btn btn-danger text-white reject" rel=' . $item->id . ' >' . __("Reject") . '</a>';
            // $delete = '<a href="" class="mr-2 btn btn-danger text-white delete" rel=' . $item->id . ' >' . __("Delete") . '</a>';
            $action = '';

            // Amount & Status
            $amount = '<span class="text-dark font-weight-bold font-16">' . $settings->currency . $item->amount . '</span><br>';
            $status = "";
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

    function fetchUserWalletStatementList(Request $request)
    {
        $totalData =  UserWalletStatements::where('user_id', $request->userId)->count();
        $rows = UserWalletStatements::where('user_id', $request->userId)->orderBy('id', 'DESC')->get();
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
            $result = UserWalletStatements::where('user_id', $request->userId)
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
            $result =  UserWalletStatements::where('user_id', $request->userId)
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
            $totalFiltered = UserWalletStatements::where('user_id', $request->userId)
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


    function editPatient(Request $request)
    {
        $rules = [
            'patient_id' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }

        $patient = AddedPatients::find($request->patient_id);
        if ($patient == null) {
            return GlobalFunction::sendSimpleResponse(false, 'Patient does not exists !');
        }

        if ($request->has('fullname')) {
            $patient->fullname = GlobalFunction::cleanString($request->fullname);
        }
        if ($request->has('gender')) {
            $patient->gender = $request->gender;
        }
        if ($request->has('age')) {
            $patient->age = $request->age;
        }
        if ($request->has('relation')) {
            $patient->relation = GlobalFunction::cleanString($request->relation);
        }
        if ($request->has('image')) {
            $patient->image =
                GlobalFunction::saveFileAndGivePath($request->image);
        }
        $patient->save();

        return GlobalFunction::sendSimpleResponse(true, 'Patient updated successfully');
    }
    function deletePatient(Request $request)
    {
        $rules = [
            'patient_id' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }

        $patient = AddedPatients::find($request->patient_id);
        if ($patient == null) {
            return GlobalFunction::sendSimpleResponse(false, 'Patient does not exists !');
        }


        $patient->delete();

        return GlobalFunction::sendSimpleResponse(true, 'Patient deleted successfully');
    }
    function addPatient(Request $request)
    {
        $rules = [
            'user_id' => 'required',
            'fullname' => 'required',
            'gender' => 'required',
            'age' => 'required',
            'relation' => 'required',
            'image' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }

        $user = Users::find($request->user_id);
        if ($user == null) {
            return GlobalFunction::sendSimpleResponse(false, 'User does not exists !');
        }

        $patient = new AddedPatients();
        $patient->user_id = $request->user_id;
        $patient->fullname = GlobalFunction::cleanString($request->fullname);
        $patient->gender = $request->gender;
        $patient->age = $request->age;
        $patient->relation = GlobalFunction::cleanString($request->relation);
        $patient->image = GlobalFunction::saveFileAndGivePath($request->image);
        $patient->save();

        return GlobalFunction::sendSimpleResponse(true, 'Patient Added successfully');
    }

    function fetchNotification(Request $request)
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

        $notifications = UserNotification::offset($request->start)
            ->limit($request->count)
            ->orderBy('id', 'DESC')
            ->get();

        return GlobalFunction::sendDataResponse(true, 'Data fetched successfully', $notifications);
    }
    function fetchHomePageData(Request $request)
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
            return GlobalFunction::sendSimpleResponse(false, 'User does not exists !');
        }

        $cats = DoctorCategories::where('is_deleted', 0)->get();
        foreach ($cats as $cat) {
            $doctors = Doctors::where('category_id', $cat->id)
                ->where('status', Constants::statusDoctorApproved)
                ->where('on_vacation', Constants::doctorNotOnVacation)
                ->get();
            $cat->doctors = $doctors;
        }

        $appointments = Appointments::where('user_id', $user->id)->where('date', $request->date)
            ->with(['doctor', 'documents'])->where('status', Constants::orderAccepted)->get();

        return response()->json([
            'status' => true,
            'message' => 'data fetched successfully!',
            'categories' => $cats,
            'appointments' => $appointments,
        ]);
    }

    function fetchWalletStatement(Request $request)
    {
        $rules = [
            'user_id' => 'required',
            'start' => 'required',
            'count' => 'required',
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
        $statement = UserWalletStatements::where('user_id', $user->id)
            ->offset($request->start)
            ->limit($request->count)
            ->orderBy('id', 'DESC')
            ->get();

        return GlobalFunction::sendDataResponse(true, 'Statement Data fetched successfully!', $statement);
    }
    function logOut(Request $request)
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

        $user = Users::where('id', $request->user_id)->first();
        if ($user == null) {
            return GlobalFunction::sendSimpleResponse(false, 'User does not exists !');
        }

        $user->device_token = null;
        $user->save();

        return GlobalFunction::sendSimpleResponse(true, 'user log out successfully');
    }
    function deleteUserAccount(Request $request)
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

        $user = Users::where('id', $request->user_id)->first();
        if ($user == null) {
            return GlobalFunction::sendSimpleResponse(false, 'User does not exists !');
        }

        AddedPatients::where('user_id', $user->id)->delete();
        UserWalletStatements::where('user_id', $user->id)->delete();
        UserWithdrawRequest::where('user_id', $user->id)->delete();

        $user->delete();

        return GlobalFunction::sendSimpleResponse(true, 'user data deleted successfully');
    }
    function updateUserDetails(Request $request)
    {
        $rules = [
            'identity' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }

        $user = Users::where('identity', $request->identity)->first();
        if ($user == null) {
            return GlobalFunction::sendSimpleResponse(false, 'User does not exists !');
        }

        if ($request->has('fullname')) {
            $user->fullname = GlobalFunction::cleanString($request->fullname);
        }
        if ($request->has('profile_image')) {
            $user->profile_image = GlobalFunction::saveFileAndGivePath($request->file('profile_image'));
        }
        if ($request->has('saved_reels')) {
            $user->saved_reels = $request->saved_reels;
        }
        if ($request->has('country_code')) {
            $user->country_code = $request->country_code;
        }
        if ($request->has('phone_number')) {
            $user->phone_number = $request->phone_number;
        }
        if ($request->has('gender')) {
            $user->gender = $request->gender;
        }
        if ($request->has('dob')) {
            $user->dob = $request->dob;
        }
        if ($request->has('favourite_doctors')) {
            $user->favourite_doctors = $request->favourite_doctors;
        }
        if ($request->has('is_notification')) {
            $user->is_notification = $request->is_notification;
        }

        $user->save();

        $user = GlobalFunction::generateUserFullData($user->id);

        return GlobalFunction::sendDataResponse(true, 'user details updated successfully', $user);
    }
    function fetchFavoriteDoctors(Request $request)
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

        $user = Users::where('id', $request->user_id)->first();
        if ($user == null) {
            return GlobalFunction::sendSimpleResponse(false, 'User does not exists !');
        }


        $doctors = Doctors::whereIn('id', explode(',', $user->favourite_doctors))->with([
            'services',
            'experience',
            'expertise',
            'serviceLocations',
            'awards',
            'slots',
            'holidays',
        ])->get();

        return GlobalFunction::sendDataResponse(true, 'user details updated successfully', $doctors);
    }
    function registerUser(Request $request)
    {
        $rules = [
            'identity' => 'required',
            'device_type' => [Rule::in(1, 2)],
            'device_token' => 'required',
            'fullname' => 'required',
            'login_type' => [Rule::in(1, 2, 3)],
            'is_login' => [Rule::in(0,1)] //1=login 0=register
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }

        $user = Users::where('identity', $request->identity)->first();
        if($request->is_login == 1 && $user == null){
               return GlobalFunction::sendSimpleResponse(false, 'user not found');
        }

        if ($user != null) {
            $user->device_type = $request->device_type;
            $user->device_token = $request->device_token;
            $user->login_type = $request->login_type;
            $user->save();

            $user = Users::find($user->id);

            return GlobalFunction::sendDataResponse(true, 'User exists already', $user);
        } else {
            $user = new Users();
            $user->identity = $request->identity;
            $user->device_type = $request->device_type;
            $user->device_token = $request->device_token;
            $user->fullname = $request->fullname;
            $user->login_type = $request->login_type;
            $user->save();

            $user = Users::find($user->id);

            return GlobalFunction::sendDataResponse(true, 'User registration successful', $user);
        }
    }
}
