<?php

namespace Tests\Unit;

use App\Http\Resources\ItemResource;
use App\Models\Game\Aspects\Category;
use App\Models\Game\Aspects\Item;
use App\Models\Game\Aspects\Job;
use App\Models\Game\Aspects\Recipe;
use App\Models\Game\Concepts\Equipment;
use App\Models\Game\Concepts\Niche;
use Tests\GameTestCase;

class ItemTest extends GameTestCase
{

	private $badItem = null,
			$goodItem = null,
			$goodJob = null;

	private function commonItems()
	{
		$this->badItem = factory(Item::class)->create([
			'name' => 'Bad Item',
			'ilvl' => 30,
			'rarity' => 1,
		]);

		$badItem2 = factory(Item::class)->create([
			'name' => 'Bad Item Z',
			'ilvl' => 22,
			'rarity' => 3,
		]);

		$this->goodItem = factory(Item::class)->create([
			'name' => 'Good Item',
			'ilvl' => 18,
			'rarity' => 2,
		]);

		return $this; // For chaining
	}

	private function withRecipes($onlyGoodItem = false)
	{
		$this->goodJob = $this->goodJob ?? factory(Job::class)->create();

		factory(Recipe::class)->create([
			'item_id' => $this->goodItem->id,
			'sublevel' => 27,
			'job_id' => $this->goodJob->id,
		]);

		if ( ! $onlyGoodItem)
		{
			$job = factory(Job::class)->create();

			factory(Recipe::class)->create([
				'item_id' => $this->badItem->id,
				'sublevel' => 100,
				'job_id' => $job->id,
			]);
		}
	}

	private function withEquipment($onlyGoodItem = false)
	{
		$this->goodJob = $this->goodJob ?? factory(Job::class)->create();
		$niche = factory(Niche::class)->create();
		$niche->jobs()->attach($this->goodJob);

		factory(Equipment::class)->create([
			'item_id' => $this->goodItem->id,
			'niche_id' => $niche->id,
			'level' => 7,
			'slot' => 2,
		]);

		if ( ! $onlyGoodItem)
		{
			$job = factory(Job::class)->create();
			$niche = factory(Niche::class)->create();
			$niche->jobs()->attach($job);

			factory(Equipment::class)->create([
				'item_id' => $this->badItem->id,
				'niche_id' => $niche->id,
				'level' => 9,
				'slot' => 1,
			]);
		}
	}

	private function assertOnlyGoodItemFilter($filter)
	{
		// Act
		$items = Item::filter($filter);

		// Assert
		$this->assertEquals(1, $items->count());
		$this->assertEquals('Good Item', $items->first()->name);
	}

	/** @test */
	function items_can_be_filtered_by_name()
	{
		$this->commonItems();

		$this->assertOnlyGoodItemFilter([
			'name' => 'goo tem',
		]);
	}

	/** @test */
	function items_can_be_filtered_by_ilvl()
	{
		$this->commonItems();

		$this->assertOnlyGoodItemFilter([
			'ilvlMin' => 15,
			'ilvlMax' => 19,
		]);
	}

	/** @test */
	function items_can_be_filtered_by_rarity()
	{
		$this->commonItems();

		$this->assertOnlyGoodItemFilter([
			'rarity' => 2,
		]);
	}

	/** @test */
	function items_can_be_filtered_by_category()
	{
		$this->commonItems();

		$category = factory(Category::class)->create([
			'name' => 'Good Category',
		]);

		$this->goodItem->category()->associate($category)->save();

		$this->assertOnlyGoodItemFilter([
			'category_id' => $category->id,
		]);
	}

	/** @test */
	function items_can_be_filtered_by_recipes_only()
	{
		$this->commonItems()->withRecipes(true);

		$this->assertOnlyGoodItemFilter([
			'recipes' => 1,
		]);
	}

	/** @test */
	function items_can_be_filtered_by_recipe_sublevel()
	{
		$this->commonItems()->withRecipes();

		$this->assertOnlyGoodItemFilter([
			'sublevel' => 27,
		]);
	}

	/** @test */
	function items_can_be_filtered_by_recipe_class()
	{
		$this->commonItems()->withRecipes();

		$this->assertOnlyGoodItemFilter([
			'rclass' => [ $this->goodJob->id, ],
		]);
	}

	/** @test */
	function user_can_filter_by_equipment_only()
	{
		$this->commonItems()->withEquipment(true);

		$this->assertOnlyGoodItemFilter([
			'equipment' => 1,
		]);
	}

	/** @test */
	function items_can_be_filtered_by_equipment_level()
	{
		$this->commonItems()->withEquipment();

		$this->assertOnlyGoodItemFilter([
			'elvlMin' => 6,
			'elvlMax' => 8,
		]);
	}

	/** @test */
	function items_can_be_filtered_by_equipment_class()
	{
		$this->commonItems()->withEquipment();

		$this->assertOnlyGoodItemFilter([
			'eclass' => [ $this->goodJob->id, ],
		]);
	}

	/** @test */
	function items_can_be_filtered_by_equipment_slot()
	{
		$this->commonItems()->withEquipment();

		$this->assertOnlyGoodItemFilter([
			'slot' => 2,
		]);
	}

	/** @test */
	function items_can_be_sorted_by_name()
	{
		$this->commonItems();

		$firstAscItem = Item::filter([
			'sorting' => 'name',
			'ordering' => 'asc',
		])->first();

		$firstDescItem = Item::filter([
			'sorting' => 'name',
			'ordering' => 'desc',
		])->first();

		$this->assertEquals('Bad Item', $firstAscItem->name);
		$this->assertEquals('Good Item', $firstDescItem->name);
	}

	/** @test */
	function items_can_be_sorted_by_ilvl()
	{
		$this->commonItems();

		$firstAscItem = Item::filter([
			'sorting' => 'ilvl',
			'ordering' => 'asc',
		])->first();

		$firstDescItem = Item::filter([
			'sorting' => 'ilvl',
			'ordering' => 'desc',
		])->first();

		$this->assertEquals('Good Item', $firstAscItem->name);
		$this->assertEquals('Bad Item', $firstDescItem->name);
	}

}
