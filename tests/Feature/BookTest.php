<?php

namespace Feature;

use App\Models\Game\Aspects\Item;
use App\Models\Game\Concepts\Listing;
use App\Models\Game\Concepts\Listing\Jotting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookTest extends TestCase
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
	public function user_can_view_recipe_books()
	{
		// Arrange
		factory(Listing::class)->states('published')->create([
			'name:en' => 'Alpha Book',
		]);

		// Act
		$response = $this->call('GET', $this->gamePath . '/books');

		// Assert
		$response->assertStatus(200);

		$response->assertSee('Alpha Book');
	}

	/** @test */
	function user_can_view_recipe_book_contents()
	{
		// Arrange
		$listing = factory(Listing::class)->state('published')->create([
			'name:en' => 'Alpha Book',
		]);

		$listingContent = Jotting::make([
			'listing_id' => $listing->id,
			'quantity' => 999,
		]);

		// Create the item, name it, and attach it to the listing content entry
		$item = factory(Item::class)->create([
			'name' => 'Beta Item',
		])->listing_jotting()->save($listingContent);

		// Act
		$response = $this->call('GET', $this->gamePath . '/books/' . $listing->id);

		// Assert
		$response->assertStatus(200);

		$response->assertSee('Alpha Book');
		$response->assertSee('Beta Item');
		$response->assertSee('999');
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

	/** @test */
	function users_can_unpublish_their_own_book()
	{

	}

	/** @test */
	function users_cannot_unpublish_anothers_book()
	{

	}

	/** @test */
	function users_can_make_ajax_call_for_books()
	{

	}

	/** @test */
	function users_can_add_book_contents_to_their_current_list()
	{

	}

}
