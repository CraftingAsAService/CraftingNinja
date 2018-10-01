<?php

namespace App\Models\Game;

use Illuminate\Database\Eloquent\Model;

/**
 * Common concepts of a game share the same basic setup
 * 	Concepts are not entities, and instead are abstract thoughts about an entity:
 * 		Where it is
 * 		Additional information
 * 		A specific classification of an entity
 */
class Entity extends Model
{

	public $timestamps = false;
	protected $guarded = [];

	/**
	 * Polymorphic Relationships
	 */

	/**
	 * Scopes
	 */

	public function scopeFilterByLevelRange($query, array $filters, $key = 'level', $min = 'lvlMin', $max = 'lvlMax')
	{
		if (isset($filters[$min]) && isset($filters[$max]))
			return $query->whereBetween($key, [$filters[$min], $filters[$max]]);
		elseif (isset($filters[$min]))
			return $query->where($key, '>=', $filters[$min]);
		elseif (isset($filters[$max]))
			return $query->where($key, '<=', $filters[$max]);
	}

	public function scopeFilterByValues($query, array $filters, array $needles)
	{
		foreach ($needles as $key)
			if (isset($filters[$key]))
				$query->where($key, $filters[$key]);

		return $query;
	}

}
