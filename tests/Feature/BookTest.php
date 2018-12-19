<?php

namespace Feature;

use App\Models\Game\Aspects\Item;
use App\Models\Game\Concepts\Listing;
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
		$book = factory(Listing::class)->states('published')->create([
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
		$book = factory(Listing::class)->create([
			'name:en' => 'Alpha Book',
		]);

		$item = factory(Item::class)->create([
			'name' => 'Beta Item',
		]);

		$book->items()->attach($item, ['quantity' => '999']);

		// Act
		$response = $this->call('GET', $this->gamePath . '/books/' . $book->id);

		// Assert
		$response->assertStatus(200);

		$response->assertSee('Alpha Book');
		$response->assertSee('Beta Item');
		$response->assertSee('999');
	}

	/** @test */
	function users_can_upvote_a_book()
	{

	}

	/** @test */
	function users_can_remove_their_upvote_from_a_book()
	{

	}

	/** @test */
	function users_can_unpublish_their_own_book()
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
