<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Allcode
 * 
 * @property int $id
 * @property string|null $keyMap
 * @property string|null $type
 * @property string|null $valueEn
 * @property string|null $valueVi
 * @property Carbon $createdAt
 * @property Carbon $updatedAt
 *
 * @package App\Models
 */
class Allcode extends Model
{
	protected $table = 'allcodes';
	public $timestamps = false;
	public static $snakeAttributes = false;

	protected $casts = [
		'createdAt' => 'datetime',
		'updatedAt' => 'datetime'
	];

	protected $fillable = [
		'keyMap',
		'type',
		'valueEn',
		'valueVi',
		'createdAt',
		'updatedAt'
	];
	public function user()
	{
		$this->hasMany(User::class, 'positionId', 'keyMap');
	}


	public function priceTypeData()
	{
		return $this->hasMany(DoctorInfor::class, 'priceId', 'keyMap');
	}

	public function provinceTypeData()
	{
		return $this->hasMany(DoctorInfor::class, 'provinceId', 'keyMap');
	}

	public function paymentTypeData()
	{
		return $this->hasMany(DoctorInfor::class, 'paymentID', 'keyMap');
	}
	public function timeTypeData()
	{
		return $this->hasMany(DoctorInfor::class, 'timeType', 'keyMap');
	}
	public static function getAllCode($type)
	{
		$all_code = self::where('type', $type)->get();

		return $all_code;
	}
}
