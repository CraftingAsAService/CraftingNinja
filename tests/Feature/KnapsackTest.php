<?php

namespace Feature;

use App\Models\Game\Aspects\Item;
use App\Models\Game\Aspects\Node;
use App\Models\Game\Aspects\Objective;
use App\Models\Game\Aspects\Recipe;
use App\Models\Game\Concepts\Listing;
use App\Models\User;
use Tests\TestCase;

class KnapsackTest extends TestCase
{

	/**
	 * Initialise classes to test against.
	 *
	 * @return	void
	 */
	public function setUp()
	{
		parent::setUp();

		// $this->withoutExceptionHandling();

		$this->setGame();
	}

	/** @test */
	function users_with_an_empty_knapsack_are_given_a_200()
	{
		// Arrange
		$user = factory(User::class)->create();

		// Act
		$response = $this->actingAs($user)->call('GET', $this->gamePath . '/knapsack');

		// Assert
		$response->assertStatus(200);
	}

	/** @test */
	function user_can_update_quantities_on_their_knapsack()
	{
		// Arrange
		$item = factory(Item::class)->create([
			'name:en' => 'Beta Item',
		]);

		$listing = factory(Listing::class)->state('unpublished')->create();
		$listing->items()->attach($item, [ 'quantity' => 4 ]);

		$user = $listing->user;

		// Act
		$response = $this->actingAs($user)->call('PUT', $this->gamePath . '/knapsack', [
			'id' => $item->id,
			'type' => 'item',
			'quantity' => -2
		]);
		$response = $this->actingAs($user)->call('PUT', $this->gamePath . '/knapsack', [
			'id' => $item->id,
			'type' => 'item',
			'quantity' => 3
		]);

		// Pull back the active listing
		$listing = Listing::with('items')->active($user->id)->firstOrFail();

		// Assert
		$response->assertStatus(200);
		$response->assertJson([
			'success' => true
		]);

		// 4 - 2 + 3 = 5
		$this->assertEquals(5, $listing->items->find($item->id)->pivot->quantity);
	}

	/** @test */
	function adding_the_same_entity_twice_updates_it()
	{
		// Arrange
		$user = factory(User::class)->create();
		$item = factory(Item::class)->create([
			'name:en' => 'Beta Item',
		]);
		$item2 = factory(Item::class)->create([
			'name:en' => 'Roma Tomatoes',
		]);

		// Act
		$response = $this->actingAs($user)->call('POST', $this->gamePath . '/knapsack', [
			'id' => $item2->id,
			'type' => 'item',
			'quantity' => 7
		]);
		$response = $this->actingAs($user)->call('POST', $this->gamePath . '/knapsack', [
			'id' => $item->id,
			'type' => 'item',
			'quantity' => 3
		]);
		$response = $this->actingAs($user)->call('POST', $this->gamePath . '/knapsack', [
			'id' => $item->id,
			'type' => 'item',
			'quantity' => 5
		]);

		// Pull back the active listing
		$listing = Listing::with('items')->active($user->id)->firstOrFail();

		// Assert
		$response->assertStatus(200);
		$response->assertJson([
			'success' => true
		]);

		$this->assertEquals(8, $listing->items->find($item->id)->pivot->quantity);
	}

	/** @test */
	function negative_quantity_updates_delete_the_entry()
	{
		// Arrange
		$item = factory(Item::class)->create([
			'name:en' => 'Beta Item',
		]);

		$listing = factory(Listing::class)->state('unpublished')->create();
		$listing->items()->attach($item, [ 'quantity' => 4 ]);

		$user = $listing->user;

		// Act
		$response = $this->actingAs($user)->call('PUT', $this->gamePath . '/knapsack', [
			'id' => $item->id,
			'type' => 'item',
			'quantity' => -4
		]);

		// Assert
		$response->assertStatus(200);
	}

	/** @test */
	function users_can_remove_things_from_their_knapsack()
	{
		// Arrange
		$item = factory(Item::class)->create([
			'name:en' => 'Beta Item',
		]);

		$listing = factory(Listing::class)->state('unpublished')->create();
		$listing->items()->save($item, [ 'quantity' => 999 ]);

		// Act
		$response = $this->actingAs($listing->user)->call('DELETE', $this->gamePath . '/knapsack', [
			'id' => $item->id,
			'type' => $listing->items->first()->pivot->jotting_type,
		]);

		// Pull back the active listing
		$listing = Listing::with('items')->active($listing->user->id)->firstOrFail();

		// Assert
		$response->assertStatus(200);
		$response->assertJson([
			'success' => true
		]);

		$this->assertEquals(0, $listing->items()->count());
	}

	/** @test */
	function users_removing_things_not_in_their_knapsack_fail_gracefully()
	{
		// Arrange
		$item = factory(Item::class)->create([
			'name:en' => 'Beta Item',
		]);

		$listing = factory(Listing::class)->state('unpublished')->create();

		// Act
		$response = $this->actingAs($listing->user)->call('DELETE', $this->gamePath . '/knapsack', [
			'id' => $item->id,
			'type' => 'item',
		]);

		// Assert
		$response->assertStatus(200);
		$response->assertJson([
			'success' => true
		]);
	}

	/** @test */
	function users_can_add_book_contents_to_their_current_list()
	{
		// Arrange
		$item = factory(Item::class)->create([
			'name:en' => 'Beta Item',
		]);

		// Create the listing and attach the item with a quantity of 999
		$listing = factory(Listing::class)->state('published')->create([
			'name:en' => 'Alpha Book',
		]);
		$listing->items()->save($item, [ 'quantity' => 999 ]);

		$user = factory(User::class)->create();

		// Act
		$response = $this->actingAs($user)->call('POST', $this->gamePath . '/books/' . $listing->id . '/add');

		$listing = Listing::with('items')->active($user->id)->firstOrFail();

		// Assert
		$response->assertStatus(200);
		$this->assertEquals(999, $listing->items()->first()->pivot->quantity);
	}

}
