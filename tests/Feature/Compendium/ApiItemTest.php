<?php

namespace Feature\Compendium;

use App\Models\Game\Aspects\Item;
use Tests\GameTestCase;

class ApiItemTest extends GameTestCase
{

	/** @test */
	public function user_can_make_ajax_call_for_items()
	{
		// Arrange
		factory(Item::class)->create([
			'name' => 'Beta Item',
		]);

		// Act
		$response = $this->call('POST', '/api/items');

		// Assert
		$response->assertStatus(200);

		$response->assertSee('Beta Item');
	}

}
