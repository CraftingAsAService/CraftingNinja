<?php

/**
 * Build data for FFXIV
 *
 *  php artisan aspir ffxiv
 *
 * # Notes about FFXIV Data/XIVAPI from Clorifex
 *
 * If you're interested in more translation, it can take quite a bit of processing to get all the useful data out.  All my stuff is open source here, https://github.com/ufx/GarlandTools/blob/master/Garland.Data/Modules/Nodes.cs.  For the most part the s prefix stands for "SaintCoinach" and generally represents what you'll find on xivapi.
 * > I've spent hours looking for a way to connect BNPCs to zone and level
 * This isn't in the client data files so xivapi can't provide it. My site uses a combination of a private server with packet-scraped data, and the defunct Libra Eorzea database for this. See: https://github.com/ufx/GarlandTools/blob/master/Garland.Data/Modules/Mobs.cs
 * > ENPC to zone
 * There's an algorithm you can use to connect a lot of these via the Level table. A significant amount still come from the defunct Libra Eorzea, but I've been working on an alternative method to pull them from the binary world data. See: https://github.com/ufx/GarlandTools/blob/master/Garland.Data/Modules/Territories.cs and https://github.com/ufx/SaintCoinach/blob/master/SaintCoinach/Xiv/Map.cs#L205
 * > Items to an instance
 * Those also mostly come from Libra and won't be updated anymore. Best alternative is scraping the lodestone HTML, but I haven't had time for this.
 * To be honest, crafters rely on lots of disparate data sources that I put a lot of work into bringing together. The raw game data is just one piece of the puzzle - there's manual input sources (https://docs.google.com/spreadsheets/d/1hEj9KCDv0TT1NiGJ0S7afS4hfGMPb6tetqXQetYETUE/edit#gid=953424709), reverse-engineered algorithms acting on the data, a few piles of hacks for weird stuff, that defunct Libra Eorzea database, and some web scraping to bring it all together. You may have better luck picking up my data imports via my open source Garland code. There's a setup & contribution guide if you're interested: https://github.com/ufx/GarlandTools/blob/master/CONTRIBUTING.md. Happy to help with any questions you've got for it.
 *
 */

namespace App\Models\Aspir\Ffxiv;

use App\Models\Aspir\Aspir;
use App\Models\Aspir\Ffxiv\GarlandTools;
use App\Models\Aspir\Ffxiv\ManualData;
use App\Models\Aspir\Ffxiv\XIVAPI;
use App\Models\Game\Aspects\Item;
use App\Models\Game\Aspects\Objective;
use DB;

class Ffxiv extends Aspir
{

	// These exist in an order-of-operations mindset
	//  get_class_methods() will pull the trait methods in this order
	use XIVAPI,
		GarlandTools,
		ManualData;

	/**
	 * Download assets
	 *  Triggered by "php artisan assets ffxiv"
	 *  Exempt from "all public functions will run" list in Aspir.php
	 */
	public function assets()
	{
		$this->icons();
		$this->maps();
	}

	private function icons()
	{
		$this->command->info('Investigating Icons');

		$basePath = base_path('../assets/ffxiv/i/');
		$iconDomain = 'https://xivapi.com/i/';

		// A stream context to ignore http warnings
		$streamContext = stream_context_create([
			'http' => ['ignore_errors' => true],
		]);

		DB::setDefaultConnection($this->gameSlug);

		$leveIcons = collect([]);

		$allObjectiveDetails = DB::table('details')
			->distinct()->select('data')
			->where('detailable_type', 'objective')
			->pluck('data');
		foreach ($allObjectiveDetails as $detail)
		{
			$detail = json_decode($detail, true);

			foreach (['frame', 'plate'] as $type)
			{
				if ( ! isset($detail[$type]))
					continue;

				$icon = str_pad($detail[$type], 6, "0", STR_PAD_LEFT);
				$folder = substr($icon, 0, 3) . "000";
				$leveIcons[] = $folder . '/' . $icon;
			}
		}

		$leveIcons = $leveIcons->unique();
		$itemIcons = Item::distinct()->select('icon')
			->pluck('icon');
		$objectiveIcons = Objective::distinct()->select('icon')
			->where('icon', '<>', 'leve')
			->where('icon', '<>', '')
			->pluck('icon');

		$allIcons = $leveIcons->merge($itemIcons)
			->merge($objectiveIcons)
			->unique();

		exec('find "' . $basePath . '" -name *.png', $existingIcons);
		$existingIcons = collect($existingIcons)->map(function($value) use ($basePath) {
			return str_replace('.png', '', str_replace($basePath, '', $value));
		});

		$iconsToDownload = $allIcons->diff($existingIcons);

		foreach ($iconsToDownload as $icon)
		{
			$this->command->info('Downloading ' . $icon);
			$image = file_get_contents($iconDomain . $icon . '.png', false, $streamContext);

			if (str_contains($image, '"Code":404'))
			{
				$this->command->error('Download failed, 404');
				continue;
			}

			$iconBase = explode('/', $icon)[0];
			if ( ! is_dir($iconBase))
				exec('mkdir -p "' . $basePath . $iconBase . '"');

			file_put_contents($basePath . $icon . '.png', $image);
		}
	}

	private function maps()
	{
		$this->command->info('Investigating Maps');

		$basePath = base_path('../assets/ffxiv/m/');
		$mapDomain = 'https://xivapi.com/m/';
		// https://xivapi.com/m/f1t1/f1t1.00.jpg

		// A stream context to ignore http warnings
		$streamContext = stream_context_create([
			'http' => ['ignore_errors' => true],
		]);

		DB::setDefaultConnection($this->gameSlug);

		$mapsToGet = collect([]);

		$allMapDetails = DB::table('details')
			->distinct()->select('data')
			->where('detailable_type', 'map')
			->pluck('data');
		foreach ($allMapDetails as $detail)
		{
			$detail = json_decode($detail, true);

			if ( ! isset($detail['image']) || empty($detail['image']))
				continue;

			// a1b2/34 becomes a1b2/a1b2.34.jpg
			list($key, $number) = explode('/', $detail['image']);
			$mapsToGet[] = $key . '/' . $key . '.' . $number;
		}

		exec('find "' . $basePath . '" -name *.jpg', $existingMaps);
		$existingMaps = collect($existingMaps)->map(function($value) use ($basePath) {
			return str_replace('.jpg', '', str_replace($basePath, '', $value));
		});

		$mapsToDownload = $mapsToGet->diff($existingMaps)->unique();

		foreach ($mapsToDownload as $map)
		{
			$this->command->info('Downloading ' . $map);
			$image = file_get_contents($mapDomain . $map . '.jpg', false, $streamContext);

			if (str_contains($image, '"Code":404'))
			{
				$this->command->error('Download failed, 404');
				continue;
			}

			$mapBase = explode('/', $map)[0];
			if ( ! is_dir($mapBase))
				exec('mkdir -p "' . $basePath . $mapBase . '"');

			file_put_contents($basePath . $map . '.jpg', $image);
		}
	}
}
