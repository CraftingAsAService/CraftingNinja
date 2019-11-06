<?php

namespace App\Console\Commands;

use App\Models\Aspir\Osmose as OsmoseModel;
use Artisan;
use Illuminate\Console\Command;

class Osmose extends Command
{

	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'osmose
							{game : The slug of the game to import data for. }';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Insert game data based on parsed files.';

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		$gameSlug = $this->argument('game');

		(new OsmoseModel($this, $gameSlug))->run();
	}
}
