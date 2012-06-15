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
		$ch = curl_init($args[0] . ($name == 'get' ? http_build_query($data) : ''));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		switch ($name) {
			case 'delete':
				curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
				break;
			case 'get':
				break;
			case 'post':
				curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
				curl_setopt($ch, CURLOPT_POST, true);
				break;
			case 'put':
				curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
				break;
			default:
				throw new Exception('Invalid method');
		}

		$out = curl_exec($ch);
		curl_close($ch);
		return $out;
	}

}
