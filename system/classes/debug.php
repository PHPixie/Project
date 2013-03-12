<?php

/**
 * Handles error reporting and debugging.
 * @package Core
 */
class Debug
{

	/**
	 * An array of logged items
	 * @var array
	 * @access public
	 * @static
	 */
	public static $logged = array();

	/**
	 * Displays the error page. If you have 'silent_errors' enabled in
	 * core.php config file, a small message will be shown instead.
	 *
	 * @return void
	 * @access public
	 */
	public function render_error($exception)
	{
		ob_end_clean();

		if ($exception->getCode() == 404)
		{
			$status = '404 Not Found';
		}
		else
		{
			$status = '503 Service Temporarily Unavailable';
		}

		header($_SERVER["SERVER_PROTOCOL"].' '.$status);
		header("Status: {$status}");

		if (Config::get('core.errors.silent', false))
		{
			echo $status;
			return;
		}

		$view = View::get('debug');
		$view->exception = $exception;
		$view->log = Debug::$logged;
		echo $view->render();
	}

	/**
	 * Catches errors and exceptions and sends them
	 * to the configured handler if one is present,
	 * otherwise render_error() will be called.
	 *
	 * @param Exception $exception Caught exception
	 * @return void
	 * @access public
	 * @static
	 */
	public static function onError($exception)
	{
		set_exception_handler(array('Debug', 'internalException'));
		set_error_handler(array('Debug', 'internalError'), E_ALL);
		$handler = Config::get('core.errors.handler', 'Debug::render_error');
		call_user_func($handler, $exception);
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
	public static function errorHandler($errno, $errstr, $errfile, $errline)
	{
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
	public static function internalException($exception)
	{
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
	public static function internalError($errno, $errstr, $errfile, $errline)
	{
		echo $errstr.' in '.$errfile.' on line '.$errline;
	}

	/**
	 * Initializes the error handler
	 *
	 * @return void
	 * @access public
	 * @static
	 */
	public static function init()
	{
		set_exception_handler(array('Debug', 'onError'));
		set_error_handler(array('Debug', 'errorHandler'), E_ALL);
	}

	/**
	 * Adds an item to the log.
	 *
	 * @param mixed $val Item to be logged
	 * @return void
	 * @access public
	 * @static
	 */
	public static function log($val)
	{
		array_unshift(Debug::$logged, $val);
	}

}