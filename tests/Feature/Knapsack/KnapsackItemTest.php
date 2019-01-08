<?php

namespace Feature\Knapsack;

use App\Models\Game\Aspects\Item;
use App\Models\Game\Concepts\Listing;
use App\Models\User;
use Tests\TestCase;

class KnapsackItemTest extends TestCase
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
	function users_can_see_items_in_their_knapsack()
	{
		// Arrange
		$item = factory(Item::class)->create([
			'name:en' => 'Beta Item',
		]);

		$listing = factory(Listing::class)->state('unpublished')->create();
		$listing->items()->save($item, [ 'quantity' => 999 ]);

		// Act
		$response = $this->actingAs($listing->user)->call('GET', $this->gamePath . '/knapsack');

		// Assert
		$response->assertStatus(200);

		$response->assertSee('Beta Item');
		$response->assertSee('Unpublished');
		$response->assertSee('999');
	}

	/** @test */
	function users_can_add_items_to_their_knapsack()
	{
		// Arrange
		$user = factory(User::class)->create();
		$item = factory(Item::class)->create([
			'name:en' => 'Beta Item',
		]);

		// Act
		$response = $this->actingAs($user)->call('POST', $this->gamePath . '/knapsack', [
			'id' => $item->id,
			'type' => 'item',
		]);

		// Pull back the active listing
		$listing = Listing::with('items')->active($user->id)->firstOrFail();

		// Assert
		$response->assertStatus(200);
		$response->assertJson([
			'success' => true
		]);

		$this->assertEquals(1, $listing->items()->count());
	}

}
