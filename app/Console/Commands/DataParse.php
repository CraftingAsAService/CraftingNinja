<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DataParse extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'osmose:parse {game}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Parse game data';

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
		echo 'Parsing data for ' . strtoupper($this->argument('game')) . PHP_EOL;

		$gameClass = '\App\Models\Game\Data\\' . ucfirst($this->argument('game'));
		$gameData = new $gameClass();
		$gameData->parse();
	}
}
