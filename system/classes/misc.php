<?php

/**
 * Miscellaneous useful functions
 */
class Misc{

    /**
     * Retrieve value from array by key, with default value support.
     * 
     * @param array   $array   Input array
     * @param string $key     Key to retrieve from the array
     * @param mixed $default Default value to return if the key is not found
     * @return mixed   An array value if it was found or default value if it is not
     * @access public  
     * @static 
     */
	public static function arr($array,$key,$default=null){
		if (isset($array[$key]))
			return $array[$key];
		return $default;
	}

    /**
     * Find full path to either a class or view by name. 
	 * It will search in the /application folder first, then all enabled modules
	 * and then the /system folder
     * 
     * @param string  $type Type of the file to find. Either 'class' or 'view'
     * @param string $name Name of the file to find
     * @return boolean Return Full path to the file or False if it is not found
     * @access public  
     * @static 
     */
	public static function find_file($type, $name) {
		$folders = array(APPDIR);
		foreach(Config::get('modules') as $module)
			$folders[] = MODDIR.$module.'/';
		$folders[]=SYSDIR;
		if($type=='class'){
			$subfolder = 'classes/';
			$dirs = array_reverse(explode('_', strtolower($name)));
			$fname = array_pop($dirs);
			$subfolder.=implode('/',$dirs).'/';
		}
		
		if ($type == 'view') {
			$subfolder = 'views/';
			$fname=$name;
		}

		foreach($folders as $folder) {
			$file = $folder.$subfolder.$fname.'.php';
		
			if (file_exists($file)) {
				return($file);
			}
		}
		return false;
	}
}