<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Artisan;
use DB;

class Reseed extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'osmose:reseed';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Insert game data based on parsed files';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		// php artisan db:seed --class FfxivGameSeeder --database ffxiv
		foreach (explode(',', env('VALID_GAMES')) as $gameSlug)
		{
			echo 'Seeding ' . $gameSlug . PHP_EOL;

			Artisan::call('db:seed', [
				'--class' => ucwords($gameSlug) . 'GameSeeder',
				'--database' => $gameSlug,
			]);
		}
	}
}
