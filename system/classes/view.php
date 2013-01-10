<?php

/**
 * Manages passing variables to templates and rendering them
 * @package Core
 */
class View{

    /**
     * Full path to template file
     * @var string 
     * @access private  
     */
	private $path;

    /**
     * The name of the view.
     * @var string
     * @access public  
     */
	public $name;

    /**
     * Stores all the variables passed to the view
     * @var array   
     * @access private 
     */
	private $_data = array();

    /**
     * Manages storing the data passed to the view as properties
     * 
     * @param string $key Property name
     * @param string $val Property value
     * @return void    
     * @access public  
     */
	public function __set($key, $val) {
		$this->_data[$key]=$val;
	}

    /**
     * Manages accessing passed data as properties
     * 
     * @param string   $key Property name
     * @return mixed	Property value
     * @access public    
     * @throws Exception If the property is not found
     */
	public function __get($key){
		if (isset($this->_data[$key]))
			return $this->_data[$key];
		throw new Exception("Value {$key} not set for view {$name}"); 
	}

    /**
     * Renders the template, all dynamically set properties
	 * will be available inside the view file as variables.
	 * Example:
	 * <code>
	 * $view = View::get('frontpage');
	 * $view->title = "Page title";
	 * echo $view->render();
	 * </code>
     *
     * @return string Rendered template
     * @access public  
     */
	public function render() {
		extract($this->_data);
		ob_start();
		include($this->path);
		$out = ob_get_contents();
		ob_end_clean();
		return $out;
	}

    /**
     * Constructs the view
     * 
     * @param string   $name The name of the template to use
     * @return View    
     * @access public    
     * @throws Exception If specified template is not found
     * @static 
     */
	public static function get($name){
		$view = new View();
		$view->name = $name;
		$file = Misc::find_file('views', $name);
		
		if ($file == false)
			throw new Exception("View {$name} not found.");
			
		$view->path=$file;
		return $view;
	}
}