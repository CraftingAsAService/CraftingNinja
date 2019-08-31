<?php

// php artisan db:seed --class JunkDataSeeder --database ffxiv

use App\Models\Game\Aspects\Item;
use App\Models\Game\Concepts\Scroll;
use App\Models\Game\Concepts\Scroll\Vote;
use App\Models\Translations\ItemTranslation;
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
		factory(ItemTranslation::class, 50)->create();

		// Generate a large number of scrolls
		//  Creating the Translations will also create the Scrolls themselves
		factory(ScrollTranslation::class, 50)->create();

		factory(Vote::class, 200)->create();

		$scrolls = Scroll::orderBy('id', 'desc')->take(50)->get();

		foreach ($scrolls as $scroll)
			$scroll->items()->attach(Item::inRandomOrder()->take(rand(1, 4))->get(), ['quantity' => rand(1, 10)]);
	}
}
