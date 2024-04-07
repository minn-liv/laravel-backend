<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Specialty
 * 
 * @property int $id
 * @property string|null $image
 * @property string|null $name
 * @property string|null $descriptionHTML
 * @property string|null $descriptionMarkdown
 * @property Carbon $createdAt
 * @property Carbon $updatedAt
 *
 * @package App\Models
 */
class Specialty extends Model
{
	protected $table = 'specialties';
	public $timestamps = false;

	protected $casts = [
		'createdAt' => 'datetime',
		'updatedAt' => 'datetime'
	];

	protected $fillable = [
		'image',
		'name',
		'descriptionHTML',
		'descriptionMarkdown',
		'createdAt',
		'updatedAt'
	];
}
