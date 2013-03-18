<?php

/**
 * Handles client request.
 * @package Core
 */
class Request
{

	/**
	 * Stores POST data
	 * @var array
	 * @access private
	 */
	private $_post;

	/**
	 * Stores GET data
	 * @var array
	 * @access private
	 */
	private $_get;

	/**
	 * Current Route
	 * @var Route
	 * @access public
	 */
	public $route;

	/**
	 * Request method
	 * @var string
	 * @access public
	 */
	public $method;

	/**
	 * Creates a new request
	 *
	 * @param  Route  $route  Route for this request
	 * @param  string $method HTTP method for the request (e.g. GET, POST)
	 * @param  array  $post   Array of POST data
	 * @param  array  $get    Array of GET data
	 * @param  array  $server Array of SERVER data
	 * @return Request Initialized request
	 *
	 * @access public
	 */
	public function __construct($route, $method = "GET", $post = array(), $get = array(), $server = array())
	{
		$this->route = $route;
		$this->method = $method;
		$this->_post = $post;
		$this->_get = $get;
		$this->_server = $server;
	}

	/**
	 * Retrieves a GET parameter
	 *
	 * @param string $key    Parameter key
	 * @param mixed $default Default value
	 * @param bool  $strip_tags Whether to apply strip_tags() for XSS protection
	 * @return mixed Returns a value if a key is specified,
	 *               or an array of GET parameters if it isn't.
	 * @access public
	 */
	public function get($key = null, $default = null, $strip_tags=true)
	{
		if ($key == null)
			return $this->_get;
		$val = Misc::arr($this->_get, $key, $default);
		if ($strip_tags)
			return strip_tags($val);
		return $val;
	}

	/**
	 * Retrieves a POST parameter
	 *
	 * @param string $key    Parameter key
	 * @param mixed $default Default value
	 * @param bool  $strip_tags Whether to apply strip_tags() for XSS protection
	 * @return mixed Returns a value if a key is specified,
	 *               or an array of POST parameters if it isn't.
	 * @access public
	 */
	public function post($key = null, $default = null, $strip_tags=true)
	{
		if ($key == null)
			return $this->_post;
		$val = Misc::arr($this->_post, $key, $default);
		if ($strip_tags)
			return strip_tags($val);
		return $val;
	}

	/**
	 * Retrieves a SERVER parameter
	 *
	 * @param string $key    Parameter key
	 * @param mixed  $default Default value
	 * @return mixed Returns a value if a key is specified,
	 *               or an array of SERVER parameters if it isn't.
	 * @access public
	 */
	public function server($key = null, $default = null)
	{
		if ($key == null)
			return $this->_server;
		return Misc::arr($this->_server, $key, $default);
	}

	/**
	 * Retrieves a Route parameter
	 *
	 * @param string $key    Parameter key
	 * @param mixed $default Default value
	 * @return mixed Returns a value if a key is specified,
	 *               or an array of Route parameters if it isn't.
	 * @access public
	 */
	public function param($key = null, $default = null)
	{
		if ($key == null)
			return $this->route->params;
		return Misc::arr($this->route->params, $key, $default);
	}

	/**
	 * Initializes the routed Controller and executes specified action
	 *
	 * @return Response A Response object with the body and headers set
	 * @access public
	 */
	public function execute()
	{
		$controller = $this->param('controller').'_Controller';
		if (!class_exists($controller))
			throw new Exception("Class {$controller} doesn't exist", 404);
		$controller = new $controller;
		$controller->request = $this;
		$controller->run($this->param('action'));
		return $controller->response;
	}

	/**
	 * Creates a Request representing current HTTP request.
	 *
	 * @return Request Request
	 * @access public
	 * @static
	 */
	public static function create()
	{
		$uri = $_SERVER['REQUEST_URI'];
		$basepath = Config::get('core.basepath', '/');
		$uri = preg_replace("#^{$basepath}(?:index\.php/)?#i", '/', $uri);
		$url_parts = parse_url($uri);
		return new Request(Route::match($url_parts['path']), $_SERVER['REQUEST_METHOD'], $_POST, $_GET, $_SERVER);
	}

}