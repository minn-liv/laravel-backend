<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class DoctorClinicSpecialty
 * 
 * @property int $id
 * @property int|null $doctorId
 * @property int|null $clinicId
 * @property int|null $specialtyId
 * @property Carbon $createdAt
 * @property Carbon $updatedAt
 *
 * @package App\Models
 */
class DoctorClinicSpecialty extends Model
{
	protected $table = 'doctor_clinic_specialty';
	public $timestamps = false;

	protected $casts = [
		'doctorId' => 'int',
		'clinicId' => 'int',
		'specialtyId' => 'int',
		'createdAt' => 'datetime',
		'updatedAt' => 'datetime'
	];

	protected $fillable = [
		'doctorId',
		'clinicId',
		'specialtyId',
		'createdAt',
		'updatedAt'
	];
}
