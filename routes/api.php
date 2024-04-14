<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

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

Route::get('/users', 'UserController@index');
Route::post('/users', 'UserController@store');
// Route::post('/api/create-new-user', 'AuthController@register');

Route::post('/login', 'UserController@login');
Route::get('/get-all-users', 'UserController@getAllUser');
Route::post('/create-new-user', 'UserController@handleCreateNewUser');
Route::get('/allcode', 'UserController@getAllCode');
Route::put('/edit-user', 'UserController@handleEditUser');
Route::delete('/delete-user', 'UserController@handleDeleteUser');

Route::get('/top-doctor-home', 'DoctorController@getTopDoctorHome');
Route::get('/get-all-doctors', 'DoctorController@getAllDoctor');
Route::post('/save-infor-doctors', 'DoctorController@postInforDoctor');
Route::get('/get-detail-doctor-by-id', 'DoctorController@getDetailDoctorById');
Route::get('/get-list-patient-for-doctor', 'DoctorController@getListPatientForDoctor');
Route::post('/send-remedy', 'DoctorController@sendRemedy');


Route::get('/get-all-specialty', 'SpecialtyController@getAllSpecialty');

Route::post('/bulk-create-schedule', 'DoctorController@bulkCreateSchedule');
Route::get('/get-schedule-doctor-by-date', 'DoctorController@getScheduleByDate');
Route::get('/get-extra-infor-doctor-by-id', 'DoctorController@getExtraInforDoctorById');
Route::get('/get-profile-doctor-by-id', 'DoctorController@getProfileDoctorById');
Route::post('/patient-book-appointment', 'PatientController@postBookAppointment');
Route::post('/verify-book-appointment', 'PatientController@postVerifyBookAppointment');
Route::post('/create-new-specialty', 'SpecialtyController@createNewSpecialty');
Route::get('/get-detail-specialty-by-id', 'SpecialtyController@getDetailSpecialtyById');
Route::post('/create-new-clinic', 'ClinicController@createClinic');
Route::get('/get-all-clinic', 'ClinicController@getAllClinic');
Route::get('/get-detail-clinic-by-id', 'ClinicController@getDetailClinicById');

Route::get('/get-all-booking', 'DoctorController@getAllBooking');
