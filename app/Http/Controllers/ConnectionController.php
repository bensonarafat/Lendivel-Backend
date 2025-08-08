<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\Doctors;
use App\Models\Constants;
use App\Models\Connection;
use Illuminate\Http\Request;
use App\Models\GlobalFunction;
use Illuminate\Support\Facades\Validator;

class ConnectionController extends Controller
{

    public function isConnected(Request $request)
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

        // make sure they are no connection btw this user and the doctor
        $connection = Connection::where(['user_id' => $request->user_id, 'doctor_id' => $request->doctor_id])
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

        // make sure they are no connection btw this user and the doctor
        $connection = Connection::where(['user_id' => $request->user_id, 'doctor_id' => $request->doctor_id])
            ->first();
        if ($connection == null) {
            return GlobalFunction::sendSimpleResponse(false, 'You can request connection to this user');
        }
        try {
            $connection = new Connection();
            $connection->doctor_id = $request->doctor_id;
            $connection->user_id = $request->user_id;
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

        if ($connection->status == Constants::orderPlacedPending) {
            $connection->status = Constants::orderAccepted;
            $connection->save();

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
}
