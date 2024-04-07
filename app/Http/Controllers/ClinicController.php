<?php

namespace App\Http\Controllers;

use App\Models\Clinic;
use App\Models\DoctorInfor;
use Exception;
use Illuminate\Http\Request;

class ClinicController extends Controller
{
    //
    public function createClinic(Request $request)
    {
        $input = $request->all();

        if (isset($input['name']) || isset($input['address']) || isset($input['imageBase64']) || isset($input['descriptionHTML']) || isset($input['descriptionMarkdown'])) {

            try {
                $clinic = new Clinic();
                $clinic->name = $input['name'];
                $clinic->address = $input['address'];
                $clinic->image = $input['imageBase64'];
                $clinic->descriptionHTML = $input['descriptionHTML'];
                $clinic->descriptionMarkdown = $input['descriptionMarkdown'];
                $clinic->createdAt = new \DateTime();
                $clinic->updatedAt = new \DateTime();

                $clinic->save();
                return response()->json([
                    'errCode' => 0,
                    'errMessage' => 'OK!'
                ]);
            } catch (Exception $e) {
                return response()->json([
                    'errCode' => -1,
                    'errMessage' => 'Error from server ... ' . $e->getMessage()
                ]);
            }
        } else {
            return response()->json([
                'errCode' => 1,
                'errMessage' => 'Missing required parameter!'
            ]);
        }
    }


    public function getAllClinic()
    {
        try {
            $clinic = Clinic::all();

            return response()->json([
                'errCode' => 0,
                'errMessage' => 'OK',
                'data' => $clinic
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'errCode' => -1,
                'errMessage' => 'Error from server ... ' . $e->getMessage()
            ]);
        }
    }

    public function getDetailClinicById(Request $request)
    {
        $input = $request->all();
        $id = $input['id'];
        if (isset($id)) {
            try {
                $data = Clinic::where('id', $id)
                    ->select(['name', 'address', 'descriptionHTML', 'descriptionMarkdown'])
                    ->first();

                if (isset($data)) {
                    $doctorClinic = [];

                    $doctorClinic = DoctorInfor::where('clinicId', $id)
                        ->select('doctorId', 'provinceId')
                        ->get();

                    $data['doctorClinic'] = $doctorClinic;
                } else {
                    $data = [];
                }
                return response()->json([
                    'errCode' => 0,
                    'errMessage' => "OK",
                    'data' => $data
                ]);
            } catch (Exception $e) {
                return response()->json([
                    'errCode' => -1,
                    'errMessage' => 'Error from server ... ' . $e->getMessage()
                ]);
            }
        } else {
            return response()->json([
                'errCode' => 1,
                'errMessage' => 'Missing required parameter!'
            ]);
        }
    }
}