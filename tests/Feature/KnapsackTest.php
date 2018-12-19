<?php

namespace Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
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

		$this->setGame();
	}

	/** @test */
	function users_can_see_items_in_their_knapsack()
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


}
