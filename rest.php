<?php

/**
 * Very simple REST Client
 *
 * @author Brad Proctor <brad@bradleyproctor.com>
 */
class Rest
{

	/**
	 * Perform the request
	 *
	 * @param array $args
	 *		url => The url
	 *		data => Optional data
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

		// Don't attempt to run if curl isn't installed
		if ( ! function_exists('curl_init')) {
			throw new Exception('CURL not available');
		}

		$ch = curl_init($args[0] . ($name == 'get' ? http_build_query($data) : ''));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
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

		$out = curl_exec($ch);
		curl_close($ch);
		return $out;
	}

}
