<?php

namespace Feature\Books;

use App\Models\Game\Aspects\Item;
use App\Models\Game\Concepts\Listing;
use App\Models\User;
use Tests\GameTestCase;

class BookTest extends GameTestCase
{

	public function addItemToNinjaCartCookie($item, $quantity = 1)
	{
		static $items = [];
		$items[] = $item;

		// Assume the user added these things manually
		$_COOKIE['NinjaCart'] = '[';
		foreach ($items as $key => $i)
			$_COOKIE['NinjaCart'] .= '{"i":' . $item->id . ',"t":"item","q":' . $quantity . ',"p":""}' . ($key != count($items) - 1 ? ',' : '');
		$_COOKIE['NinjaCart'] .= ']';
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
	function user_can_access_creation_form_with_cart_contents()
	{
		$this->setUser();

		$item = factory(Item::class)->create([
			'name:en' => 'Beta Item',
		]);

		$this->addItemToNinjaCartCookie($item);

		$response = $this->call('GET', $this->gamePath . '/books/create');

		$response->assertOk();
		$response->assertSee('Beta Item');
	}

	/** @test */
	function user_cannot_access_creation_form_without_cart_contents()
	{
		// Note: Not setting NinjaCart cookie
		$this->setUser();

		$response = $this->call('GET', $this->gamePath . '/books/create');

		$response->assertRedirect('/knapsack');
	}

	/** @test */
	function guests_cannot_access_creation_form()
	{
		// Note: Not setting User
		$item = factory(Item::class)->create([
			'name:en' => 'Beta Item',
		]);

		$this->addItemToNinjaCartCookie($item);

		$response = $this->call('GET', $this->gamePath . '/books/create');

		$response->assertRedirect('/login');
	}

	/** @test */
	function users_can_submit_listing_creation()
	{
		$this->withoutExceptionHandling();
		$this->setUser();

		$item = factory(Item::class)->create([
			'name:en' => 'Beta Item',
		]);

		$this->addItemToNinjaCartCookie($item);

		$response = $this->call('POST', $this->gamePath . '/books', [
			'name'        => 'Awesome Book',
			'description' => 'Use this book to level up',
			'job_id'      => 17,
			'min_level'   => 1,
			'max_level'   => 255,
		]);

		$listing = Listing::first();

		$this->assertEquals($this->user->name, $listing->user->name);
		$this->assertEquals('Awesome Book', $listing->name);
		$this->assertEquals('Use this book to level up', $listing->description);
		$this->assertEquals(17, $listing->job_id);
		$this->assertEquals(1, $listing->min_level);
		$this->assertEquals(255, $listing->max_level);

		$response->assertRedirect('/compendium?chapter=books&filter=yours');
	}

	/** @test */
	function invalid_names_will_not_create_a_listing()
	{

	}

	/** @test */
	function invalid_job_ids_will_not_create_a_listing()
	{

	}

	/** @test */
	function invalid_levels_will_not_create_a_listing()
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
