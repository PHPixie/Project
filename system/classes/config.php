<?php

/**
 * Handles retrieving of the configuration options.
 * You can add configuration files to /application/config folder
 * and later access them via the get() method.
 * @package Core
 */
class Config {
	
    /**
     * Array of configuration files and values loaded from them
     * @var array  
     * @access protected 
     * @static 
     */
	protected static $groups = array();
	
	/**
     * Loads a group configuration file it has not been loaded before and
	 * returns its options. If the group doesn't exist creates an empty one
     * 
     * @param string    $name Name of the configuration group to load
     * @return array    Array of options for this group
     * @access public    
     * @static 
     */
	public static function get_group($name) {
		
		if (!isset(Config::$groups[$name])) {

			$file = Misc::find_file('config', $name);
			
			if (!$file)
				Config::$groups[$name] = array(
					'file' => APPDIR.'config/'.$name.'.php',
					'options' => array()
				);
			else
				Config::load_group($name,$file);
		}
		
		return Config::$groups[$name]['options'];
	}
	
	/**
     * Loads group from file
     * 
     * @param string $name Name to assign the loaded group
	 * @param string $file File to load
     * @access public    
     * @static 
     */
	public static function load_group($name, $file) {
	
		Config::$groups[$name] = array(
			'file' => $file,
			'options' => include($file)
		);
	}
	
    /**
     * Retrieves a configuration value. You can use a dot notation
	 * to access properties in group arrays. The first part of the key
	 * specifies the configuration file from which options should be loaded from
	 * <code>
	 *     //Loads ['default']['user'] option
	 *     //from database.php configuration file
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
		$group_name = array_shift($keys);
		$group = Config::get_group($group_name);
		if (empty($keys))
			return $group;
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
		
		if (array_key_exists (1,$p))
			return $p[1];
			
		throw new Exception("Configuration not set for {$p[0]}.");
	}
	
	/**
     * Sets a configuration option.
     * 
     * @param string    $key    Configuration key to set
	 * @param string    $value  Value to set for this option
     * @access public    
     * @static 
     */
	public static function set($key,$value){
		$keys = explode('.', $key);
		$group_name = array_shift($keys);
		$group = Config::get_group($group_name);
		$subgroup = &$group;
		
		foreach($keys as $i => $key) {
		
			if ($i == count($keys) - 1) {
			
				$subgroup[$key] = $value;
				
			} else {
			
				if(!isset($subgroup[$key])||!is_array($subgroup[$key]))
					$subgroup[$key]=array();
				$subgroup = & $subgroup[$key];
				
			}
		}
		
		Config::$groups[$group_name]['options'] = $group;
	}
	
	/**
     * Writes a configuration group back to the file it was loaded from
     * 
     * @param string    $group    Name of the group to write
     * @access public    
     * @static 
     */
	public static function write($group){
		Config::get_group($group);
		$group=Config::$groups[$group];
		file_put_contents($group['file'],"<?php\r\nreturn ".var_export($group['options'],true).";");
	}

}