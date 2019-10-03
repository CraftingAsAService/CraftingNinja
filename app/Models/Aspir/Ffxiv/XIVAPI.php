<?php

/**
 * XIVAPI
 * 	Get data from XIVAPI
 */

namespace App\Models\Aspir\Ffxiv;

trait XIVAPI
{

	public function achievements()
	{
		$this->setData('achievement', [
				'id'      => '$data->ID',
				'name'    => '$data->Name',
				'item_id' => '$data->ItemTargetID',
				'icon'    => '$data->IconID',
			], '$data->ID');
	}

}
