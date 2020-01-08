<?php

function getCookieValue($key)
{
	if (isset($_COOKIE[$key]) && $_COOKIE[$key])
		return $key;
	return null;
}
