<?php

/**
 * Handles retrieving of the configuration options.
 * You can add any configuration values to your /application/config.php file
 * as associative array and get those values using the get() method.
 */
class Config {

    /**
     * Array of configuration options
     * @var array  
     * @access public 
     * @static 
     */
	public static $data=array();

    /**
     * Retrieves a configuration value. You can use a dot notation
	 *  to access properties in nested arrays like this:
	 * <code>
	 *     Config::get('database.default.user');
	 * </code>
     * 
     * @param string    $key Configuration key to retrieve.
     * @param string    $default Default value to return if the key is not found. 
     * @return mixed    Configuration value
     * @access public    
     * @throws Exception If default value is not specified and the key is not found
     * @static 
     */
	public static function get() {
		$p = func_get_args();
		$keys = explode('.', $p[0]);
		$group=Config::$data;
		for ($i = 0; $i < count($keys); $i++) {
			if ($i == count($keys) - 1) {
				if (isset($group[$keys[$i]]))
					return $group[$keys[$i]];
				break;
			}
			$group = Misc::arr($group, $keys[$i], null);
			if (!is_array($group))
				break;
		}
		if (isset($p[1]))
			return $p[1];
		throw new Exception("Configuration not set for {$p[0]}.");
	}

}