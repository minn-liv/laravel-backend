<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Booking
 * 
 * @property int $id
 * @property string|null $statusId
 * @property int|null $doctorId
 * @property int|null $patientId
 * @property string|null $date
 * @property string|null $timeType
 * @property string|null $token
 * @property Carbon $createdAt
 * @property Carbon $updatedAt
 *
 * @package App\Models
 */
class Booking extends Model
{
	protected $table = 'bookings';
	public $timestamps = false;
	public static $snakeAttributes = false;

	protected $casts = [
		'doctorId' => 'int',
		'patientId' => 'int',
		'createdAt' => 'datetime',
		'updatedAt' => 'datetime'
	];

	protected $hidden = [
		'token'
	];

	protected $fillable = [
		'statusId',
		'doctorId',
		'patientId',
		'date',
		'timeType',
		'token',
		'createdAt',
		'updatedAt'
	];

	public function doctorData()
	{
		return $this->belongsTo(User::class, 'doctorId', 'id');
	}

	public function doctorDataList()
	{
		return $this->belongsTo(User::class, 'doctorId', 'id');
	}
	public function patientData()
	{
		return $this->belongsTo(User::class, 'patientId', 'id');
	}
	public function timeTypeDataPatient()
	{
		return $this->belongsTo(Allcode::class, 'timeType', 'keyMap');
	}
}