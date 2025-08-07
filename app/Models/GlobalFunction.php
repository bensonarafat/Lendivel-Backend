<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Google\Client;

class GlobalFunction extends Model
{
    use HasFactory;

    public static function deleteAppointmentScheduledReminders($appointment){
        ScheduledReminders::where('appointment_id', $appointment->id)->delete();
    }

    public static function decodeDoctorsMobileNumber($doctor){
        $digits = explode(' ', $doctor->mobile_number)[0];
        $number = $doctor->country_code." ".$digits;
        return $number;
    }

    public static function roundNumber($number)
    {
        return round($number, 2);
    }

    public static function sendPushNotificationToUsers($title, $message)
    {

        $client = new Client();
        $client->setAuthConfig('googleCredentials.json');
        $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
        $client->fetchAccessTokenWithAssertion();
        $accessToken = $client->getAccessToken();
        $accessToken = $accessToken['access_token'];

        $contents = File::get(base_path('googleCredentials.json'));
        $json = json_decode(json: $contents, associative: true);

        $url = 'https://fcm.googleapis.com/v1/projects/'.$json['project_id'].'/messages:send';
        $notificationArray = array('title' => $title, 'body' => $message);

        $fields = array(
            'message'=> [
                'topic'=> 'patient',
                'notification' => $notificationArray,
            ]
        );

        $headers = array(
            'Content-Type:application/json',
            'Authorization:Bearer ' . $accessToken
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        // print_r(json_encode($fields));
        $result = curl_exec($ch);
        Log::debug($result);

        if ($result === FALSE) {
            die('FCM Send Error: ' . curl_error($ch));
        }
        curl_close($ch);

        if ($result) {
            return json_encode(['status' => true, 'message' => 'Notification sent successfully']);
        } else {
            return json_encode(['status' => false, 'message ' => 'Not sent!']);
        }
    }
    public static function sendPushNotificationToDoctors($title, $message)
    {
        $client = new Client();
        $client->setAuthConfig('googleCredentials.json');
        $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
        $client->fetchAccessTokenWithAssertion();
        $accessToken = $client->getAccessToken();
        $accessToken = $accessToken['access_token'];

        $contents = File::get(base_path('googleCredentials.json'));
        $json = json_decode(json: $contents, associative: true);

        $url = 'https://fcm.googleapis.com/v1/projects/'.$json['project_id'].'/messages:send';
        $notificationArray = array('title' => $title, 'body' => $message);

        $fields = array(
            'message'=> [
                'topic'=> 'doctor',
                'notification' => $notificationArray,
            ]
        );

        $headers = array(
            'Content-Type:application/json',
            'Authorization:Bearer ' . $accessToken
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        // print_r(json_encode($fields));
        $result = curl_exec($ch);
        Log::debug($result);

        if ($result === FALSE) {
            die('FCM Send Error: ' . curl_error($ch));
        }
        curl_close($ch);

        if ($result) {
            return json_encode(['status' => true, 'message' => 'Notification sent successfully']);
        } else {
            return json_encode(['status' => false, 'message ' => 'Not sent!']);
        }
    }

    public static function returnAppointmentStatus($status)
    {
        $statusPill = "";
        switch ($status) {
            case (Constants::orderPlacedPending):
                $statusPill = '<span class="badge bg-warning text-white">' . __('Pending') . '</span>';
                break;
            case (Constants::orderAccepted):
                $statusPill = '<span class="badge bg-primary text-white">' . __('Accepted') . '</span>';
                break;
            case (Constants::orderCompleted):
                $statusPill = '<span class="badge bg-success text-white">' . __('Completed') . '</span>';
                break;
            case (Constants::orderDeclined):
                $statusPill = '<span class="badge bg-danger text-white">' . __('Declined') . '</span>';
                break;
            case (Constants::orderCancelled):
                $statusPill = '<span class="badge bg-danger text-white">' . __('Cancelled') . '</span>';
                break;
        }
        return $statusPill;
    }

    public static function sendSimpleResponse($status, $msg)
    {
        return response()->json(['status' => $status, 'message' => $msg]);
    }
    public static function sendDataResponse($status, $msg, $data)
    {
        return response()->json(['status' => $status, 'message' => $msg, 'data' => $data]);
    }

    public static function generateUserFullData($id)
    {
        $user = Users::where('id', $id)
            ->with(['patients'])
            ->first();

        return $user;
    }
    public static function generateDoctorFullData($id)
    {
        $doctor = Doctors::where('id', $id)
            ->with([
                'services',
                'experience',
                'expertise',
                'serviceLocations',
                'awards',
                'slots',
                'holidays',
                'bankAccount',
            ])
            ->first();

        return $doctor;
    }

    public static function sendPushToDoctor($title, $message, $doctor, $data = null)
    {

        if ($doctor->is_notification == 1) {
            $client = new Client();
            $client->setAuthConfig('googleCredentials.json');
            $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
            $client->fetchAccessTokenWithAssertion();
            $accessToken = $client->getAccessToken();
            $accessToken = $accessToken['access_token'];

            $contents = File::get(base_path('googleCredentials.json'));
            $json = json_decode(json: $contents, associative: true);

            $url = 'https://fcm.googleapis.com/v1/projects/'.$json['project_id'].'/messages:send';
            $notificationArray = array('title' => $title, 'body' => $message);

            if($doctor->device_type == Constants::deviceIOS){
                $fields = array(
                    'message'=> [
                        'token'=> $doctor->device_token,
                        'notification' => $notificationArray,
                        'data'=> $data,
                        "apns"=> [
                        "payload"=> [
                            "aps"=> [
                                "sound"=> "default"
                            ],
                            "content-available"=> true
                            ]
                        ],
                    ]
                );
            }else{
                $data = array_merge($data, $notificationArray);
                $fields = array(
                    'message'=> [
                        'token'=> $doctor->device_token,
                        'data'=> $data,
                        "apns"=> [
                        "payload"=> [
                            "aps"=> [
                                "sound"=> "default"
                            ],
                            "content-available"=> true
                            ]
                        ],
                    ]
                );
            }
            // Log::debug($fields);

            $headers = array(
                'Content-Type:application/json',
                'Authorization:Bearer ' . $accessToken
            );
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
            // print_r(json_encode($fields));
            $result = curl_exec($ch);
            Log::debug($result);

            if ($result === FALSE) {
                die('FCM Send Error: ' . curl_error($ch));
            }
            curl_close($ch);

            if ($result) {
                return json_encode(['status' => true, 'message' => 'Notification sent successfully']);
            } else {
                return json_encode(['status' => false, 'message ' => 'Not sent!']);
            }
        }
    }
    public static function sendPushToUser($title, $message, $user, $data = null)
    {
        if ($user->is_notification == 1) {
            $client = new Client();
            $client->setAuthConfig('googleCredentials.json');
            $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
            $client->fetchAccessTokenWithAssertion();
            $accessToken = $client->getAccessToken();
            $accessToken = $accessToken['access_token'];

            $contents = File::get(base_path('googleCredentials.json'));
            $json = json_decode(json: $contents, associative: true);

            $url = 'https://fcm.googleapis.com/v1/projects/'.$json['project_id'].'/messages:send';
            $notificationArray = array('title' => $title, 'body' => $message);

            if($user->device_type == Constants::deviceIOS){
                $fields = array(
                    'message'=> [
                        'token'=> $user->device_token,
                        'notification' => $notificationArray,
                        'data'=> $data,
                        "apns"=> [
                        "payload"=> [
                            "aps"=> [
                                "sound"=> "default"
                            ],
                            "content-available"=> true
                            ]
                        ],
                    ]
                );
            }else{
                $data = array_merge($data, $notificationArray);
                Log::debug($data);
                $fields = array(
                    'message'=> [
                        'token'=> $user->device_token,
                        'data'=> $data,
                        "apns"=> [
                        "payload"=> [
                            "aps"=> [
                                "sound"=> "default"
                            ],
                            "content-available"=> true
                            ]
                        ],
                    ]
                );
            }



            $headers = array(
                'Content-Type:application/json',
                'Authorization:Bearer ' . $accessToken
            );
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
            // print_r(json_encode($fields));
            $result = curl_exec($ch);
            Log::debug($result);

            if ($result === FALSE) {
                die('FCM Send Error: ' . curl_error($ch));
            }
            curl_close($ch);

            if ($result) {
                return json_encode(['status' => true, 'message' => 'Notification sent successfully']);
            } else {
                return json_encode(['status' => false, 'message ' => 'Not sent!']);
            }
        }
    }

    public static function createMediaUrl($media)
    {
        $url = env('FILES_BASE_URL') . $media;
        return $url;
    }

    public static function uploadFilToS3($request, $key)
    {
        $s3 = Storage::disk('s3');
        $file = $request->file($key);
        $fileName = time() . $file->getClientOriginalName();
        $fileName = str_replace(" ", "_", $fileName);
        $filePath = 'uploads/' . $fileName;
        $result =  $s3->put($filePath, file_get_contents($file), 'public-read');
        return $filePath;
    }

    public static function point2point_distance($lat1, $lon1, $lat2, $lon2, $unit = 'K', $radius)
    {
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);

        if ($unit == "K") {
            return (($miles * 1.609344) <= $radius);
        } else if ($unit == "N") {
            return ($miles * 0.8684);
        } else {
            return $miles;
        }
    }


    public static function detectPaymentGateway($gateway)
    {
        $name = "";
        switch ($gateway) {
            case (Constants::stripe):
                $name = 'Stripe';
                break;
            case (Constants::addedByAdmin):
                $name = 'Added By Admin';
                break;
            case (Constants::flutterWave):
                $name = 'Flutterwave';
                break;
            case (Constants::razorPay):
                $name = 'Razorpay';
                break;
            case (Constants::payStack):
                $name = 'Paystack';
                break;
            case (Constants::payPal):
                $name = 'PayPal';
                break;
            case (Constants::sslCommerze):
                $name = 'SSLCommerze';
                break;
        }

        return $name;
    }

    public static function cleanString($string)
    {
        return  str_replace(array('<', '>', '{', '}', '[', ']', '`'), '', $string);
    }

    public static function deleteFile($filename)
    {
        if ($filename != null && file_exists(storage_path('app/public/' . $filename))) {
            unlink(storage_path('app/public/' . $filename));
        }
    }

    public static function saveFileAndGivePath($file)
    {
        if ($file != null) {
            $path = $file->store('uploads');
            return $path;
        } else {
            return null;
        }
    }

    public static function formateTimeString($timeString)
    {
        if ($timeString != null) {
            return substr_replace($timeString, ":", 2, 0);
        }
        return "";
    }

    public static function generatePlatformEarningHistoryNumber()
    {
        $token =  rand(100000, 999999);

        $first = Constants::prefixPlatformEarningHistory;
        $first .= GlobalFunction::generateRandomString(3);
        $first .= $token;
        $first .= GlobalFunction::generateRandomString(3);
        $count = PlatformEarningHistory::where('earning_number', $first)->count();

        while ($count >= 1) {

            $token =  rand(100000, 999999);

            $first = GlobalFunction::generateRandomString(3);
            $first .= $token;
            $first .= GlobalFunction::generateRandomString(3);
            $count = PlatformEarningHistory::where('earning_number', $first)->count();
        }

        return $first;
    }
    public static function generateDoctorEarningHistoryNumber()
    {
        $token =  rand(100000, 999999);
        $first = Constants::prefixDoctorEarningHistory;
        $first .= GlobalFunction::generateRandomString(3);
        $first .= $token;
        $first .= GlobalFunction::generateRandomString(3);
        $count = DoctorEarningHistory::where('earning_number', $first)->count();

        while ($count >= 1) {
            $token =  rand(100000, 999999);
            $first = GlobalFunction::generateRandomString(3);
            $first .= $token;
            $first .= GlobalFunction::generateRandomString(3);
            $count = DoctorEarningHistory::where('earning_number', $first)->count();
        }

        return $first;
    }

    public static function generateDoctorNumber()
    {
        $token =  rand(100000, 999999);

        $first = Constants::prefixDoctorNumber;
        $first .= GlobalFunction::generateRandomString(3);
        $first .= $token;
        $first .= GlobalFunction::generateRandomString(3);
        $count = Doctors::where('doctor_number', $first)->count();

        while ($count >= 1) {
            $token =  rand(100000, 999999);
            $first = GlobalFunction::generateRandomString(3);
            $first .= $token;
            $first .= GlobalFunction::generateRandomString(3);
            $count = Doctors::where('doctor_number', $first)->count();
        }

        return $first;
    }

    public static function generateDoctorWithdrawRequestNumber()
    {
        $token =  rand(100000, 999999);
        $first = Constants::prefixDoctorWithDrawRequestNumber;
        $first .= GlobalFunction::generateRandomString(3);
        $first .= $token;
        $first .= GlobalFunction::generateRandomString(3);
        $count = DoctorPayoutHistory::where('request_number', $first)->count();

        while ($count >= 1) {

            $token =  rand(100000, 999999);
            $first = GlobalFunction::generateRandomString(3);
            $first .= $token;
            $first .= GlobalFunction::generateRandomString(3);
            $count = DoctorPayoutHistory::where('request_number', $first)->count();
        }

        return $first;
    }
    public static function generateUserWithdrawRequestNumber()
    {
        $token =  rand(100000, 999999);
        $first = Constants::prefixUserWithDrawRequestNumber;
        $first .= GlobalFunction::generateRandomString(3);
        $first .= $token;
        $first .= GlobalFunction::generateRandomString(3);
        $count = UserWithdrawRequest::where('request_number', $first)->count();

        while ($count >= 1) {

            $token =  rand(100000, 999999);
            $first = GlobalFunction::generateRandomString(3);
            $first .= $token;
            $first .= GlobalFunction::generateRandomString(3);
            $count = UserWithdrawRequest::where('request_number', $first)->count();
        }

        return $first;
    }
    public static function generateAppointmentNumber()
    {
        $token =  rand(100000, 999999);

        $first = Constants::prefixAppointmentNumber;
        $first .= GlobalFunction::generateRandomString(3);
        $first .= $token;
        $first .= GlobalFunction::generateRandomString(3);
        $count = Appointments::where('appointment_number', $first)->count();

        while ($count >= 1) {
            $token =  rand(100000, 999999);
            $first = GlobalFunction::generateRandomString(3);
            $first .= $token;
            $first .= GlobalFunction::generateRandomString(3);
            $count = Appointments::where('appointment_number', $first)->count();
        }

        return $first;
    }

    public static function addDoctorStatementEntry($doctorId, $appointmentNumber, $amount, $crOrDr, $type, $summary)
    {
        $stmt = new DoctorWalletStatements();
        $stmt->transaction_id = GlobalFunction::generateDoctorTransactionId();
        $stmt->doctor_id = $doctorId;
        $stmt->appointment_number = $appointmentNumber;
        $stmt->amount = $amount;
        $stmt->cr_or_dr = $crOrDr;
        $stmt->type = $type;
        $stmt->summary = $summary;
        $stmt->save();
    }
    public static function addUserStatementEntry($userId, $appointmentNumber, $amount, $crOrDr, $type, $summary)
    {
        $stmt = new UserWalletStatements();
        $stmt->transaction_id = GlobalFunction::generateTransactionId();
        $stmt->user_id = $userId;
        $stmt->appointment_number = $appointmentNumber;
        $stmt->amount = $amount;
        $stmt->cr_or_dr = $crOrDr;
        $stmt->type = $type;
        $stmt->summary = $summary;
        $stmt->save();
    }

    public static function generateDoctorTransactionId()
    {
        $token =  rand(100000, 999999);

        $first = Constants::prefixDoctorTransactionId;
        $first .= GlobalFunction::generateRandomString(3);
        $first .= $token;
        $first .= GlobalFunction::generateRandomString(3);
        $count = DoctorWalletStatements::where('transaction_id', $first)->count();

        while ($count >= 1) {

            $token =  rand(100000, 999999);

            $first = GlobalFunction::generateRandomString(3);
            $first .= $token;
            $first .= GlobalFunction::generateRandomString(3);
            $count = DoctorWalletStatements::where('transaction_id', $first)->count();
        }

        return $first;
    }
    public static function generateTransactionId()
    {
        $token =  rand(100000, 999999);
        $first = Constants::prefixUserTransactionId;
        $first .= GlobalFunction::generateRandomString(3);
        $first .= $token;
        $first .= GlobalFunction::generateRandomString(3);
        $count = UserWalletStatements::where('transaction_id', $first)->count();

        while ($count >= 1) {

            $token =  rand(100000, 999999);

            $first = GlobalFunction::generateRandomString(3);
            $first .= $token;
            $first .= GlobalFunction::generateRandomString(3);
            $count = UserWalletStatements::where('transaction_id', $first)->count();
        }

        return $first;
    }


    public static function generateRandomString($length)
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
