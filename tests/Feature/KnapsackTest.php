<?php

namespace Feature;

use App\Models\Game\Aspects\Item;
use App\Models\Game\Aspects\Node;
use App\Models\Game\Aspects\Objective;
use App\Models\Game\Aspects\Recipe;
use App\Models\Game\Concepts\Knapsack;
use App\Models\Game\Concepts\Scroll;
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
		$response = $this->actingAs($user)->call('GET', '/knapsack');

		// Assert
		$response->assertStatus(200);
	}

}
