<?php

namespace Tests;

abstract class BookTestCase extends GameTestCase
{

	public function addItemToNinjaCartCookie($item, $quantity = 1)
	{
		static $items = [];
		$items[] = $item;

		// Assume the user added these things manually
		$_COOKIE['NinjaCart'] = '[';
		foreach ($items as $key => $i)
			$_COOKIE['NinjaCart'] .= '{"i":' . $item->id . ',"t":"item","q":' . $quantity . ',"p":""}' . ($key != count($items) - 1 ? ',' : '');
		$_COOKIE['NinjaCart'] .= ']';
	}

}
