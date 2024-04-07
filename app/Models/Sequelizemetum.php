<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Sequelizemetum
 * 
 * @property string $name
 *
 * @package App\Models
 */
class Sequelizemetum extends Model
{
	protected $table = 'sequelizemeta';
	protected $primaryKey = 'name';
	public $incrementing = false;
	public $timestamps = false;
}
