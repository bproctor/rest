<?php

/**
 * rest.php
 *
 * A very simple REST client
 *
 * Copyright (c) 2012 Brad Proctor. (http://bradleyproctor.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @author      Brad Proctor
 * @copyright   Copyright (c) 2012 Brad Proctor
 * @license     MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @link        http://bradleyproctor.com/
 * @version     0.1
 */
class Rest
{

	/**
	 * Perform the request
	 *
	 * @param array $args
	 *		1 => The url
	 *		2 => Optional data
	 *		3 => Optional extra curl options
	 *
	 * @return string
	 *		Returns the output from the remote server
	 */
	public static function __callStatic($name, array $args)
	{
		if (count($args) < 1) {
			throw new Exception('Expecting at least 1 parameter');
		}
		$data = isset ($args[1]) ? $args[1] : array();
		$options = isset ($args[2]) ? $args[2] : null;

		// Don't attempt to run if curl isn't installed
		if ( ! function_exists('curl_init')) {
			throw new Exception('CURL not available');
		}

		// Build the request
		$ch = curl_init($args[0] . ($name == 'get' ? http_build_query($data) : ''));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
		$options and curl_setopt($ch, $options);
		switch ($name) {
			case 'delete':
			case 'post':
			case 'put':
				curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($name));
				break;
			case 'get':
				break;
			default:
				throw new Exception('Invalid method');
		}

		// Perform the request and return the results
		$out = curl_exec($ch);
		curl_close($ch);
		return $out;
	}

}
