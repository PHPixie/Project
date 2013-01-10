<?php

/**
 * Routing class to extract and parse request parameters from the URL.
 * @package Core
 */
class Route {

    /**
     * Name of the route.
     * @var string
     * @access public  
     */
	public $name;

    /**
     * Extracted parameters
     * @var array  
     * @access public 
     */
	public $params=array();

    /**
     * Associative array of routes.
     * @var array  
     * @access private 
     * @static 
     */
	private static $rules=array();

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
	public static function add($name, $rule, $defaults = array()) {
		Route::$rules[$name]=array(
			'rule'=>$rule,
			'defaults'=>$defaults
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
	public static function get($name) {
		if (!isset(Route::$rules[$name]))
			throw new Exception("Route {$name} not found.");
		$route = new Route();
		$route-> name = $name;
		return $route;
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
	public static function match($uri) {
		$matched = false;
		foreach(Route::$rules as $name=>$rule) {
			$rule=$rule['rule'];
			if (is_callable($rule)) {
				if (($data = $rule($uri)) !== FALSE) {
					$matched = $name;
					break;
				}
			}else {
				$pattern = is_array($rule)?$rule[0]:$rule;
				$pattern = str_replace(')', ')?', $pattern);
				
				$pattern=preg_replace_callback('/<.*?>/',
					function($str) use ($rule){
						$str=$str[0];
						$regexp='[a-zA-Z0-9\-\.]+';
						if(is_array($rule))
							$regexp=Misc::arr($rule[1],str_replace(array('<','>'),'',$str),$regexp);
						return '(?P'.$str.$regexp.')';
					},$pattern);
				
				preg_match('#^'.$pattern.'/?$#',$uri,$match);
				if(!empty($match[0])){
					$matched=$name;
					$data=array();
						foreach($match as $k=>$v)
							if(!is_numeric($k))
								$data[$k]=$v;
					break;
				}
			}
		}
		if($matched==false)
			throw new Exception('No route matched your request');
		$rule=Route::$rules[$matched];

		$params=array_merge($rule['defaults'],$data);
		
		if(!isset($params['controller']))
			throw new Exception("Route {$matched} matched, but no controller was defined for this route");
		if(!isset($params['action']))
			throw new Exception("Route {$matched} matched with controller {$params['controller']}, but no action was defined for this route");
		
		$route=Route::get($matched);
		$route->params=$params;
		return $route;
	}

}