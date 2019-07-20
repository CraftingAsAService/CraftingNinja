<?php

namespace Feature\Compendium;

use App\Models\Game\Concepts\Listing;
use Tests\GameTestCase;

class ApiBookTest extends GameTestCase
{

	/** @test */
	public function user_can_make_ajax_call_for_books()
	{
		// Arrange
		factory(Listing::class)->create([
			'name' => 'Beta Book',
		]);

		// Act
		$response = $this->call('POST', '/api/books');

		// Assert
		$response->assertStatus(200);

		$response->assertSee('Beta Book');

	}

}
