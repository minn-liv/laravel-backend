<?php

namespace App\Http\Controllers;

use App\Mail\SendMail;
use App\Mail\SubmissionConfirmation;
use App\Models\Booking;
use App\Models\User;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PatientController extends Controller
{
    //
    public function postBookAppointment(Request $request)
    {
        $input = $request->all();
        $token = Str::uuid()->toString();
        $url = 'http://localhost:3000/verify-booking?token=' . $token . '&' . 'doctorId=' . $input['doctorId'];

        $body = [
            'title' => 'Booking Verification',
            'body' => 'Click here to verify booking ' . $url
        ];
        if (isset($input['email']) || isset($input['doctorId']) || isset($input['timeType']) || isset($input['date']) || isset($input['fullName']) || isset($input['selectedGender']) || isset($input['address'])) {
            try {
                Mail::to($request->input('email'))->send(new SubmissionConfirmation($token, $request->input('doctorId')));


                $user = User::firstOrCreate(
                    ['email' => $input['email']],
                    [
                        'email' => $input['email'],
                        'roleId' => 'R3',
                        'gender' => $input['selectedGender'],
                        'address' => $input['address'],
                        'firstName' => $input['fullName'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );

                if ($user) {
                    Booking::firstOrCreate(
                        ['patientId' => $user->id],
                        [
                            'statusId' => 'S1',
                            'doctorId' => $input['doctorId'],
                            'patientId' => $user->id,
                            'date' => $input['date'],
                            'timeType' => $input['timeType'],
                            'token' => $token,
                            'createdAt' => now(),
                            'updatedAt' =>  now(),
                        ]
                    );
                }

                return response()->json([
                    'errCode' => 0,
                    'errMessage' => 'Book success'
                ]);
            } catch (Exception $e) {
                return response()->json([
                    'errCode' => -1,
                    'errMessage' => 'Error from the server' . $e->getMessage()
                ]);
            }
        } else {
            return response()->json([
                'errCode' => 1,
                'errMessage' => 'Missing required parameter'
            ]);
        }
    }

    public function postVerifyBookAppointment(Request $request)
    {
        $input = $request->all();


        if (isset($input['token']) || isset($input['doctorId'])) {
            try {
                $appointment = Booking::where('doctorId', $input['doctorId'])
                    ->where('token', $input['token'])
                    ->where('statusId', 'S1')
                    ->first();

                if ($appointment) {
                    $appointment->statusId = 'S2';
                    $appointment->update();

                    return response()->json([
                        'errCode' => 0,
                        'errMessage' => 'Update the appointment succeed!'
                    ]);
                } else {
                    return response()->json([
                        'errCode' => 2,
                        'errMessage' => 'Appointment has been activated or does not exist!'
                    ]);
                }
            } catch (Exception $e) {
                return response()->json([
                    'errCode' => -1,
                    'errMessage' => 'Error from the server' . $e->getMessage()
                ]);
            }
        } else {
            return response()->json([
                'errCode' => 1,
                'errMessage' => 'Missing required parameter'
            ]);
        }
    }
}