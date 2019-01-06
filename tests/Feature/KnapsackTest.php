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

		$this->withoutExceptionHandling();

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
	function users_can_see_recipes_in_their_knapsack()
	{
		// Arrange
		$item = factory(Item::class)->create([
			'name:en' => 'Beta Item',
		]);

		$recipe = factory(Recipe::class)->create([
			// 'item_id' => $item->id,
		]);

		$listing = factory(Listing::class)->state('unpublished')->create();
		$listing->recipes()->save($recipe, [ 'quantity' => 888 ]);

		// Act
		$response = $this->actingAs($listing->user)->call('GET', $this->gamePath . '/knapsack');

		// Assert
		$response->assertStatus(200);

		$response->assertSee('Beta Item');
		$response->assertSee('Unpublished');
		$response->assertSee('888');
	}

	/** @test */
	function users_can_see_nodes_in_their_knapsack()
	{
		// Arrange
		$node = factory(Node::class)->create([
			'name:en' => 'Rock Formation',
		]);

		$listing = factory(Listing::class)->state('unpublished')->create();
		$listing->nodes()->save($node, [ 'quantity' => 777 ]);

		// Act
		$response = $this->actingAs($listing->user)->call('GET', $this->gamePath . '/knapsack');

		// Assert
		$response->assertStatus(200);

		$response->assertSee('Rock Formation');
		$response->assertSee('Unpublished');
		$response->assertSee('777');
	}

	/** @test */
	function users_can_see_objectives_in_their_knapsack()
	{
		// Arrange
		$objective = factory(Objective::class)->create([
			'name:en' => 'Battle Royale',
		]);

		$listing = factory(Listing::class)->state('unpublished')->create();
		$listing->objectives()->save($objective, [ 'quantity' => 666 ]);

		// Act
		$response = $this->actingAs($listing->user)->call('GET', $this->gamePath . '/knapsack');

		// Assert
		$response->assertStatus(200);

		$response->assertSee('Battle Royale');
		$response->assertSee('Unpublished');
		$response->assertSee('666');
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
