<?php
class Cache {

	/**
     * An associative array of cache instances
     * @var array   
     * @access private 
     * @static 
     */
	private static $_instances=array();
	
	/**
     * Magic method to call default cache configuration methods.
     * 
     * @param string $method      Method to call
     * @param array  $arguments   Arguments passed to the method
     * @return mixed              Returns the result of called mathod
     * @access public  
     */
	public static function __callStatic($method, $arguments) {
		return call_user_func_array(array(static::instance('default'),$method), $arguments);
	}
	
	/**
     * Gets an instance of a cache configuration
     * 
     * @param string  $config Configuration name.
	 *                        Defaults to  'default'.
     * @return Abstract_Cache Driver implementation of Abstact_Cache
     * @access public 
     * @static 
     */
	public static function instance($config='default'){
		if (!isset(Cache::$_instances[$config])) {
			$driver = Config::get("cache.{$config}.driver");
			$driver="{$driver}_Cache";
			Cache::$_instances[$config] = new $driver($config);
		}
		return Cache::$_instances[$config];
	}

}