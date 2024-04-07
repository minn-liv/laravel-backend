<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Clinic
 * 
 * @property int $id
 * @property string|null $address
 * @property string|null $name
 * @property string|null $descriptionMarkdown
 * @property string|null $descriptionHTML
 * @property string|null $image
 * @property Carbon $createdAt
 * @property Carbon $updatedAt
 *
 * @package App\Models
 */
class Clinic extends Model
{
	protected $table = 'clinics';
	public $timestamps = false;

	protected $casts = [
		'createdAt' => 'datetime',
		'updatedAt' => 'datetime'
	];

	protected $fillable = [
		'address',
		'name',
		'descriptionMarkdown',
		'descriptionHTML',
		'image',
		'createdAt',
		'updatedAt'
	];
}
