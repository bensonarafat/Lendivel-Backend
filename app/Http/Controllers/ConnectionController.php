<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\Doctors;
use App\Models\Constants;
use App\Models\Connection;
use App\Models\ChatActivity;
use App\Models\ConnectionPayment;
use Illuminate\Http\Request;
use App\Models\GlobalFunction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ConnectionController extends Controller
{

    public function isConnected(Request $request)
    {
        $rules = [
            'doctor_id' => 'required',
            'user_id' => 'required',
            'request_by' => 'required|in:doctor,user'
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

        $user = User::where('id', $request->user_id)->first();
        if ($user == null) {
            return GlobalFunction::sendSimpleResponse(false, 'User does not exists!');
        }

        // make sure they are no connection btw this user and the doctor
        $connection = Connection::where([
            'user_id' => $request->user_id,
            'doctor_id' => $request->doctor_id,
            'request_by' => $request->request_by,
            "status" => Constants::connectionPlacedPending
        ])
            ->first();
        if ($connection == null) {
            return GlobalFunction::sendSimpleResponse(false, 'No connection found');
        } else {
            return GlobalFunction::sendDataResponse(true, 'Connection found', $connection->toArray());
        }
    }

    public function requestConnection(Request $request)
    {
        $rules = [
            'doctor_id' => 'required',
            'user_id' => 'required',
            'request_by' => 'required|in:doctor,user',
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

        $user = User::where('id', $request->user_id)->first();
        if ($user == null) {
            return GlobalFunction::sendSimpleResponse(false, 'User does not exists!');
        }

        // make sure they are no connection btw this user and the doctor or any pending connection request
        $connection = Connection::where([
            'user_id' => $request->user_id,
            'doctor_id' => $request->doctor_id,
            'request_by' => $request->request_by,
            "status" => Constants::connectionPlacedPending
        ])->first();
        if ($connection != null) {
            return GlobalFunction::sendSimpleResponse(false, 'You can\'t request connection to this user');
        }
        try {
            $connection = new Connection();
            $connection->doctor_id = $request->doctor_id;
            $connection->user_id = $request->user_id;
            $connection->request_by = $request->request_by;
            $connection->message = $request->message;
            $connection->save();
            return GlobalFunction::sendSimpleResponse(true, 'Connection sent successfully');
        } catch (Exception $e) {
            return response()->json(['status' => false, 'message' => "There was an error requesting connection"]);
        }
    }

    public function acceptConnection(Request $request)
    {
        $rules = [
            'connection_id' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }

        $connection = Connection::where('id', $request->connection_id)
            ->with(['user', 'doctor'])
            ->first();
        if ($connection == null) {
            return GlobalFunction::sendSimpleResponse(false, 'Connection does not exists!');
        }

        $doctor = Doctors::where('id', $connection->doctor_id)->first();
        if ($doctor == null) {
            return GlobalFunction::sendSimpleResponse(false, 'Doctor does not exists!');
        }

        if ($connection->status == Constants::connectionPlacedPending) {
            $connection->status = Constants::connectionAccepted;
            $connection->save();

            // Create Chat activities when connection is accepted if not already exist
            $checkActivities = ChatActivity::where(
                [
                    "user_id" => $connection->user_id,
                    "doctor_id" => $connection->doctor_id,
                ]
            )->exists();
            if (!$checkActivities) {
                ChatActivity::create([
                    "user_id" => $connection->user_id,
                    "doctor_id" => $connection->doctor_id,
                    "status"    => Constants::chatActivityActive,
                ]);
            }

            // Send Push to user
            $title = "Connection :" . $connection->doctor->name;
            $message = "Connection has been accepted!";
            $notifyData = [
                'type' => Constants::notifyAppointment . '',
                'id' => $connection->id . ''
            ];

            GlobalFunction::sendPushToUser($title, $message, $connection->user, $notifyData);

            return GlobalFunction::sendSimpleResponse(true, 'Connection accepted successfully');
        } else {
            return response()->json(['status' => false, 'message' => "This connection can't be accepted!"]);
        }
    }

    public function declineConnection(Request $request)
    {
        $rules = [
            'connection_id' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }

        $connection = Connection::where('id', $request->connection_id)
            ->with(['user', 'doctor'])
            ->first();
        if ($connection == null) {
            return GlobalFunction::sendSimpleResponse(false, 'Connection does not exists!');
        }

        $doctor = Doctors::where('id', $connection->doctor_id)->first();
        if ($doctor == null) {
            return GlobalFunction::sendSimpleResponse(false, 'Doctor does not exists!');
        }

        if ($connection->status == Constants::connectionPlacedPending) {
            $connection->status = Constants::connectionDeclined;
            $connection->save();

            // Send Push to user
            $title = "Connection :" . $connection->doctor->name;
            $message = "Connection has been declined!";
            $notifyData = [
                'type' => Constants::notifyAppointment . '',
                'id' => $connection->id . ''
            ];
            GlobalFunction::sendPushToUser($title, $message, $connection->user, $notifyData);

            return GlobalFunction::sendSimpleResponse(true, 'Connection declined successfully');
        } else {
            return response()->json(['status' => false, 'message' => "This connection can't be declined!"]);
        }
    }

    public function cancelConnectionRequest(Request $request)
    {
        $rules = [
            'connection_id' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }

        $connection = Connection::where('id', $request->connection_id)
            ->with(['user', 'doctor'])
            ->first();
        if ($connection == null) {
            return GlobalFunction::sendSimpleResponse(false, 'Connection does not exists!');
        }

        if ($connection->status == Constants::connectionPlacedPending) {
            $connection->delete();

            // Send Push to user
            $title = "Connection Request Cancelled";
            $message = "Your connection request has been cancelled!";
            GlobalFunction::sendPushToUser($title, $message, $connection->user);

            return GlobalFunction::sendSimpleResponse(true, 'Connection request cancelled successfully');
        } else {
            return response()->json(['status' => false, 'message' => "This connection can't be cancelled!"]);
        }
    }

    public function fetchMyConnections(Request $request)
    {
        $rules = [
            'user_id' => 'required',
            'request_by' => 'required|in:doctor,user'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }

        if ($request->request_by == 'doctor') {
            $doctor = Doctors::where('id', $request->user_id)->first();
            if ($doctor == null) {
                return GlobalFunction::sendSimpleResponse(false, 'Doctor does not exists!');
            }
            // Fetch connections for the user
            $connections = Connection::where('doctor_id', $request->user_id)
                ->with(['user'])
                ->get();

            if ($connections->isEmpty()) {
                return GlobalFunction::sendSimpleResponse(false, 'No connections found');
            } else {
                return GlobalFunction::sendDataResponse(true, 'Connections found', $connections);
            }
        } else {
            $user = User::where('id', $request->user_id)->first();
            if ($user == null) {
                return GlobalFunction::sendSimpleResponse(false, 'User does not exists!');
            }

            // Fetch connections for the user
            $connections = Connection::where('user_id', $request->user_id)
                ->with(['doctor'])
                ->get();

            if ($connections->isEmpty()) {
                return GlobalFunction::sendSimpleResponse(false, 'No connections found');
            } else {
                return GlobalFunction::sendDataResponse(true, 'Connections found', $connections);
            }
        }
    }

    public function reconnectConnection(Request $request)
    {
        $rules = [
            'doctor_id' => 'required',
            'user_id' => 'required',
            'message' => 'nullable|string|max:255'
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

        $user = User::where('id', $request->user_id)->first();
        if ($user == null) {
            return GlobalFunction::sendSimpleResponse(false, 'User does not exists!');
        }

        // make sure there is already a chat activity
        $chatActivity = ChatActivity::where(
            [
                "user_id" => $request->user_id,
                "doctor_id" => $request->doctor_id
            ]
        )->first();
        if ($chatActivity != null) {
            return GlobalFunction::sendSimpleResponse(false, 'Chat activity already exists');
        } else {
            // Create new chat activity
            ChatActivity::create([
                "user_id" => $request->user_id,
                "doctor_id" => $request->doctor_id,
                "status"    => Constants::chatActivityActive,
            ]);

            // Send Push to user
            $title = "Reconnected with " . $doctor->name;
            $message = "You have reconnected with the doctor!";
            GlobalFunction::sendPushToUser($title, $message, $user);

            return GlobalFunction::sendSimpleResponse(true, 'Reconnection successful');
        }
    }

    public function pauseCommunication(Request $request)
    {
        $rules = [
            'doctor_id' => 'required',
            'user_id' => 'required'
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

        $user = User::where('id', $request->user_id)->first();
        if ($user == null) {
            return GlobalFunction::sendSimpleResponse(false, 'User does not exists!');
        }

        // make sure there is already a chat activity
        $chatActivity = ChatActivity::where(
            [
                "user_id" => $request->user_id,
                "doctor_id" => $request->doctor_id
            ]
        )->first();
        if ($chatActivity != null) {
            $chatActivity->status = Constants::chatActivityPause;
            $chatActivity->save();

            // Send Push to user
            $title = $doctor->name . " paused communication";
            $message = $doctor->name . " has paused communication, you won't be able to message with the doctor at the moment";
            $notifyData = [
                'type' => Constants::notifyAppointment . '',
                'id' => $chatActivity->id . ''
            ];
            GlobalFunction::sendPushToUser($title, $message, $user, $notifyData);

            return GlobalFunction::sendSimpleResponse(true, 'Chat activities updated');
        } else {
            return response()->json(['status' => false, 'message' => "Sorry, you can't pause the chat!"]);
        }
    }

    public function resumeCommunication(Request $request)
    {
        $rules = [
            'doctor_id' => 'required',
            'user_id' => 'required'
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

        $user = User::where('id', $request->user_id)->first();
        if ($user == null) {
            return GlobalFunction::sendSimpleResponse(false, 'User does not exists!');
        }

        // make sure there is already a chat activity
        $chatActivity = ChatActivity::where(
            [
                "user_id" => $request->user_id,
                "doctor_id" => $request->doctor_id
            ]
        )->first();
        if ($chatActivity != null) {
            $chatActivity->status = Constants::chatActivityActive;
            $chatActivity->save();


            // Send Push to user
            $title = $doctor->name . " resume communication";
            $message = $doctor->name . " has resume communication, now you can continue chatting with the doctor";
            $notifyData = [
                'type' => Constants::notifyAppointment . '',
                'id' => $chatActivity->id . ''
            ];
            GlobalFunction::sendPushToUser($title, $message, $user, $notifyData);

            return GlobalFunction::sendSimpleResponse(true, 'Chat activities updated');
        } else {
            return response()->json(['status' => false, 'message' => "Sorry, you can't resume the chat!"]);
        }
    }

    public function blockCommunication(Request $request)
    {
        $rules = [
            'doctor_id' => 'required',
            'user_id' => 'required'
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

        $user = User::where('id', $request->user_id)->first();
        if ($user == null) {
            return GlobalFunction::sendSimpleResponse(false, 'User does not exists!');
        }

        // make sure there is already a chat activity
        $chatActivity = ChatActivity::where(
            [
                "user_id" => $request->user_id,
                "doctor_id" => $request->doctor_id
            ]
        )->first();
        if ($chatActivity != null) {
            $chatActivity->status = Constants::chatActivityBlocked;
            $chatActivity->save();

            // Send Push to user
            $title = $doctor->name . " Blocked Communication";
            $message = $doctor->name . " has blocked communication, you won't be able to send messages with the doctor at the moment";
            $notifyData = [
                'type' => Constants::notifyAppointment . '',
                'id' => $chatActivity->id . ''
            ];
            GlobalFunction::sendPushToUser($title, $message, $user, $notifyData);

            return GlobalFunction::sendSimpleResponse(true, 'Chat activities updated');
        } else {
            return response()->json(['status' => false, 'message' => "Sorry, you can't block the chat!"]);
        }
    }


    public function fetchRandomDoctor(Request $request)
    {
        $rules = [
            'user_id' => 'required',
            'category_id' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }

        $user = User::where('id', $request->user_id)->first();
        if ($user == null) {
            return GlobalFunction::sendSimpleResponse(false, 'User does not exists!');
        }

        try {
            // check if there is already a connection between the doctor and user then send that same doctor else random
            $connection = Connection::where(["user_id" => $request->user_id,  'request_by' => 'doctor', 'status' => Constants::connectionPlacedPending])
                ->first();
            if ($connection != null) {
                $doctor = Doctors::find($connection->doctor_id);
                if ($request->category_id == $doctor->category_id) {
                    return GlobalFunction::sendDataResponse(true, 'Doctor Data fetched successfully', $doctor);
                }
            }
            $doctor = Doctors::where("category_id", $request->category_id)->with(['category'])->inRandomOrder()->first();
            return GlobalFunction::sendDataResponse(true, 'Doctor Data fetched successfully', $doctor);
        } catch (Exception $e) {
            return response()->json(['status' => false, 'message' => "There was an error fetching random doctors. Try again later"]);
        }
    }


    public function fetchRandomUser(Request $request)
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

        try {
            // check if there is already a connection between the user and doctor then send that same user else random
            $connection = Connection::where(["doctor_id" => $request->doctor_id, 'request_by' => 'doctor', 'status' => Constants::connectionPlacedPending])
                ->first();
            if ($connection != null) {
                $user = User::find($connection->user_id);
                return GlobalFunction::sendDataResponse(true, 'User Data fetched successfully', $user);
            }
            $user = User::inRandomOrder()->first();
            return GlobalFunction::sendDataResponse(true, 'User Data fetched successfully', $user);
        } catch (Exception $e) {
            return response()->json(['status' => false, 'message' => "There was an error fetching random users. Try again later"]);
        }
    }


    public function updateServiceCharge(Request $request)
    {
        $rules = [
            'connection_id' => 'required|exists:connections,id',
            'service_amount' => 'required|numeric',
            'extra_charge' =>   'nullable|numeric',
            'discount_amount' => 'nullable|numeric',
            'tax_amount' => 'nullable|numeric',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }


        try {

            $serviceAmount = $request->service_amount;
            $discountAmount = $request->discount_amount ?? 0;
            $taxAmount = $request->tax_amount ?? 0;

            // Auto calculations
            $subTotal = ($serviceAmount - $discountAmount) + ($request->extra_charge ?? 0);
            $payableAmount = $subTotal + $taxAmount + ($request->extra_charge ?? 0);

            $updateData = [
                "service_amount"  => $serviceAmount,
                "discount_amount" => $discountAmount,
                "extra_charge"    => ($request->extra_charge  ?? 0),
                "total_tax_amount" => $taxAmount,
                "subtotal"        => $subTotal,
                "payable_amount"  => $payableAmount,
            ];


            Connection::whereId($request->connection_id)->update($updateData);

            return response()->json([
                'status' => true,
                'message' => 'Service charge updated successfully',
                'data' => $updateData
            ]);
        } catch (Exception $e) {
            return response()->json(['status' => false, 'message' => "Oops, there was an error"]);
        }
    }

    public function updateConnectionPayment(Request $request)
    {
        $rules = [
            'connection_id' => 'required|exists:connections,id',
            'amount'         => 'required|numeric',
            'user_id'        => 'required|exists:users,id',
            'payment_method' => 'required|string',  // e.g., card, transfer, cash
            'currency'       => 'nullable|string', // default could be 'NGN' or 'USD'
            'expiry_date'    => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }

        try {
            DB::beginTransaction();

            // Create payment record
            $payment = ConnectionPayment::create([
                'connection_id' => $request->connection_id,
                'user_id'        => $request->user_id,
                'amount'         => $request->amount,
                'payment_method' => $request->payment_method,
                'currency'       => $request->currency ?? 'NGN',
                'status'         => 'success', // assuming payment is successful
                'paid_at'        => now(),
            ]);

            // Update appointment status
            Connection::whereId($request->connection_id)->update([
                'payment_status' => 'success',
                'expiry_date'    => $request->expiry_date,
            ]);

            DB::commit();

            return response()->json([
                'status'  => true,
                'message' => 'Payment recorded successfully',
                'data'    => $payment,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status'  => false,
                'message' => "Oops, there was an error: " . $e->getMessage(),
            ]);
        }
    }
}