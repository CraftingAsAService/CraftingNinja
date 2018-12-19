<?php

namespace Tests\Unit;

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
	function book_json_response_is_valid()
	{

	}

	/** @test */
	function books_can_be_tied_to_a_job()
	{

	}

	/** @test */
	function books_can_be_deleted_by_author()
	{

	}

	/** @test */
	function books_cannot_be_deleted_unless_its_the_author()
	{

	}

}
