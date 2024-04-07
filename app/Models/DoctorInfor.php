<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class DoctorInfor
 * 
 * @property int $id
 * @property int $doctorId
 * @property int|null $specialtyId
 * @property int|null $clinicId
 * @property string|null $priceId
 * @property string|null $provinceId
 * @property string|null $paymentId
 * @property string|null $addressClinic
 * @property string|null $nameClinic
 * @property string|null $note
 * @property int $count
 * @property Carbon $createdAt
 * @property Carbon $updatedAt
 *
 * @package App\Models
 */
class DoctorInfor extends Model
{
	protected $table = 'doctor_infor';
	public $timestamps = false;
	public static $snakeAttributes = false;

	protected $casts = [
		'doctorId' => 'int',
		'specialtyId' => 'int',
		'clinicId' => 'int',
		'count' => 'int',
		'createdAt' => 'datetime',
		'updatedAt' => 'datetime'
	];

	public function priceTypeData()
	{
		return $this->belongsTo(Allcode::class, 'priceId', 'keyMap');
	}

	public function provinceTypeData()
	{
		return $this->belongsTo(Allcode::class, 'provinceId', 'keyMap');
	}

	public function paymentTypeData()
	{
		return $this->belongsTo(Allcode::class, 'paymentID', 'keyMap');
	}

	protected $fillable = [
		'doctorId',
		'specialtyId',
		'clinicId',
		'priceId',
		'provinceId',
		'paymentId',
		'addressClinic',
		'nameClinic',
		'note',
		'count',
		'createdAt',
		'updatedAt'
	];
}
