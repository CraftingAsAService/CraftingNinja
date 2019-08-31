<?php

// php artisan db:seed --class JunkDataSeeder --database ffxiv

use App\Models\Translations\ScrollTranslation;
use Illuminate\Database\Seeder;

class JunkDataSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$this->scrolls();
	}

	private function scrolls()
	{
		// Generate a large number of scrolls
		//  Creating the Translations will also create the Scrolls themselves
		factory(ScrollTranslation::class, 50)->create();

		// TODO add items to the scrolls
		// TODO add votes to the scrolls

	}
}
