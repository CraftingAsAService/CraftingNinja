<?php

namespace Feature;

use App\Models\Game\Aspects\Category;
use App\Models\Game\Aspects\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookTest extends TestCase
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
	function users_can_add_items_to_a_list()
	{

	}

	/** @test */
	function users_can_add_recipes_to_a_list()
	{

	}

	/** @test */
	function users_can_add_npcs_to_a_list()
	{

	}

}
