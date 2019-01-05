<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListingTest extends TestCase
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
	function listing_json_response_is_valid()
	{

	}

	/** @test */
	function listings_can_be_tied_to_a_job()
	{

	}

	/** @test */
	function listings_can_be_deleted_by_author()
	{

	}

	/** @test */
	function listings_cannot_be_deleted_unless_its_the_author()
	{

	}


	/** @test */
	function lists_can_expire()
	{

	}

	/** @test */
	function lists_can_be_saved()
	{
		// from session to database
	}

	/** @test */
	function lists_can_be_deleted()
	{

	}

	/** @test */
	function lists_can_be_published_as_a_book()
	{

	}

	/** @test */
	function lists_can_contain_items()
	{

	}

	/** @test */
	function lists_can_contain_recipes()
	{

	}

	/** @test */
	function lists_can_contain_npcs()
	{

	}

	/** @test */
	function lists_can_be_shared()
	{

	}

}
