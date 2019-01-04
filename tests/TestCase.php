<?php

namespace Tests;

use App\Models\Game;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
	use CreatesApplication;

	protected $gameSlug = 'savg', // Super Awesome Video Game
			  $gamePath = null;

	/**
	 * Initialise classes to test against.
	 *
	 * @return	void
	 */
	public function setUp()
	{
		parent::setUp();

		$this->artisan('migrate');

		// $this->withoutExceptionHandling();
	}

	/**
	 * By calling this function, we're setting the selected game to our test slug
	 */
	public function setGame()
	{
		$this->artisan('migrate', [
			'--path' => 'database/migrations/games',
			'--database' => $this->gameSlug, // aka Connection
		]);

		\DB::setDefaultConnection($this->gameSlug);

		factory(Game::class)->create([
			'slug' => $this->gameSlug,
			'version' => '1.1.1',
			'name:en' => 'Super Awesome Video Game',
			'abbreviation:en' => 'test',
			'description:en' => 'A Test Game',
		]);

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
			'name' => 'Yeet McGee'
		]);

		$this->be($user);
	}

}
