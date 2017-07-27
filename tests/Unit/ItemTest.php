<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

// use App\Models\Game\Job;
use App\Models\Game\Item;
// use App\Models\Game\Recipe;

class ItemTest extends TestCase
{
	use DatabaseMigrations;

	public function setUp()
	{
		parent::setUp();

		// Create the Game
		$game = factory(App\Models\Game::class)->create();

		config(['game' => $game]);

		// Make some items
		$items = factory(App\Models\Game\Item::class, 3)->make();

		// Attach items to the game
		$game->items()->saveMany($items);
	}
	/**
	 * @test
	 */
	public function items_can_be_sorted_by_their_name()
	{
		// Arrange
		$per_page = 10;

		// Act
		$asc_items = Item::sortByName('asc')->paginate($per_page)->pluck('name');
		$desc_items = Item::sortByName('desc')->paginate($per_page)->pluck('name');

		// Assert
		$this->_compare_order_of_array_values($asc_items, 'asc');
		$this->_compare_order_of_array_values($desc_items, 'desc');
	}








	/**
	 * Helper Functions
	 */

	private function _compare_order_of_array_values($values, $direction)
	{
		$sorted = $values->sort();
		if ($direction == 'desc')
			$sorted = $sorted->reverse();

		$this->assertTrue($values->toArray() === $sorted->toArray());
	}

}
