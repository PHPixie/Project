<?php

/**
 * Routing class to extract and parse request parameters from the URL.
 * @package Core
 */
class Route
{

	/**
	 * Name of the route.
	 * @var string
	 * @access public
	 */
	public $name;

	/**
	 * Rule for this route.
	 * @var mixed
	 * @access public
	 */
	public $rule;

	/**
	 * Default parameters for this route.
	 * @var mixed
	 * @access public
	 */
	public $defaults;

	/**
	 * Extracted parameters
	 * @var array
	 * @access public
	 */
	public $params = array();

	/**
	 * Associative array of route rules.
	 * @var array
	 * @access private
	 * @static
	 */
	private static $rules = array();

	/**
	 * Associative array of route instances.
	 * @var array
	 * @access private
	 * @static
	 */
	private static $routes = array();

	/**
	 * Constructs a route.
	 *
	 * @param string $name		Name of the route
	 * @param mixed $rule		Rule for this route
	 * @param array $defaults	Default parameters for the route
	 * @return Route Initialized Route
	 * @access protected
	 */
	protected function __construct($name, $rule, $defaults)
	{
		$this->name = $name;
		$this->rule = $rule;
		$this->defaults = $defaults;
	}

	/**
	 * Generates a url for a route
	 *
	 * @param array $params    Parameters to substitute in the route
	 * @param bool $absolute   Whether to return an absolute url
	 * @param string $protocol	Protocol to use for absolute url
	 * @return string Generated url
	 * @access public
	 */
	public function url($params = array(), $absolute = false, $protocol = 'http')
	{
		if (is_callable($this->rule))
			throw new Exception("The rule for '{$this->name}' route is a function and cannot be reversed");

		$url = is_array($this->rule) ? $this->rule[0] : $this->rule;

		$replace = array();
		$params = array_merge($this->defaults, $params);
		foreach ($params as $key => $value)
			$replace["<{$key}>"] = $value;
		$url = str_replace(array_keys($replace), array_values($replace), $url);

		$count = 1;
		$chars = '[^\(\)]*?';
		while ($count > 0)
			$url = preg_replace("#\({$chars}<{$chars}>{$chars}\)#", '', $url, -1, $count);

		$url = str_replace(array('(', ')'), '', $url);

		if ($absolute)
			$url = $protocol.'://'.$_SERVER['HTTP_HOST'].$url;

		return $url;
	}

	/**
	 * Ads a route
	 *
	 * @param string $name     Name of the route. Routes with the same name will override one another.
	 * @param mixed $rule     Either an expression to match URI against or a function that will
	 *                        be passed the URI and must return either an associative array of
	 *                        extracted parameters (if it matches) or False.
	 * @param array   $defaults An associated array of default values.
	 * @return void
	 * @access public
	 * @static
	 */
	public static function add($name, $rule, $defaults = array())
	{
		Route::$rules[$name] = array(
			'rule' => $rule,
			'defaults' => $defaults
		);
	}

	/**
	 * Gets route by name
	 *
	 * @param string $name Route name
	 * @return Route
	 * @access public
	 * @throws Exception If specified route doesn't exist
	 * @static
	 */
	public static function get($name)
	{
		if (!isset(Route::$rules[$name]))
			throw new Exception("Route {$name} not found.");

		if (!isset(Route::$routes[$name]))
		{
			$rules = Route::$rules[$name];
			Route::$routes[$name] = new static($name, $rules['rule'], $rules['defaults']);
		}

		return Route::$routes[$name];
	}

	/**
	 * Matches the URI against available routes to find the correct one.
	 *
	 * @param string   $uri Request URI
	 * @return Route
	 * @access public
	 * @throws Exception If no route matches the URI
	 * @throws Exception If route matched but no Controller was defined for it
	 * @throws Exception If route matched but no action was defined for it
	 * @static
	 */
	public static function match($uri)
	{
		$matched = false;
		foreach (Route::$rules as $name => $rule)
		{
			$rule = $rule['rule'];
			if (is_callable($rule))
			{
				if (($data = $rule($uri)) !== FALSE)
				{
					$matched = $name;
					break;
				}
			}
			else
			{
				$pattern = is_array($rule) ? $rule[0] : $rule;
				$pattern = str_replace(')', ')?', $pattern);

				$pattern = preg_replace_callback('/<.*?>/', function($str) use ($rule) {
						$str = $str[0];
						$regexp = '[a-zA-Z0-9\-\._]+';
						if (is_array($rule))
							$regexp = Misc::arr($rule[1], str_replace(array('<', '>'), '', $str), $regexp);
						return '(?P'.$str.$regexp.')';
					}, $pattern);

				preg_match('#^'.$pattern.'/?$#', $uri, $match);
				if (!empty($match[0]))
				{
					$matched = $name;
					$data = array();
					foreach ($match as $k => $v)
						if (!is_numeric($k))
							$data[$k] = $v;
					break;
				}
			}
		}
		if ($matched == false)
			throw new Exception('No route matched your request', 404);
		$rule = Route::$rules[$matched];

		$params = array_merge($rule['defaults'], $data);

		if (!isset($params['controller']))
			throw new Exception("Route {$matched} matched, but no controller was defined for this route", 404);
		if (!isset($params['action']))
			throw new Exception("Route {$matched} matched with controller {$params['controller']}, but no action was defined for this route", 404);

		$route = Route::get($matched);
		$route->params = $params;
		return $route;
	}

}