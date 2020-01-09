<?php

function getCookieValue($key, $defaultValue = null)
{
	if (isset($_COOKIE[$key]) && $_COOKIE[$key])
		return $_COOKIE[$key];
	return $defaultValue;
}
