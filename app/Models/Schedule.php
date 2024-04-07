<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Schedule
 * 
 * @property int $id
 * @property int|null $currentNumber
 * @property int|null $maxNumber
 * @property string|null $date
 * @property string|null $timeType
 * @property int|null $doctorId
 * @property Carbon $createdAt
 * @property Carbon $updatedAt
 *
 * @package App\Models
 */
class Schedule extends Model
{
	protected $table = 'schedules';
	public static $snakeAttributes = false;

	public $timestamps = true;
	// protected $attributes = [
	// 	'createAt' => new \DateTime(),
	// 	'updatedAt' => new \DateTime(),
	// ];

	protected $casts = [
		'currentNumber' => 'int',
		'maxNumber' => 'int',
		'doctorId' => 'int',
		'createdAt' => 'datetime',
		'updatedAt' => 'datetime'
	];


	protected $fillable = [
		'currentNumber',
		'maxNumber',
		'date',
		'timeType',
		'doctorId',
		'createdAt',
		'updatedAt'
	];
	public function timeTypeData()
	{
		return $this->belongsTo(Allcode::class, 'timeType', 'keyMap');
	}
	public function doctorData()
	{
		return $this->belongsTo(User::class, 'doctorId', 'id');
	}
	protected static function booted()
	{
		static::creating(function (Model $model) {
			// Set model’s published_at value to be current date and time
			$model->createdAt = $model->freshTimestamp();
			$model->updatedAt = $model->freshTimestamp();
		});
	}
}
