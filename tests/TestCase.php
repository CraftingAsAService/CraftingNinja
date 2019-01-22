<?php

namespace Tests;

use App\Models\Game;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
	use CreatesApplication;

	/**
	 * Initialise classes to test against.
	 *
	 * @return	void
	 */
	public function setUp()
	{
		parent::setUp();

		$this->artisan('migrate');
	}

	public function setUser()
	{
		$user = new User([
			'name' => 'Yeet McGee'
		]);

		$this->be($user);
	}

	public function showErrors()
	{
		$this->withoutExceptionHandling();
	}

}
