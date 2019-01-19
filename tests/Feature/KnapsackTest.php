<?php

namespace Feature;

use App\Models\Game\Aspects\Item;
use App\Models\Game\Aspects\Node;
use App\Models\Game\Aspects\Objective;
use App\Models\Game\Aspects\Recipe;
use App\Models\Game\Concepts\Knapsack;
use App\Models\Game\Concepts\Listing;
use App\Models\User;
use Tests\GameTestCase;

class KnapsackTest extends GameTestCase
{

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

	/** @test */
	function users_can_clear_their_list()
	{
		// Arrange
		$item = factory(Item::class)->create([
			'name:en' => 'Beta Item',
		]);

		$user = factory(User::class)->create();

		$response = $this->actingAs($user)->call('POST', $this->gamePath . '/knapsack', [
			'id' => $item->id,
			'type' => 'item',
		]);
		$listing = Listing::with('items')->active($user->id)->firstOrFail();
		$preItemCount = $listing->items()->count();

		// Act
		$response2 = $this->actingAs($user)->call('DELETE', $this->gamePath . '/knapsack/clear');

		$listing2 = Listing::with('items')->active($user->id)->firstOrFail();

		// Assert
		$response->assertStatus(200);
		$response2->assertStatus(200);
		$this->assertEquals(1, $preItemCount);
		$this->assertEquals(0, $listing2->items()->count());
	}

	/** @test */
	function users_can_share_their_current_knapsack_contents()
	{
		$listing = factory(Listing::class)->state('unpublished')->create();
		$this->be($listing->user);

		$item = factory(Item::class)->create();
		$item2 = factory(Item::class)->create();
		$listing->items()->attach($item2);
		$listing->items()->attach($item);
		$recipe = factory(Recipe::class)->create([
			'item_id' => factory(Item::class)->create()->id,
		]);
		$listing->recipes()->attach($recipe);
		$objective = factory(Objective::class)->create();
		$listing->objectives()->attach($objective);
		$node = factory(Node::class)->create();
		$listing->nodes()->attach($node);

		// Take the contents of the list, and compress it
		$knapsack = new Knapsack;

		$string = $knapsack->compressToString();

		$this->assertEquals('i:1,2|n:1|o:1|r:1', base64_decode($string));
	}

	/** @test */
	function users_can_use_a_knapsack_links()
	{

	}

}
