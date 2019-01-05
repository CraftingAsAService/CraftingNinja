<?php

namespace Feature;

use App\Models\Game\Aspects\Item;
use App\Models\Game\Concepts\Listing;
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
	function users_with_an_empty_knapsack_are_given_a_200()
	{

	}

	/** @test */
	function users_can_see_recipes_in_their_knapsack()
	{

	}

	/** @test */
	function users_can_see_npcs_in_their_knapsack()
	{

	}

	/** @test */
	function users_can_remove_things_from_their_knapsack()
	{

	}

	/** @test */
	function user_can_update_quantities_on_their_knapsack()
	{

	}

	/** @test */
	function users_can_add_book_contents_to_their_current_list()
	{


		// Act
		// $response = $this->call('POST', $this->gamePath . '/')
	}


}
