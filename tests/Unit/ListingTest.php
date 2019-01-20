<?php

namespace Tests\Unit;

use App\Models\Game\Aspects\Item;
use App\Models\Game\Aspects\Job;
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
		$job = factory(Job::class)->create([
			'name:en' => 'Good Job',
		]);
		factory(Listing::class)->create([
			'job_id' => $job->id,
		]);
		factory(Listing::class)->create([
			'job_id' => factory(Job::class)->create()->id,
		]);

		$listing = Listing::with('job')->filter([
			'class' => [ $job->id ],
		])->get();

		$this->assertEquals(1, $listing->count());
		$this->assertEquals('Good Job', $listing->first()->job->name);
	}

	/** @test */
	function listings_can_be_have_a_level()
	{
		factory(Listing::class)->create([
			'min_level' => 15,
			'max_level' => 19,
		]);
		factory(Listing::class)->create([
			'min_level' => 17,
			'max_level' => 22
		]);

		$listing = Listing::filter([
			'level' => 16,
		])->get();

		$this->assertEquals(1, $listing->count());
		$this->assertEquals(15, $listing->first()->min_level);
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
