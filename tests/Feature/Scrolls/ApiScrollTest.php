<?php

namespace Feature\Scrolls;

use App\Models\Game\Aspects\Item;
use App\Models\Game\Concepts\Scroll;
use App\Models\User;
use Tests\ScrollTestCase;

class ApiScrollTest extends ScrollTestCase
{

	/** @test */
	function users_can_make_ajax_call_for_scrolls()
	{
		// Arrange
		factory(Scroll::class)->create([
			'name:en' => 'Alpha Scroll',
		]);

		// Act
		$response = $this->call('POST', '/api/scroll');

		// Assert
		$response->assertStatus(200);

		$response->assertSee('Alpha Scroll');
	}

}
