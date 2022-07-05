<?php

class ExternalRedirects
{
	private static function callback($matches)
	{
		global $externalRedirects;
		$address = $matches[1];
		$find = null;

		foreach ($externalRedirects as $replace)
		{
			if (null === $find) $find = $replace; else
			{
				$address = preg_replace($find, $replace, $address);
				$find = null;
			}
		}

		return '<a href="'.$address.'"';
	}

	public static function replace($address)
	{
		global $externalRedirects;
		if (!is_array($externalRedirects)) return $address;
		$find = null;

		foreach ($externalRedirects as $replace)
		{
			if (null === $find) $find = $replace; else
			{
				$address = preg_replace($find, $replace, $address);
				$find = null;
			}
		}

		return $address;
	}

	public static function solve($content)
	{
		global $externalRedirects;
		if (is_array($externalRedirects))
			return $content = preg_replace_callback('/<a href="([^"]+)"/i',
				array('ExternalRedirects', 'callback'), $content);
		else return $content;
	}
}

?>