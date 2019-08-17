<?php

namespace Feature\Compendium;

use App\Models\Game\Concepts\Listing;
use Tests\GameTestCase;

class ApiScrollTest extends GameTestCase
{

	/** @test */
	public function user_can_make_ajax_call_for_scrolls()
	{
		// Arrange
		factory(Listing::class)->create([
			'name' => 'Beta Scroll',
		]);

		// Act
		$response = $this->call('POST', '/api/scrolls');

		// Assert
		$response->assertStatus(200);

		$response->assertSee('Beta Scroll');

	}

}
