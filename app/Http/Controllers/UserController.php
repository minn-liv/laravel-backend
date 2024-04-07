<?php

namespace App\Http\Controllers;

use App\Models\Allcode;
use App\Models\User;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $user = User::all();
        return response()->json($user);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $user = User::create($request->all());
        return response()->json($user);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }

    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $data = '';
        $input = $request->all();

        if (!$input['email'] && !$input['password']) {
            return response()->json([
                'errCode' => 1,
                'message' => 'Email and password are required'
            ]);
        }
        $user = User::checkEmailUser($input['email']);
        if ($user) {
            if (Hash::check($input['password'], $user['password'])) {
                $data =  [
                    'errCode' => 0,
                    'errMessage' => 'Ok',
                    'user' => $user
                ];
            } else {
                $data = [
                    'errCode' => 3,
                    'errMessage' => 'Wrong password'
                ];
            }
        } else {
            $data = [
                'errCode' => 2,
                'errMessage' => 'User not found'
            ];
        }
        return response()->json($data);
    }
    public function getAllUser(Request $request)
    {
        $input = $request->all();
        $users = [];
        $id = $input['id'];
        if (!$input['id']) {
            return response()->json([
                'errCode' => 1,
                'errMessage' => 'Missing required parameter'
            ], 200);
        } else {
            if ($id === 'ALL') {
                $users = User::getAllUser();
            }
            if ($id !== 'ALL') {
                $users = User::getUser($id);
            }
        }

        return response()->json([
            'errCode' => 0,
            'errMessage' => 'Ok',
            'users' => $users
        ]);
    }

    public function getAllCode(Request $request)
    {
        $input = $request->all();
        $type = $input['type'];
        if ($type) {
            try {
                $all_code = Allcode::getAllCode($type);

                return response()->json([
                    'errCode' => 0,
                    'data' => $all_code
                ]);
            } catch (Exception $e) {
                return response()->json([
                    'errCode' => 1,
                    'errMessage' => $e,
                ]);
            }
        } else {
            return response()->json([
                'errCode' => 1,
                'errMessage' => 'Missing required parameter',
            ]);
        }
    }

    public function handleCreateNewUser(Request $request)
    {
        $message = '';
        $input = $request->all();
        $user = new User();

        // Check email exist
        try {
            $check = User::checkEmailUser($input['email']);
            if (isset($check)) {
                return response()->json([
                    'errCode' => 1,
                    'errMessage' => 'Your email is already is used. Pls try another email'
                ]);
            } else {
                $hash_password = bcrypt($input['password']);

                $user->email = $input['email'];
                $user->password = $hash_password;
                $user->firstName = $input['firstName'];
                $user->lastName = $input['lastName'];
                $user->address = $input['address'];
                $user->phonenumber = $input['phonenumber'];
                $user->gender = $input['gender'];
                $user->roleId = $input['roleId'];
                $user->positionId = $input['positionId'];
                $user->image = base64_decode($input['avatar']);
                $user->createdAt =  new DateTime();
                $user->updatedAt =  new DateTime();

                $user->save();
                return response()->json([
                    'errCode' => 0,
                    'message' => "OK"
                ]);

                // return response()->json($user);
            }
        } catch (Exception  $e) {
            $message = [
                'errMessage' => $e
            ];
        }
        return response()->json($message);
    }

    public function handleEditUser(Request $request)
    {
        $input = $request->all();

        if (!$input['id'] || !$input['roleId'] || !$input['positionId'] || !$input['gender']) {
            return response()->json([
                'errCode' => 2,
                'errMessage' => "Missing required parameters"
            ], 200);
        } else {
            $user = User::findOrFail($input['id']);
            if (isset($user)) {
                $user->firstName = $input['firstName'];
                $user->lastName = $input['lastName'];
                $user->address = $input['address'];
                $user->roleId = $input['roleId'];
                $user->positionId = $input['positionId'];
                $user->gender = $input['gender'];
                $user->phonenumber = $input['phonenumber'];
                $user->image = base64_decode($input['avatar']);

                $user->save();
                return response()->json([
                    'errCode' => 0,
                    'message' => "Update the user succeed"
                ]);
            } else {
                return response()->json([
                    'errCode' => 1,
                    'errMessage' => 'User not found'
                ]);
            }
        }
    }

    public function handleDeleteUser(Request $request)
    {
        $input = $request->all();

        if (isset($input['id'])) {
            $user = User::findOrFail($input['id']);
            if (isset($user)) {
                $user->delete();

                return response()->json([
                    'errCode' => 0,
                    'errMessage' => 'The user is deleted '
                ]);
            } else {
                return response()->json([
                    'errCode' => 2,
                    'errMessage' => 'The user is not exist '
                ]);
            }
        } else {
            return response()->json([
                'errCode' => 1,
                'errMessage' => 'Missing required parameters'
            ]);
        }
    }
}
