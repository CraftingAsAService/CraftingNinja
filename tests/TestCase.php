<?php

namespace Tests;

use App\Models\Game;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
	use CreatesApplication;

	protected $user = null;

	/**
	 * Initialise classes to test against.
	 *
	 * @return	void
	 */
	protected function setUp(): void
	{
		parent::setUp();

		$this->artisan('migrate');
	}

}
