<?php

/**
 * Mock class for Misc
 */
class Misc
{

	public static $file;

	public static function arr($array, $key, $default = null)
	{
		if (isset($array[$key]))
			return $array[$key];
		return $default;
	}

	public static function find_file()
	{
		return static::$file;
	}

}