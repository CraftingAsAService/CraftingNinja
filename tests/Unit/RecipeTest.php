<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

// use Faker\Factory as Faker;

// use App\Models\Game;
use App\Models\Game\Job;
use App\Models\Game\Item;
use App\Models\Game\Recipe;

class RecipeTest extends TestCase
{
	use DatabaseMigrations;

	public function setUp()
	{
		parent::setUp();

		// Create the Game
		$game = factory(App\Models\Game::class)->create();

		config(['game' => $game]);

		// Make a job, give it a specific name
		$jobs = factory(App\Models\Game\Job::class, 2)->make();
		$game->jobs()->saveMany($jobs);

		// Make some items
		$items = factory(App\Models\Game\Item::class, 3)->make();

		// Attach items to the game
		$game->items()->saveMany($items);

		// Attach recipe to individual item
		// Set the recipe's job_id to the same first job (leaving a job created, but with zero recipes)
		$items[0]->recipes()->save(factory(App\Models\Game\Recipe::class)->make([ 'job_id' => $jobs[0]->id ]));
		$items[1]->recipes()->save(factory(App\Models\Game\Recipe::class)->make([ 'job_id' => $jobs[0]->id ]));

		// Add some other data that shouldn't show up
		$bad_game = factory(App\Models\Game::class)->create();
		$bad_jobs = factory(App\Models\Game\Job::class, 2)->make();
		$bad_items = factory(App\Models\Game\Item::class, 3)->make();

		$bad_game->jobs()->saveMany($bad_jobs);
		$bad_game->items()->saveMany($bad_items);

		$bad_items[0]->recipes()->save(factory(App\Models\Game\Recipe::class)->make([ 'job_id' => $bad_jobs[0]->id ]));
		$bad_items[1]->recipes()->save(factory(App\Models\Game\Recipe::class)->make([ 'job_id' => $bad_jobs[1]->id ]));
	}

	// A user visits the Compendium, they're intially presented with the recipes tab
	// By default, we'll just show them the alphabetically

	/**
	 * @test
	 */
	public function recipes_can_be_sorted_by_their_related_items_name()
	{
		$this->_recipes_sorted_by_item_value('name', 'asc');
		$this->_recipes_sorted_by_item_value('name', 'desc');
	}

	/**
	 * @test
	 */
	public function recipes_can_be_sorted_by_their_related_items_ilvl()
	{
		$this->_recipes_sorted_by_item_value('ilvl', 'asc');
		$this->_recipes_sorted_by_item_value('ilvl', 'desc');
	}

	/**
	 * @test
	 */
	public function recipes_can_be_sorted_by_their_level()
	{
		$this->_recipes_sorted_by_value('level', 'asc');
		$this->_recipes_sorted_by_value('level', 'desc');
	}

	/**
	 * @test
	 */
	public function recipes_can_be_filtered_by_their_crafting_job()
	{
		// Arrange
		// setUp() applied recipes to the 0-index job, but none to the 1-index job
		$per_page = 10;
		$jobs = Job::all();

		// Act
		$has_recipes = Recipe::whereJobId($jobs[0]->id)->paginate($per_page);
		$no_recipes = Recipe::whereJobId($jobs[1]->id)->paginate($per_page);

		// Assert
		$this->assertTrue(count($has_recipes->items()) > 0);
		$this->assertTrue($no_recipes->items() == []);
	}

	/**
	 * @test
	 */
	public function recipes_can_be_filtered_by_their_items_name_with_a_given_string()
	{
		// Arrange
		$per_page = 10;
		$item = Recipe::first()->item;
		$searched_string = $item->name;
		$partial_string = substr($searched_string, 0, (int) (strlen($searched_string) / 2));
		$regex_partial_string = preg_replace('/ /', '.', preg_quote($partial_string, '/'));

		// Act
		$full_name_match = Recipe::filterByItemName($searched_string)->paginate($per_page);
		$partial_name_match = Recipe::filterByItemName($partial_string)->paginate($per_page);

		// Assert
		$this->assertEquals($full_name_match->first()->item->name, $searched_string);
		$this->assertTrue((bool) preg_match('/' . $regex_partial_string . '/', $partial_name_match->first()->item->name));
	}

	/**
	 * @test
	 */
	public function recipes_are_specific_to_their_items_game()
	{
		// Arrange
		$per_page = 10;

		// Relation with item handles game ownership
		$all_recipes = Recipe::paginate($per_page)->pluck('item')->pluck('game_id');

		// By diffing against the selected game id, it should empty out the array
		$this->assertEmpty(array_diff([config('game')->id], $all_recipes->toArray()));
	}

	/**
	 * @test
	 */
	public function recipes_can_be_filtered_by_their_job()
	{
		// Arrange
		$per_page = 10;
		$job = Job::first();

		// Act
		$employed_recipies = Recipe::whereJobId($job->id)->paginate($per_page);

		$this->assertNotEmpty($employed_recipies);
	}

	/**
	 * @test
	 */
	public function all_recipes_can_be_generated_into_a_valid_json_file()
	{
		// The user will download an entire json file with all recipe information for searching purposes
		// JSON will need to include the basics for displaying
		// Item, and it's name
		// TODO
	}

	/**
	 * Helper Functions
	 */

	private function _recipes_sorted_by_value($sort, $direction)
	{
		// Arrange
		$per_page = 10;

		// Act
		$recipes = Recipe::orderBy($sort, $direction)->paginate($per_page);

		$values = $recipes->pluck($sort);

		// Assert
		$this->_compare_order_of_array_values($values, $direction);
	}

	private function _recipes_sorted_by_item_value($sort, $direction)
	{
		// Arrange
		$per_page = 10;

		// Act
		$recipes = Recipe::sortByItemValue($sort, $direction)->paginate($per_page);

		$values = $recipes->pluck('item.' . $sort);

		// Assert
		$this->_compare_order_of_array_values($values, $direction);
	}

	private function _compare_order_of_array_values($values, $direction)
	{
		$sorted = $values->sort();
		if ($direction == 'desc')
			$sorted = $sorted->reverse();

		$this->assertTrue($values->toArray() === $sorted->toArray());
	}

}