<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class History
 * 
 * @property int $id
 * @property int|null $patientId
 * @property int|null $doctorId
 * @property string|null $description
 * @property string|null $files
 * @property Carbon $createdAt
 * @property Carbon $updatedAt
 *
 * @package App\Models
 */
class History extends Model
{
	protected $table = 'histories';
	public $timestamps = false;

	protected $casts = [
		'patientId' => 'int',
		'doctorId' => 'int',
		'createdAt' => 'datetime',
		'updatedAt' => 'datetime'
	];

	protected $fillable = [
		'patientId',
		'doctorId',
		'description',
		'files',
		'createdAt',
		'updatedAt'
	];
}
