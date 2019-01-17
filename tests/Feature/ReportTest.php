<?php

namespace Feature;

use App\Models\Game\Concepts\Listing;
use App\Models\Game\Concepts\Report;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\GameTestCase;

class ReportTest extends GameTestCase
{

	/** @test */
	function users_can_report_listings()
	{
		// Arrange
		$listing = factory(Listing::class)->state('published')->create();
		$user = factory(User::class)->create();

		// Act
		$response = $this->actingAs($user)->call('POST', $this->gamePath . '/report', [
			'id' => $listing->id,
			'type' => 'listing',
			'reason' => 'Inaccurate',
		]);

		$listing = Listing::with('reports')->find($listing->id);

		// Assert
		$response->assertStatus(200);
		$this->assertEquals(1, $listing->reports->count());
	}

	// $this->withoutExceptionHandling();

}
