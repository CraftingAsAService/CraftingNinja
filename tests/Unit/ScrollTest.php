<?php

namespace Tests\Unit;

use App\Models\Game\Aspects\Item;
use App\Models\Game\Aspects\Job;
use App\Models\Game\Aspects\Node;
use App\Models\Game\Aspects\Objective;
use App\Models\Game\Aspects\Recipe;
use App\Models\Game\Concepts\Scroll;
use Tests\GameTestCase;

class ScrollTest extends GameTestCase
{

	/** @test */
	function scrolls_can_be_tied_to_a_job()
	{
		$job = factory(Job::class)->create([
			'name:en' => 'Good Job',
		]);
		factory(Scroll::class)->create([
			'job_id' => $job->id,
		]);
		factory(Scroll::class)->create([
			'job_id' => factory(Job::class)->create()->id,
		]);

		$scroll = Scroll::with('job')->filter([
			'sclass' => [ $job->id ],
		])->get();

		$this->assertEquals(1, $scroll->count());
		$this->assertEquals('Good Job', $scroll->first()->job->name);
	}

	/** @test */
	function scrolls_can_be_have_a_min_level()
	{
		factory(Scroll::class)->create([
			'min_level' => 15,
			'max_level' => 19,
		]);
		factory(Scroll::class)->create([
			'min_level' => 17,
			'max_level' => 22
		]);

		$scroll = Scroll::filter([
			'min_level' => 16,
		])->get();

		$this->assertEquals(1, $scroll->count());
		$this->assertEquals(15, $scroll->first()->min_level);
	}

	/** @test */
	function scrolls_can_be_have_a_max_level()
	{
		factory(Scroll::class)->create([
			'min_level' => 15,
			'max_level' => 19,
		]);
		factory(Scroll::class)->create([
			'min_level' => 17,
			'max_level' => 22
		]);

		$scroll = Scroll::filter([
			'max_level' => 21,
		])->get();

		$this->assertEquals(1, $scroll->count());
		$this->assertEquals(22, $scroll->first()->max_level);
	}

	private function scrolls_can_contain_entity($entityName, $entity)
	{
		$scroll = factory(Scroll::class)->create();
		$scroll->$entityName()->attach($entity);

		$this->assertEquals(1, $scroll->fresh()->$entityName()->count());
	}

	/** @test */
	function scrolls_can_contain_items()
	{
		$item = factory(Item::class)->create();
		$this->scrolls_can_contain_entity('items', $item);
	}

	/** @test */
	function scrolls_can_contain_recipes()
	{
		$recipe = factory(Recipe::class)->create([
			'item_id' => factory(Item::class)->create()->id,
		]);
		$this->scrolls_can_contain_entity('recipes', $recipe);
	}

	/** @test */
	function scrolls_can_contain_objectives()
	{
		$objective = factory(Objective::class)->create();
		$this->scrolls_can_contain_entity('objectives', $objective);
	}

	/** @test */
	function scrolls_can_contain_nodes()
	{
		$node = factory(Node::class)->create();
		$this->scrolls_can_contain_entity('nodes', $node);
	}

}
