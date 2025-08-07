<?php

namespace App\Http\Controllers;

use App\Models\Constants;
use App\Models\Doctors;
use App\Models\GlobalFunction;
use App\Models\GlobalSettings;
use App\Models\ReelComments;
use App\Models\ReelLikes;
use App\Models\ReelReports;
use App\Models\Reels;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

use function Psy\debug;

class ReelController extends Controller
{
    //

    function fetchReelByIdDoctor(Request $request){

        $rules = [
            'doctor_id' => 'required',
            'reel_id' => 'required',
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

        $reel = Reels::where('id', $request->reel_id)->withCount(['comments','likes'])->with(['doctor'])->first();
        if ($reel == null) {
            return GlobalFunction::sendSimpleResponse(false, 'Reel does not exists!');
        }

        $reel->is_liked = false;
        $reelLike = ReelLikes::where('reel_id', $reel->id)->where('doctor_id', $doctor->id)->first();
        if($reelLike != null){
            $reel->is_liked = true;
        }

        return GlobalFunction::sendDataResponse(true, 'reel fetch successfully', $reel);

    }
    function fetchReelByIdPatient(Request $request){

        $rules = [
            'user_id' => 'required',
            'reel_id' => 'required',
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

        $reel = Reels::where('id', $request->reel_id)->withCount(['comments','likes'])->with(['doctor'])->first();
        if ($reel == null) {
            return GlobalFunction::sendSimpleResponse(false, 'Reel does not exists!');
        }

        $reel->is_liked = false;
        $reelLike = ReelLikes::where('reel_id', $reel->id)->where('user_id', $user->id)->first();
        if($reelLike != null){
            $reel->is_liked = true;
        }

        return GlobalFunction::sendDataResponse(true, 'reel fetch successfully', $reel);

    }

    function deleteReelReport($reportId){
        ReelReports::where('id', $reportId)->delete();
        return GlobalFunction::sendSimpleResponse(true, 'reel report deleted successfully!');
    }

    function fetchAllReelsReportList(Request $request)
    {
        $totalData =  ReelReports::count();
        $rows = ReelReports::orderBy('id', 'DESC')->get();

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
            $result = ReelReports::offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
            $result =  ReelReports::where('description', 'LIKE', "%{$search}%")
                ->orwhere('reason', 'LIKE', "%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

            $totalFiltered = ReelReports::where('description', 'LIKE', "%{$search}%")
            ->orwhere('reason', 'LIKE', "%{$search}%")
            ->offset($start)
            ->limit($limit)
            ->orderBy($order, $dir)
            ->count();
        }
        $data = array();
        foreach ($result as $item) {
            $contentUrl = GlobalFunction::createMediaUrl($item->reel->video);
            $view = '<a href="" class="mt-2 btn btn-info text-white view-content" data-url=' . $contentUrl . ' rel=' . $item->id . ' >' . __("View") . '</a>';

            $thumbUrl = GlobalFunction::createMediaUrl($item->reel->thumb);
            $reelView = '<img src="' . $thumbUrl . '" width="60" height="90"><br>'.$view.'';

            $doctor = Doctors::find($item->reel->doctor_id);
            $doctorLabel = '<a href="' . route('viewDoctorProfile', $doctor->id) . '" >' . $doctor->name . '</a>';

            $reportedBy = '';
            if($item->report_by == 1){
                $reportedBy = '<span  class="badge bg-primary text-white ">' . __("Doctor") . '</span> <br><a href="' . route('viewDoctorProfile', $item->doctor_id) . '" >' . $item->doctor->name . '</a>';
            }
            if($item->report_by == 0){

                $reportedBy =  '<span  class="badge bg-primary text-white ">' . __("User") . '</span> <br> <a href="' . route('viewUserProfile', $item->user_id) . '" >' . $item->user->fullname . '</a>';
            }
            $reportSummary = '<b><span>Reason : '.$item->reason.'</span></b><br><span>'.$item->description.'</span>';

            $deleteReel = '<a href="" class="mr-2 btn btn-danger text-white delete-reel" rel=' . $item->reel_id . ' >' . __("Delete Reel") . '</a>';
            $deleteReport = '<a href="" class="mr-2 btn btn-success text-white delete-reel-report" rel=' . $item->id . ' >' . __("Delete Report") . '</a>';

            $action = $deleteReport  . $deleteReel;

            $data[] = array(
                $reelView,
                $reportSummary,
                $doctorLabel,
                $reportedBy,
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

    function reports(){
        return view('reports');
    }

    function fetchDoctorReels_Admin(Request $request)
    {
        $totalData =  Reels::where('doctor_id', $request->doctorId)->count();
        $rows = Reels::where('doctor_id', $request->doctorId)->orderBy('id', 'DESC')->get();

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
            $result = Reels::where('doctor_id', $request->doctorId)
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
            $result =  Reels::where('doctor_id', $request->doctorId)
                ->where('description', 'LIKE', "%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

            $totalFiltered = Reels::where('doctor_id', $request->doctorId)
            ->where('description', 'LIKE', "%{$search}%")
            ->offset($start)
            ->limit($limit)
            ->orderBy($order, $dir)
            ->count();
        }
        $data = array();
        foreach ($result as $item) {
            $contentUrl = GlobalFunction::createMediaUrl($item->video);

            $thumbUrl = GlobalFunction::createMediaUrl($item->thumb);
            $thumb = '<img src="' . $thumbUrl . '" width="60" height="90">';


            $stats = '<span>Views : '.$item->views.'</span><br><span>Likes : '.$item->likes->count().'</span><br><span>Comments : '.$item->comments->count().'</span>';

            $view = '<a href="" class="mr-2 btn btn-info text-white view-content" data-url=' . $contentUrl . ' rel=' . $item->id . ' >' . __("View") . '</a>';

            $delete = '<a href="" class="mr-2 btn btn-danger text-white delete" rel=' . $item->id . ' >' . __("Delete") . '</a>';
            $action = $view  . $delete;


            $data[] = array(
                $thumb,
                $item->description,
                $stats,
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
    function fetchAllReelsList(Request $request)
    {
        $totalData =  Reels::count();
        $rows = Reels::orderBy('id', 'DESC')->get();

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
            $result = Reels::offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
            $result =  Reels::where('description', 'LIKE', "%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

            $totalFiltered = Reels::where('description', 'LIKE', "%{$search}%")
            ->offset($start)
            ->limit($limit)
            ->orderBy($order, $dir)
            ->count();
        }
        $data = array();
        foreach ($result as $item) {
            $contentUrl = GlobalFunction::createMediaUrl($item->video);

            $thumbUrl = GlobalFunction::createMediaUrl($item->thumb);
            $thumb = '<img src="' . $thumbUrl . '" width="60" height="90">';

            $doctor = '<a href="' . route('viewDoctorProfile', $item->doctor->id) . '" >' . $item->doctor->name . '</a>';

            $stats = '<span>Views : '.$item->views.'</span><br><span>Likes : '.$item->likes->count().'</span><br><span>Comments : '.$item->comments->count().'</span>';

            $view = '<a href="" class="mr-2 btn btn-info text-white view-content" data-url=' . $contentUrl . ' rel=' . $item->id . ' >' . __("View") . '</a>';

            $delete = '<a href="" class="mr-2 btn btn-danger text-white delete" rel=' . $item->id . ' >' . __("Delete") . '</a>';
            $action = $view  . $delete;


            $data[] = array(
                $thumb,
                $item->description,
                $doctor,
                $stats,
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

    function deleteReelAdmin($id)
    {
        $item = Reels::find($id);

        ReelComments::where('reel_id', $id)->delete();
        ReelLikes::where('reel_id', $id)->delete();
        ReelReports::where('reel_id', $id)->delete();

        GlobalFunction::deleteFile($item->video);
        GlobalFunction::deleteFile($item->thumb);

        $item->delete();

        return GlobalFunction::sendSimpleResponse(true, 'reel deleted successfully!');
    }

    function reels(){
        return view('reels');
    }
    function reportReel(Request $request){
        $rules = [
            'reel_id' => 'required',
            'reason' => 'required',
            'description' => 'required',
            'report_by' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }
        $reel = Reels::where('id', $request->reel_id)->first();
        if ($reel == null) {
            return GlobalFunction::sendSimpleResponse(false, 'Reel does not exists!');
        }

        if($request->report_by == 1){ // Doctor
            if(!$request->has('doctor_id')){
                return GlobalFunction::sendSimpleResponse(false, 'doctor id required.');
            }
            $doctor = Doctors::where('id', $request->doctor_id)->first();
            if ($doctor == null) {
                return GlobalFunction::sendSimpleResponse(false, 'Doctor does not exists!');
            }
            $report = ReelReports::where('reel_id', $reel->id)->where('doctor_id', $doctor->id)->first();
            if($report != null){
                return GlobalFunction::sendSimpleResponse(false, 'report submitted already!');
            }

        }
        if($request->report_by == 0){ // User
            if(!$request->has('user_id')){
                return GlobalFunction::sendSimpleResponse(false, 'user id required.');
            }
            $user = Users::where('id', $request->user_id)->first();
            if ($user == null) {
                return GlobalFunction::sendSimpleResponse(false, 'User does not exists !');
            }
            $report = ReelReports::where('reel_id', $reel->id)->where('user_id', $user->id)->first();
            if($report != null){
                return GlobalFunction::sendSimpleResponse(false, 'report submitted already!');
            }
        }

        $report = new ReelReports();
        $report->reason = $request->reason;
        $report->description = $request->description;
        $report->reel_id = $request->reel_id;
        $report->report_by = $request->report_by;
        if($request->report_by == 0){
            $report->user_id = $request->user_id;
        }
        if($request->report_by == 1){
            $report->doctor_id = $request->doctor_id;
        }
        $report->save();

        return GlobalFunction::sendSimpleResponse(true, 'report submitted successfully');

    }

    function fetchSavedReels(Request $request){
        $rules = [
            'ids'=> 'required',
            'type'=> 'required', // 0=user 1=doctor
            'id'=> 'required', //User or doctor id
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }
        $idsArray = explode(',', $request->ids);
        $reels = Reels::whereIn('id', $idsArray)->with(['doctor'])->withCount(['likes','comments'])->get();

        foreach($reels as $reel){
            $reel->is_liked = false;
            if($request->type == 1){
                $reelLike = ReelLikes::where('reel_id', $reel->id)->where('doctor_id', $request->id)->first();
            }else{
                $reelLike = ReelLikes::where('reel_id', $reel->id)->where('user_id', $request->id)->first();
            }
            if($reelLike != null){
                $reel->is_liked = true;
            }
        }

        return GlobalFunction::sendDataResponse(true,'saved reels fetched successfully', $reels);
    }

    function fetchMyReels_DoctorApp(Request $request){

        $rules = [
            'doctor_id'=> 'required',
            'start'=> 'required',
            'count'=> 'required',
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

        $reels = Reels::where('doctor_id', $doctor->id)
                ->with(['doctor'])
                ->withCount(['comments','likes'])
                ->orderBy('id', 'DESC')
                ->offset($request->start)
                ->limit($request->count)
                ->get();

        foreach($reels as $reel){

            $reelLike = ReelLikes::where('reel_id', $reel->id)->where('doctor_id', $doctor->id)->first();
            if($reelLike != null){
                $reel->is_liked = true;
            }else{
                $reel->is_liked = false;
            }
        }

        return GlobalFunction::sendDataResponse(true,'my (doctors) reels fetched successfully', $reels);

    }
    function fetchDoctorReels(Request $request){

        $rules = [
            'doctor_id'=> 'required',
            'user_id'=> 'required',
            'start'=> 'required',
            'count'=> 'required',
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

        $user = Users::where('id', $request->user_id)->first();
        if ($user == null) {
            return GlobalFunction::sendSimpleResponse(false, 'User does not exists !');
        }

        $reels = Reels::where('doctor_id', $doctor->id)
                ->with(['doctor'])
                ->withCount(['comments','likes'])
                ->orderBy('id', 'DESC')
                ->offset($request->start)
                ->limit($request->count)
                ->get();

        foreach($reels as $reel){

            $reelLike = ReelLikes::where('reel_id', $reel->id)->where('user_id', $user->id)->first();
            if($reelLike != null){
                $reel->is_liked = true;
            }else{
                $reel->is_liked = false;
            }
        }

        return GlobalFunction::sendDataResponse(true,'doctor reels fetched successfully', $reels);

    }

    function deleteReel(Request $request){
        $rules = [
            'reel_id' => 'required',
            'doctor_id'=> 'required'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }
        $reel = Reels::where('id', $request->reel_id)->first();
        if ($reel == null) {
            return GlobalFunction::sendSimpleResponse(false, 'Reel does not exists!');
        }

        $doctor = Doctors::where('id', $request->doctor_id)->first();
        if ($doctor == null) {
            return GlobalFunction::sendSimpleResponse(false, 'Doctor does not exists!');
        }
        if($reel->doctor_id != $doctor->id){
            return GlobalFunction::sendSimpleResponse(false, 'this reel not owned by this doctor.');
        }

        ReelComments::where('reel_id', $reel->id)->delete();
        ReelLikes::where('reel_id', $reel->id)->delete();
        ReelReports::where('reel_id', $reel->id)->delete();

        GlobalFunction::deleteFile($reel->video);
        GlobalFunction::deleteFile($reel->thumb);

        $reel->delete();

        return GlobalFunction::sendSimpleResponse(true, 'reel deleted successfully');
    }

    function likeReelPatientApp(Request $request){
        $rules = [
            'reel_id' => 'required',
            'user_id'=> 'required'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }
        $reel = Reels::where('id', $request->reel_id)->first();
        if ($reel == null) {
            return GlobalFunction::sendSimpleResponse(false, 'Reel does not exists!');
        }

        $user = Users::where('id', $request->user_id)->first();
        if ($user == null) {
            return GlobalFunction::sendSimpleResponse(false, 'User does not exists !');
        }

        $reelLike = ReelLikes::where('reel_id', $reel->id)->where('user_id', $user->id)->first();
        if($reelLike != null){
            $reelLike->delete();
            return GlobalFunction::sendSimpleResponse(true, 'Reel unliked success');
        }

        $reelLike = new ReelLikes();
        $reelLike->like_by = 0; //0=user 1=doctor
        $reelLike->reel_id = $request->reel_id;
        $reelLike->user_id = $request->user_id;
        $reelLike->save();


        // Send Push To doctor who created reel
        $title =  "You got a like on your Reel!";
        $message =  $user->fullname." has liked your reel!";
        $notifyData = [
            'type'=> Constants::notifyReel.'',
            'id'=> $reel->id.''
        ];
        GlobalFunction::sendPushToDoctor($title, $message, $reel->doctor,$notifyData);

        return GlobalFunction::sendSimpleResponse(true,'reel liked by user successfully');
    }
    function likeReelDoctorApp(Request $request){
        $rules = [
            'reel_id' => 'required',
            'doctor_id'=> 'required'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }
        $reel = Reels::where('id', $request->reel_id)->first();
        if ($reel == null) {
            return GlobalFunction::sendSimpleResponse(false, 'Reel does not exists!');
        }

        $doctor = Doctors::where('id', $request->doctor_id)->first();
        if ($doctor == null) {
            return GlobalFunction::sendSimpleResponse(false, 'Doctor does not exists!');
        }

        $reelLike = ReelLikes::where('reel_id', $reel->id)->where('doctor_id', $doctor->id)->first();
        if($reelLike != null){
            $reelLike->delete();
            return GlobalFunction::sendSimpleResponse(true, 'Reel unliked successful!');
        }

        $reelLike = new ReelLikes();
        $reelLike->like_by = 1; //0=user 1=doctor
        $reelLike->reel_id = $request->reel_id;
        $reelLike->doctor_id = $request->doctor_id;
        $reelLike->save();

        if($request->doctor_id != $reel->doctor_id){
            // Send Push To doctor who created reel
            $title =  "You got a like on your Reel!";
            $message =  $doctor->name." has liked your reel!";
            $notifyData = [
                'type'=> Constants::notifyReel.'',
                'id'=> $reel->id.''
            ];
            GlobalFunction::sendPushToDoctor($title, $message, $reel->doctor,$notifyData);
        }

        return GlobalFunction::sendSimpleResponse(true,'reel liked by doctor successfully');
    }

    function increaseReelViewCount(Request $request){
        $rules = [
            'reel_id' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }
        $reel = Reels::where('id', $request->reel_id)->first();
        if ($reel == null) {
            return GlobalFunction::sendSimpleResponse(false, 'reel does not exists!');
        }

        $reel->views += 1;
        $reel->save();

        return GlobalFunction::sendSimpleResponse(true,'view increased successfully');
    }

    function fetchReelComments(Request $request){
        $rules = [
            'reel_id' => 'required',
            'start' => 'required',
            'count' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }
        $reel = Reels::where('id', $request->reel_id)->first();
        if ($reel == null) {
            return GlobalFunction::sendSimpleResponse(false, 'reel does not exists!');
        }
        $comments = ReelComments::where('reel_id', $reel->id)
                    ->with(['user','doctor'])
                    ->orderBy('id','DESC')
                    ->offset($request->start)
                    ->limit($request->count)
                    ->get();

        return GlobalFunction::sendDataResponse(true,'comments fetched successfully', $comments);
    }

    function addCommentOnReelPatientApp(Request $request){
        $rules = [
            'user_id' => 'required',
            'reel_id' => 'required',
            'comment' => 'required',
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

        $reel = Reels::where('id', $request->reel_id)->first();
        if ($reel == null) {
            return GlobalFunction::sendSimpleResponse(false, 'reel does not exists!');
        }

        $reelComment = new ReelComments();
        $reelComment->reel_id = $reel->id;
        $reelComment->user_id = $user->id;
        $reelComment->comment_by = 0; //0=user 1=doctor
        $reelComment->comment = $request->comment;
        $reelComment->save();

        // Send Push To doctor who created reel
        $title =  $user->fullname." has commented on your reel!";
        $message = $reelComment->comment;
        $notifyData = [
            'type'=> Constants::notifyReel.'',
            'id'=> $reel->id.''
        ];
        GlobalFunction::sendPushToDoctor($title, $message, $reel->doctor,$notifyData);

        $comment = ReelComments::where('id', $reelComment->id)->with(['doctor','user'])->first();

        return GlobalFunction::sendDataResponse(true,'comment added successfully', $comment);

    }
    function addCommentOnReelDoctorApp(Request $request){
        $rules = [
            'doctor_id' => 'required',
            'reel_id' => 'required',
            'comment' => 'required',
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
        $reel = Reels::where('id', $request->reel_id)->first();
        if ($reel == null) {
            return GlobalFunction::sendSimpleResponse(false, 'reel does not exists!');
        }
        $reelComment = new ReelComments();
        $reelComment->reel_id = $reel->id;
        $reelComment->doctor_id = $doctor->id;
        $reelComment->comment_by = 1; //0=user 1=doctor
        $reelComment->comment = $request->comment;
        $reelComment->save();

        if($request->doctor_id != $reel->doctor_id){
            // Send Push To doctor who created reel
            $title = $doctor->name." has commented on your reel!";
            $message = $reelComment->comment;
            $notifyData = [
                'type'=> Constants::notifyReel.'',
                'id'=> $reel->id.''
            ];
            GlobalFunction::sendPushToDoctor($title, $message, $reel->doctor, $notifyData);
        }

        $reelComment = ReelComments::where('id', $reelComment->id)->with(['user','doctor'])->first();

        return GlobalFunction::sendDataResponse(true,'comment added successfully', $reelComment);

    }

    function fetchReelsPatientApp(Request $request){

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

        $query = Reels::query();

        if($request->has('category_id')){
            $doctorIds = Doctors::where([
                'category_id'=> $request->category_id,
                'status'=> Constants::statusDoctorApproved,
            ])->pluck('id');

            $query = $query->whereIn('doctor_id', $doctorIds);
        }

        $reels = $query->withCount(['comments','likes'])->with(['doctor'])->inRandomOrder()->get();

        foreach($reels as $reel){

            $reelLike = ReelLikes::where('reel_id', $reel->id)->where('user_id', $user->id)->first();
            if($reelLike != null){
                $reel->is_liked = true;
            }else{
                $reel->is_liked = false;
            }
        }

        return GlobalFunction::sendDataResponse(true,'reels fetched succesfully', $reels);
    }
    function fetchReelsDoctorApp(Request $request){

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

        $query = Reels::query();

        if($request->has('category_id')){
            $doctorIds = Doctors::where([
                'category_id'=> $request->category_id,
                'status'=> Constants::statusDoctorApproved,
            ])->pluck('id');

            $query = $query->whereIn('doctor_id', $doctorIds);
        }

        $reels = $query->withCount(['comments','likes'])->with(['doctor'])->inRandomOrder()->get();

        foreach($reels as $reel){

            $reelLike = ReelLikes::where('reel_id', $reel->id)->where('doctor_id', $doctor->id)->first();
            if($reelLike != null){
                $reel->is_liked = true;
            }else{
                $reel->is_liked = false;
            }
        }

        return GlobalFunction::sendDataResponse(true,'reels fetched succesfully', $reels);
    }

    function uploadReelByDoctor(Request $request){
        $rules = [
            'doctor_id' => 'required',
            'video' => 'required',
            'thumb' => 'required',
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

        $reel = new Reels();
        $reel->doctor_id = $doctor->id;
        $reel->video = GlobalFunction::saveFileAndGivePath($request->video);
        $reel->thumb = GlobalFunction::saveFileAndGivePath($request->thumb);
        if($request->has('description')){
            $reel->description = $request->description;
        }
        $reel->save();

        $reel = Reels::where('id', $reel->id)->withCount(['comments','likes'])->with(['doctor'])->first();



        return GlobalFunction::sendDataResponse(true,'reel uploaded successfully', $reel);
    }
}
