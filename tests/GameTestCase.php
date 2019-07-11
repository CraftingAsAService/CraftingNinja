<?php

namespace Tests;

use App\Models\Game;
use App\Models\User;

abstract class GameTestCase extends TestCase
{

	protected $gameSlug = 'savg', // Super Awesome Video Game
			  $gamePath = null;

	/**
	 * Initialise classes to test against.
	 *
	 * @return	void
	 */
	protected function setUp(): void
	{
		parent::setUp();

		$this->setGame();
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

}
