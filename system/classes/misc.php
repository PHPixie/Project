<?php

/**
 * Miscellaneous useful functions.
 * @package Core
 */
class Misc
{

	/**
	 * Retrieve value from array by key, with default value support.
	 *
	 * @param array  $array   Input array
	 * @param string $key     Key to retrieve from the array
	 * @param mixed  $default Default value to return if the key is not found
	 * @return mixed An array value if it was found or default value if it is not
	 * @access public
	 * @static
	 */
	public static function arr($array, $key, $default = null)
	{
		if (isset($array[$key]))
		{
			return $array[$key];
		}
		return $default;
	}

	/**
	 * Finds full path to a specified file
	 * It will search in the /application folder first, then in all enabled modules
	 * and then the /system folder
	 *
	 * @param string  $subfolder  Subfolder to search in e.g. 'classes' or 'views'
	 * @param string  $name       Name of the file without extension
	 * @param string  $extension  File extension
	 * @param boolean $return_all If 'true' returns all mathced files as array,
	 *                            otherwise returns the first file found
	 * @return mixed  Full path to the file or False if it is not found
	 * @access public
	 * @static
	 */
	public static function find_file($subfolder, $name, $extension = 'php', $return_all = false)
	{
		$folders = array(APPDIR);

		foreach (Config::get('core.modules', array()) as $module)
		{
			$folders[] = MODDIR.$module.'/';
		}
		$folders[] = SYSDIR;

		$fname = $name.'.'.$extension;
		$found_files = array();

		foreach ($folders as $folder)
		{
			$file = $folder.$subfolder.'/'.$fname;
			if (file_exists($file))
			{
				if (!$return_all)
				{
					return($file);
				}
				$found_files[] = $file;
			}
		}

		if (!empty($found_files))
		{
			return $found_files;
		}

		return false;
	}

}
