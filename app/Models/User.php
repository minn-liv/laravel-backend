<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class User
 * 
 * @property int $id
 * @property string|null $email
 * @property string|null $password
 * @property string|null $firstName
 * @property string|null $lastName
 * @property string|null $address
 * @property string|null $gender
 * @property string|null $roleId
 * @property string|null $phonenumber
 * @property string|null $positionId
 * @property string|null $image
 * @property Carbon $createdAt
 * @property Carbon $updatedAt
 *
 * @package App\Models
 */
class User extends Model
{
    protected $table = 'users';
    public $timestamps = false;

    protected $casts = [
        'createdAt' => 'datetime',
        'updatedAt' => 'datetime'
    ];

    protected $hidden = [
        // 'password'
    ];

    protected $fillable = [
        'id',
        'email',
        'password',
        'firstName',
        'lastName',
        'address',
        'gender',
        'roleId',
        'phonenumber',
        'positionId',
        'image',
        'createdAt',
        'updatedAt'
    ];

    public static $snakeAttributes = false;

    public function positionData()
    {
        return $this->belongsTo(Allcode::class, 'positionId', 'keyMap');
    }

    public function genderData()
    {
        return $this->belongsTo(Allcode::class, 'gender', 'keyMap');
    }

    public function Markdown()
    {
        return $this->hasOne(Markdown::class, 'doctorId', 'id');
    }
    public function Doctor_Infor()
    {
        return $this->hasOne(DoctorInfor::class, 'doctorId', 'id');
    }

    public function doctorData()
    {
        return $this->hasMany(Schedule::class, 'doctorId', 'id');
    }

    public function patientData()
    {
        return $this->hasMany(Booking::class, 'patientId', 'id');
    }


    public static function getAllUser()
    {
        $users = self::all();
        foreach ($users as $user) {
            $user->image = base64_encode($user->image);
        }
        return $users;
    }
    public static function checkEmailUser($email)
    {
        $user = self::where('email', $email)->first();

        return $user;
    }
}