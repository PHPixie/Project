<?php

/**
 * Simple class for accessing session data
 * @package Core
 */
class Session
{

	/**
	 * Makes sure the session is initialized
	 *
	 * @return void
	 * @access private
	 * @static
	 */
	private static function check()
	{
		if (!session_id())
		{
			session_start();
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
	public static function get($key, $default = null)
	{
		Session::check();
		return Misc::arr($_SESSION, $key, $default);
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
	public static function set($key, $val)
	{
		Session::check();
		$_SESSION[$key] = $val;
	}

	/**
	 * Removes a session variable
	 *
	 * @param string $key Variable name
	 * @return void
	 * @access public
	 * @static
	 */
	public static function remove($key)
	{
		Session::check();

		if (!isset($_SESSION[$key]))
			return;

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
	public static function reset()
	{
		Session::check();
		$_SESSION = array();
	}

	/**
	 * Gets ot sets flash messages.
	 * If the value parameter is passed the message is set, otherwise it is retrieved.
	 * After the message is retrieved for the first time it is removed.
	 *
	 * @param $key  The name of the flash message
	 * @param $val  Flash message content
	 * @return mixed
	 * @access public
	 * @static
	 */
	public static function flash($key, $val = null)
	{
		Session::check();
		$key = "flash_{$key}";
		if ($val != null)
		{
			Session::set($key, $val);
		}
		else
		{
			$val = Session::get($key);
			Session::remove($key);
		}

		return $val;
	}

}