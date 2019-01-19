<?php

namespace Tests\Unit;

use App\Models\Game\Aspects\Item;
use App\Models\Game\Aspects\Node;
use App\Models\Game\Aspects\Objective;
use App\Models\Game\Aspects\Recipe;
use App\Models\Game\Concepts\Listing;
use Tests\GameTestCase;

class ListingTest extends GameTestCase
{

	/** @test */
	function listings_can_be_tied_to_a_job()
	{

	}

	/** @test */
	function listings_can_be_have_a_minimum_level()
	{

	}

	/** @test */
	function listings_can_be_have_a_maximum_level()
	{

	}

	/** @test */
	function lists_can_be_deleted()
	{

	}

	private function lists_can_contain_entity($entityName, $entity)
	{
		$listing = factory(Listing::class)->create();
		$listing->$entityName()->attach($entity);

		$this->assertEquals(1, $listing->fresh()->$entityName()->count());
	}

	/** @test */
	function lists_can_contain_items()
	{
		$item = factory(Item::class)->create();
		$this->lists_can_contain_entity('items', $item);
	}

	/** @test */
	function lists_can_contain_recipes()
	{
		$recipe = factory(Recipe::class)->create([
			'item_id' => factory(Item::class)->create()->id,
		]);
		$this->lists_can_contain_entity('recipes', $recipe);
	}

	/** @test */
	function lists_can_contain_objectives()
	{
		$objective = factory(Objective::class)->create();
		$this->lists_can_contain_entity('objectives', $objective);
	}

	/** @test */
	function lists_can_contain_nodes()
	{
		$node = factory(Node::class)->create();
		$this->lists_can_contain_entity('nodes', $node);
	}

}
