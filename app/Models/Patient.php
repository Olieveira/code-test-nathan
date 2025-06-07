<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;

class Patient extends Model
{
	use HasFactory;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'user_id',
		'name',
		'gender',
		'birthdate',
		'image_path'
	];

	protected $dates = [
		'birthdate'
	];

	// Retorna o plural de dia, mês ou ano
	private function pluralizeInterval($number, $singular, $plural)
	{
		if ($number > 1) {
			return $number . ' ' . $plural;
		}
		return $number . ' ' . $singular;
	}

	// Imprime a idade em dias, meses ou anos
	public function getAge()
	{
		$interval = $this->birthdate->diff(Carbon::now());

		if ($interval->y > 0) {
			return $this->pluralizeInterval($interval->format('%y'), 'ano', 'anos');
		} else if ($interval->m > 0) {
			return $this->pluralizeInterval($interval->format('%m'), 'mês', 'meses');
		}

		return $this->pluralizeInterval($interval->format('%d'), 'dia', 'dias');
	}
}
