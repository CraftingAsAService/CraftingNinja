<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use App\Models\User;

abstract class TestCase extends BaseTestCase
{
	use CreatesApplication;

	protected $gameSlug = 'test',
			  $gamePath = null;

	/**
	 * By calling this function, we're setting the selected game to our test slug
	 */
	public function setGame()
	{
		\DB::setDefaultConnection($this->gameSlug);

		config([
			'game' => [
				'slug' => $this->gameSlug,
				'data' => [
					'name' => 'Testing Adventure',
					'slug' => $this->gameSlug,
					'abbreviation' => 'test',
					'version' => '1.1.1',
					'description' => 'A Test Game',
				]
			]
		]);

		$this->gamePath = 'https://' . $this->gameSlug . '.' . config('app.base_url');
	}

	public function setUser()
	{
		$user = new User([
			'name' => 'Yish McGee'
		]);

		$this->be($user);
	}

	/**
	 * Initialise classes to test against.
	 *
	 * @return	void
	 */
	public function setUp()
	{
		parent::setUp();

		// Only creating one test game for unit testing
		// Normally this exists in it's own database, not just a prefix within the same database
		// I couldn't figure out how to do unit testing with two+ distinct databases
		config(['database.connections.' . $this->gameSlug => array_merge(config('database.connections.testing'), [
			'prefix' => $this->gameSlug,
		])]);

		$this->artisan('migrate', [
			'--path' => 'database/migrations/games',
			'--database' => $this->gameSlug,
			'--force' => true,
		]);
	}

}
