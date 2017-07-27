<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

use App\Models\Game;

class GameDataSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$gameData = json_decode(Storage::get('seeds/games.json'), true);

		// Update the games table and translations to match the json
		foreach ($gameData as $slug => $data)
		{
			$game = Game::updateOrCreate(
				['slug' => $slug],
				['version' => $data['version']]
			);

			// Leave $data with just the translations
			unset($data['version']);

			// Fill in the translations
			$game->fill($data)->save();
		}
	}
}
