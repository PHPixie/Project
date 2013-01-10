<?php

/**
 * Handles error reporting and debugging.
 * @package Core
 */
class Debug {

    /**
     * Caught exception
     * @var Exception 
     * @access public  
     */
	public $exception;

    /**
     * An array of logged items
     * @var array  
     * @access public 
     * @static 
     */
	public static $logged=array();

    /**
     * Displays the error page
     * 
     * @return void   
     * @access public 
     */
	public function render() {
		ob_end_clean();
		$view = View::get('debug');
		$view->exception = $this->exception;
		$view->log = Debug::$logged;
		header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found"); 
		header("Status: 404 Not Found");
		echo $view->render();
	}

    /**
     * Catches errors and exceptions and processes them
     * 
     * @param Exception $exception Caught exception
     * @return void    
     * @access public  
     * @static 
     */
	public static function onError($exception) {
		set_exception_handler(array('Debug', 'internalException'));
		set_error_handler ( array('Debug', 'internalError'), E_ALL);
		$error = new Debug();
		$error->exception = $exception;
		$error->render();
	}

    /**
     * Converts PHP Errors to Exceptions
     * 
     * @param string        $errno   Error number
     * @param string        $errstr  Error message
     * @param string        $errfile File in which the error occurred
     * @param string        $errline Line at which the error occurred
     * @return void           
     * @access public         
     * @throws ErrorException Throws converted exception to be immediately caught
     * @static 
     */
	public static function errorHandler($errno, $errstr, $errfile, $errline) {
		throw new ErrorException($errstr, $errno, 0, $errfile, $errline);
	}

    /**
     * Handles exceptions that occurred while inside the error handler. Prevents recursion.
     * 
     * @param Exception  $exception Caught exception
     * @return void   
     * @access public 
     * @static 
     */
	public static function internalException($exception) {
		echo $exception->getMessage().' in '.$exception->getFile().' on line '.$exception->getLine();
	}

    /**
     * Handles errors that occurred while inside the error handler. Prevents recursion.
     * 
     * @param string        $errno   Error number
     * @param string        $errstr  Error message
     * @param string        $errfile File in which the error occurred
     * @param string        $errline Line at which the error occurred
     * @return void    
     * @access public  
     * @static 
     */
	public static function internalError($errno, $errstr, $errfile, $errline) {
		echo $errstr.' in '.$errfile.' on line '.$errline;
	}

    /**
     * Initializes the error handler
     * 
     * @return void   
     * @access public 
     * @static 
     */
	public static function init(){
		set_exception_handler(array('Debug', 'onError'));
		set_error_handler ( array('Debug', 'errorHandler'), E_ALL);
	}

    /**
     * Adds an item to the log.
     * 
     * @param mixed $val Item to be logged
     * @return void    
     * @access public  
     * @static 
     */
	public static function log($val){
		array_unshift(Debug::$logged,$val);
	}
	
}