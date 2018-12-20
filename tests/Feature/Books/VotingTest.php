<?php

namespace Feature\Books;

use App\Models\Game\Concepts\Listing;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VotingTest extends TestCase
{

	/**
	 * Initialise classes to test against.
	 *
	 * @return	void
	 */
	public function setUp()
	{
		parent::setUp();

		$this->setGame();
	}

	/** @test */
	function users_can_upvote_published_book()
	{
		// Arrange
		$listing = factory(Listing::class)->state('published')->create([
			'name:en' => 'Alpha Book',
		]);
		$user = factory(User::class)->create();

		// Act
		$response = $this->actingAs($user)->call('POST', $this->gamePath . '/books/' . $listing->id . '/vote', [
			'dir' => 1
		]);

		// Assert
		$response->assertJson([
			'votes' => 1,
		]);
	}

	/** @test */
	function users_who_upvote_a_book_twice_are_only_counted_once()
	{
		// Arrange
		$listing = factory(Listing::class)->state('published')->create([
			'name:en' => 'Alpha Book',
		]);
		$user = factory(User::class)->create();

		// Act
		$firstResponse = $this->actingAs($user)->call('POST', $this->gamePath . '/books/' . $listing->id . '/vote', [
			'dir' => 1
		]);
		$secondResponse = $this->actingAs($user)->call('POST', $this->gamePath . '/books/' . $listing->id . '/vote', [
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
	function users_can_remove_their_upvote_from_a_book()
	{
		// Arrange
		$listing = factory(Listing::class)->state('published')->create([
			'name:en' => 'Alpha Book',
		]);
		$user = factory(User::class)->create();

		// Act
		$firstResponse = $this->actingAs($user)->call('POST', $this->gamePath . '/books/' . $listing->id . '/vote', [
			'dir' => 1
		]);
		$secondResponse = $this->actingAs($user)->call('POST', $this->gamePath . '/books/' . $listing->id . '/vote', [
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
	function users_cannot_vote_on_unpublished_books()
	{
		// Arrange
		$listing = factory(Listing::class)->state('unpublished')->create([
			'name:en' => 'Private Book',
		]);
		$user = factory(User::class)->create();

		// Act
		$response = $this->actingAs($user)->call('POST', $this->gamePath . '/books/' . $listing->id . '/vote', [
			'dir' => 1
		]);

		// Assert
		$response->assertStatus(404);
	}

	/** @test */
	function guests_cannot_upvote_books()
	{
		// Arrange
		$listing = factory(Listing::class)->state('published')->create([
			'name:en' => 'Alpha Book',
		]);

		// Act
		$response = $this->call('POST', $this->gamePath . '/books/' . $listing->id . '/vote', [
			'dir' => 1
		]);

		// Assert
		// Expecting a 401, no user set
		$response->assertStatus(401);
	}

}
