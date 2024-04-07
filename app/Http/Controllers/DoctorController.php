<?php

namespace App\Http\Controllers;

use App\Mail\SendMail;
use App\Mail\SubmissionConfirmation;
use App\Models\Booking;
use App\Models\DoctorInfor;
use App\Models\Markdown;
use App\Models\Schedule;
use App\Models\User;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Mail;

class DoctorController extends Controller
{
    //
    public function getTopDoctorHome(Request $request)
    {
        $input = $request->all();
        $limit = $input['limit'];
        try {
            $user = User::with('positionData')->with('genderData')->where('roleId', 'R2')->orderBy('created_at', 'DESC')->limit($limit)->get();

            return response()->json([
                'errCode' => 0,
                'data' => $user
            ]);
        } catch (Exception $e) {
            return response()->json([
                'errCode' => -1,
                'data' => 'Error from server... ' . $e->getMessage()
            ]);
        }
    }

    public function getAllDoctor()
    {

        try {
            $users = User::where('roleId', 'R2')->get();
            $users->makeHidden(['password', 'image']);

            if (isset($users)) {
                return response()->json([
                    'errCode' => 0,
                    'data' => $users
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                'errCode' => -1,
                'errMessage' => 'Error from server...' . $e->getMessage()
            ]);
        }
    }

    public function getDetailDoctorById(Request $request)
    {
        $input = $request->all();

        if (isset($input['id'])) {
            try {
                $user = User::where('id', $input['id'])
                    ->with('Markdown')
                    ->with(['Doctor_Infor' => function ($query) {
                        $query->with('priceTypeData')
                            ->with('provinceTypeData')
                            ->with('paymentTypeData')->get();
                    }])->first();
                return response()->json([
                    'errCode' => 0,
                    'data' => $user
                ]);
            } catch (Exception $e) {
                return response()->json([
                    'errCode' => -1,
                    'errMessage' => 'Error from server...' . $e->getMessage()
                ]);
            }
        }
    }

    public function postInforDoctor(Request $request)
    {
        $input = $request->all();

        if (!$input['doctorId'] || !$input['contentHTML'] || !$input['contentMarkdown'] || !$input['action'] || !$input['selectedPrice'] || !$input['selectedPayment'] || !$input['selectedProvince'] || !$input['nameClinic'] || !$input['addressClinic'] || !$input['note'] || !$input['specialtyId']) {
            return response()->json([
                'errCode' => -1,
                'errMessage' => 'Missing required parameter'
            ]);
        } else {
            try {
                // upsert Markdown table
                if ($input['action'] === 'CREATE') {
                    $mark_down =  new Markdown();
                    $mark_down->contentHTML = $input['contentHTML'];
                    $mark_down->contentMarkdown = $input['contentMarkdown'];
                    $mark_down->description = $input['description'];
                    $mark_down->doctorId = $input['doctorId'];
                    $mark_down->createdAt =  new DateTime();
                    $mark_down->updatedAt =  new DateTime();

                    $mark_down->save();
                } else if ($input['action'] === 'EDIT') {
                    $doctor_markdown = Markdown::where('doctorId', $input['doctorId'])->first();

                    if (isset($doctor_markdown)) {
                        $doctor_markdown->contentHTML = $input['contentHTML'];
                        $doctor_markdown->contentMarkdown = $input['contentMarkdown'];
                        $doctor_markdown->description = $input['description'];
                        $doctor_markdown->updatedAt = new Date();

                        $doctor_markdown->update();
                    }
                }

                // upsert to Doctor_Infor table


                $doctor_info = DoctorInfor::where('doctorId', $input['doctorId'])->first();
                if (isset($doctor_info)) {
                    // update
                    $doctor_info->doctorId = $input['doctorId'];
                    $doctor_info->priceId = $input['selectedPrice'];
                    $doctor_info->provinceId = $input['selectedProvince'];
                    $doctor_info->paymentId = $input['selectedPayment'];
                    $doctor_info->nameClinic = $input['nameClinic'];
                    $doctor_info->addressClinic = $input['addressClinic'];
                    $doctor_info->note = $input['note'];
                    $doctor_info->specialtyId = $input['specialtyId'];
                    $doctor_info->clinicId = $input['clinicId'];

                    $doctor_info->update();
                } else {
                    $doctor = new DoctorInfor();
                    // create
                    $doctor->doctorId = $input['doctorId'];
                    $doctor->priceId = $input['selectedPrice'];
                    $doctor->provinceId = $input['selectedProvince'];
                    $doctor->paymentId = $input['selectedPayment'];
                    $doctor->nameClinic = $input['nameClinic'];
                    $doctor->addressClinic = $input['addressClinic'];
                    $doctor->note = $input['note'];
                    $doctor->specialtyId = $input['specialtyId'];
                    $doctor->clinicId = $input['clinicId'];
                    $doctor->createdAt = new DateTime();
                    $doctor->updatedAt = new DateTime();

                    $doctor->save();
                }

                return response()->json([
                    'errCode' => 0,
                    'errMessage' => 'Save infor doctor succeed!'
                ]);
            } catch (Exception $e) {
                return response()->json([
                    'errCode' => -1,
                    'errMessage' => 'Error from server...' . $e->getMessage()
                ]);
            }
        }
    }

    public function bulkCreateSchedule(Request $request)
    {
        $input = $request->all();
        define('MAX_NUMBER_SCHEDULE', 10);
        if (isset($input['arrSchedule']) || isset($input['doctorId']) || isset($input['formattedDate'])) {
            try {
                $schedule = $input['arrSchedule'];
                if ($schedule && count($schedule) > 0) {
                    $schedule = array_map(function ($item) {
                        $item['maxNumber'] = MAX_NUMBER_SCHEDULE;
                        return $item;
                    }, $schedule);
                }



                $existing = Schedule::where('doctorId', $input['doctorId'])
                    ->where('date', $input['formattedDate'])
                    ->select('timeType', 'date', 'doctorId', 'maxNumber')
                    ->get()
                    ->toArray();

                $toCreate = array_udiff($schedule, $existing, function ($a, $b) {
                    return ($a['timeType'] === $b['timeType'] && strtotime($a['date']) === strtotime($b['date'])) ? 0 : 1;
                });
                // Create data
                if ($toCreate && count($toCreate) > 0) {
                    foreach ($toCreate as &$item) {
                        $item['createdAt'] = now();
                        $item['updatedAt'] = now();
                    }
                    Schedule::insert($toCreate);

                    return response()->json([
                        'errCode' => 0,
                        'errMessage' => 'OK'
                    ]);
                }
            } catch (Exception $e) {
                return response()->json([
                    'errCode' => -1,
                    'errMessage' => 'Error from the server...' . $e->getMessage()
                ]);
            }
        } else {
            return response()->json([
                'errCode' => 1,
                'errMessage' => 'Missing required parameter'
            ]);
        }
    }

    public function getScheduleByDate(Request $request)
    {
        $input = $request->all();

        if (isset($input['doctorId']) || isset($input['date'])) {
            try {
                $schedule = Schedule::with('timeTypeData')
                    ->with(['doctorData'])
                    ->where('doctorId', $input['doctorId'])
                    ->where('date', $input['date'])
                    ->get();

                return response()->json([
                    'errCode' => 0,
                    'data' => $schedule
                ]);
            } catch (Exception $e) {
                return response()->json([
                    'errCode' => -1,
                    'errMessage' => 'Error from the server...' . $e->getMessage()
                ]);
            }
        } else {
            return response()->json([
                'errCode' => 1,
                'errMessage' => 'Missing required parameter'
            ]);
        }
    }

    public function getExtraInforDoctorById(Request $request)
    {
        $id = $request['doctorId'];
        if (isset($id)) {
            try {
                $data = DoctorInfor::where('doctorId', $id)
                    ->with('priceTypeData')
                    ->with('provinceTypeData')
                    ->with('paymentTypeData')
                    ->first();

                return response()->json([
                    'errCode' => 1,
                    'data' => $data
                ]);
            } catch (Exception $e) {

                return response()->json([
                    'errCode' => -1,
                    'errMessage' => 'Error from the server...' . $e->getMessage()
                ]);
            }
        } else {
            return response()->json([
                'errCode' => 1,
                'errMessage' => 'Missing required parameter'
            ]);
        }
    }

    public function getProfileDoctorById(Request $request)
    {
        $id = $request['doctorId'];
        if (isset($id)) {
            try {
                $user = User::where('id', $id)
                    ->with('positionData')
                    ->with('Markdown')
                    ->with(['Doctor_Infor' => function ($query) {
                        $query->with('priceTypeData')
                            ->with('provinceTypeData')
                            ->with('paymentTypeData')->get();
                    }])
                    ->first();

                return response()->json([
                    'errCode' => 0,
                    'data' => $user
                ]);
            } catch (Exception $e) {
                return response()->json([
                    'errCode' => -1,
                    'errMessage' => 'Error from server...' . $e->getMessage()
                ]);
            }
        } else {
            return response()->json([
                'errCode' => 1,
                'errMessage' => 'Missing required parameter'
            ]);
        }
    }

    public function getListPatientForDoctor(Request $request)
    {
        $input = $request->all();
        $doctorId = $input['doctorId'];
        $date = $input['date'];

        $id = $request['doctorId'];
        if (isset($doctorId) || isset($date)) {
            try {
                $data = Booking::where('statusId', 'S2')
                    ->where('doctorId', $doctorId)
                    ->where('date', $date)
                    ->with('timeTypeDataPatient')
                    ->with(['patientData' => function ($query) {
                        $query->with('genderData')
                            ->get();
                    }])
                    ->get();


                return response()->json([
                    'errCode' => 0,
                    'data' => $data
                ]);
            } catch (Exception $e) {
                return response()->json([
                    'errCode' => -1,
                    'errMessage' => 'Error from server...' . $e->getMessage()
                ]);
            }
        } else {
            return response()->json([
                'errCode' => 1,
                'errMessage' => 'Missing required parameter'
            ]);
        }
    }

    public function sendRemedy(Request $request)
    {
        $input = $request->all();

        $email = $input['email'];
        $name = $input['patientName'];
        $attachment = $request->file('imgBase64');

        if ($attachment) {
            $attachmentPath = $attachment->getRealPath();
            $attachmentName = $attachment->getClientOriginalName();
            $attachmentMime = $attachment->getClientMimeType();
        } else {
            $attachmentPath = null;
            $attachmentName = null;
            $attachmentMime = null;
        }
        if (isset($input['email']) || isset($input['doctorId']) || isset($input['patientId']) || isset($input['timeType'])) {
            try {
                $appointment = Booking::where('doctorId', $input['doctorId'])
                    ->where('patientId', $input['patientId'])
                    ->where('timeType', $input['timeType'])
                    ->where('statusId', 'S2')
                    ->first();
                if ($appointment) {
                    $appointment->statusId = 'S3';
                    $appointment->save();
                }

                Mail::send('emails.booking_success', ['name' => $name], function ($message) use ($email, $name, $attachmentPath, $attachmentName, $attachmentMime) {
                    $message->to($email, $name)
                        ->subject('Form Submission');

                    if ($attachmentPath) {
                        $message->attach($attachmentPath, [
                            'as' => $attachmentName,
                            'mime' => $attachmentMime,
                        ]);
                        $message->embed($attachmentPath, $attachmentName);
                    }
                });



                return response()->json([
                    'errCode' => 0,
                    'errMessage' => 'OK'
                ]);
            } catch (Exception $e) {
                return response()->json([
                    'errCode' => -1,
                    'errMessage' => 'Error from server...' . $e->getMessage()
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
