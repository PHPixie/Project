<?php

/**
 * Handles client request.
 * @package Core
 */
class Request {

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
     * Retrieves a GET parameter 
     * 
     * @param string $key    Parameter key
     * @param mixed $default Default value
     * @return mixed Returns a value if a key is specified,
	 *               or an array of GET parameters if it isn't.
     * @access public  
     */
	public function get($key = null, $default = null) {
		if ($key == null)
			return $this->_get;
		return Misc::arr($this->_get,$key,$default);
	}

    /**
     * Retrieves a POST parameter 
     * 
     * @param string $key    Parameter key
     * @param mixed $default Default value
     * @return mixed Returns a value if a key is specified,
	 *               or an array of POST parameters if it isn't.
     * @access public  
     */
	public function post($key = null, $default = null) {
		if ($key == null)
			return $this->_post;
		return Misc::arr($this->_post,$key,$default);
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
	public function param($key = null, $default = null) {
		if ($key == null)
			return $this->route->params;
		return Misc::arr($this->route->params,$key,$default);
	}

    /**
     * Initializes the routed Controller and executes specified action 
     * 
     * @return Response A Response object with the body and headers set
     * @access public 
     */
	public function execute() {
		$controller=$this->param('controller').'_Controller';
		$controller = new $controller;
		$controller->request = $this;
		$controller->run($this->param('action'));
		return $controller->response;
	}

    /**
     * Initializes the Request and process the URI into a Route
     * 
     * @return object Request 
     * @access public 
     * @static 
     */
	public static function create(){
		$request = new Request();
		$request->_post = $_POST;
		$request->_get = $_GET;
		$url_parts = parse_url($_SERVER['REQUEST_URI']);
		$request->route = Route::match($url_parts['path']);
		$request->method=$_SERVER['REQUEST_METHOD']; 
		return $request;
	}

}