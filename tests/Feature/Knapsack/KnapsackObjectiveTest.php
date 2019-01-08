<?php

namespace Feature\Knapsack;

use App\Models\Game\Aspects\Objective;
use App\Models\Game\Concepts\Listing;
use App\Models\User;
use Tests\TestCase;

class KnapsackObjectiveTest extends TestCase
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
	function users_can_add_objectives_to_their_knapsack()
	{
		// Arrange
		$user = factory(User::class)->create();
		$objective = factory(Objective::class)->create([
			'name:en' => 'Battle Royale',
		]);

		// Act
		$response = $this->actingAs($user)->call('POST', $this->gamePath . '/knapsack', [
			'id' => $objective->id,
			'type' => 'objective',
		]);

		// Pull back the active listing
		$listing = Listing::with('objectives')->active($user->id)->firstOrFail();

		// Assert
		$response->assertStatus(200);
		$response->assertJson([
			'success' => true
		]);

		$this->assertEquals(1, $listing->objectives()->count());
	}

}
