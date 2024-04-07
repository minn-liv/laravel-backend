<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Markdown
 * 
 * @property int $id
 * @property string $contentHTML
 * @property string $contentMarkdown
 * @property string|null $description
 * @property int|null $doctorId
 * @property int|null $specialtyId
 * @property int|null $clinicId
 * @property Carbon $createdAt
 * @property Carbon $updatedAt
 *
 * @package App\Models
 */
class Markdown extends Model
{
	protected $table = 'markdowns';
	public $timestamps = false;

	protected $casts = [
		'doctorId' => 'int',
		'specialtyId' => 'int',
		'clinicId' => 'int',
		'createdAt' => 'datetime',
		'updatedAt' => 'datetime'
	];

	protected $fillable = [
		'contentHTML',
		'contentMarkdown',
		'description',
		'doctorId',
		'specialtyId',
		'clinicId',
		'createdAt',
		'updatedAt'
	];

	public function doctor()
	{
		$this->belongsTo(User::class, 'doctorId', 'id');
	}
}
