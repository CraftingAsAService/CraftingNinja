<?php

namespace Feature\Books;

use App\Models\Game\Aspects\Item;
use App\Models\Game\Concepts\Listing;
use App\Models\User;
use Tests\GameTestCase;

class BookTest extends GameTestCase
{

	public function getCartCookie()
	{
		return '[{"i":10590,"t":"item","q":1,"p":"/assets/ffxiv/item/6074.png"},{"i":10627,"t":"item","q":"2","p":"/assets/ffxiv/item/6111.png"},{"i":1608,"t":"item","q":1,"p":"/assets/ffxiv/item/87.png"}]';
	}

	/** @test */
	function user_can_view_recipe_books()
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
	function users_can_make_ajax_call_for_books()
	{
		// Arrange
		factory(Listing::class)->states('published')->create([
			'name:en' => 'Alpha Book',
		]);

		// Act
		$response = $this->call('GET', $this->gamePath . '/api/books');

		// Assert
		$response->assertStatus(200);

		$response->assertSee('Alpha Book');
	}

	/** @test */
	function user_can_create_book_from_knapsack_contents()
	{


	}








	/** @test */
	function users_can_publish_their_own_listings()
	{
		// Arrange
		$listing = factory(Listing::class)->states('unpublished')->create();

		// Act
		$response = $this->actingAs($listing->user)->call('POST', $this->gamePath . '/books/' . $listing->id . '/publish', [
			'dir' => 1
		]);

		// Assert
		$response->assertJson([
			'published' => true,
		]);
	}

	/** @test */
	function users_can_unpublish_their_own_book()
	{
		// Arrange
		$listing = factory(Listing::class)->states('published')->create([
			'name:en' => 'Alpha Book',
		]);

		// Act
		$response = $this->actingAs($listing->user)->call('POST', $this->gamePath . '/books/' . $listing->id . '/publish', [
			'dir' => -1
		]);

		// Assert
		$response->assertJson([
			'published' => false,
		]);
	}

	/** @test */
	function users_cannot_publish_anothers_listing()
	{
		// Arrange
		$listing = factory(Listing::class)->states('unpublished')->create();
		$user = factory(User::class)->create();

		// Act
		$response = $this->actingAs($user)->call('POST', $this->gamePath . '/books/' . $listing->id . '/publish', [
			'dir' => 1
		]);

		// Assert
		$response->assertJson([
			'published' => false,
		]);
	}

	/** @test */
	function users_cannot_unpublish_anothers_book()
	{
		// Arrange
		$listing = factory(Listing::class)->states('published')->create([
			'name:en' => 'Alpha Book',
		]);
		$user = factory(User::class)->create();

		// Act
		$response = $this->actingAs($user)->call('POST', $this->gamePath . '/books/' . $listing->id . '/publish', [
			'dir' => -1
		]);

		// Assert
		$response->assertJson([
			'published' => true,
		]);
	}

	/** @test */
	function user_can_view_book_contents()
	{
		// Arrange
		$item = factory(Item::class)->create([
			'name:en' => 'Beta Item',
		]);

		// Create the listing and attach the item with a quantity of 999
		$listing = factory(Listing::class)->state('published')->create([
			'name:en' => 'Alpha Book',
		]);
		$listing->items()->save($item, [ 'quantity' => 999 ]);

		// Act
		$response = $this->call('GET', $this->gamePath . '/books/' . $listing->id);

		// Assert
		$response->assertStatus(200);

		$response->assertSee('Alpha Book');
		$response->assertSee('Beta Item');
		$response->assertSee('999');
	}

}
