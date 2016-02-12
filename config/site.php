<?php

return [
	// Administrators are handled by the ENV file
	'admins' => explode(',', env('ADMINISTRATORS', [])),
];