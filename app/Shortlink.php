<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Shortlink extends Model
{
    protected $fillable = [
        'url', 'user_id',
    ];

    /**
     * Get the user who owns the shortlink.
     */
    public function users()
    {
        return $this->belongsTo('App\User');
    }

	/**
	 * Get the clicks for the shortlink.
	 */
	public function clicks()
	{
		return $this->hasMany('App\Click');
	}
}
