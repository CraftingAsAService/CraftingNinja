<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class Navigation extends TestCase
{

	public function test_that_you_can_visit_the_homepage_and_receive_a_200()
	{
		// Arrange
		// Act
		$this->visit('/')
		// Assert
			 ->assertResponseOk(true);
	}

}
