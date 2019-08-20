<?php

namespace Feature\Sling;

use App\Models\Game\Aspects\Objective;
use App\Models\Game\Concepts\Scroll;
use App\Models\User;
use Tests\GameTestCase;

class SlingObjectiveTest extends GameTestCase
{

	/** @test */
	function users_can_see_objectives_in_their_sling()
	{
		// Arrange
		$objective = factory(Objective::class)->create([
			'name:en' => 'Battle Royale',
		]);

		$scroll = factory(Scroll::class)->state('unpublished')->create();
		$scroll->objectives()->save($objective, [ 'quantity' => 666 ]);

		// Act
		$response = $this->actingAs($scroll->user)->call('GET', $this->gamePath . '/sling');

		// Assert
		$response->assertStatus(200);

		$response->assertSee('Battle Royale');
		$response->assertSee('Unpublished');
		$response->assertSee('666');
	}

	/** @test */
	function users_can_add_objectives_to_their_sling()
	{
		// Arrange
		$user = factory(User::class)->create();
		$objective = factory(Objective::class)->create([
			'name:en' => 'Battle Royale',
		]);

		// Act
		$response = $this->actingAs($user)->call('POST', $this->gamePath . '/sling', [
			'id' => $objective->id,
			'type' => 'objective',
		]);

		// Pull back the active scroll
		$scroll = Scroll::with('objectives')->active($user->id)->firstOrFail();

		// Assert
		$response->assertStatus(200);
		$response->assertJson([
			'success' => true
		]);

		$this->assertEquals(1, $scroll->objectives()->count());
	}

}
