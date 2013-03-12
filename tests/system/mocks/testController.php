<?php

class Test_Controller extends Controller
{

	public $counter = 0;

	public function before()
	{
		$this->counter++;
	}

	public function after()
	{
		$this->counter++;
	}

	public function action_index()
	{
		$this->counter++;
	}

}
