<?php

namespace Feature\Books;

use App\Models\Game\Aspects\Item;
use App\Models\Game\Concepts\Listing;
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
