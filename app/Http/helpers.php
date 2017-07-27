<?php

function buildGameURL($slug)
{
	// environment prefix
	$prefix = '';
	if (app()->environment('local'))
		$prefix = 'dev.';

	return '//' . $prefix . $slug . '.' . 'craftingasaservice.com';
}