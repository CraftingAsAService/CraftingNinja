<?php

namespace Feature;

use App\Models\Game\Aspects\Category;
use App\Models\Game\Aspects\Item;
use App\Models\Game\Aspects\Recipe;
use App\Models\Game\Concepts\Equipment;
use Tests\GameTestCase;

class BookTest extends GameTestCase
{

	/** @test */
	public function user_can_make_ajax_call_for_items()
	{
		// Arrange
		factory(Item::class)->create([
			'name' => 'Beta Item',
		]);

		// Act
		$response = $this->call('GET', $this->gamePath . '/api/items');

		// Assert
		$response->assertStatus(200);

		$response->assertSee('Beta Item');
	}

	/** @test */
	function user_can_filter_ajax_item_call_by_name_case_insensitively()
	{
		factory(Item::class)->create([
			'name' => 'Beta Item',
		]);
		factory(Item::class)->create([
			'name' => 'Gamma Item',
		]);

		$response = $this->call('GET', $this->gamePath . '/api/items', [
			'name' => 'bet tem',
		]);

		$response->assertStatus(200);
		$response->assertSee('Beta Item');
		$response->assertDontSee('Gamma Item');
	}

	/** @test */
	function user_can_filter_items_by_ilvl_and_rarity()
	{
		factory(Item::class)->create([
			'name' => 'Bad Item',
			'ilvl' => 18,
			'rarity' => 1,
		]);
		factory(Item::class)->create([
			'name' => 'Good Item',
			'ilvl' => 22,
			'rarity' => 2,
		]);
		factory(Item::class)->create([
			'name' => 'Bad Item',
			'ilvl' => 30,
			'rarity' => 3,
		]);

		$response = $this->call('GET', $this->gamePath . '/api/items', [
			'ilvlMin' => 15,
			'ilvlMax' => 25,
			'rarity' => 2
		]);

		$response->assertStatus(200);
		$response->assertSee('Good Item');
		$response->assertDontSee('Bad Item');
	}

	/** @test */
	function user_can_filter_by_recipes_only()
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

		$response = $this->call('GET', $this->gamePath . '/api/items', [
			'recipes' => 1,
		]);

		$response->assertStatus(200);
		$response->assertSee('Good Item');
		$response->assertDontSee('Bad Item');
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

		$response = $this->call('GET', $this->gamePath . '/api/items', [
			'equipment' => 1,
		]);

		$response->assertStatus(200);
		$response->assertSee('Good Item');
		$response->assertDontSee('Bad Item');

	}


	// $this->withoutExceptionHandling();


}
