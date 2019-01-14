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


	// $this->withoutExceptionHandling();


}
