<?php

namespace Tests\Unit;

use App\Http\Resources\ItemResource;
use App\Models\Game\Aspects\Category;
use App\Models\Game\Aspects\Item;
use App\Models\Game\Aspects\Recipe;
use Tests\GameTestCase;

class ItemTest extends GameTestCase
{

	/** @test */
	function item_json_response_contains_valid_category_name()
	{
		// Arrange
		$item = factory(Item::class)->create([
			'name' => 'Good Item',
		]);
		$category = factory(Category::class)->create([
			'name' => 'Awesome Category',
		]);
		$item->category()->associate($category);
		$item->save();

		$items = Item::with('category')->get();

		// Act
		$itemCollection = ItemResource::collection($items);

		// Assert
		$this->assertEquals('Awesome Category', $itemCollection->first()->category->name);
	}

	/** @test */
	function items_can_be_filtered_by_name()
	{
		factory(Item::class)->create([
			'name' => 'Good Item',
		]);

		factory(Item::class)->create([
			'name' => 'Bad Item',
		]);

		$items = Item::withTranslation()->filter([
			'name' => 'goo tem',
		]);

		$this->assertEquals(1, $items->count());
		$this->assertEquals('Good Item', $items->first()->name);
	}

	/** @test */
	function items_can_be_filtered_by_ilvl()
	{
		factory(Item::class)->create([
			'name' => 'BadX Item',
			'ilvl' => 18,
		]);
		factory(Item::class)->create([
			'name' => 'Good Item',
			'ilvl' => 22,
		]);
		factory(Item::class)->create([
			'name' => 'BadY Item',
			'ilvl' => 30,
		]);

		$items = Item::withTranslation()->filter([
			'ilvlMin' => 19,
			'ilvlMax' => 29,
		]);

		$this->assertEquals(1, $items->count());
		$this->assertEquals('Good Item', $items->first()->name);
	}

	/** @test */
	function items_can_be_filtered_by_rarity()
	{
		factory(Item::class)->create([
			'name' => 'BadX Item',
			'rarity' => 1,
		]);
		factory(Item::class)->create([
			'name' => 'Good Item',
			'rarity' => 2,
		]);
		factory(Item::class)->create([
			'name' => 'BadY Item',
			'rarity' => 3,
		]);

		$items = Item::withTranslation()->filter([
			'rarity' => 2,
		]);

		$this->assertEquals(1, $items->count());
		$this->assertEquals('Good Item', $items->first()->name);
	}

	/** @test */
	function items_can_be_filtered_by_recipes_only()
	{
		factory(Item::class)->create([
			'name' => 'Bad Item',
		]);

		$item = factory(Item::class)->create([
			'name' => 'Good Item',
		]);

		$recipe = factory(Recipe::class)->create([
			'item_id' => $item->id,
		]);

		$items = Item::withTranslation()->filter([
			'recipes' => 1,
		]);

		$this->assertEquals(1, $items->count());
		$this->assertEquals('Good Item', $items->first()->name);
	}

	/** @test */
	function user_can_filter_by_equipment_only()
	{
		factory(Item::class)->create([
			'name' => 'Bad Item',
		]);

		$item = factory(Item::class)->create([
			'name' => 'Good Item',
		]);

		$equipment = factory(Equipment::class)->create([
			'item_id' => $item->id,
		]);

		$items = Item::withTranslation()->filter([
			'equipment' => 1,
		]);

		$this->assertEquals(1, $items->count());
		$this->assertEquals('Good Item', $items->first()->name);
	}

	/** @test */
	function items_can_be_filtered_by_recipe_sublevel()
	{
		$bitem = factory(Item::class)->create([
			'name' => 'Bad Item',
		]);

		factory(Recipe::class)->create([
			'item_id' => $bitem->id,
			'sublevel' => 100,
		]);

		$item = factory(Item::class)->create([
			'name' => 'Good Item',
		]);

		factory(Recipe::class)->create([
			'item_id' => $item->id,
			'sublevel' => 27,
		]);

		$items = Item::withTranslation()->filter([
			'recipes' => 1,
			'sublevel' => 27,
		]);

		$this->assertEquals(1, $items->count());
		$this->assertEquals('Good Item', $items->first()->name);
	}

	/** @test */
	function items_can_be_filtered_by_recipe_class()
	{

	}

	/** @test */
	function items_can_be_filtered_by_recipe_difficulty()
	{

	}

	/** @test */
	function items_can_be_filtered_by_equipment_level()
	{

	}

	/** @test */
	function items_can_be_filtered_by_equipment_class()
	{

	}

	/** @test */
	function items_can_be_filtered_by_equipment_slot()
	{

	}

	/** @test */
	function items_can_be_filtered_by_equipment_slots()
	{

	}

	/** @test */
	function items_can_be_sorted_by_name()
	{
		factory(Item::class)->create([
			'name' => 'Beta Item',
		]);
		factory(Item::class)->create([
			'name' => 'Gamma Item',
		]);

		$firstAscItem = Item::withTranslation()->filter([
			'sorting' => 'name',
			'ordering' => 'asc',
		])->first();

		$firstDescItem = Item::withTranslation()->filter([
			'sorting' => 'name',
			'ordering' => 'desc',
		])->first();

		$this->assertEquals('Beta Item', $firstAscItem->name);
		$this->assertEquals('Gamma Item', $firstDescItem->name);
	}

	/** @test */
	function items_can_be_sorted_by_ilvl()
	{
		factory(Item::class)->create([
			'name' => 'Beta Item',
			'ilvl' => 22,
		]);
		factory(Item::class)->create([
			'name' => 'Gamma Item',
			'ilvl' => 21,
		]);

		$firstAscItem = Item::withTranslation()->filter([
			'sorting' => 'ilvl',
			'ordering' => 'asc',
		])->first();

		$firstDescItem = Item::withTranslation()->filter([
			'sorting' => 'ilvl',
			'ordering' => 'desc',
		])->first();

		$this->assertEquals('Gamma Item', $firstAscItem->name);
		$this->assertEquals('Beta Item', $firstDescItem->name);
	}

}
