<?php

namespace Feature;

use App\Models\Game\Concepts\Scroll;
use App\Models\Game\Concepts\Report;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\GameTestCase;

class ReportTest extends GameTestCase
{

	/** @test */
	function users_can_report_scrolls()
	{
		// Arrange
		$scroll = factory(Scroll::class)->state('published')->create();
		$user = factory(User::class)->create();

		// Act
		$response = $this->actingAs($user)->call('POST', $this->gamePath . '/report', [
			'id' => $scroll->id,
			'type' => 'scroll',
			'reason' => 'Inaccurate',
		]);

		$scroll = Scroll::with('reports')->find($scroll->id);

		// Assert
		$response->assertStatus(200);
		$this->assertEquals(1, $scroll->reports->count());
	}

	/** @test */
	function users_cannot_report_unpublished_scrolls()
	{
		// Arrange
		$scroll = factory(Scroll::class)->state('unpublished')->create();
		$user = factory(User::class)->create();

		// Act
		$response = $this->actingAs($user)->call('POST', $this->gamePath . '/report', [
			'id' => $scroll->id,
			'type' => 'scroll',
			'reason' => 'Inaccurate',
		]);

		$response->assertStatus(404);
		$this->assertEquals(0, $scroll->reports->count());
	}

	// $this->withoutExceptionHandling();

}
