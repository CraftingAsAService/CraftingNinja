<?php

namespace Feature\Knapsack;

use App\Models\Game\Aspects\Node;
use App\Models\Game\Concepts\Listing;
use App\Models\User;
use Tests\GameTestCase;

class KnapsackNodeTest extends GameTestCase
{

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
	function users_can_add_nodes_to_their_knapsack()
	{
		// Arrange
		$user = factory(User::class)->create();

		$node = factory(Node::class)->create([
			'name:en' => 'Rock Formation',
		]);

		// Act
		$response = $this->actingAs($user)->call('POST', $this->gamePath . '/knapsack', [
			'id' => $node->id,
			'type' => 'node',
		]);

		// Pull back the active listing
		$listing = Listing::with('nodes')->active($user->id)->firstOrFail();

		// Assert
		$response->assertStatus(200);
		$response->assertJson([
			'success' => true
		]);

		$this->assertEquals(1, $listing->nodes()->count());
	}

}
