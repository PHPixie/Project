<?php

/**
 * Bootstraps the system
 */
class Bootstrap{

    /**
     * Autload function. Searches for the class file and includes it.
     * 
     * @param unknown   $class Class name
     * @return void      
     * @access public    
     * @throws Exception If the class is not found
     * @static 
     */
	public static function autoload($class) {
		$file = Misc::find_file('class', $class);
		if ($file == false)
			throw new Exception("Class {$class} not found.");
		require_once($file);
	}
	
    /**
     * Runs the application
     * 
     * @return void   
     * @access public 
     * @static 
     */
	public static function run() {

		/**
		 * Application folder
		 */
			define('APPDIR', ROOTDIR.'/application/');

		/**
		 * Modules folder
		 */
			define('MODDIR', ROOTDIR.'/modules/');

		/**
		 * System folder
		 */
			define('SYSDIR', ROOTDIR.'/system/');

		/**
		 * Web folder
		 */
			define('WEBDIR', ROOTDIR.'/web/');
		/**
		 * Helper functions
		 */
		require_once('classes/misc.php');

		/**
		 * Configuration handler
		 */
		require_once('classes/config.php');

		/**
		 * Applications configuration file
		 */
		require_once('application/config.php');
		
		spl_autoload_register('Bootstrap::autoload');
		Debug::init();
		foreach(Config::get('routes') as $route)
			Route::add($route[0],$route[1],$route[2]);
	}
}