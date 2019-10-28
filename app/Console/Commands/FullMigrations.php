<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Artisan;
use DB;

class FullMigrations extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'migrate:full';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Run the core migrations, and the game migrations';

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		Artisan::call('migrate', [
			'--force' => true,
		]);

		// php artisan migrate --path database/migrations/games --database [intendedSlug]
		foreach (explode(',', env('VALID_GAMES')) as $gameSlug)
		{
			echo 'Migrating caas_' . $gameSlug . PHP_EOL;
			DB::statement('CREATE SCHEMA IF NOT EXISTS `caas_' . $gameSlug . '`');

			Artisan::call('migrate', [
				'--path' => 'database/migrations/games',
				'--database' => $gameSlug,
				'--force' => true,
			]);
		}

		Artisan::call('db:seed', [
			'--database' => 'caas',
			'--class' => 'GameTableSeeder',
		]);
	}
}
