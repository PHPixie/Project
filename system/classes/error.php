<?php

/**
 * Short description for class.
 * @package Core
 */
class Error {

    /**
     * Description for public
     * @var unknown 
     * @access public  
     */
	public $exception;

    /**
     * Short description for function
     * 
     * @return void   
     * @access public 
     */
	public function render() {
		ob_end_clean();
		$view = View::get('error');
		$view->exception = $this->exception;
		echo $view->render();
	}

    /**
     * Short description for function
     * 
     * @param unknown $exception Parameter description (if any) ...
     * @return void    
     * @access public  
     * @static 
     */
	public static function onError($exception) {
		set_exception_handler(array('Error', 'internalException'));
		set_error_handler ( array('Error', 'internalError'), E_ALL);
		$error = new Error();
		$error->exception = $exception;
		$error->render();
	}

    /**
     * Short description for function
     * 
     * @param unknown        $errno   Parameter description (if any) ...
     * @param unknown        $errstr  Parameter description (if any) ...
     * @param unknown        $errfile Parameter description (if any) ...
     * @param unknown        $errline Parameter description (if any) ...
     * @return void           
     * @access public         
     * @throws ErrorException Exception description (if any) ...
     * @static 
     */
	public static function errorHandler($errno, $errstr, $errfile, $errline) {
		throw new ErrorException($errstr, $errno, 0, $errfile, $errline);
	}

    /**
     * Short description for function
     * 
     * @param mixed  $exception Parameter description (if any) ...
     * @return void   
     * @access public 
     * @static 
     */
	public static function internalException($exception) {
		echo $exception->getMessage().' in '.$exception->getFile().' on line '.$exception->getLine();
	}

    /**
     * Short description for function
     * 
     * @param unknown $errno   Parameter description (if any) ...
     * @param string  $errstr  Parameter description (if any) ...
     * @param string  $errfile Parameter description (if any) ...
     * @param string  $errline Parameter description (if any) ...
     * @return void    
     * @access public  
     * @static 
     */
	public static function internalError($errno, $errstr, $errfile, $errline) {
		echo $errstr.' in '.$errfile.' on line '.$errline;
	}

    /**
     * Short description for function
     * 
     * @return void   
     * @access public 
     * @static 
     */
	public static function init(){
		set_exception_handler(array('Error', 'onError'));
		set_error_handler ( array('Error', 'errorHandler'), E_ALL);
	}

}