<?php

namespace Feature\Scrolls;

use App\Models\Game\Concepts\Listing;
use App\Models\User;
use Tests\GameTestCase;

class VotingTest extends GameTestCase
{

	/** @test */
	function users_can_upvote_published_scroll()
	{
		// Arrange
		$listing = factory(Listing::class)->state('published')->create([
			'name:en' => 'Alpha Scroll',
		]);
		$user = factory(User::class)->create();

		// Act
		$response = $this->actingAs($user)->call('POST', $this->gamePath . '/scrolls/' . $listing->id . '/vote', [
			'dir' => 1
		]);

		// Assert
		$response->assertJson([
			'votes' => 1,
		]);
	}

	/** @test */
	function users_who_upvote_a_scroll_twice_are_only_counted_once()
	{
		// Arrange
		$listing = factory(Listing::class)->state('published')->create([
			'name:en' => 'Alpha Scroll',
		]);
		$user = factory(User::class)->create();

		// Act
		$firstResponse = $this->actingAs($user)->call('POST', $this->gamePath . '/scrolls/' . $listing->id . '/vote', [
			'dir' => 1
		]);
		$secondResponse = $this->actingAs($user)->call('POST', $this->gamePath . '/scrolls/' . $listing->id . '/vote', [
			'dir' => 1
		]);

		// Assert
		$firstResponse->assertJson([
			'votes' => 1,
		]);
		$secondResponse->assertJson([
			'votes' => 1,
		]);
	}

	/** @test */
	function users_can_remove_their_upvote_from_a_scroll()
	{
		// Arrange
		$listing = factory(Listing::class)->state('published')->create([
			'name:en' => 'Alpha Scroll',
		]);
		$user = factory(User::class)->create();

		// Act
		$firstResponse = $this->actingAs($user)->call('POST', $this->gamePath . '/scrolls/' . $listing->id . '/vote', [
			'dir' => 1
		]);
		$secondResponse = $this->actingAs($user)->call('POST', $this->gamePath . '/scrolls/' . $listing->id . '/vote', [
			'dir' => 0
		]);

		// Assert
		$firstResponse->assertJson([
			'votes' => 1,
		]);
		$secondResponse->assertJson([
			'votes' => 0,
		]);
	}

	/** @test */
	function users_cannot_vote_on_unpublished_scrolls()
	{
		// Arrange
		$listing = factory(Listing::class)->state('unpublished')->create([
			'name:en' => 'Private scroll',
		]);
		$user = factory(User::class)->create();

		// Act
		$response = $this->actingAs($user)->call('POST', $this->gamePath . '/scrolls/' . $listing->id . '/vote', [
			'dir' => 1
		]);

		// Assert
		$response->assertStatus(404);
	}

	/** @test */
	function guests_cannot_upvote_scrolls()
	{
		// Arrange
		$listing = factory(Listing::class)->state('published')->create();

		// Act
		$response = $this->call('POST', $this->gamePath . '/scrolls/' . $listing->id . '/vote', [
			'dir' => 1
		]);

		// Assert
		// Expecting a 401, no user set
		$response->assertStatus(401);
	}

}
