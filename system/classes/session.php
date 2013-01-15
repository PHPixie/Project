<?php

/**
 * Simple class for accessing session data
 * @package Core
 */
class Session{

    /**
     * Flag to check if the session was already intialized
     * @var boolean 
     * @access private  
     * @static 
     */
	private static $initialized=false;

    /**
     * Makes sure the session is initialized
     * 
     * @return void   
     * @access private 
     * @static 
     */
	private static function check(){
		if(!Session::$initialized){
			session_start();
			Session::$initialized=true;
		}
	}

    /**
     * Gets a session variable
     * 
     * @param string $key     Variable name
     * @param mixed $default Default value
     * @return mixed Session value
     * @access public  
     * @static 
     */
	public static function get($key, $default = null) {
		Session::check();
		return Misc::arr($_SESSION,$key,$default);
	}

    /**
     * Sets a session variable
     * 
     * @param string $key Variable name
     * @param mixed $val Variable value
     * @return void    
     * @access public  
     * @static 
     */
	public static function set($key, $val) {
		Session::check();
		$_SESSION[$key]=$val;
	}
	
	/**
     * Removes a session variable
     * 
     * @param string $key Variable name
     * @return void    
     * @access public  
     * @static 
     */
	public static function remove($key) {
		Session::check();
		$var = $_SESSION[$key];
		unset($_SESSION[$key], $var);
	}
	
	/**
     * Resets the session
     * 
     * @return void    
     * @access public  
     * @static 
     */
	public static function reset() {
		Session::check();
		$_SESSION=array();
	}
}