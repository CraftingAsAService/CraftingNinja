<?php

namespace App\Models;

use App\Models\Game\Concepts\Scroll;
use App\Models\Game\Concepts\Scroll\Vote;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Hashids\Hashids;

class User extends Authenticatable
{
	use Notifiable;

	protected $connection = 'caas';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'name', 'email', 'password',
		'provider', 'provider_id', 'avatar',
	];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'password', 'remember_token',
		'provider', 'provider_id',
	];

	/**
	 * Scopes
	 */


	/**
	 * Relationships
	 */

	public function scrolls()
	{
		return $this->hasMany(Scroll::class)->withTranslation();
	}

	public function votes()
	{
		return $this->hasMany(Vote::class);
	}

	/**
	 * Methods
	 */

	static public function configureHashids()
	{
		return new Hashids('AuthorID', 5, implode('', range('a', 'z')));
	}

	static public function encodeId($userId)
	{
		$hashids = self::configureHashids();
		return $hashids->encode($userId);
	}

	public function encodedId()
	{
		return self::encodeId($this->id);
	}

	static public function decodeId($encodedID)
	{
		$hashids = self::configureHashids();
		return $hashids->decode($encodedID);
	}

}
