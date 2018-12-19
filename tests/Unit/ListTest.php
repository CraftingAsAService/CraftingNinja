<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListTest extends TestCase
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
