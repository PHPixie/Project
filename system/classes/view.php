<?php

/**
 * Manages passing variables to templates and rendering them
 * @package Core
 */
class View
{

	/**
	 * Full path to template file
	 * @var string
	 * @access private
	 */
	protected $path;

	/**
	 * The name of the view.
	 * @var string
	 * @access public
	 */
	public $name;

	/**
	 * Stores all the variables passed to the view
	 * @var array
	 * @access protected
	 */
	protected $_data = array();

	/**
	 * File extension of the templates
	 * @var string
	 * @access protected
	 */
	protected $_extension = 'php';

	/**
	 * Constructs the view
	 *
	 * @param string   $name The name of the template to use
	 * @return View
	 * @throws Exception If specified template is not found
	 * @access protected
	 */
	protected function __construct($name)
	{
		$this->name = $name;
		$file = Misc::find_file('views', $name, $this->_extension);

		if ($file == false)
			throw new Exception("View {$name} not found.");

		$this->path = $file;
	}

	/**
	 * Manages storing the data passed to the view as properties
	 *
	 * @param string $key Property name
	 * @param string $val Property value
	 * @return void
	 * @access public
	 */
	public function __set($key, $val)
	{
		$this->_data[$key] = $val;
	}

	/**
	 * Manages accessing passed data as properties
	 *
	 * @param string   $key Property name
	 * @return mixed	Property value
	 * @access public
	 * @throws Exception If the property is not found
	 */
	public function __get($key)
	{
		if (isset($this->_data[$key]))
			return $this->_data[$key];
		throw new Exception("Value {$key} not set for view {$this->name}");
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
	public function render()
	{
		extract($this->_data);
		ob_start();
		include($this->path);
		return ob_get_clean();
	}

	/**
	 * Shorthand for constructing a view.
	 *
	 * @param string   $name The name of the template to use
	 * @return View
	 * @throws Exception If specified template is not found
	 * @static
	 * @access public
	 */
	public static function get($name)
	{
		$class = get_called_class();
		return new $class($name);
	}

}