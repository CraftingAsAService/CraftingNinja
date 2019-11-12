<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Assets extends Command
{

	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'assets
							{game : The slug of the game to download assets for. }';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Download asset files from various sources.';

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		$className = ucwords($this->argument('game'));

		// Assume the job is in this repository
		$class = '\\App\\Models\\Aspir\\' . $className . '\\' . $className;

		if ( ! class_exists($class))
			return $this->error('Aspir spell for ' . $className . ' does not exist');

		(new $class($this))->assets();
	}

}
