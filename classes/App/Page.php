<?php
namespace App;

class Page extends \PHPixie\Controller {
	
	protected $view;
	
	public function before() {
		$this->view = $this->pixie-> view('main');
	}
	
	public function after() {
		$this->response->body = $this->view->render();
	}
	
}