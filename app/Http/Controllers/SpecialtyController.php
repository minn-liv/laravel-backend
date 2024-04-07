<?php

namespace App\Http\Controllers;

use App\Models\Clinic;
use App\Models\DoctorInfor;
use App\Models\Specialty;
use Exception;
use Illuminate\Http\Request;

class SpecialtyController extends Controller
{
    //
    public function getAllSpecialty()
    {
        try {
            $specialty = Specialty::all();
            return response()->json([
                'errCode' => 0,
                'errMessage' => 'OK',
                'data' => $specialty
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'errCode' => -1,
                'errMessage' => 'Error from server ... ' . $e->getMessage()
            ]);
        }
    }


    public function createNewSpecialty(Request $request)
    {
        $input = $request->all();

        if (isset($input['name']) || isset($input['imageBase64']) || isset($input['descriptionHTML']) || isset($input['descriptionMarkdown'])) {

            try {
                $specialty = new Specialty();
                $specialty->name = $input['name'];
                $specialty->image = $input['imageBase64'];
                $specialty->descriptionHTML = $input['descriptionHTML'];
                $specialty->descriptionMarkdown = $input['descriptionMarkdown'];
                $specialty->createdAt = new \DateTime();
                $specialty->updatedAt = new \DateTime();

                $specialty->save();
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

    public function getDetailSpecialtyById(Request $request)
    {
        $input = $request->all();
        $id = $input['id'];
        $location = $input['location'];
        if (isset($id) || isset($location)) {
            try {
                $data = Specialty::where('id', $id)
                    ->select(['descriptionHTML', 'descriptionMarkdown'])
                    ->first();

                if (isset($data)) {
                    $doctorSpecialty = [];
                    if ($location === "ALL") {
                        $doctorSpecialty = DoctorInfor::where('specialtyId', $id)
                            ->select('doctorId', 'provinceId')
                            ->get();
                    } else {
                        // find by location
                        $doctorSpecialty = DoctorInfor::where('specialtyId', $id)
                            ->where('provinceId', $location)
                            ->select('doctorId', 'provinceId')
                            ->get();
                    }

                    $data['doctorSpecialty'] = $doctorSpecialty;
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