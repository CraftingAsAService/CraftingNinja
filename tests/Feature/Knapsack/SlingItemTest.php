<?php

namespace Feature\Sling;

use App\Models\Game\Aspects\Item;
use App\Models\Game\Concepts\Scroll;
use App\Models\User;
use Tests\GameTestCase;

class SlingItemTest extends GameTestCase
{

	/** @test */
	function users_can_see_items_in_their_sling()
	{
		// Arrange
		$item = factory(Item::class)->create([
			'name:en' => 'Beta Item',
		]);

		$scroll = factory(Scroll::class)->state('unpublished')->create();
		$scroll->items()->save($item, [ 'quantity' => 999 ]);

		// Act
		$response = $this->actingAs($scroll->user)->call('GET', $this->gamePath . '/sling');

		// Assert
		$response->assertStatus(200);

		$response->assertSee('Beta Item');
		$response->assertSee('Unpublished');
		$response->assertSee('999');
	}

	/** @test */
	function users_can_add_items_to_their_sling()
	{
		// Arrange
		$user = factory(User::class)->create();
		$item = factory(Item::class)->create([
			'name:en' => 'Beta Item',
		]);

		// Act
		$response = $this->actingAs($user)->call('POST', $this->gamePath . '/sling', [
			'id' => $item->id,
			'type' => 'item',
		]);

		// Pull back the active scroll
		$scroll = Scroll::with('items')->active($user->id)->firstOrFail();

		// Assert
		$response->assertStatus(200);
		$response->assertJson([
			'success' => true
		]);

		$this->assertEquals(1, $scroll->items()->count());
	}

}
