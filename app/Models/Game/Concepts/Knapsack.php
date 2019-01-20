<?php

namespace App\Models\Game\Concepts;

use App\Models\Game\Aspects\Item;
use App\Models\Game\Aspects\Node;
use App\Models\Game\Aspects\Objective;
use App\Models\Game\Aspects\Recipe;
use App\Models\Game\Concepts\Listing;

class Knapsack {

	protected $listing;

	public function __construct()
	{
		$this->get();
	}

	public function get()
	{
		// Get the active list, create one if it does not exist
		$this->listing = Listing::active()->firstOrCreate([
			'user_id' => auth()->user()->id,
		]);
	}

	/**
	 * Change User's Active Listing
	 * @param  integer $id       ID of the Entity
	 * @param  string $type     Type of the Entity, Singular word expected
	 * @param  integer $quantity Amount to add, subtract; false will delete
	 */
	public function change($id, $type, $quantity = 1)
	{
		// Find the entity we're trying to add or update
		$entityClass = 'App\\Models\\Game\\Aspects\\' . ucwords($type);
		$entity = $entityClass::find($id);

		if (is_null($entity))
			return;

		$relation = str_plural($type);

		// Quantity is set to false: Delete
		if ($quantity === false)
			return $this->listing->$relation()->detach($entity);

		// Attach or update the entity
		if ( ! $this->listing->$relation->contains($entity))
			// Entry does not yet exist: Create
			return $this->listing->$relation()->attach($entity, [ 'quantity' => $quantity ]);

		// Entry already exists
		$updatedQuantity = $this->listing->$relation->find($entity->id)->pivot->quantity + $quantity;

		// New quantity is not valid: Delete
		if ($updatedQuantity <= 0)
			return $this->listing->$relation()->detach($entity);

		// New quantity is valid: Update
		return $this->listing->$relation()->updateExistingPivot($entity, [
			'quantity' => $updatedQuantity
		]);
	}

	public function remove($id, $type)
	{
		return $this->change($id, $type, false);
	}

	public function truncate()
	{
		foreach (Listing::$polymorphicRelationships as $relation)
			foreach ($this->listing->$relation as $entity)
				$this->remove($entity->id, $entity->pivot->jotting_type);
	}

	public function compressToString()
	{
		$this->listing->fresh();

		$shorthand = [];

		foreach (Listing::$polymorphicRelationships as $letter => $rel)
		{
			if ($this->listing->$rel->count() == 0)
				continue;

			$entries = [];
			foreach ($this->listing->$rel as $entry)
				$entries[] = $entry->id . ($entry->pivot->quantity > 1 ? 'x' . $entry->pivot->quantity : '');

			$shorthand[] = $letter . ':' . implode(',', $entries);
		}

		return base64_encode(implode('|', $shorthand));
	}

	public function importFromString($string)
	{
		$string = base64_decode($string);

		foreach (explode('|', $string) as $r)
		{
			list($letter, $entries) = explode(':', $r);
			$entries = explode(',', $entries);

			// $entries are "$id" or a spaceless "$id x $qty"
			foreach ($entries as $id)
			{
				$qty = 1;
				if (preg_match('/x/', $id))
					list($id, $qty) = explode('x', $id);

				self::change($id, str_singular(Listing::$polymorphicRelationships[$letter]), $qty);
			}
		}
	}

}
