<?php

/**
 * Base Controller class. Controllers contain the  logic of your website,
 * each action representing a reply to a particular request, e.g. a single page.
 * @package Core
 */
class Controller
{

	/**
	 * Request for this controller. Holds all input data.
	 * @var Request
	 * @access public
	 */
	public $request;

	/**
	 * Response for this controller. It will be updated with headers and
	 * response body during controller execution
	 * @var Response
	 * @access public
	 */
	public $response;

	/**
	 * If set to False stops controller execution
	 * @var boolean
	 * @access public
	 */
	public $execute = true;

	/**
	 * This method is called before the action.
	 * You can override it if you need to,
	 * it doesn't do anything by default.
	 *
	 * @return void
	 * @access public
	 */
	public function before()
	{

	}

	/**
	 * This method is called after the action.
	 * You can override it if you need to,
	 * it doesn't do anything by default.
	 *
	 * @return void
	 * @access public
	 */
	public function after()
	{

	}

	/**
	 * Creates new Controller
	 *
	 * @return void
	 * @access public
	 */
	public function __construct()
	{
		$this->response = new Response;
	}

	/**
	 * Runs the appropriate action.
	 * It will execute the before() method before the action
	 * and after() method after the action finishes.
	 *
	 * @param string    $action Name of the action to execute.
	 * @return void
	 * @access public
	 * @throws Exception If the specified action doesn't exist
	 */
	public function run($action)
	{
		$action = 'action_'.$action;
		if (!method_exists($this, $action))
			throw new Exception("Method {$action} doesn't exist in ".get_class($this), 404);
		$this->execute = true;
		$this->before();
		if ($this->execute)
			$this->$action();
		if ($this->execute)
			$this->after();
	}

}