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

class Ffxiv extends Aspir
{

	// These exist in an order-of-operations mindset
	//  get_class_methods() will pull the trait methods in this order
	use XIVAPI,
		GarlandTools,
		ManualData;

}
