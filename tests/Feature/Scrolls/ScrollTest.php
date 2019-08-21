<?php

namespace Feature\Scrolls;

use App\Models\Game\Aspects\Item;
use App\Models\Game\Concepts\Scroll;
use App\Models\User;
use Tests\ScrollTestCase;

class ScrollTest extends ScrollTestCase
{

	/** @test */
	function user_can_view_recipe_scrolls()
	{
		// Arrange
		factory(Scroll::class)->create([
			'name:en' => 'Alpha Scroll',
		]);

		// Act
		$response = $this->call('GET', $this->gamePath . '/scrolls');

		// Assert
		$response->assertStatus(200);

		$response->assertSee('Alpha Scroll');
	}

	/** @test */
	function users_can_make_ajax_call_for_scrolls()
	{
		// Arrange
		factory(Scroll::class)->create([
			'name:en' => 'Alpha Scroll',
		]);

		// Act
		$response = $this->call('GET', $this->gamePath . '/api/scrolls');

		// Assert
		$response->assertStatus(200);

		$response->assertSee('Alpha Scroll');
	}

}
