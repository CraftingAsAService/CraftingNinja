<?php

namespace Feature\Books;

use App\Models\Game\Aspects\Item;
use App\Models\Game\Concepts\Listing;
use App\Models\Game\Concepts\Listing\Jotting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JottingTest extends TestCase
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

}
