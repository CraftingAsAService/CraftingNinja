<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Aspir extends Command
{

	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'aspir
							{game : The slug of the game to build data for. }';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Build importable data files from various sources.';

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		$className = $this->argument('game');

		$class = '\\CraftingNinja\\Dojo\\' . $className . '\\Aspir\\Aspir';

		if ( ! class_exists($class))
			return $this->error('Aspir spell for ' . $className . ' does not exist');

		(new $class($this))->run();
	}

}
