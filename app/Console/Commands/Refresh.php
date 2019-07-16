<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Refresh extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'refresh
							{--clear : Will not re-cache Configs and Routes}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Full Cache Refresh';

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		// Clear various laravel caches
		if ( ! is_file(storage_path('framework/down')))
			$this->call('opcache:clear');
		$this->call('config:' . ($this->option('clear') ? 'clear': 'cache'));
		$this->call('route:' . ($this->option('clear') ? 'clear': 'cache'));
		$this->call('cache:clear');
		$this->call('view:clear');
		$this->call('queue:restart');
	}

}
