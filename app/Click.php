<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Click extends Model
{
	public $timestamps = false;

	protected $fillable = [
		'shortlink_id', 'referer', 'created_at',
	];

	/**
	 * Get the shortlink of the click.
	 */
	public function users()
	{
		return $this->belongsTo('App\User');
	}
}
