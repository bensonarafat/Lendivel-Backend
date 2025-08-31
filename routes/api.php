<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ConnectionController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\ReelController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\UsersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
//******************/ Users
Route::prefix('user')->group(function () {
    // Common
    Route::post('registerUser', [UsersController::class, 'registerUser'])->middleware('checkHeader');
    Route::post('updateUserDetails', [UsersController::class, 'updateUserDetails'])->middleware('checkHeader');
    Route::post('deleteUserAccount', [UsersController::class, 'deleteUserAccount'])->middleware('checkHeader');
    Route::post('fetchMyUserDetails', [UsersController::class, 'fetchMyUserDetails'])->middleware('checkHeader');
    Route::post('addPatient', [UsersController::class, 'addPatient'])->middleware('checkHeader');
    Route::post('editPatient', [UsersController::class, 'editPatient'])->middleware('checkHeader');
    Route::post('deletePatient', [UsersController::class, 'deletePatient'])->middleware('checkHeader');
    Route::post('fetchPatients', [UsersController::class, 'fetchPatients'])->middleware('checkHeader');
    Route::post('fetchFavoriteDoctors', [UsersController::class, 'fetchFavoriteDoctors'])->middleware('checkHeader');
    Route::post('fetchHomePageData', [UsersController::class, 'fetchHomePageData'])->middleware('checkHeader');
    Route::post('searchDoctor', [DoctorController::class, 'searchDoctor'])->middleware('checkHeader');
    Route::post('fetchDoctorProfile', [DoctorController::class, 'fetchDoctorProfile'])->middleware('checkHeader');
    Route::post('fetchDoctorReviews', [DoctorController::class, 'fetchDoctorReviews'])->middleware('checkHeader');
    Route::post('logOut', [UsersController::class, 'logOut'])->middleware('checkHeader');

    // Wallet
    Route::post('addMoneyToUserWallet', [UsersController::class, 'addMoneyToUserWallet'])->middleware('checkHeader');
    Route::post('fetchWalletStatement', [UsersController::class, 'fetchWalletStatement'])->middleware('checkHeader');
    Route::post('submitUserWithdrawRequest', [UsersController::class, 'submitUserWithdrawRequest'])->middleware('checkHeader');
    Route::post('fetchUserWithdrawRequests', [UsersController::class, 'fetchUserWithdrawRequests'])->middleware('checkHeader');

    // Appointments
    Route::post('fetchAcceptedPendingAppointmentsOfDoctorByDate', [AppointmentController::class, 'fetchAcceptedPendingAppointmentsOfDoctorByDate'])->middleware('checkHeader');
    Route::post('fetchCoupons', [AppointmentController::class, 'fetchCoupons'])->middleware('checkHeader');
    Route::post('rescheduleAppointment', [AppointmentController::class, 'rescheduleAppointment'])->middleware('checkHeader');
    Route::post('cancelAppointment', [AppointmentController::class, 'cancelAppointment'])->middleware('checkHeader');
    Route::post('fetchAppointmentDetails', [AppointmentController::class, 'fetchAppointmentDetails'])->middleware('checkHeader');
    Route::post('addRating', [AppointmentController::class, 'addRating'])->middleware('checkHeader');
    Route::post('fetchMyPrescriptions', [AppointmentController::class, 'fetchMyPrescriptions'])->middleware('checkHeader');
    Route::post('fetchMyAppointments', [AppointmentController::class, 'fetchMyAppointments'])->middleware('checkHeader');
    Route::post('scheduleAppointmentReminders', [AppointmentController::class, 'scheduleAppointmentReminders'])->middleware('checkHeader');

    // Notification
    Route::post('fetchNotification', [UsersController::class, 'fetchNotification'])->middleware('checkHeader');
    Route::get('TEST_sendNotificationToUser', [UsersController::class, 'TEST_sendNotificationToUser']);

    // Reels
    Route::post('fetchReelsPatientApp', [ReelController::class, 'fetchReelsPatientApp'])->middleware('checkHeader');
    Route::post('addCommentOnReelPatientApp', [ReelController::class, 'addCommentOnReelPatientApp'])->middleware('checkHeader');
    Route::post('likeReelPatientApp', [ReelController::class, 'likeReelPatientApp'])->middleware('checkHeader');
    Route::post('fetchReelByIdPatient', [ReelController::class, 'fetchReelByIdPatient'])->middleware('checkHeader');
    Route::post('fetchDoctorReels', [ReelController::class, 'fetchDoctorReels'])->middleware('checkHeader');
});


// I have to separate the appointment from the user, so that doctor and user can use the same route
Route::post('addAppointment', [AppointmentController::class, 'addAppointment'])->middleware('checkHeader');


//******************/ Doctor
Route::post('doctorRegistration', [DoctorController::class, 'doctorRegistration'])->middleware('checkHeader');
Route::post('updateDoctorDetails', [DoctorController::class, 'updateDoctorDetails'])->middleware('checkHeader');
Route::post('deleteDoctorAccount', [DoctorController::class, 'deleteDoctorAccount'])->middleware('checkHeader');
Route::post('logOutDoctor', [DoctorController::class, 'logOutDoctor'])->middleware('checkHeader');
Route::post('fetchDoctorCategories', [DoctorController::class, 'fetchDoctorCategories'])->middleware('checkHeader');
Route::post('fetchDoctorReviews', [DoctorController::class, 'fetchDoctorReviews'])->middleware('checkHeader');
Route::post('suggestDoctorCategory', [DoctorController::class, 'suggestDoctorCategory'])->middleware('checkHeader');
Route::post('fetchDoctorNotifications', [DoctorController::class, 'fetchDoctorNotifications'])->middleware('checkHeader');
Route::post('fetchMyDoctorProfile', [DoctorController::class, 'fetchMyDoctorProfile'])->middleware('checkHeader');
Route::post('addEditService', [DoctorController::class, 'addEditService'])->middleware('checkHeader');
Route::post('addEditAwards', [DoctorController::class, 'addEditAwards'])->middleware('checkHeader');
Route::post('addEditExpertise', [DoctorController::class, 'addEditExpertise'])->middleware('checkHeader');
Route::post('addEditExperience', [DoctorController::class, 'addEditExperience'])->middleware('checkHeader');
Route::post('addEditServiceLocations', [DoctorController::class, 'addEditServiceLocations'])->middleware('checkHeader');
Route::post('addAppointmentSlots', [DoctorController::class, 'addAppointmentSlots'])->middleware('checkHeader');
Route::post('manageDrBankAccount', [DoctorController::class, 'manageDrBankAccount'])->middleware('checkHeader');
Route::post('deleteAppointmentSlot', [DoctorController::class, 'deleteAppointmentSlot'])->middleware('checkHeader');
Route::post('addHoliday', [DoctorController::class, 'addHoliday'])->middleware('checkHeader');
Route::post('deleteHoliday', [DoctorController::class, 'deleteHoliday'])->middleware('checkHeader');
Route::post('fetchFaqCats', [DoctorController::class, 'fetchFaqCats'])->middleware('checkHeader');
Route::post('fetchUserDetails', [DoctorController::class, 'fetchUserDetails'])->middleware('checkHeader');
Route::post('checkMobileNumberExists', [DoctorController::class, 'checkMobileNumberExists'])->middleware('checkHeader');

// Appointments
Route::post('fetchAppointmentRequests', [AppointmentController::class, 'fetchAppointmentRequests'])->middleware('checkHeader');
Route::post('fetchAppointmentDetails', [AppointmentController::class, 'fetchAppointmentDetails'])->middleware('checkHeader');
Route::post('fetchAcceptedAppointsByDate', [AppointmentController::class, 'fetchAcceptedAppointsByDate'])->middleware('checkHeader');
Route::post('acceptAppointment', [AppointmentController::class, 'acceptAppointment'])->middleware('checkHeader');
Route::post('declineAppointment', [AppointmentController::class, 'declineAppointment'])->middleware('checkHeader');
Route::post('addPrescription', [AppointmentController::class, 'addPrescription'])->middleware('checkHeader');
Route::post('editPrescription', [AppointmentController::class, 'editPrescription'])->middleware('checkHeader');
Route::post('completeAppointment', [AppointmentController::class, 'completeAppointment'])->middleware('checkHeader');
Route::post('fetchAppointmentHistory', [AppointmentController::class, 'fetchAppointmentHistory'])->middleware('checkHeader');

// Connection
Route::post("random-doctors", [ConnectionController::class, "fetchRandomDoctor"])->name("checkHeader");
Route::post("random-users", [ConnectionController::class, "fetchRandomUser"])->name("checkHeader");
Route::post("isConnected", [ConnectionController::class, "isConnected"])->middleware("checkHeader");
Route::post("requestConnection", [ConnectionController::class, "requestConnection"])->middleware('checkHeader');
Route::post("acceptConnection", [ConnectionController::class, "acceptConnection"])->middleware("checkHeader");
Route::post("declineConnection", [ConnectionController::class, "declineConnection"])->middleware("checkHeader");
Route::post("cancelConnectionRequest", [ConnectionController::class, "cancelConnectionRequest"])->middleware("checkHeader");
Route::post("fetchMyConnections", [ConnectionController::class, "fetchMyConnections"])->middleware("checkHeader");
Route::post("reconnectConnection", [ConnectionController::class, "reconnectConnection"])->middleware("checkHeader");

// Pause Messages
Route::post("pause-messages", [ConnectionController::class, "pauseCommunication"])->name("checkHeader");
Route::post("resume-messages", [ConnectionController::class, "resumeCommunication"])->name("checkHeader");
Route::post("block-messages", [ConnectionController::class, "blockCommunication"])->name("checkHeader");


// Wallet
Route::post('fetchDoctorWalletStatement', [AppointmentController::class, 'fetchDoctorWalletStatement'])->middleware('checkHeader');
Route::post('fetchDoctorEarningHistory', [AppointmentController::class, 'fetchDoctorEarningHistory'])->middleware('checkHeader');
Route::post('submitDoctorWithdrawRequest', [AppointmentController::class, 'submitDoctorWithdrawRequest'])->middleware('checkHeader');
Route::post('fetchDoctorPayoutHistory', [AppointmentController::class, 'fetchDoctorPayoutHistory'])->middleware('checkHeader');

// Settings
Route::post('fetchGlobalSettings', [SettingsController::class, 'fetchGlobalSettings'])->middleware('checkHeader');
Route::post('pushNotificationToSingleUser', [SettingsController::class, 'pushNotificationToSingleUser'])->middleware('checkHeader');
Route::post('uploadFileGivePath', [SettingsController::class, 'uploadFileGivePath'])->middleware('checkHeader');
Route::post('generateAgoraToken', [SettingsController::class, 'generateAgoraToken'])->middleware('checkHeader');

// Reels
Route::post('uploadReelByDoctor', [ReelController::class, 'uploadReelByDoctor'])->middleware('checkHeader');
Route::post('fetchReelsDoctorApp', [ReelController::class, 'fetchReelsDoctorApp'])->middleware('checkHeader');
Route::post('addCommentOnReelDoctorApp', [ReelController::class, 'addCommentOnReelDoctorApp'])->middleware('checkHeader');
Route::post('likeReelDoctorApp', [ReelController::class, 'likeReelDoctorApp'])->middleware('checkHeader');
Route::post('fetchReelByIdDoctor', [ReelController::class, 'fetchReelByIdDoctor'])->middleware('checkHeader');
Route::post('deleteReel', [ReelController::class, 'deleteReel'])->middleware('checkHeader');
Route::post('fetchMyReels_DoctorApp', [ReelController::class, 'fetchMyReels_DoctorApp'])->middleware('checkHeader');


Route::post('reportReel', [ReelController::class, 'reportReel'])->middleware('checkHeader');
Route::post('fetchSavedReels', [ReelController::class, 'fetchSavedReels'])->middleware('checkHeader');
Route::post('fetchReelComments', [ReelController::class, 'fetchReelComments'])->middleware('checkHeader');
Route::post('increaseReelViewCount', [ReelController::class, 'increaseReelViewCount'])->middleware('checkHeader');

// Cron
Route::get('sendScheduledReminders_Cron', [SettingsController::class, 'sendScheduledReminders_Cron']);
