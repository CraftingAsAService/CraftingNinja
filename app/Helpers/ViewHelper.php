<?php

	function cdn($asset)
	{
		$cdn = config('site.cdn');

		// Check if we added cdn's to the config file
		if( ! $cdn)
			return asset( $asset );

		// Cache md5 results, no need to touch the file every time
		$md5_filename = Cache::get('md5:' . $asset, function() use ($asset) {
			// md5 file contents, only first 8 characters
			$md5 = is_file(public_path() . $asset) ? ('.' . substr(md5_file(public_path() . $asset), 0, 8)) : '';
			// Place md5 string inside filename
			return preg_replace('/\.([^\.]+)$/', $md5 . '.$1', $asset);
		});

		return '//' . $cdn . $md5_filename;
	}